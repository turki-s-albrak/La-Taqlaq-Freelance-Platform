
<?php
/**
 * منصة لا تقلق (lataqlaq) - نقطة الدخول الموحدة
 */

# 1. إعدادات الأمان للجلسة (تأمين الـ Cookies قبل بدء الجلسة)
ini_set('session.cookie_httponly', 1); // منع الوصول للجلسة عبر الجافاسكريبت (حماية من XSS)
ini_set('session.use_only_cookies', 1);

session_start();

# 2. حظر الكاش فوراً لحماية البيانات وحل ثغرة زر الرجوع في المتصفح
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

# 3. استدعاء ملفات النواة والإعدادات الأساسية للتطبيق
require_once '../app/config/config.php';
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/database/Database.php';

# 4. تشغيل تطبيق الـ MVC (الـ Router الرئيسي)
$app = new App();