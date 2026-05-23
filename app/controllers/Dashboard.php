<?php
/**
 * متحكم لوحة التحكم الخاصة بالمستخدم (مركز العمليات)
 */
class Dashboard extends Controller {
    private $userModel;
    private $orderModel;
    private $workspaceModel;

    public function __construct() {
        if (!Controller::isLoggedIn()) {
            header('Location: ' . URLROOT . '/users/login');
            exit();
        }
        
        // تحميل الموديلات اللازمة للوحة التحكم
        $this->userModel = $this->model('User');
        $this->orderModel = $this->model('Order');
        $this->workspaceModel = $this->model('Workspace');
    }

    public function index() {
        // 1. جلب الرصيد المالي الحي بدقة عالية
        $userData = $this->userModel->findUserByEmail($_SESSION['user_email']);

        // 2. جلب إحصائيات العميل (مشاريعي)
        $myProjects = $this->orderModel->getClientProjects($_SESSION['user_id']);
        
        // 3. جلب إحصائيات المستقل (أعمالي)
        $myWork = $this->workspaceModel->getFreelancerWorkspaces($_SESSION['user_id']);

        // 4. تمرير البيانات المجمعة للواجهة
        $data = [
            'title'         => 'لوحة التحكم | مركز العمليات',
            'balance'       => $userData['balance'],
            'projectsCount' => count($myProjects),
            'worksCount'    => count($myWork)
        ];

        // ملاحظة: تأكد من مسار ملف الواجهة لديك، عادة يكون داخل مجلد pages
        $this->view('dashboard', $data); 
    }
}