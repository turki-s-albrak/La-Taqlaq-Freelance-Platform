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
            die("المشروع المطلوب غير موجود.");
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

            $data = [
                'order' => $order,
                'bids'  => $bids
            ];

            $this->view('orders/show', $data);
        }
    }

    # دالة معالجة قبول العرض وحجز الأموال في الخزنة الآمنة
    public function accept($bidId) {
        // حماية دلالية: يجب أن يكون الطلب POST لحظر التلاعب عبر الروابط المباشرة
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $bidModel = $this->model('Bid');
            $escrowModel = $this->model('Escrow');
            $userModel = $this->model('User');

            // 1. جلب تفاصيل العرض المحال وقاعدة البيانات للتحقق
            $this->db = new Database(); // فحص سريع داخلي
            $this->db->query("SELECT * FROM bids WHERE bidId = :bidId");
            $this->db->bind(':bidId', $bidId);
            $bid = $this->db->single();

            if (!$bid || $bid['status'] !== 'pending') {
                $_SESSION['flash_error'] = 'عذراً، هذا العرض غير متاح للقبول حالياً.';
                header('Location: ' . URLROOT . '/orders');
                exit();
            }

            // 2. جلب المشروع المرتبط للتأكد أن من يضغط الزر هو العميل صاحب المشروع نفسه!
            $order = $this->orderModel->getOrderById($bid['orderId']);
            if ($order['clientId'] != $_SESSION['user_id']) {
                $_SESSION['flash_error'] = 'انتهاك أمني: لا تمتلك الصلاحية لقبول عروض هذا المشروع!';
                header('Location: ' . URLROOT . '/orders/show/' . $bid['orderId']);
                exit();
            }

            // 3. --- الـ Edge Case المالية الأهم: فحص رصيد العميل بدقة ---
            $clientData = $userModel->findUserByEmail($_SESSION['user_email']);
            
            if ($clientData['balance'] < $bid['price']) {
                // منع العملية وإظهار رسالة ومضية صارمة
                $_SESSION['flash_error'] = 'فشل قبول العرض! رصيدك الحالي ($' . number_format($clientData['balance'], 2) . ') لا يغطي قيمة العرض ($' . number_format($bid['price'], 2) . '). يرجى شحن حسابك أولاً.';
                header('Location: ' . URLROOT . '/orders/show/' . $bid['orderId']);
                exit();
            }

            // 4. تنفيذ المعاملة المالية وسلسلة التحديثات في الخزنة
            if ($escrowModel->acceptBidAndLockFunds($bid['orderId'], $order['clientId'], $bid['freelancerId'], $bid['price'], $bidId)) {
                $_SESSION['flash_success'] = 'تم قبول العرض بنجاح! وتم خصم المبلغ وحجزه في الخزنة الآمنة (Escrow)، وبدأت مساحة العمل.';
            } else {
                $_SESSION['flash_error'] = 'حدث خطأ غير متوقع أثناء معالجة العملية المالية، يرجى المحاولة لاحقاً.';
            }

            header('Location: ' . URLROOT . '/orders/show/' . $bid['orderId']);
            exit();
        } else {
            // تحويل تلقائي في حال محاولة الدخول بـ GET
            header('Location: ' . URLROOT . '/orders');
            exit();
        }
    }
}
