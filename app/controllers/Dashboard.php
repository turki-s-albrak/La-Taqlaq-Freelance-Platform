<?php
/**
 * متحكم لوحة التحكم الخاصة بالمستخدم
 */
class Dashboard extends Controller {
    private $userModel;

    public function __construct() {
        if (!Controller::isLoggedIn()) {
            header('Location: ' . URLROOT . '/users/login');
            exit();
        }
        $this->userModel = $this->model('User');
    }

    public function index() {
        // جلب بيانات المستخدم المحدثة من قاعدة البيانات لقراءة الرصيد الحالي بدقة
        $userData = $this->userModel->findUserByEmail($_SESSION['user_email']);

        $data = [
            'title' => 'لوحة التحكم',
            'balance' => $userData['balance']
        ];

        $this->view('dashboard', $data);
    }
}