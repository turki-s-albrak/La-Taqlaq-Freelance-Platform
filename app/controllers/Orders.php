<?php
/**
 * متحكم المشاريع - يعالج نشر وتصفح المشاريع والعروض
 */
class Orders extends Controller {
    private $orderModel;
    private $bidModel;

    public function __construct() {
        // حماية الصفحات: منع أي زائر غير مسجل من دخول قسم المشاريع
        if (!Controller::isLoggedIn()) {
            header('Location: ' . URLROOT . '/users/login');
            exit();
        }
        
        $this->orderModel = $this->model('Order');
        $this->bidModel = $this->model('Bid');
    }

    # دالة تصفح جميع المشاريع المتاحة في المنصة
    public function index() {
        // جلب المشاريع عبر الموديل
        $orders = $this->orderModel->getOrders();

        $data = [
            'page_title' => 'تصفح المشاريع المتاحة',
            'orders'     => $orders
        ];

        // استدعاء واجهة التصفح وتمرير البيانات لها
        $this->view('orders/index', $data);
    }

    # دالة عرض ومعالجة صفحة إضافة مشروع جديد
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // تصفية المدخلات لحماية الموقع من ثغرات XSS
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'clientId'    => $_SESSION['user_id'],
                'title'       => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'price'       => trim($_POST['price']),
                'page_title'  => 'نشر مشروع جديد'
            ];

            // --- معالجة الحالات الاستثنائية والتحقق من الأخطاء (Edge Cases Validation) ---

            // 1. التحقق من وجود حقول فارغة
            if (empty($data['title']) || empty($data['description']) || empty($data['price'])) {
                $_SESSION['flash_error'] = 'يرجى ملء جميع الحقول المطلوبة لنشر المشروع.';
                $this->view('orders/create', $data);
                return;
            }

            // 2. فحص الميزانية: تحويل المدخل لرقم عشري والتحقق من أنه ليس سالباً أو صفراً
            $priceFloat = floatval($data['price']);
            if ($priceFloat <= 0) {
                $_SESSION['flash_error'] = 'فشل نشر المشروع! يجب أن تكون الميزانية قيمة موجبة أكبر من صفر.';
                $this->view('orders/create', $data);
                return;
            }

            // تحديث القيمة الممررة لتكون رقمية تماماً بعد الفحص
            $data['price'] = $priceFloat;

            // --- إتمام عملية النشر بعد اجتياز الفحوصات بنجاح ---
            if ($this->orderModel->addOrder($data)) {
                $_SESSION['flash_success'] = 'تم نشر مشروعك بنجاح في السوق!';
                // حالياً سنعيده للوحة التحكم لحين إنشاء صفحة تصفح المشاريع
                header('Location: ' . URLROOT . '/dashboard');
                exit();
            } else {
                $_SESSION['flash_error'] = 'حدث خطأ غير متوقع أثناء نشر المشروع، حاول لاحقاً.';
                $this->view('orders/create', $data);
            }

        } else {
            // طلب GET عادي لعرض الصفحة وتصفير البيانات
            $data = [
                'title'       => '',
                'description' => '',
                'price'       => '',
                'page_title'  => 'نشر مشروع جديد'
            ];

            $this->view('orders/create', $data);
        }
    }

    # دالة عرض تفاصيل المشروع وتقديم العروض ومعالجة الثغرات
    public function show($id) {
        // 1. جلب تفاصيل المشروع
        $order = $this->orderModel->getOrderById($id);
        
        if (!$order) {
            $_SESSION['flash_error'] = 'عذراً، المشروع الذي تبحث عنه غير موجود أو تم حذفه.';
            header('Location: ' . URLROOT . '/orders');
            exit();
        }

        // معالجة إرسال عرض جديد (POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'orderId'      => $id,
                'freelancerId' => $_SESSION['user_id'],
                'price'        => trim($_POST['price']),
                'message'      => trim($_POST['message']),
                'order'        => $order,
                'bids'         => $this->bidModel->getBidsByOrderId($id)
            ];

            // --- Edge Cases الفحص الأمني الصارم لتقديم العروض ---

            // الحظر 1: منع العميل صاحب المشروع من تقديم عرض لنفسه
            if ($order['clientId'] == $_SESSION['user_id']) {
                $_SESSION['flash_error'] = 'أمن المنصة: لا يمكنك تقديم عرض عمل على مشروع قمت بنشره بنفسك!';
                header('Location: ' . URLROOT . '/orders/show/' . $id);
                exit();
            }

            // الحظر 2: منع تقديم أكثر من عرض لنفس المستقل على نفس المشروع
            if ($this->bidModel->checkUserBid($id, $_SESSION['user_id'])) {
                $_SESSION['flash_error'] = 'أمن المنصة: لقد قمت بتقديم عرض سابقاً على هذا المشروع، لا يمكن تكرار العروض.';
                header('Location: ' . URLROOT . '/orders/show/' . $id);
                exit();
            }

            // الحظر 3: التحقق من الحقول الفارغة والميزانية السلبية أو الصفرية
            if (empty($data['price']) || empty($data['message']) || floatval($data['price']) <= 0) {
                $_SESSION['flash_error'] = 'يرجى إدخال سعر عرض صحيح أكبر من صفر مع كتابة رسالة العرض.';
                header('Location: ' . URLROOT . '/orders/show/' . $id);
                exit();
            }

            // --- إتمام الحفظ بعد عبور الفحوصات بنجاح ---
            $data['price'] = floatval($data['price']);
            if ($this->bidModel->addBid($data)) {
                $_SESSION['flash_success'] = 'تم تقديم عرضك المالي بنجاح وبأمان للعميل.';
                header('Location: ' . URLROOT . '/orders/show/' . $id);
                exit();
            } else {
                $_SESSION['flash_error'] = 'حدث خطأ غير متوقع، يرجى المحاولة لاحقاً.';
                header('Location: ' . URLROOT . '/orders/show/' . $id);
                exit();
            }

        } else {
            // طلب GET العادي: جلب العروض الحالية لعرضها في الأسفل
            $bids = $this->bidModel->getBidsByOrderId($id);

            // --- [التعديل المعماري النظيف: تجهيز البيانات هنا بدلاً من الواجهة] ---
            $hasActiveEscrow = false;
            $myBid = null;

            foreach($bids as $b) {
                // التحقق من وجود عقد نشط (لمنع العميل من حذف المشروع)
                if($b['status'] == 'accepted') {
                    $hasActiveEscrow = true;
                }
                // البحث عن عرض المستقل الحالي (لتفعيل فورم التعديل)
                if(isset($_SESSION['user_id']) && $b['freelancerId'] == $_SESSION['user_id']) {
                    $myBid = $b;
                }
            }

            // تمرير جميع البيانات جاهزة ونظيفة للواجهة
            $data = [
                'order'           => $order,
                'bids'            => $bids,
                'hasActiveEscrow' => $hasActiveEscrow,
                'myBid'           => $myBid
            ];

            $this->view('orders/show', $data);
        }
    }


    # دالة معالجة قبول العرض وحجز الأموال في الخزنة الآمنة (النسخة المحمية من التكرار)
    public function accept($bidId) {
        // حماية دلالية: يجب أن يكون الطلب POST لحظر التلاعب عبر الروابط المباشرة
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $bidModel = $this->model('Bid');
            $escrowModel = $this->model('Escrow');
            $userModel = $this->model('User');

            // 1. جلب تفاصيل العرض صمتاً عبر الموديل المخصص للتحقق
            $bid = $escrowModel->getBidById($bidId);

            if (!$bid || $bid['status'] !== 'pending') {
                $_SESSION['flash_error'] = 'عذراً، هذا العرض غير متاح للقبول حالياً.';
                header('Location: ' . URLROOT . '/orders');
                exit();
            }

            //  2. الفحص الحاسم: سؤال الموديل إن كان للمشروع عقد مسبق لمنع تكرار البيانات
            if ($escrowModel->hasExistingEscrow($bid['orderId'])) {
                $_SESSION['flash_error'] = 'حظر مالي: هذا المشروع لديه عقد مالي نشط بالفعل في الخزنة، ولا يمكن قبول عروض أخرى!';
                header('Location: ' . URLROOT . '/orders/show/' . $bid['orderId']);
                exit();
            }

            // 3. جلب المشروع المرتبط للتأكد أن من يضغط الزر هو العميل صاحب المشروع
            $order = $this->orderModel->getOrderById($bid['orderId']);
            if ($order['clientId'] != $_SESSION['user_id']) {
                $_SESSION['flash_error'] = 'انتهاك أمني: لا تمتلك الصلاحية لقبول عروض هذا المشروع!';
                header('Location: ' . URLROOT . '/orders/show/' . $bid['orderId']);
                exit();
            }

            // 4. فحص رصيد العميل بدقة
            $clientData = $userModel->findUserByEmail($_SESSION['user_email']);
            
            if ($clientData['balance'] < $bid['price']) {
                $_SESSION['flash_error'] = 'فشل قبول العرض! رصيدك الحالي ($' . number_format($clientData['balance'], 2) . ') لا يغطي قيمة العرض ($' . number_format($bid['price'], 2) . '). يرجى شحن حسابك أولاً.';
                header('Location: ' . URLROOT . '/orders/show/' . $bid['orderId']);
                exit();
            }

            // 5. تنفيذ المعاملة المالية وسلسلة التحديثات في الخزنة
            if ($escrowModel->acceptBidAndLockFunds($bid['orderId'], $order['clientId'], $bid['freelancerId'], $bid['price'], $bidId)) {
                $_SESSION['flash_success'] = 'تم قبول العرض بنجاح! وتم خصم المبلغ وحجزه في الخزنة الآمنة. مرحباً بك في مساحة العمل.';
                //  التوجيه فوراً لغرفة العمل 
                header('Location: ' . URLROOT . '/workspaces/room/' . $bid['orderId']);
                exit();
            }

            header('Location: ' . URLROOT . '/orders/show/' . $bid['orderId']);
            exit();
        } else {
            // تحويل تلقائي في حال محاولة الدخول بـ GET
            header('Location: ' . URLROOT . '/orders');
            exit();
        }
    }

    # دالة حذف المشروع (للعميل فقط وقبل التعاقد)
    public function delete($orderId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order = $this->orderModel->getOrderById($orderId);
            $escrowModel = $this->model('Escrow');

            // حماية 1: التأكد من ملكية المشروع
            if (!$order || $order['clientId'] != $_SESSION['user_id']) {
                $_SESSION['flash_error'] = 'لا تملك صلاحية حذف هذا المشروع.';
                header('Location: ' . URLROOT . '/orders');
                exit();
            }

            // حماية 2: التأكد من عدم وجود عقد مالي (لا تحذف مشروعاً قيد التنفيذ)
            if ($escrowModel->hasExistingEscrow($orderId)) {
                $_SESSION['flash_error'] = 'لا يمكن حذف المشروع لأنه يحتوي على عقد مالي نشط.';
                header('Location: ' . URLROOT . '/orders/show/' . $orderId);
                exit();
            }

            if ($this->orderModel->deleteOrder($orderId)) {
                $_SESSION['flash_success'] = 'تم إلغاء وحذف المشروع بنجاح.';
            }
            header('Location: ' . URLROOT . '/orders');
            exit();
        }
    }

    # دالة تعديل العرض المالي للمستقل
    public function edit_bid($bidId, $orderId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $price = floatval($_POST['price']);
            $message = trim($_POST['message']);

            // التحقق من الحقول
            if ($price <= 0 || empty($message)) {
                $_SESSION['flash_error'] = 'يرجى إدخال بيانات عرض صحيحة.';
                header('Location: ' . URLROOT . '/orders/show/' . $orderId);
                exit();
            }

            if ($this->bidModel->updateBid($bidId, $price, $message)) {
                $_SESSION['flash_success'] = 'تم تعديل عرضك المالي بنجاح.';
            }
            header('Location: ' . URLROOT . '/orders/show/' . $orderId);
            exit();
        }
    }

    # دالة عرض صفحة "مشاريعي" الخاصة بالعميل
    public function my_projects() {
        // جلب جميع مشاريع العميل (النشطة، قيد التنفيذ، والمكتملة)
        $projects = $this->orderModel->getClientProjects($_SESSION['user_id']);

        $data = [
            'page_title' => 'مشاريعي وإدارة العقود',
            'projects'   => $projects
        ];

        $this->view('orders/my_projects', $data);
    }
}
