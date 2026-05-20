<?php
/**
 * موديل المستخدمين - يتعامل مع جدول users بأعلى معايير الأمان
 */
class User {
    private $db;

    public function __construct() {
        // إنشاء كائن من كلاس الـ Database الآمن الذي صممناه
        $this->db = new Database();
    }

    # 1. البحث عن مستخدم بواسطة البريد الإلكتروني (يستخدم في تسجيل الدخول والتحقق من التكرار)
    public function findUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // إذا وجد السجل يعيده بالكامل، وإلا يعيد false
        return ($this->db->rowCount() > 0) ? $row : false;
    }

    # 2. تسجيل مستخدم جديد في النظام مع تشفير البيانات الحساسة
    public function register($data) {
        $this->db->query("INSERT INTO users (userName, email, password, secretWord) VALUES (:userName, :email, :password, :secretWord)");
        
        // ربط البيانات بالمتغيرات
        $this->db->bind(':userName', $data['userName']);
        $this->db->bind(':email', $data['email']);
        
        // تشفير كلمة المرور والكلمة السرية ذاتياً قبل الحفظ في قاعدة البيانات بالاعتماد على BCRYPT
        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
        $hashed_secret = password_hash($data['secretWord'], PASSWORD_BCRYPT);
        
        $this->db->bind(':password', $hashed_password);
        $this->db->bind(':secretWord', $hashed_secret);

        // تنفيذ الاستعلام
        return $this->db->execute();
    }

    # 3. التحقق من بيانات تسجيل الدخول
    public function login($email, $password) {
        $row = $this->findUserByEmail($email);

        // إذا لم يجد المستخدم
        if (!$row) {
            return false;
        }

        // التحقق الآمن من مطابقة كلمة المرور المشفرة
        if (password_verify($password, $row['password'])) {
            return $row; // نجاح المطابقة، إرجاع بيانات المستخدم بالكامل لتهيئة الجلسة
        } else {
            return false; // كلمة المرور خاطئة
        }
    }

    # دالة شحن أو تحديث رصيد المستخدم في قاعدة البيانات
    public function updateBalance($userId, $amount) {
        $this->db->query("UPDATE users SET balance = balance + :amount WHERE userId = :userId");
        $this->db->bind(':amount', $amount);
        $this->db->bind(':userId', $userId);
        
        return $this->db->execute();
    }
}