<?php
/**
 * متحكم الإدارة المركزي - بوابة التحكم المؤمنة بالكامل
 */
class Admin extends Controller {
    private $adminModel;
    private $workspaceModel; // أضفناه للتحقق من العقود المالية قبل الحذف

    public function __construct() {
        // --- 🔒 بوابة التأمين الصارمة (The Security Gate) ---
        // فحص: إذا لم يكن مسجلاً، أو كان مسجلاً ولكن دوره ليس 'admin'، اطرده فوراً!
        if (!Controller::isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
            // فتح جلسة مؤقتة إن لم تكن نشطة لتمرير الرسالة التحذيرية
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['flash_error'] = 'انتهاك أمني: لا تمتلك صلاحيات مدير النظام لدخول هذه الصفحة!';
            header('Location: ' . URLROOT );
            exit();
        }

        // تهيئة موديل الإدارة والموديلات المساعدة
        $this->adminModel = $this->model('AdminModel');
        $this->workspaceModel = $this->model('Workspace'); 
    }

    # 1. الصفحة الرئيسية للإدارة (الملخص والإحصائيات)
    public function dashboard() {
        $stats = $this->adminModel->getCounts();

        $data = [
            'page_title' => 'لوحة تحكم مدير النظام',
            'stats'      => $stats
        ];

        $this->view('admin/dashboard', $data);
    }

    # 2. صفحة إدارة المستخدمين (جدول الأعضاء، الترقية، الحظر)
    public function users() {
        $users = $this->adminModel->getAllUsers();

        $data = [
            'page_title' => 'إدارة أعضاء المنصة',
            'users'      => $users
        ];

        $this->view('admin/users', $data);
    }

    // دالة معالجة الترقية إلى أدمن
    public function make_admin($userId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->adminModel->updateUserRole($userId, 'admin')) {
                $_SESSION['flash_success'] = 'تم ترقية المستخدم إلى مدير نظام بنجاح.';
            }
            header('Location: ' . URLROOT . '/admin/users');
            exit();
        }
    }

    // دالة معالجة حظر/حذف مستخدم
    public function ban_user($userId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // منع الأدمن من حظر نفسه بالخطأ
            if ($userId == $_SESSION['user_id']) {
                $_SESSION['flash_error'] = 'حظر أمني: لا يمكنك حظر أو حذف حسابك الشخصي الحالي!';
                header('Location: ' . URLROOT . '/admin/users');
                exit();
            }

            if ($this->adminModel->deleteUser($userId)) {
                $_SESSION['flash_success'] = 'تم حظر وإقصاء المستخدم من المنصة نهائياً.';
            }
            header('Location: ' . URLROOT . '/admin/users');
            exit();
        }
    }

    # 3. صفحة إدارة ومراقبة المشاريع
    public function projects() {
        $projects = $this->adminModel->getAllOrdersWithStatus();

        $data = [
            'page_title' => 'مراقبة وإدارة مشاريع المنصة',
            'projects'   => $projects
        ];

        $this->view('admin/projects', $data);
    }

    // دالة معالجة حذف مشروع (مع الحماية المالية المعمارية)
    public function delete_project($orderId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 🚨 حماية مالية: فحص ما إذا كان المشروع يحتوي على عقد مالي نشط أو نزاع
            $escrow = $this->workspaceModel->getEscrowByOrderId($orderId);
            if ($escrow && in_array($escrow['status'], ['in_progress', 'disputed'])) {
                $_SESSION['flash_error'] = 'حظر مالي: لا يمكنك حذف مشروع يحتوي على أموال محجوزة أو نزاع قائم! قم بحل النزاع أولاً.';
                header('Location: ' . URLROOT . '/admin/projects');
                exit();
            }

            if ($this->adminModel->deleteOrder($orderId)) {
                $_SESSION['flash_success'] = 'تم حذف المشروع وتطهير المحتوى بنجاح.';
            } else {
                $_SESSION['flash_error'] = 'حدث خطأ غير متوقع أثناء محاولة حذف المشروع.';
            }
            header('Location: ' . URLROOT . '/admin/projects');
            exit();
        }
    }

    # 4. صفحة النزاعات العامة للمدير
    public function disputes() {
        $disputes = $this->adminModel->getDisputes();
        $data = [
            'page_title' => 'إدارة ونزاعات العقود المالية',
            'disputes'   => $disputes
        ];
        $this->view('admin/disputes', $data);
    }

    # 5. غرفة معاينة النزاع (التحكيم)
    public function review_dispute($disputeId) {
        $dispute = $this->adminModel->getDisputeById($disputeId);
        
        // 🚨 التعديل المعماري: ارتداد رشيق بدلاً من الشاشة البيضاء (die)
        if (!$dispute) {
            $_SESSION['flash_error'] = 'عذراً، النزاع المطلوب غير موجود أو تم حسمه مسبقاً.';
            header('Location: ' . URLROOT . '/admin/disputes');
            exit();
        }

        // استدعاء موديل مساحة العمل لجلب رسائل الشات للرقابة
        $chatMessages = $this->workspaceModel->getWorkspaceMessages($dispute['escrowId']);

        // معالجة اتخاذ القرار (POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($dispute['status'] !== 'open') {
                $_SESSION['flash_error'] = 'تم حسم هذا النزاع وإغلاقه مسبقاً.';
                header('Location: ' . URLROOT . '/admin/disputes');
                exit();
            }

            $decision = $_POST['decision'];

            if ($decision === 'refund') {
                // قرار لصالح العميل
                if ($this->adminModel->resolveRefundClient($dispute['escrowId'], $dispute['clientId'], $dispute['price'], $disputeId)) {
                    $_SESSION['flash_success'] = 'تم حسم النزاع قضائياً، وإعادة كافة الأموال المحجوزة لرصيد العميل بنجاح.';
                }
            } elseif ($decision === 'pay') {
                // قرار لصالح المستقل
                if ($this->adminModel->resolvePayFreelancer($dispute['escrowId'], $dispute['freelancerId'], $dispute['price'], $disputeId)) {
                    $_SESSION['flash_success'] = 'تم حسم النزاع قضائياً، وصرف كامل المبالغ المحجوزة لحساب أرباح المستقل بنجاح.';
                }
            }

            header('Location: ' . URLROOT . '/admin/disputes');
            exit();
        }

        $data = [
            'page_title' => 'غرفة التحكيم ومعاينة النزاع المعلق',
            'dispute'    => $dispute,
            'messages'   => $chatMessages
        ];
        $this->view('admin/review_dispute', $data);
    }
}