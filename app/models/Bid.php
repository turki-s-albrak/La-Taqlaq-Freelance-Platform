<?php
/**
 * موديل العروض - يتعامل مع جدول bids بأعلى معايير الأمان
 */
class Bid {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    # 1. جلب جميع العروض المقدمة على مشروع معين مع أسماء المستقلين
    public function getBidsByOrderId($orderId) {
        $this->db->query("SELECT bids.*, users.userName 
                          FROM bids 
                          JOIN users ON bids.freelancerId = users.userId 
                          WHERE bids.orderId = :orderId 
                          ORDER BY bids.created_at DESC");
        $this->db->bind(':orderId', $orderId);
        return $this->db->resultSet();
    }

    # 2. فحص Edge Case: هل قدم هذا المستقل عرضاً سابقاً على هذا المشروع؟
    public function checkUserBid($orderId, $freelancerId) {
        $this->db->query("SELECT * FROM bids WHERE orderId = :orderId AND freelancerId = :freelancerId");
        $this->db->bind(':orderId', $orderId);
        $this->db->bind(':freelancerId', $freelancerId);
        $this->db->single();
        return ($this->db->rowCount() > 0);
    }

    # 3. إدخال عرض جديد في قاعدة البيانات
    public function addBid($data) {
        $this->db->query("INSERT INTO bids (orderId, freelancerId, price, message) VALUES (:orderId, :freelancerId, :price, :message)");
        $this->db->bind(':orderId', $data['orderId']);
        $this->db->bind(':freelancerId', $data['freelancerId']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':message', $data['message']);
        return $this->db->execute();
    }

    # تحديث بيانات العرض (للمستقل)
    public function updateBid($bidId, $price, $message) {
        $this->db->query("UPDATE bids SET price = :price, message = :message WHERE bidId = :bidId");
        $this->db->bind(':price', $price);
        $this->db->bind(':message', $message);
        $this->db->bind(':bidId', $bidId);
        return $this->db->execute();
    }
}