<?php
/**
 * موديل مساحة العمل - يدير الرسائل، المرفقات، وحالات الخزنة المشتركة
 */
class Workspace {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    # 1. جلب بيانات سجل الخزنة بدقة للتأكد من أطراف المشروع
    public function getEscrowById($escrowId) {
        $this->db->query("SELECT escrow.*, orders.title as orderTitle, orders.description as orderDesc 
                          FROM escrow 
                          JOIN orders ON escrow.orderId = orders.orderId 
                          WHERE escrow.escrowId = :escrowId");
        $this->db->bind(':escrowId', $escrowId);
        return $this->db->single();
    }

    # 2. جلب جميع رسائل غرفة الدردشة مرتبة تصاعدياً حسب وقت الإرسال
    public function getWorkspaceMessages($escrowId) {
        $this->db->query("SELECT messages.*, users.userName, users.role 
                          FROM messages 
                          JOIN users ON messages.senderId = users.userId 
                          WHERE messages.escrowId = :escrowId 
                          ORDER BY messages.created_at ASC");
        $this->db->bind(':escrowId', $escrowId);
        return $this->db->resultSet();
    }

    # 3. حفظ رسالة جديدة في قاعدة البيانات (مع أو بدون مرفق PDF)
    public function saveMessage($data) {
        $this->db->query("INSERT INTO messages (escrowId, senderId, message, attachment) VALUES (:escrowId, :senderId, :message, :attachment)");
        $this->db->bind(':escrowId', $data['escrowId']);
        $this->db->bind(':senderId', $data['senderId']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':attachment', $data['attachment']);
        return $this->db->execute();
    }

    # 4. دالة تسليم المشروع وإطلاق المبالغ للمستقل (تحديث مالي)
    public function completeProject($escrowId, $freelancerId, $price) {
        // أ. تحديث رصيد المستقل وإضافة الأرباح له
        $this->db->query("UPDATE users SET balance = balance + :price WHERE userId = :freelancerId");
        $this->db->bind(':price', $price);
        $this->db->bind(':freelancerId', $freelancerId);
        if (!$this->db->execute()) return false;

        // ب. تحديث حالة الخزنة إلى مكتملة (completed)
        $this->db->query("UPDATE escrow SET status = 'completed' WHERE escrowId = :escrowId");
        $this->db->bind(':escrowId', $escrowId);
        return $this->db->execute();
    }

    # دالة تسجيل النزاع وتجميد الخزنة المالية في قاعدة البيانات
    public function createDispute($escrowId, $userId, $reason) {
        // أ. تحويل حالة الخزنة الموحدة إلى متنازع عليها (disputed)
        $this->db->query("UPDATE escrow SET status = 'disputed' WHERE escrowId = :escrowId");
        $this->db->bind(':escrowId', $escrowId);
        if (!$this->db->execute()) return false;

        // ب. إنشاء سجل النزاع في جدول disputes
        $this->db->query("INSERT INTO disputes (escrowId, raised_by, reason, status) 
                          VALUES (:escrowId, :raised_by, :reason, 'open')");
        $this->db->bind(':escrowId', $escrowId);
        $this->db->bind(':raised_by', $userId);
        $this->db->bind(':reason', $reason);
        
        return $this->db->execute();
    }
}