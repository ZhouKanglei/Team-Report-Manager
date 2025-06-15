<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<title>文件共享-粘贴复制</title>


<script src="https://cdn.bootcss.com/clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
    function copyText() {
      var input = document.getElementById("paste");
      input.select(); 
      document.execCommand("copy"); // 执行浏览器复制命令 
      alert("复制成功！");
    }
</script>

</head>
<body>
<h1>在线粘帖板</h1>

<form action="paste.php" method="post">
<textarea id="paste" name="paste" rows="10" style="width: 100%; overflow: auto; word-break: break-all">
<?php readfile('paste.txt', 'r'); ?>
</textarea>

<input type="submit" value="提交">
<input type="button" onclick="copyText()" value="复制">
</form>


<hr>

<h1>文件上传</h1>
<form action="upload_file.php" method="post" enctype="multipart/form-data">
       <label for="file">文件名：</label>
       <input type="file" name="file" id="file"><br>
       <input type="submit" name="submit" value="提交">
</form>
<hr>
<h1>文件分享</h1>

<?php
// header("Content-Type: text/html;charset=utf-8");

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
            if(is_dir($temp) && $fl!='.' && $fl != '..'){
                echo '目录：'.$temp.'<br>';
                read_all($temp);
            }else{
                if($fl != '.' && $fl != '..' ){
		    $filename = basename($temp);
		    array_push($files, $temp);
                }
            }
        }
    }
    
    // ----------------------------------------
    sort($files);
    $allowedExts = array("xlxs", "xls", "doc", "docx", "ppt", "pptx");
    for($i=0; $i<count($files); $i++)
    {
	$filename = basename($files[$i]);
	$temp = $files[$i];
        str_replace(strrchr($filename, '.'), '', $filename);
	echo "<li>".$filename.' ';
	$mid = explode(".", $filename);
	$extension = end($mid);
	# if(in_array($extension, $allowedExts))
		# echo '<a href="https://api.idocv.com/view/url?url=http://49.233.33.23:8080/app/share/'.$temp.'" >在线预览（Google）'.'</a> | ';
	if(strpos($filename,'.pdf') !== false)
        	echo '<a href="../../pdf_viewer.php?pdf=app/share/'.$temp.'" >在线预览'.'</a> | ';
	else  
		echo '<a href="'.$temp.'" >预览'.'</a> | ';
        echo '<a href="'.$temp.'" download>下载</a></li>';
    }
}

read_all('./upload/');
echo '<h1>殿下分享</h1>';
read_all('./download/');

?>




</body>
</html>


