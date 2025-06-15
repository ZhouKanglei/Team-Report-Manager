<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <title>周报历史记录</title>
</head>


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
        header('filename='.$file);
        readfile($file);
}
    

function read_all ($dir){
    if(!is_dir($dir)) return false;

    $handle = opendir($dir);
    $files = array();
    
    // ----------------------------------------
    if($handle){
        while(($fl = readdir($handle)) !== false){
            $temp = $dir.DIRECTORY_SEPARATOR.$fl;
            //如果不加  $fl!='.' && $fl != '..'  则会造成把$dir的父级目录也读取出来
            if(is_dir($temp) && $fl!='.' && $fl != '..'){
                echo '目录：'.$temp.'<br>';
                read_all($temp);
            }else{
                if($fl != '.' && $fl != '..' && strpos($temp,'.pdf') !== false){
		    $filename = basename($temp);
                    //str_replace(strrchr($filename, '.'), '', $filename);
                    //echo $filename.' <a href="pdf_viewer.php?pdf='.$temp.'" >在线预览'.'</a> | ';
                    //echo '<a href="'.$temp.'" download>下载</a><br>';
		    array_push($files, $temp);
                }
            }
        }
    }
    
    // ----------------------------------------
    rsort($files);
    for($i=0; $i<count($files); $i++)
    {
	$filename = basename($files[$i]);
        str_replace(strrchr($filename, '.'), '', $filename);
        echo '<li>'.$filename.' <a href="pdf_viewer.php?pdf='.$files[$i].'" >在线预览'.'</a> | ';
        echo '<a href="'.$temp.'" download>下载</a></li>';
    }
}

date_default_timezone_set('PRC');
$time = date("Y-m-d h:i:s");
echo '<h1> VR 715 周报历史记录（截至 '.$time.'）</h1>';
echo '<div style="width:300px;float:left;"><h2> <a href="cloud"> 云组 </a> </h2>';
read_all('./cloud/weekly_reports');
echo '</div>';
echo '<div style="width:320px;float:left;"><h2> <a href="point"> 点云组 </a> </h2>';
read_all('./point_old/weekly_reports');
echo '</div>';
echo '<div style="width:300px;float:left;"><h2> <a href="run"> 运动组 </a> </h2>';
read_all('./run/weekly_reports');
echo '</div>';
?>

</html>