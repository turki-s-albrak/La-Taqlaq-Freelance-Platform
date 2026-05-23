<?php
/**
 * موديل الإدارة - يدير الإحصائيات، النزاعات، المستخدمين، والمشاريع
 */
class AdminModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    # --- [1] قسم الإحصائيات (Dashboard Stats) ---
    public function getCounts() {
        $stats = [];
        
        // إجمالي عدد المستخدمين
        $this->db->query("SELECT COUNT(*) as total FROM users");
        $stats['users'] = $this->db->single()['total'];
        
        // إجمالي العروض المفتوحة (المعلقة التي لم تحسم بعد)
        $this->db->query("SELECT COUNT(*) as total FROM bids WHERE status = 'pending'");
        $stats['pending_bids'] = $this->db->single()['total'];
        
        // عدد النزاعات المفتوحة حالياً
        $this->db->query("SELECT COUNT(*) as total FROM disputes WHERE status = 'open'");
        $stats['open_disputes'] = $this->db->single()['total'];
        
        return $stats;
    }

    # --- [2] قسم إدارة المستخدمين (Users Management) ---
    public function getAllUsers() {
        $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    // ترقية المستخدم إلى أدمن أو العكس
    public function updateUserRole($userId, $role) {
        $this->db->query("UPDATE users SET role = :role WHERE userId = :userId");
        $this->db->bind(':role', $role);
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }

    // حظر مستخدم (سنقوم بتبسيطها برمجياً عبر تغيير دورة لـ 'banned' أو حذفه، والأفضل حذف الحساب لحماية المنصة حالياً)
    public function deleteUser($userId) {
        $this->db->query("DELETE FROM users WHERE userId = :userId");
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }

# --- [3] قسم إدارة المشاريع (Orders Moderation) ---
    public function getAllOrdersWithStatus() {
        // جلب المشاريع، ومعرفة حالتها، وجلب السعر الفعلي للعقد (escrowPrice) إن وجد لتفادي الخلط
        $this->db->query("SELECT orders.*, users.userName, escrow.status as escrow_status, escrow.price as escrowPrice 
                          FROM orders 
                          JOIN users ON orders.clientId = users.userId
                          LEFT JOIN escrow ON orders.orderId = escrow.orderId
                          ORDER BY orders.created_at DESC");
        return $this->db->resultSet();
    }

    // حذف مشروع غير لائق
    public function deleteOrder($orderId) {
        $this->db->query("DELETE FROM orders WHERE orderId = :orderId");
        $this->db->bind(':orderId', $orderId);
        return $this->db->execute();
    }

    # جلب جميع النزاعات المفتوحة مع تفاصيل المشروع والخصوم
    public function getDisputes() {
        $this->db->query("SELECT disputes.*, escrow.price, orders.title as orderTitle, 
                                 client.userName as clientName, freelancer.userName as freelancerName
                          FROM disputes
                          JOIN escrow ON disputes.escrowId = escrow.escrowId
                          JOIN orders ON escrow.orderId = orders.orderId
                          JOIN users as client ON escrow.clientId = client.userId
                          JOIN users as freelancer ON escrow.freelancerId = freelancer.userId
                          ORDER BY disputes.created_at DESC");
        return $this->db->resultSet();
    }

    # جلب تفاصيل نزاع واحد محدد بالـ ID لغرفة المعاينة
    public function getDisputeById($disputeId) {
        $this->db->query("SELECT disputes.*, escrow.price, escrow.clientId, escrow.freelancerId, orders.title as orderTitle, 
                                 client.userName as clientName, freelancer.userName as freelancerName
                          FROM disputes
                          JOIN escrow ON disputes.escrowId = escrow.escrowId
                          JOIN orders ON escrow.orderId = orders.orderId
                          JOIN users as client ON escrow.clientId = client.userId
                          JOIN users as freelancer ON escrow.freelancerId = freelancer.userId
                          WHERE disputes.disputeId = :disputeId");
        $this->db->bind(':disputeId', $disputeId);
        return $this->db->single();
    }

    # --- القرار المالي 1: إنصاف العميل (إعادة الأموال المحجوزة لرصيد العميل) ---
    public function resolveRefundClient($escrowId, $clientId, $price, $disputeId) {
        // أ. إعادة المال للعميل
        $this->db->query("UPDATE users SET balance = balance + :price WHERE userId = :clientId");
        $this->db->bind(':price', $price);
        $this->db->bind(':clientId', $clientId);
        if (!$this->db->execute()) return false;

        // ب. إغلاق العقد في الخزنة كـ ملغى
        $this->db->query("UPDATE escrow SET status = 'cancelled' WHERE escrowId = :escrowId");
        $this->db->bind(':escrowId', $escrowId);
        if (!$this->db->execute()) return false;

        // ج. تحديث سجل النزاع بحسم القرار وإغلاقه
        $this->db->query("UPDATE disputes SET admin_decision = 'refund_client', status = 'resolved' WHERE disputeId = :disputeId");
        $this->db->bind(':disputeId', $disputeId);
        return $this->db->execute();
    }

    # --- القرار المالي 2 المطور: إنصاف المستقل وصرف الأموال له ---
    public function resolvePayFreelancer($escrowId, $freelancerId, $price, $disputeId) {
        // 1. تحديث رصيد المستقل وزيادته بقيمة العقد
        $this->db->query("UPDATE users SET balance = balance + :price WHERE userId = :freelancerId");
        $this->db->bind(':price', $price);
        $this->db->bind(':freelancerId', $freelancerId);
        if (!$this->db->execute()) return false;

        // 2. تحديث حالة العقد في الخزنة إلى مكتمل (completed) ليغلق تلقائياً
        $this->db->query("UPDATE escrow SET status = 'completed' WHERE escrowId = :escrowId");
        $this->db->bind(':escrowId', $escrowId);
        if (!$this->db->execute()) return false;

        // 3. تحديث سجل النزاع بحسم القرار وإغلاقه
        $this->db->query("UPDATE disputes SET admin_decision = 'pay_freelancer', status = 'resolved' WHERE disputeId = :disputeId");
        $this->db->bind(':disputeId', $disputeId);
        return $this->db->execute();
    }
    
}
