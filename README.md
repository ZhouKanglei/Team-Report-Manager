# 课题组周报提交系统

## 📌 项目背景
为了配合导师每周查看周报的要求，作为小组组长，我需定期向各位组员收集周报，并将其整合后发送给导师。然而，手动收集与合并多个组员的周报不仅繁琐、耗时，还容易出错，极大增加了管理成本。

![](https://raw.githubusercontent.com/ZhouKanglei/jidianxia/master/picgo/202506151319205.png)

起初我们尝试使用微信小程序自动化流程，尽管极大地方便了组员提交，但由于内容质量参差不齐，未能满足导师预期。为平衡效率与质量，在 [cenyc 师兄](https://github.com/cenyc "师兄 Github 主页")提供的代码基础上进行了微小的适配与功能改进，搭建了本周报提交平台。

![运动组提交页面](https://raw.githubusercontent.com/ZhouKanglei/jidianxia/master/picgo/202506151258753.png)

该系统支持以下功能：

- 📄 PDF 周报上传

- 📬 周报自动合并并发送邮件

- 📂 历史周报归档与查看


## ⚙ 参数配置

如果你的课题组有多个研究小组，最简单的方法是在站点目录建立多个文件夹：

![](https://raw.githubusercontent.com/ZhouKanglei/jidianxia/master/picgo/202506151304748.png)

请前往 `run/param.php` 修改相关参数：

```php
<?php

$info = array(
    0 => array(
        "name" => "组员 1",
        "email" => "mail@qq.com"
    ),
    1 => array(
        "name" => "组员 2",
        "email" => "mail@qq.com"
    ),
);

$cc = array(
    'mail1@qq.com',
    'mail2@qq.com',
);

$leader_email = 'leader@qq.com'; // 收件人邮箱（导师或组长）
$leader_name = '组长';           // 收件人名称
$subject = '周报';               // 邮件标题

$sender_email = 'sender@qq.com';         // 发件人邮箱
$sender_name = '发送者';                 // 发件人名称
$sender_password = 'password';           // 邮箱授权码（非登录密码）

```

## 🧩 使用宝塔面板部署站点

### 📌 前提条件

1.  已在服务器上安装宝塔面板（https://www.bt.cn/）

![](https://raw.githubusercontent.com/ZhouKanglei/jidianxia/master/picgo/202506151306756.png)
    
2.  已安装以下运行环境（推荐使用 LAMP 或 LNMP）：
    
    -   PHP（建议版本 7.2+）
        
    -   Nginx 或 Apache
        
    -   MySQL（如有数据库需求）
        
    -   phpMyAdmin（可选）
        
    -   开启 `SMTP` 发信功能（QQ 邮箱建议配置 SSL）
        

---

### 📂 步骤一：上传项目文件

1.  登录宝塔面板。
    
2.  进入左侧菜单 →【网站】→【添加站点】
    
    -   填写域名（如无域名，可使用 IP 测试）
        
    -   设置根目录，例如 `/www/wwwroot/weekly_report`
        
3.  添加完成后，点击站点名称 →【根目录】，上传你的项目代码（可以是 ZIP 压缩包）。
    
4.  解压项目到站点根目录下。
    

---

### ⚙ 步骤二：配置运行环境

1.  在站点设置中，选择【PHP版本】，安装并启用以下扩展：
    
    -   `fileinfo`（PDF 上传需要）
        
    -   `openssl`（邮件发送需要）
        
2.  配置 `.user.ini` 或 `php.ini` 以满足上传文件大小需求（如上传周报 >2MB）：
    
    ```ini
    upload_max_filesize = 10M
    post_max_size = 20M
    ```
    

---

### 📧 步骤三：配置 SMTP 邮件功能

修改 `run/param.php` 文件，填写正确的邮箱发件信息。默认使用 QQ 邮箱发送邮件，可以修改 `run/combine_pdf.php`。

### ⏰ 步骤四：配置定时任务（自动合并并发送周报）

1.  进入宝塔 →【计划任务】→【添加任务】
    
    -   类型：Shell脚本
        
    -   执行周期：每周日 23:59
        
    -   脚本内容（请替换为你的实际路径）：
        
        ```bash
        php /www/wwwroot/weekly_report/run/combine_pdf.php
        ```

![](https://raw.githubusercontent.com/ZhouKanglei/jidianxia/master/picgo/202506151311784.png)

2.  可在合适时间手动运行测试，确保邮件正常发送。
    

---

### ✅ 步骤五：测试功能

1.  浏览器访问你的站点地址。
    
2.  上传一个 PDF 测试周报。
    
3.  观察上传效果及合并发送结果（可在邮箱或代码日志中查看）。
    

---


## 🔒 注意事项
当前系统为轻量级实现，尚未集成登录认证机制，因此在隐私性与访问控制方面仍存在一定风险：

- 上传的周报文件未加密，默认可被具有访问权限的用户查看；

- 缺少权限隔离与用户身份验证机制；

- 若部署在公网服务器，请谨慎开放访问入口或通过 .htpasswd 等方式做基础保护。

尽管如此，对于一个小规模的课题组内部使用而言，已基本满足周报收集与管理的需求，显著降低了组长整理周报的工作量。后续可根据实际需求逐步完善系统安全性与用户交互体验。

