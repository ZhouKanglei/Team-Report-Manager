<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <title>运动组周报上传</title>
</head>

<style type="text/css">
    #content_div{
        text-align:center
    }
</style>
<body>
<div style="width: 100%; float: left;">
<div id="content_div">
    <div style="margin-top: 20px"><img src="images/run.jpg" style="width: 25%"></div>
    <form style="margin-top: 20px" action="upload_file.php" method="post"
          enctype="multipart/form-data">
        <label>提交人:</label>
        <select name="who">
            <option value="-1">请选择周报提交人</option>
            <?php
                foreach ($info as $key => $value) {
                    print <<<EOT
                        <option value="{$key}">{$value['name']}</option>
EOT;
                }
            ?>
        </select>
        <br />
        <label for="file">选择周报:</label>
        <input type="file" name="file" id="file" />
        <br />
        <input type="submit" name="submit" value="提交" />
    </form>
</div>
<div style="margin-top: 150px">
    <?php
        echo "已提交：";
        foreach ($submit_id as $value) {
            echo '<a href="../pdf_viewer.php?pdf=./run/upload/'.$value.'.pdf"'." title='提交时间：".date("y/m/d H:i:s", filemtime('./upload/'.$value.'.pdf'))."'>".$info[$value]['name']."</a>（".date("y/m/d H:i:s", filemtime('./upload/'.$value.'.pdf'))."）";
        }
    ?>
    <HR>
    <?php
    echo "未提交：";
    foreach ($not_submit_id as $value) {
        echo $info[$value]['name']." ";
    }
    ?>
</div>
</div>


</body>
</html>