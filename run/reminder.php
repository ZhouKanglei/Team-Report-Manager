
<?php
header("Content-Type: text/html;charset=utf-8");
ini_set("display_errors", 0);
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL ^ E_WARNING);

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
echo($not_submit_info."<br/>");

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
    $mail->isSMTP();                                      // 使用SMTP方式发送
    $mail->Host = 'smtp.qq.com';                         // SMTP邮箱域名
    $mail->SMTPAuth = true;                               // 启用SMTP验证功能
    $mail->Username = "zhoukanglei@qq.com";                    // 邮箱用户名(完整email地址)
    $mail->Password = "fdesmxxwhpkedhae";                            // smtp授权码，非邮箱登录密码
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';
    $mail->CharSet = "utf-8";                             //设置字符集编码 "GB2312"

    // 设置发件人信息，显示为  你看我那里像好人(xxxx@126.com)
    $mail->setFrom($mail->Username, '运动组-周康垒-马跃');
//    $address = 'cenyunchi@qq.com';
    //设置收件人 参数1为收件人邮箱 参数2为该收件人设置的昵称  添加多个收件人 多次调用即可
    $mail->addAddress($address, '梁老师');
    //抄送
    foreach ($cc as $value) {
        $mail->addBCC($value);
    }

//    if (is_array($address)) {
//        foreach ($address as $item) {
//            if (is_array($item)) {
//                $mail->addAddress($item['address'], $item['nickname']);
//            } else {
//                $mail->addAddress($item);
//            }
//        }
//    } else {
//        $mail->addAddress($address, 'adsf');
//    }


    //设置回复人 参数1为回复人邮箱 参数2为该回复人设置的昵称
    //$mail->addReplyTo('*****@126.com', 'Information');

    if ($file !== '') $mail->AddAttachment($file); // 添加附件

    $mail->isHTML(true);    //邮件正文是否为html编码 true或false
    $mail->Subject = $subject;     //邮件主题
    $mail->Body = $body;           //邮件正文 若isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取的html文件
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';  //附加信息，可以省略

    return $mail->Send() ? true : 'ErrorInfo:' . $mail->ErrorInfo;
}


error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(120);
date_default_timezone_set('UTC');
$start = microtime(true);


date_default_timezone_set('PRC');
//查看周报附件是否存在
if (date('w') == 0) {
    foreach ($not_submit_id as $value) {
        $not_submit_info = '【温馨提示】系统检测到 <strong>'.$info[$value]['name'].'</strong> 未提交周报，请及时<a href="http://49.233.33.23/run/">提交周报</a>！</br>每周周报提交截止时间为23:59，逾期未提交，请单独发给梁老师。</br></br>';
        echo($not_submit_info);
        
        $send_result = send_mail_by_smtp($info[$value]['email'], array(), date('Ymd').' 周报提交提醒', $not_submit_info, '');
        
        if ($send_result == true) {
	        echo('发送成功！！！</br>');
        } else {
	        echo('发送失败！！！</br>');
        }
    }
} else {
    echo('</br>***未到发送时间***</br></br>');
    foreach ($not_submit_id as $value) {
        $not_submit_info = '【温馨提示】系统检测到 <strong>'.$info[$value]['name'].'</strong> 未提交周报，请及时<a href="http://49.233.33.23/run/">提交周报</a>！</br>每周周报提交截止时间为23:59，逾期未提交，请单独发给梁老师。</br></br>';
        echo($not_submit_info);
    }
}
?>