<?php
/**
 * متحكم المستخدمين - يعالج التسجيل، تسجيل الدخول، وتأمين الجلسات
 */
class Users extends Controller {
    private $userModel;

    public function __construct() {
        // استدعاء موديل المستخدم تلقائياً عند تهيئة الكنترولر
        $this->userModel = $this->model('User');
    }

    # دالة عرض ومعالجة صفحة إنشاء حساب جديد
    public function register() {
        // التحقق مما إذا كان الطلب قادماً عبر فورم (POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // تصفية المدخلات النصية لحماية الموقع من ثغرات الحقن النصي XSS
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // تجهيز البيانات القادمة من الفورم 
            $data = [
                'userName'   => trim($_POST['userName']),
                'email'      => trim($_POST['email']),
                'password'   => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'secretWord' => trim($_POST['secretWord']),
                'title'      => 'إنشاء حساب جديد'
            ];

            // --- معالجة الحالات الاستثنائية والتحقق من الأخطاء (Edge Cases Validation) ---
            
            // 1. التحقق من وجود حقول فارغة
            if (empty($data['userName']) || empty($data['email']) || empty($data['password']) || empty($data['secretWord'])) {
                $_SESSION['flash_error'] = 'يرجى ملء جميع الحقول المطلوبة.';
                $this->view('users/register', $data);
                return;
            }

            // 2. التحقق من صحة صيغة البريد الإلكتروني
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash_error'] = 'صيغة البريد الإلكتروني غير صحيحة.';
                $this->view('users/register', $data);
                return;
            }

            // 3. التحقق من قوة كلمة المرور (على الأقل 6 خانات كمثال للبساطة)
            if (strlen($data['password']) < 6) {
                $_SESSION['flash_error'] = 'يجب ألا تقل كلمة المرور عن 6 أحرف أو أرقام.';
                $this->view('users/register', $data);
                return;
            }

            // 4. التحقق من تطابق كلمتي المرور
            if ($data['password'] != $data['confirm_password']) {
                $_SESSION['flash_error'] = 'كلمات المرور غير متطابقة.';
                $this->view('users/register', $data);
                return;
            }

            // 5. التحقق من عدم تكرار البريد الإلكتروني في قاعدة البيانات
            if ($this->userModel->findUserByEmail($data['email'])) {
                $_SESSION['flash_error'] = 'هذا البريد الإلكتروني مسجل بالفعل في المنصة.';
                $this->view('users/register', $data);
                return;
            }

            // --- إتمام عملية التسجيل بعد اجتياز كافة الفحوصات بنجاح ---
            if ($this->userModel->register($data)) {
                // إرسال رسالة نجاح ومضية وتوجيه المستخدم لصفحة تسجيل الدخول (سنبنيها لاحقاً)
                $_SESSION['flash_success'] = 'تم إنشاء الحساب بنجاح! يمكنك الآن تسجيل الدخول.';
                header('Location: ' . URLROOT . '/users/login');
                exit();
            } else {
                $_SESSION['flash_error'] = 'حدث خطأ ما أثناء التسجيل، يرجى المحاولة لاحقاً.';
                $this->view('users/register', $data);
            }

        } else {
            // إذا كان الطلب GET عادي (مجرد فتح الصفحة)، يتم تصفير البيانات وعرض الواجهة
            $data = [
                'userName'   => '',
                'email'      => '',
                'password'   => '',
                'confirm_password' => '',
                'secretWord' => '',
                'title'      => 'إنشاء حساب جديد'
            ];

            $this->view('users/register', $data);
        }
    }

    # دالة عرض ومعالجة صفحة تسجيل الدخول
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // تصفية المدخلات لحماية الموقع من ثغرات XSS
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'email'    => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'title'    => 'تسجيل الدخول'
            ];

            // 1. التحقق من وجود حقول فارغة
            if (empty($data['email']) || empty($data['password'])) {
                $_SESSION['flash_error'] = 'يرجى إدخال البريد الإلكتروني وكلمة المرور.';
                $this->view('users/login', $data);
                return;
            }

            // 2. محاولة تسجيل الدخول عبر الموديل والتحقق من البيانات المشفرة
            $loggedInUser = $this->userModel->login($data['email'], $data['password']);

            if ($loggedInUser) {
                // إذا كانت البيانات صحيحة، قم بإنشاء الجلسة الآمنة للمستخدم
                $this->createUserSession($loggedInUser);
            } else {
                // إذا فشل التحقق (إما الإيميل خطأ أو كلمة المرور خطأ)
                $_SESSION['flash_error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة.';
                $this->view('users/login', $data);
            }

        } else {
            // طلب GET عادي لعرض الصفحة
            $data = [
                'email' => '',
                'password' => '',
                'title' => 'تسجيل الدخول'
            ];

            $this->view('users/login', $data);
        }
    }

    # دالة داخلية سرية لتهيئة جلسة المستخدم الآمنة بعد نجاح تسجيل الدخول
    private function createUserSession($user) {
        $_SESSION['user_id'] = $user['userId'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['userName'];
        $_SESSION['user_role'] = $user['role']; // حفظ الـ Role (user أو admin) للتحكم بالصلاحيات

        // إرسال رسالة ترحيبية وتوجيهه إلى الكنترولر الافتراضي (الرئيسية)
        $_SESSION['flash_success'] = 'مرحباً بك مجدداً يا ' . $user['userName'] . '!';
        if ($user['role'] === 'admin') {
            header('Location: ' . URLROOT . '/admin/dashboard'); // توجيه الأدمن إلى لوحة التحكم الخاصة به
        } else {
            header('Location: ' . URLROOT . '/dashboard'); // توجيه المستخدم العادي إلى لوحة التحكم الرئيسية
        }
        exit();
    }

    # دالة تسجيل الخروج الآمن وسد ثغرة زر الرجوع
    public function logout() {
        // تفريغ مصفوفة الجلسة وتدميرها بالكامل
        $_SESSION = array();
        session_destroy();

        // إرسال ترويسات حظر الكاش للتأكيد الصارم على المتصفح
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // التوجيه لصفحة تسجيل الدخول مع رسالة ومضية
        session_start(); // فتح جلسة مؤقتة فقط لتمرير رسالة الخروج الومضية
        $_SESSION['flash_success'] = 'تم تسجيل خروجك بنجاح وأمان.';
        header('Location: ' . URLROOT );
        exit();
    }

    # دالة شحن الرصيد التجريبي
    public function deposit() {
        // حماية الصفحات: منع غير المسجلين من دخول هذه الدالة
        if (!Controller::isLoggedIn()) {
            header('Location: ' . URLROOT . '/users/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $amount = floatval($_POST['amount']);

            // Edge Case: التحقق من أن المبلغ أكبر من صفر وليس رقماً سالباً أو خيالياً
            if ($amount <= 0 || $amount > 5000) {
                $_SESSION['flash_error'] = 'يرجى إدخال مبلغ شحن صحيح بين 1 و 5000 دولار.';
                header('Location: ' . URLROOT . '/dashboard');
                exit();
            }

            // تحديث الرصيد في قاعدة البيانات
            if ($this->userModel->updateBalance($_SESSION['user_id'], $amount)) {
                $_SESSION['flash_success'] = 'تم شحن حسابك بمبلغ ' . $amount . '$ بنجاح!';
            } else {
                $_SESSION['flash_error'] = 'حدث خطأ غير متوقع أثناء شحن الرصيد.';
            }
            
            header('Location: ' . URLROOT . '/dashboard');
            exit();
        }
    }

    # دالة عرض ومعالجة استعادة كلمة المرور عبر الكلمة السرية المشفرة
    public function forgot_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // تصفية المدخلات
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'email'            => trim($_POST['email']),
                'secretWord'       => trim($_POST['secretWord']),
                'new_password'     => trim($_POST['new_password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'title'            => 'استعادة كلمة المرور'
            ];

            // 1. التحقق من الحقول الأساسية
            if (empty($data['email']) || empty($data['secretWord']) || empty($data['new_password']) || empty($data['confirm_password'])) {
                $_SESSION['flash_error'] = 'يرجى ملء جميع الحقول المطلوبة.';
                $this->view('users/forgot_password', $data);
                return;
            }

            // 2. التحقق من قوة وتطابق كلمة المرور الجديدة
            if (strlen($data['new_password']) < 6) {
                $_SESSION['flash_error'] = 'يجب ألا تقل كلمة المرور الجديدة عن 6 أحرف أو أرقام.';
                $this->view('users/forgot_password', $data);
                return;
            }

            if ($data['new_password'] !== $data['confirm_password']) {
                $_SESSION['flash_error'] = 'كلمات المرور الجديدة غير متطابقة.';
                $this->view('users/forgot_password', $data);
                return;
            }

            // 3. التحقق الأمني من البريد والكلمة السرية (عبر الموديل الذي يفك التشفير)
            $user = $this->userModel->verifySecretWord($data['email'], $data['secretWord']);

            if (!$user) {
                // رسالة مبهمة أمنياً لمنع الاستكشاف
                $_SESSION['flash_error'] = 'عذراً، البريد الإلكتروني أو الكلمة السرية غير صحيحة.';
                $this->view('users/forgot_password', $data);
                return;
            }

            // 4. تنفيذ التحديث النهائي في قاعدة البيانات
            if ($this->userModel->updatePassword($user['userId'], $data['new_password'])) {
                $_SESSION['flash_success'] = 'تم تحديث كلمة المرور بنجاح! يمكنك الآن تسجيل الدخول.';
                header('Location: ' . URLROOT . '/users/login');
                exit();
            } else {
                $_SESSION['flash_error'] = 'حدث خطأ غير متوقع أثناء تحديث البيانات.';
                $this->view('users/forgot_password', $data);
            }

        } else {
            // طلب GET عادي لعرض الصفحة
            $data = [
                'email'            => '',
                'secretWord'       => '',
                'new_password'     => '',
                'confirm_password' => '',
                'title'            => 'استعادة كلمة المرور'
            ];

            $this->view('users/forgot_password', $data);
        }
    }
}