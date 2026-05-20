<?php
/**
 * متحكم مساحات العمل وغرف الدردشة الآمنة
 */
class Workspaces extends Controller {
    private $workspaceModel;

    public function __construct() {
        if (!Controller::isLoggedIn()) {
            header('Location: ' . URLROOT . '/users/login');
            exit();
        }
        $this->workspaceModel = $this->model('Workspace');
    }

# دالة عرض مساحة العمل وإرسال الرسائل والمرفقات (نسخة محدثة ومصلحة)
    public function room($escrowId) {
        $escrow = $this->workspaceModel->getEscrowById($escrowId);
        if (!$escrow) {
            die("مساحة العمل المطلوبة غير موجودة.");
        }

        if ($_SESSION['user_id'] != $escrow['clientId'] && $_SESSION['user_id'] != $escrow['freelancerId'] && $_SESSION['user_role'] != 'admin') {
            $_SESSION['flash_error'] = 'انتهاك أمني: لا تمتلك صلاحية دخول مساحة العمل هذه!';
            header('Location: ' . URLROOT . '/dashboard');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
            
            if ($escrow['status'] !== 'in_progress' && $escrow['status'] !== 'disputed') {
                $_SESSION['flash_error'] = 'المحادثة مغلقة نظراً لانتهاء حالة المشروع.';
                header('Location: ' . URLROOT . '/workspaces/room/' . $escrowId);
                exit();
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            
            $data = [
                'escrowId'   => $escrowId,
                'senderId'   => $_SESSION['user_id'],
                'message'    => isset($_POST['message']) ? trim($_POST['message']) : '',
                'attachment' => null
            ];

            // معالجة رفع ملف الـ PDF الآمن وتصحيح مسار الحفظ
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
                $file = $_FILES['pdf_file'];
                $fileName = $file['name'];
                $fileTmpName = $file['tmp_name'];
                
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $fileTmpName);
                finfo_close($finfo);

                if ($fileExt === 'pdf' && $mimeType === 'application/pdf') {
                    $newFileName = uniqid('lataqlaq_', true) . '.pdf';
                    
                    // استخدام مسار حقيقي صارم يعتمد على موقع الملف الحالي للوصول لمجلد public/uploads
                    $uploadTarget = dirname(__DIR__, 2) . '/public/uploads/' . $newFileName;

                    if (move_uploaded_file($fileTmpName, $uploadTarget)) {
                        $data['attachment'] = $newFileName;
                    }
                } else {
                    $_SESSION['flash_error'] = 'فشل الرفع! يُسمح برفع ملفات المستندات بصيغة PDF فقط لحماية المنصة.';
                    header('Location: ' . URLROOT . '/workspaces/room/' . $escrowId);
                    exit();
                }
            }

            // فحص الأمان: يسمح بمرور الطلب إذا كان هناك نص أو كان هناك ملف مرفوع
            if (empty($data['message']) && empty($data['attachment'])) {
                $_SESSION['flash_error'] = 'لا يمكن إرسال رسالة فارغة بدون مرفق.';
                header('Location: ' . URLROOT . '/workspaces/room/' . $escrowId);
                exit();
            }

            if ($this->workspaceModel->saveMessage($data)) {
                header('Location: ' . URLROOT . '/workspaces/room/' . $escrowId);
                exit();
            }
        }

        // معالجة الضغط على زر "إنهاء واستلام المشروع" من قِبل العميل
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complete_project'])) {
            if ($_SESSION['user_id'] != $escrow['clientId']) {
                $_SESSION['flash_error'] = 'صلاحية مرفوضة.';
                header('Location: ' . URLROOT . '/workspaces/room/' . $escrowId);
                exit();
            }

            if ($escrow['status'] === 'in_progress') {
                if ($this->workspaceModel->completeProject($escrowId, $escrow['freelancerId'], $escrow['price'])) {
                    $_SESSION['flash_success'] = 'تم استلام المشروع بنجاح وإطلق الأرباح لحساب المستقل!';
                } else {
                    $_SESSION['flash_error'] = 'حدث خطأ أثناء معالجة العملية.';
                }
            }
            header('Location: ' . URLROOT . '/workspaces/room/' . $escrowId);
            exit();
        }

        $data = [
            'escrow'   => $escrow,
            'messages' => $this->workspaceModel->getWorkspaceMessages($escrowId)
        ];

        $this->view('workspaces/room', $data);
    }

    # دالة فرعية لجلب الرسائل بصيغة JSON لتحديث الشات تلقائياً عبر الجافاسكريبت
    public function get_messages_json($escrowId) {
        // التحقق من الأمان أولاً
        $escrow = $this->workspaceModel->getEscrowById($escrowId);
        if (!$escrow || ($_SESSION['user_id'] != $escrow['clientId'] && $_SESSION['user_id'] != $escrow['freelancerId'] && $_SESSION['user_role'] != 'admin')) {
            echo json_encode([]);
            exit();
        }

        // جلب الرسائل وإرسالها فوراً
        $messages = $this->workspaceModel->getWorkspaceMessages($escrowId);
        
        // تمرير معرف المستخدم الحالي داخل المصفوفة ليعرف الجافاسكريبت من المرسل
        foreach ($messages as &$msg) {
            $msg['current_user_id'] = $_SESSION['user_id'];
            $msg['url_root'] = URLROOT;
        }
        
        header('Content-Type: application/json');
        echo json_encode($messages);
        exit();
    }

    # دالة تمكين العميل أو المستقل من فتح نزاع رسمي على المشروع (نسخة مطابقة لمعيار MVC)
    public function raise_dispute($escrowId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // 1. جلب بيانات الخزنة والتحقق من الصلاحية
            $escrow = $this->workspaceModel->getEscrowById($escrowId);
            if (!$escrow || $escrow['status'] !== 'in_progress') {
                $_SESSION['flash_error'] = 'لا يمكن فتح نزاع على هذا العقد حالياً.';
                header('Location: ' . URLROOT . '/workspaces/room/' . $escrowId);
                exit();
            }

            // 2. التحقق من أن القائم بالعملية هو العميل أو المستقل المرتبط بالعقد
            if ($_SESSION['user_id'] != $escrow['clientId'] && $_SESSION['user_id'] != $escrow['freelancerId']) {
                $_SESSION['flash_error'] = 'انتهاك أمني: غير مصرح لك بفتح نزاع.';
                header('Location: ' . URLROOT . '/dashboard');
                exit();
            }

            // تصفية حقل سبب النزاع
            $reason = filter_var(trim($_POST['dispute_reason']), FILTER_SANITIZE_SPECIAL_CHARS);
            if (empty($reason)) {
                $_SESSION['flash_error'] = 'يرجى كتابة سبب واضح لفتح النزاع لتتمكن الإدارة من مراجعته.';
                header('Location: ' . URLROOT . '/workspaces/room/' . $escrowId);
                exit();
            }

            // 3. استدعاء الموديل لتنفيذ المعاملة المترابطة خلف الكواليس
            if ($this->workspaceModel->createDispute($escrowId, $_SESSION['user_id'], $reason)) {
                $_SESSION['flash_success'] = 'تم تقديم طلب النزاع الرسمي بنجاح، وتجميد الخزنة المالية. تم تحويل القضية لمدير النظام .';
            } else {
                $_SESSION['flash_error'] = 'حدث خطأ غير متوقع أثناء تسجيل النزاع، يرجى المحاولة لاحقاً.';
            }

            header('Location: ' . URLROOT . '/workspaces/room/' . $escrowId);
            exit();
        }
    }
}