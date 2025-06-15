
<?php
header("Content-Type: text/html;charset=utf-8");
ini_set("display_errors", 0);
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL ^ E_WARNING);

chdir('/www/wwwroot/VR715/run/');
echo('----start-----<br/>');

/**
 * Simply import all pages and different bounding boxes from different PDF documents.
 */
use setasign\Fpdi;
use setasign\tcpdf;
require_once 'lib/fpdi_fpdf/vendor/autoload.php';
require_once 'lib/fpdi_fpdf/vendor/setasign/tcpdf/tcpdf.php';
require_once 'param.php';

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

$not_submit_info = '已提交：';
foreach ($submit_id as $value) {
    $not_submit_info = $not_submit_info."".$info[$value]['name']." ";
}
$not_submit_info = $not_submit_info.'<br/>未提交：';
foreach ($not_submit_id as $value) {
    $not_submit_info = $not_submit_info."".$info[$value]['name']." ";
}
echo($not_submit_info);

#pdf converter
$sleep = rand(10,30);
sleep($sleep);
$lock_file = __DIR__.'/lock.txt';

if (!file_exists($lock_file)) {
    $myfile = fopen($lock_file, "w");
    fclose($myfile);
} else {
    echo('----lock exist----<br/>');
}

echo('------<br/>');


/**
 * @param $address mixed 收件人  多个收件人/或需要设置收件人昵称时为数组 array($address1,$address1)/array(array('address'=>$address1,'nickname'=>$nickname1),array('address'=>$address2,'nickname'=>$nickname2))
 * @param $subject string 邮件主题
 * @param $body    string 邮件内容
 * @param  $file   string 附件
 * @return bool|string   发送成功返回true 反之返回报错信息
 * @throws Exception
 */
function send_mail_by_smtp($address, $cc, $subject, $body, $file = '')
{
    require_once 'lib/PHPMailer-master/Exception.php';
    require_once 'lib/PHPMailer-master/PHPMailer.php';
    require_once 'lib/PHPMailer-master/SMTP.php';

    date_default_timezone_set("Asia/Shanghai");//设定时区东八区

    $mail = new PHPMailer();

    //Server settings
    $mail->SMTPDebug = 2;
    $mail->isSMTP();                                    // 使用SMTP方式发送
    $mail->Host = 'smtp.qq.com';                        // SMTP邮箱域名
    $mail->SMTPAuth = true;                             // 启用SMTP验证功能
    $mail->Username = $sender_email;                    // 邮箱用户名(完整email地址)
    $mail->Password = $sender_password;                 // smtp授权码，非邮箱登录密码
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';
    $mail->CharSet = "utf-8";                             //设置字符集编码 "GB2312"

    // 设置发件人信息，显示为  你看我那里像好人(xxxx@126.com)
    $mail->setFrom($mail->Username, $sender_name);
    // 设置收件人 参数 1 为收件人邮箱 参数 2 为该收件人设置的昵称  添加多个收件人 多次调用即可
    $mail->addAddress($address, $leader_name);
    // 抄送
    foreach ($cc as $value) {
        $mail->addBCC($value);
    }

    // 设置回复地址
    if ($file !== '') $mail->AddAttachment($file); // 添加附件

    $mail->isHTML(true);    //邮件正文是否为html编码 true或false
    $mail->Subject = $subject;     //邮件主题
    $mail->Body = $body;           //邮件正文 若isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取的html文件
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';  //附加信息，可以省略

    return $mail->Send() ? true : 'ErrorInfo:' . $mail->ErrorInfo;
}

echo('-------<br/>');


error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(120);
date_default_timezone_set('UTC');
$start = microtime(true);

//$pdf = new Fpdi\Fpdi();
$pdf = new Fpdi\TcpdfFpdi();

if ($pdf instanceof \TCPDF) {
    $pdf->SetProtection(['print'], '', 'owner');
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
}

echo('----read files--------<br/>');
$files = array();
$handler = opendir("upload");
while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
    if ($filename != "." && $filename != "..") {
        $files[] = $filename ;
    }
}
closedir($handler);
print_r($files);
echo('<br/>');

$combine_pdf_files = array();

foreach ($info as $item => $value) {
    $pdf_tmp = $item.'.pdf';
    if (!empty($files) && in_array($pdf_tmp, $files)) {
        $combine_pdf_files[] = __DIR__.'/upload/'.$pdf_tmp;
    }
}

echo('----准备合成');

//合成周报
$combine_file = __DIR__ .'/weekly_reports/'.date('Ymd').'_周报.pdf';

if (!empty($combine_pdf_files)) {
    foreach ($combine_pdf_files as $file) {
        $pageCount = $pdf->setSourceFile($file);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $pdf->AddPage();
            $pageId = $pdf->importPage($pageNo, '/MediaBox');
            //$pageId = $pdf->importPage($pageNo, Fpdi\PdfReader\PageBoundaries::ART_BOX);
            $s = $pdf->useTemplate($pageId, 10, 10, 200);
        }
    }
    date_default_timezone_set('PRC');
    //$pdf->Output('I', $file);
    $pdf->Output($combine_file, 'F');
}

echo('----合成成功----<br/>');
date_default_timezone_set('PRC');
//查看周报附件是否存在
if (file_exists($combine_file) && (date('w') == 0 or date('w') == 6)) {
    $body = '老师好，<br/><br/>'.'附件是本周运动组周报，请查收。<br/><br/>'.$not_submit_info.'组员';
    echo($body);

    $send_result = send_mail_by_smtp($leader_email, $cc, date('Ymd').'_周报', $body, $combine_file);

    //删除个人周报
    if ($send_result == true) {
        array_map('unlink', glob(__DIR__ .'/upload/*.pdf'));
	    echo('发送成功，删除upload中的文件！！！');
    } else {
	    echo('发送失败！！！');
    }
} else {
    echo(file_exists($combine_file).' '.date('w').'<br/>');
    echo('温馨提示：只有在每周第6天才能提交周报！！！');
}
?>
