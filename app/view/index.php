<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <style>
        .bs-docs-footer-links {
            padding-left: 0;
            margin-top: 20px;
        }
        .bs-docs-footer-links li:first-child {
            padding-left: 0;
        }
        .bs-docs-footer-links li {
            display: inline;
            padding: 0 2px;
        }
        .bs-docs-footer {
            padding-top: 40px;
            padding-bottom: 40px;
            margin-top: 100px;
            color: #767676;
            text-align: center;
            border-top: 1px solid #e5e5e5;
        }
        .bg-msg{padding: 15px}
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <p class="bg-msg bg-warning text-center">本页面数据仅供个人参考，数据为程序自动计算，不代表源码作者观点，任何投资的损失与源码作者无关。</p>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>代码</th>
                    <th>名称</th>
                    <th>现价</th>
                    <th>市盈率</th>
                    <th>动作</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($datalist as $d):?>
                    <tr>
                        <td><?php echo $d['code'];?></td>
                        <td><?php echo $d['name'];?></td>
                        <td><?php echo $d['price'];?></td>
                        <td><?php echo $d['pe'];?></td>
                        <td><span class="label label-<?php echo Aip::estimateClass($d['action']);?>"><?php echo Aip::estimateText($d['action']);?></span></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="bs-docs-footer" role="contentinfo">
        <div class="container text-center">

            <p>股市有风险，投资需谨慎！</p>
            <p>本项目源码受 <a rel="license" href="https://github.com/Jathon-yang/AIP/blob/master/LICENSE" target="_blank">MIT</a>开源协议保护。</p>
            
			<ul class="bs-docs-footer-links text-muted">
			  <li>当前版本：Alpha</li>
			  <li>·</li>
			  <li><a href="https://github.com/Jathon-yang/AIP" target="_blank">GitHub 仓库</a></li>
			  <li>·</li>
			  <li><a href="https://github.com/twbs/bootstrap" target="_blank">Bootstrap</a></li>
			  <li>·</li>
			  <li><a href="https://github.com/Jathon-yang/AIP/issues" target="_blank">Issues</a></li>
			</ul>
        </div>
    </footer>

</body>
</html>