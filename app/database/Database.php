<?php
/**
 * كلاس الاتصال بقاعدة البيانات - باستخدام PDO والـ Prepared Statements الصارمة
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh; // Database Handler
    private $stmt;
    private $error;

    public function __construct() {
        // إعداد رابط الاتصال (DSN) مع تحديد الترميز لمنع مشاكل اللغة العربية
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        
        // خيارات الـ PDO لرفع مستوى الأمان والأداء
        $options = [
            PDO::ATTR_PERSISTENT => true, // الحفاظ على اتصال مستمر لتحسين الأداء
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // تفعيل رمي الأخطاء كـ Exceptions لمعالجتها
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // جلب البيانات كمصفوفة ترابطية دائماً كما طلبت
            PDO::ATTR_EMULATE_PREPARES => false, // إيقاف المحاكاة لاعتماد الحماية الحقيقية من SQL Injection
        ];

        // محاولة الاتصال الآمن
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            // عرض رسالة آمنة عند الفشل (دون تسريب تفاصيل السيرفر للمستخدم النهائي)
            die("عذراً، حدث خطأ أثناء الاتصال بقاعدة البيانات.");
        }
    }

    # 1. تجهيز الاستعلام (Prepare Statement)
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    # 2. ربط القيم بالاستعلام بشكل آمن (Bind Values) لمنع الحقن
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    # 3. تنفيذ الاستعلام (Execute)
    public function execute() {
        return $this->stmt->execute();
    }

    # 4. جلب مجموعة من السجلات (تستخدم لجلب قائمة المشاريع مثلاً)
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    # 5. جلب سجل واحد فقط (تستخدم لجلب بيانات مستخدم أو مشروع محدد)
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    # 6. حساب عدد الصفوف الناتجة (تستخدم للتحقق من تكرار البريد الإلكتروني مثلاً)
    public function rowCount() {
        return $this->stmt->rowCount();
    }
}