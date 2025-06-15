<?php

include "../share_link.php";

header("Content-Type: text/html;charset=utf-8");
require_once 'param.php';
date_default_timezone_set('PRC');
$combine_file = __DIR__ .'/weekly_reports/'.date('Ymd').'_运动组.pdf';
$submit_time = date("Y-m-d")." 15:00:00";
$now_time = date("Y-m-d H:i:s");
if (file_exists($combine_file) && date('w') == 0 && (strtotime($submit_time) < strtotime($now_time))) {
    echo '<div style="width: 200px;margin: auto;"><div style="margin-top: 20px; "><img src="images/timg1.jpg" style="width: 200px"></div><center>周报已发送，逾期未提交请自行交给老板！！！</center></div>';
    exit;
}

$files = array();
$handler = opendir("upload");
while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
    if ($filename != "." && $filename != "..") {
        $files[] = $filename;
    }
}
closedir($handler);


// 检测提交情况
$submit_id = array();
$not_submit_id = array();
foreach ($info as $key => $value) {
    $report_name = $key.'.pdf';
    
    if (in_array($report_name, $files, TRUE)) {
        $submit_id[] = $key;
    } else {
        $not_submit_id[] = $key;
    }
}

include "temp.php";




