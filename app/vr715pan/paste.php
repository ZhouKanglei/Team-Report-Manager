<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
<title>文件共享、剪贴复制</title>
</head>
<body>

</body>
</html>

<?php
echo $_POST["paste"];

$myfile = fopen("paste.txt", "w") or die("Unable to open file!");
fwrite($myfile, $_POST["paste"]);
fclose($myfile);

ob_start();
header("Refresh:2;url=index.php");
ob_end_flush();

?>
