<?php
/**
 * الكنترولر الأساسي - جميع الكنترولرز الأخرى ترث منه
 */
class Controller {
    
    # دالة جلب الموديل (Model) للتعامل مع قاعدة البيانات
    public function model($model) {
        // التحقق من وجود ملف الموديل
        if (file_exists('../app/models/' . $model . '.php')) {
            require_once '../app/models/' . $model . '.php';
            // إنشاء كائن جديد من الموديل (مثلاً: return new User();)
            return new $model();
        } else {
            die("الموديل " . $model . " غير موجود.");
        }
    }

    # دالة استدعاء الواجهة (View) مع دمج الهيدر والفوتر تلقائياً وتفكيك البيانات
    public function view($view, $data = []) {
        // التحقق من وجود ملف الواجهة داخل مجلد pages
        if (file_exists('../app/views/pages/' . $view . '.php')) {
            
            // تفكيك المصفوفة وتحويل المفاتيح إلى متغيرات صريحة داخل الـ View
            extract($data);

            // استدعاء الهيدر التلقائي (يحتوي على النبار المشترك)
            if (file_exists('../app/views/layout/header.php')) {
                require_once '../app/views/layout/header.php';
            }

            // استدعاء الصفحة المطلوبة ذاتها
            require_once '../app/views/pages/' . $view . '.php';

            // استدعاء الفوتر التلقائي (يحتوي على الجافاسكريبت والـ Bootstrap)
            if (file_exists('../app/views/layout/footer.php')) {
                require_once '../app/views/layout/footer.php';
            }
            
        } else {
            // في حال عدم وجود الصفحة
            die("الواجهة (View) غير موجودة.");
        }
    }

    # دالة مساعدة للتحقق مما إذا كان المستخدم مسجل الدخول حالياً
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    # دالة مساعدة لجلب دور المستخدم الحالي (user أم admin)
    public static function getUserRole() {
        return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
    }
}