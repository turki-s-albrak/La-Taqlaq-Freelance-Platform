<?php
/**
 * كلاس النواة (Router) - يقرأ الرابط ويوجهه للكنترولر المناسب
 */
class App {
    // المتحكم الافتراضي، الدالة الافتراضية، والمتغيرات الافتراضية
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        # 1. التحقق من وجود الكنترولر المطلوب في مجلد controllers
        if (isset($url[0])) {
            // تحويل الحرف الأول لكبير ليطابق تسمية الملفات (مثلاً: pages تصبح Pages)
            $controllerName = ucwords($url[0]);
            
            if (file_exists('../app/controllers/' . $controllerName . '.php')) {
                $this->currentController = $controllerName;
                unset($url[0]);
            }
        }

        # 2. استدعاء ملف الكنترولر وإنشاء كائن (Instance) منه
        require_once '../app/controllers/' . $this->currentController . '.php';
        $this->currentController = new $this->currentController;

        # 3. التحقق من الدالة (Method) المطلوبة داخل الكنترولر
        if (isset($url[1])) {
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        # 4. جلب المتغيرات المتبقية في الرابط (إن وجدت) أو تركها كمصفوفة فارغة
        $this->params = $url ? array_values($url) : [];

        # 5. استدعاء الدالة المحددة وتمرير المتغيرات لها بشكل ديناميكي آمن
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    # دالة معالجة وتصفية الرابط (URL) لحمايته من أي محاولة اختراق
    private function getUrl() {
        if (isset($_GET['url'])) {
            // إزالة السلاش الزائد من نهاية الرابط
            $url = rtrim($_GET['url'], '/');
            // تصفية الرابط من أي حروف غريبة قد تستخدم للاختراق (Sanitization)
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // تفكيك الرابط عبر السلاش إلى مصفوفة
            return explode('/', $url);
        }
        return [];
    }
}
