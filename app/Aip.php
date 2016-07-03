<?php defined('BASE_PATH') OR exit('No direct script access allowed');

/**
 * AIP核心类
 * @Auther Jathon
 * Date: 2016/7/2
 * Time: 8:44
 */
class Aip{
    /**
     * 运行核心类
     */
    public static function run(){
        $data = [];
        $data['datalist'] = self::getEtfData(Config::$etf_list);
        return self::view('index',$data);
    }

    /**
     * 以Cli模式运行核心类
     */
    public static function runCli(){
		//只允许在Cli模式下运行
		if(php_sapi_name() != 'cli') exit('No direct script access allowed');
		
        //判断是否开启邮件提醒功能或者邮件列表是否为空
        if(!Config::$email_config['send_open'] || empty(Config::$email_config['send_list'])) return ;

        require_once APP_PATH.'/assets/PHPMailer/class.phpmailer.php';//载入邮件组件类

        $datalist = self::getEtfData(Config::$etf_list);
        if(empty($datalist)) return ;

        $body = '<table><thead><tr><th width="50px">代码</th><th width="80px">名称</th><th width="50px">现价</th><th width="50px">市盈率</th><th width="80px">参考动作</th></tr></thead><tbody>';
        foreach($datalist as $k => $d){
            $body .= '<tr>';
            $body .= '<td>'.$d['code'].'</td>';
            $body .= '<td>'.$d['name'].'</td>';
            $body .= '<td>'.$d['price'].'</td>';
            $body .= '<td>'.$d['pe'].'</td>';
            $body .= '<td>'.self::estimateText($d['action']).'</td>';
            $body .= '</tr>';
            if($k >= 10) break;
        }
        $body .= '</tbody></table>';

        $body .= '<p><a href="'.Config::$host_index.'">查看更多</a></p>';


        //echo $body;exit;
        $mail = new PHPMailer(); //new一个PHPMailer对象出来
        $mail->CharSet ="UTF-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->IsSMTP(); //设定使用SMTP服务
        $mail->SMTPDebug  = false;                     // 启用SMTP调试功能 1 = errors and messages 2 = messages only
        $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
        //$mail->SMTPSecure = "tls";                 // 安全协议
        $mail->Host       = Config::$email_config['host'];  // SMTP 服务器
        $mail->Port       = Config::$email_config['port'];  // SMTP服务器的端口号
        $mail->Username   = Config::$email_config['username'];  // SMTP服务器用户名
        $mail->Password   = Config::$email_config['password'];  // SMTP服务器密码
        $mail->SetFrom(Config::$email_config['username'], 'Aip');
        $mail->AddReplyTo(Config::$email_config['username'],'Aip');
        $mail->Subject    = 'ETF监控';
        $mail->AltBody    = $body;
        $mail->MsgHTML($body);

        foreach(Config::$email_config['send_list'] as $addr){
            $mail->AddAddress($addr);
        }

        if(!$mail->Send()) {
            $error = 'Mailer Error: ' . $mail->ErrorInfo.' - '.date('Y-m-d H:i:s')."\r\n";//错误信息
            file_put_contents(STORAGE_PATH.'/logs/email_error-'.date('Ym').'.log',$error,FILE_APPEND);
            return FALSE;
            //echo "Mailer Error: " . $mail->ErrorInfo;//错误信息
        }else{
            unset($mail);
            $msg = '发送成功【'.implode(',',Config::$email_config['send_list']).'】 - '.date('Y-m-d H:i:s')."\r\n";
            file_put_contents(STORAGE_PATH.'/logs/email_success-'.date('Ym').'.log',$msg,FILE_APPEND);
            return TRUE;
        }
    }

    /**
     * 获取ETF数据
     * @return array
     */
    public static function getEtfData($etf_list){
        $content = self::cache('score_data'.implode('-',$etf_list),function()use($etf_list){
            $content = file_get_contents('https://www.jisilu.cn/jisiludata/etf.php?___t='.time());
            $data = json_decode($content,1);

            $result = [];
            foreach($data['rows'] as $d){
                if(!empty($etf_list) && !in_array($d['cell']['fund_id'],$etf_list)) continue;
                $result[] = [
                    'code' => $d['cell']['fund_id'],
                    'name' => $d['cell']['fund_nm'],
                    'price' => $d['cell']['price'],
                    'pe' => $d['cell']['pe'],
                    'action' => self::estimate($d['cell']['pe']),
                ];
            }
            return $result;
        },3600);
        return empty($content) ? [] : json_decode($content,1);
    }

    /**
     * 简易缓存函数
     * @param $cache_key 缓存下标
     * @param $source_func 数据来源函数
     * @param int $time 缓存时间(秒)
     * @param bool|false $force 强制读取数据源
     * @return string
     */
    public static function cache($cache_key,$source_func,$time = 86400,$force = false){

        $cache_key = md5($cache_key);
        $cache_path = STORAGE_PATH.'cache/'.$cache_key.'.tmp';
        $cache_time = is_file($cache_path) ? filemtime($cache_path) : 0;
        if($cache_time+$time > time() && !$force) return file_get_contents($cache_path);

        $data = call_user_func($source_func);

        if(empty($data)) return '';
        if(is_array($data)) $data = json_encode($data);
        file_put_contents($cache_path,$data);
        return $data;
    }


    /**
     * 定投动作预测
     * @param $pe
     * @return int
     */
    public static function estimate($pe){
        if($pe <= 10) return 1;//双倍定投
        if($pe >= 20) return 2;//卖出
        if($pe <= 15) return 3;//定投
        if($pe >= 15) return 4;//停止定投
    }

    /**
     * 返回定投动作中文
     * @param string $type
     * @return array|string
     */
    public static function estimateText($type = 'all'){
        $data =  array(
            '1' => '双倍定投',
            '2' => '全部卖出',
            '3' => '继续定投',
            '4' => '停止定投'
        );
        return $type !== 'all' ? (isset($data[$type]) ? $data[$type] : '') : $data;
    }

    /**
     * 返回定投动作Label类名
     * @param string $type
     * @return array|string
     */
    public static function estimateClass($type = 'all'){
        $data =  array(
            '1' => 'primary',
            '2' => 'danger',
            '3' => 'success',
            '4' => 'warning'
        );
        return $type !== 'all' ? (isset($data[$type]) ? $data[$type] : '') : $data;
    }

    /**
     * 简易模板载入函数
     * @param $view 模板名
     * @param $data 模板数据
     */
    private static function view($view,$data){
        extract($data);
        require_once(APP_PATH.'/view/'.$view.'.php');
    }

}