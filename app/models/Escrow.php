<?php
/**
 * موديل الخزنة المالية - يدير العمليات المالية والضمانات الآمنة
 */
class Escrow {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    # دالة قبول العرض وبدء الخزنة (تنفذ عدة عمليات مترابطة بحذر)
    public function acceptBidAndLockFunds($orderId, $clientId, $freelancerId, $price, $bidId) {
        
        // 1. خصم المبلغ من رصيد العميل (صاحب المشروع)
        $this->db->query("UPDATE users SET balance = balance - :price WHERE userId = :clientId");
        $this->db->bind(':price', $price);
        $this->db->bind(':clientId', $clientId);
        if (!$this->db->execute()) return false;

        // 2. إنشاء سجل الخزنة وحجز الأموال بحالة قيد التنفيذ
        $this->db->query("INSERT INTO escrow (orderId, clientId, freelancerId, price, status) 
                          VALUES (:orderId, :clientId, :freelancerId, :price, 'in_progress')");
        $this->db->bind(':orderId', $orderId);
        $this->db->bind(':clientId', $clientId);
        $this->db->bind(':freelancerId', $freelancerId);
        $this->db->bind(':price', $price);
        if (!$this->db->execute()) return false;

        // 3. تحديث حالة العرض المقبول إلى accepted
        $this->db->query("UPDATE bids SET status = 'accepted' WHERE bidId = :bidId");
        $this->db->bind(':bidId', $bidId);
        if (!$this->db->execute()) return false;

        // 4. تحديث باقي عروض هذا المشروع تلقائياً إلى rejected لحسم العملية
        $this->db->query("UPDATE bids SET status = 'rejected' WHERE orderId = :orderId AND bidId != :bidId");
        $this->db->bind(':orderId', $orderId);
        $this->db->bind(':bidId', $bidId);
        return $this->db->execute();
    }

    # فحص هل للمشروع عقد مالي مسبق في الخزنة؟ (تعيد true أو false فقط)
    public function hasExistingEscrow($orderId) {
        $this->db->query("SELECT * FROM escrow WHERE orderId = :orderId");
        $this->db->bind(':orderId', $orderId);
        $this->db->execute();
        return ($this->db->rowCount() > 0);
    }

    # 2. جلب تفاصيل العقد بالـ ID (تستخدم في صفحة تفاصيل المشروع بعد قبول العرض)
    public function getBidById($bidId) {
        $this->db->query("SELECT * FROM bids WHERE bidId = :bidId");
        $this->db->bind(':bidId', $bidId);
        return $this->db->single();
    }
}