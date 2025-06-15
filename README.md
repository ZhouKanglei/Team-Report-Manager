# 🧾 课题组周报提交系统：从混乱到自动化

> 每到周末，组长最头疼的莫过于“催交周报”与“整理汇总”两件事。组员拖延、格式混乱、重复上传等问题屡见不鲜，手动整合一份整洁的汇报材料，简直堪比打 Boss。为了提高效率、减轻负担，我们搭建了一个周报提交系统，不仅支持组员周报上传与自动合并、邮件发送，还能归档历史记录，彻底解放双手。
>
> ![](https://raw.githubusercontent.com/ZhouKanglei/jidianxia/master/picgo/202506151319205.png)
>
> 项目地址：https://github.com/ZhouKanglei/Team-Report-Manager



本文将详细介绍该系统的功能特点、部署方法与注意事项，欢迎有类似需求的朋友参考使用～ 👇

## 📌 项目背景

我们一度尝试[微信小程序](https://github.com/ZhouKanglei/jixia_helper "稷下助手")解决这个问题，确实降低了组员提交的门槛，但也不可避免地带来了内容质量不稳定、格式多样等问题，**效率提升了，质量却打了折扣**。

于是，在 [cenyc 师兄](https://github.com/cenyc "师兄 Github") 提供的初始代码基础上，我们做了定制化改进，开发了这个**面向课题组的周报提交平台**。

![](https://raw.githubusercontent.com/ZhouKanglei/jidianxia/master/picgo/202506151258753.png)

---

## 🚀 功能简介

平台目前支持以下核心功能，覆盖了周报管理的主流程：

-   📄 支持 **PDF 周报上传**
    
-   📬 每周自动合并全部周报并发送至指定邮箱（如导师）
    
-   📂 可查看 **历史提交记录**
    

通过定时任务和邮件自动化，极大**减轻了组长的重复性工作负担**。

---

## ⚙ 参数配置说明

如需部署多个研究小组，只需在服务器根目录下为每组创建一个子文件夹：

![](https://raw.githubusercontent.com/ZhouKanglei/jidianxia/master/picgo/202506151304748.png)

各小组的成员信息、收件人邮箱等信息在 `run/param.php` 中统一配置：

```php
$info = array(
    0 => array("name" => "组员 1", "email" => "mail@qq.com"),
    1 => array("name" => "组员 2", "email" => "mail@qq.com"),
);

$cc = array('mail1@qq.com', 'mail2@qq.com');

$leader_email = 'leader@qq.com';     // 收件人（导师）
$leader_name = '组长';               // 显示名称
$subject = '周报';                   // 邮件标题

$sender_email = 'sender@qq.com';     // 发件人邮箱
$sender_name = '发送者';
$sender_password = 'smtp授权码';      // QQ邮箱为授权码，非登录密码
```

---

## 🧩 使用宝塔面板部署站点（建议）

为方便运维，推荐使用 [宝塔面板](https://www.bt.cn/) 进行部署，可视化程度高、维护方便。

### 📌 前提条件

确保服务器已安装宝塔面板，并配置以下环境：

-   PHP ≥ 7.2
    
-   Apache / Nginx
    
-   可选数据库（如需拓展）
    
-   启用 `SMTP`（用于邮件发送）
    

---

### 📂 第一步：上传项目代码

1.  登录宝塔面板 →【网站】→【添加站点】
    
2.  设置域名或使用内网 IP，设置根目录（例如 `/www/wwwroot/weekly_report`）
    
3.  上传项目压缩包并解压至该目录
    

---

### ⚙ 第二步：启用 PHP 扩展

进入【站点设置】→【PHP设置】，确保开启以下扩展：

-   `fileinfo`（上传PDF所需）
    
-   `openssl`（邮件功能所需）
    

调整上传限制（如上传文件较大）：

```ini
upload_max_filesize = 10M
post_max_size = 20M
```

---

### 📧 第三步：配置 SMTP 邮箱信息

编辑 `run/param.php`，填入发送邮箱及授权码。推荐使用 QQ 邮箱并启用 SSL 端口（465）以保证邮件稳定投递。

---

### ⏰ 第四步：设置定时任务

在【计划任务】中添加 Shell 脚本，每周定时执行：

-   类型：Shell脚本
    
-   执行周期：每周日 23:59
    
-   脚本内容（注意替换路径）：
    

```bash
php /www/wwwroot/weekly_report/run/combine_pdf.php
```

![](https://raw.githubusercontent.com/ZhouKanglei/jidianxia/master/picgo/202506151311784.png)

---

### ✅ 第五步：测试平台运行

部署完成后，浏览器访问你的服务器地址：

1.  上传一份 PDF 周报
    
2.  手动运行 `combine_pdf.php` 或等待定时任务
    
3.  检查邮箱是否收到合并后的周报
    

---

## 🔒 写在最后：关于隐私和安全

当前系统尚属轻量级实现，**未集成用户登录与权限系统**，存在如下隐私风险：

-   上传内容未加密，所有访问者可查看；
    
-   无身份验证机制，潜在安全隐患；
    
-   建议内部使用或配合 `.htpasswd` 等基础保护方式；
    

但从实际使用角度看，已**满足一个课题组的日常使用需求**，显著提升了组织与沟通效率。

---

## 💬 后续计划

欢迎大家基于本项目进行二次开发或提出改进建议：

-   引入身份认证（如 OAuth、CAS 登录）
    
-   支持多文件上传 / 进度记录
    
-   对接飞书 / 企业微信自动通知
    

---

感谢阅读，如果你也在为收集组员周报头疼，不妨一试！🚀