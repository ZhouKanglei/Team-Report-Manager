<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <title>周报在线查看</title>
</head>
</html>

<?php
header("Content-Type: text/html;charset=utf-8");

function read_pdf($file) {
        if(strtolower(substr(strrchr($file,'.'),1)) != 'pdf') {
            echo '文件格式不对.';
            return;
        }
        if(!file_exists($file)) {
            echo '文件不存在';
            return;
        }
        header('Content-type: application/pdf');
        header('Location:pdfjs/web/viewer.html?file=../../'.$file);
        // header('filename='.$file);
        // readfile($file);
}

if (isset($_GET['pdf'])) {
    read_pdf($_GET['pdf']);
}


?>
