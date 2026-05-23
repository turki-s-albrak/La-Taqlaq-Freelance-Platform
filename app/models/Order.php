<?php
/**
 * موديل المشاريع - يتعامل مع جدول orders بأعلى معايير الأمان
 */
class Order {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    # 1. إدخال مشروع جديد في قاعدة البيانات
    public function addOrder($data) {
        $this->db->query("INSERT INTO orders (clientId, title, description, price) VALUES (:clientId, :title, :description, :price)");
        
        // ربط البيانات بالمتغيرات لمنع الـ SQL Injection
        $this->db->bind(':clientId', $data['clientId']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);

        return $this->db->execute();
    }

    # جلب جميع المشاريع مرتبة من الأحدث إلى الأقدم مع اسم العميل
    public function getOrders() {
        $this->db->query("SELECT orders.*, users.userName 
                          FROM orders 
                          JOIN users ON orders.clientId = users.userId 
                          ORDER BY orders.created_at DESC");
        
        return $this->db->resultSet();
    }

    # جلب تفاصيل مشروع محدد بواسطة الـ ID مع اسم العميل
    public function getOrderById($id) {
        $this->db->query("SELECT orders.*, users.userName 
                          FROM orders 
                          JOIN users ON orders.clientId = users.userId 
                          WHERE orders.orderId = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    # حذف المشروع نهائياً (للعميل)
    public function deleteOrder($orderId) {
        $this->db->query("DELETE FROM orders WHERE orderId = :orderId");
        $this->db->bind(':orderId', $orderId);
        return $this->db->execute();
    }

    # جلب جميع مشاريع العميل مع حالة التعاقد (إن وجدت) واسم المستقل المنفذ
    public function getClientProjects($clientId) {
        $this->db->query("SELECT orders.*, 
                                 escrow.status as escrowStatus, 
                                 escrow.price as finalPrice,
                                 users.userName as freelancerName 
                          FROM orders 
                          LEFT JOIN escrow ON orders.orderId = escrow.orderId 
                          LEFT JOIN users ON escrow.freelancerId = users.userId 
                          WHERE orders.clientId = :clientId 
                          ORDER BY orders.created_at DESC");
        
        $this->db->bind(':clientId', $clientId);
        return $this->db->resultSet();
    }
}