<div class="container my-5">
    
    <div class="mb-4">
        <a href="<?php echo URLROOT; ?>/orders" class="btn btn-white text-secondary fw-bold shadow-sm border rounded-pill px-4 py-2 hover-link">
            &larr; العودة لتصفح المشاريع
        </a>
    </div>

    <div class="row g-4">
        
        <div class="col-12 col-lg-8">
            
            <div class="card shadow-sm border-0 p-4 p-md-5 bg-white rounded-4 mb-4 border-start border-primary border-4">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 border-bottom border-light pb-4">
                        <div>
                            <h2 class="fw-bold text-dark mb-2"><?php echo $order['title']; ?></h2>
                            <div class="d-flex flex-wrap gap-3 text-muted small fw-bold">
                                <span>👤 صاحب المشروع: <strong class="text-primary"><?php echo $order['userName']; ?></strong></span>
                                <span>📅 تاريخ النشر: <?php echo date('Y-m-d', strtotime($order['created_at'])); ?></span>
                            </div>
                            
                            <?php 
                            // زر حذف المشروع (يظهر للعميل فقط بشرط عدم وجود تعاقد)
                            if($order['clientId'] == $_SESSION['user_id'] && !$hasActiveEscrow): 
                            ?>
                                <div class="mt-3">
                                    <form action="<?php echo URLROOT; ?>/orders/delete/<?php echo $order['orderId']; ?>" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من إلغاء وحذف هذا المشروع نهائياً؟ لا يمكن التراجع عن هذا الإجراء.');">
                                        <button type="submit" class="btn btn-outline-danger btn-sm fw-bold shadow-sm rounded-pill px-3">🗑️ إلغاء المشروع نهائياً</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="bg-success bg-opacity-10 text-success fw-bold px-4 py-3 rounded-4 fs-5 border border-success border-opacity-25 text-center shadow-sm">
                            <span class="d-block xsmall text-success mb-1 opacity-75" style="font-size: 0.75rem;">الميزانية المقترحة</span>
                            $<?php echo number_format($order['price'], 2); ?>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold text-secondary mb-3">📝 تفاصيل وشروط المشروع:</h6>
                    <p class="text-dark mb-0" style="line-height: 1.9; font-size: 0.95rem; text-align: justify;">
                        <?php echo nl2br($order['description']); ?>
                    </p>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                <h4 class="fw-bold text-dark mb-0">💼 العروض المقدمة <span class="badge bg-secondary rounded-pill"><?php echo count($bids); ?></span></h4>
            </div>

            <?php if(empty($bids)): ?>
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light">
                    <div class="fs-1 mb-3">📜</div>
                    <h5 class="fw-bold text-muted mb-0">لا توجد عروض مقدمة على هذا المشروع حتى الآن. كن أول من يغتنم الفرصة!</h5>
                </div>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach($bids as $bid): ?>
                        <?php 
                            // تمييز بصري لعرض المستخدم نفسه
                            $isMyBid = ($bid['freelancerId'] == $_SESSION['user_id']); 
                            $cardClass = $isMyBid ? 'border-primary border-2 bg-primary bg-opacity-10' : 'border-light bg-white';
                        ?>
                        <div class="card shadow-sm <?php echo $cardClass; ?> rounded-4 overflow-hidden bid-card transition-hover">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-secondary bg-opacity-10 p-2 rounded-circle fs-5">👤</div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark">
                                                <?php echo $bid['userName']; ?> 
                                                <?php echo $isMyBid ? '<span class="badge bg-primary ms-1 rounded-pill">عرضك الحالي</span>' : ''; ?>
                                            </h6>
                                            <small class="text-muted fw-bold" style="font-size: 0.75rem;"><?php echo date('Y-m-d H:i', strtotime($bid['created_at'])); ?></small>
                                        </div>
                                    </div>
                                    <div class="text-success fw-bold fs-5 bg-white px-3 py-1 rounded-pill shadow-sm border border-success border-opacity-25">
                                        $<?php echo number_format($bid['price'], 2); ?>
                                    </div>
                                </div>
                                
                                <p class="text-secondary small mb-4" style="line-height: 1.7; text-align: justify; padding-right: 3rem;">
                                    <?php echo nl2br($bid['message']); ?>
                                </p>
                                
                                <?php if($order['clientId'] == $_SESSION['user_id']): ?>
                                    <div class="border-top border-secondary border-opacity-10 pt-3 text-end">
                                        <?php if($bid['status'] == 'pending' && !$hasActiveEscrow): ?>
                                            <form action="<?php echo URLROOT; ?>/orders/accept/<?php echo $bid['bidId']; ?>" method="POST" onsubmit="return confirm('تأكيد مالي: سيتم سحب مبلغ $<?php echo number_format($bid['price'], 2); ?> من رصيدك وحجزه في الخزنة كضمان. هل أنت متأكد من التعاقد مع هذا المستقل؟');">
                                                <button type="submit" class="btn btn-success fw-bold px-4 py-2 shadow-sm rounded-pill btn-hover-effect">
                                                    🤝 قبول العرض وبدء التعاقد
                                                </button>
                                            </form>
                                        <?php elseif($bid['status'] == 'accepted'): ?>
                                            <span class="badge bg-success bg-gradient px-4 py-2 rounded-pill fs-6 shadow-sm">✅ تم التعاقد مع هذا العرض</span>
                                        <?php elseif($bid['status'] == 'rejected'): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-4 py-2 rounded-pill fw-bold">❌ عرض مرفوض</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($order['clientId'] != $_SESSION['user_id'] && $bid['status'] == 'accepted'): ?>
                                    <div class="border-top border-secondary border-opacity-10 pt-3 text-end">
                                        <span class="badge bg-success bg-gradient px-4 py-2 rounded-pill shadow-sm">🏆 هذا هو العرض الفائز بالمشروع</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-lg border-0 p-4 bg-white rounded-4 sticky-top border-top border-4 border-dark" style="top: 2rem;">
                <div class="card-body p-0">
                    
                    <?php if($order['clientId'] == $_SESSION['user_id']): ?>
                        <div class="text-center">
                            <h5 class="fw-bold text-dark mb-3">🛠️ إدارة مشروعك</h5>
                            <?php if($hasActiveEscrow): ?>
                                <div class="bg-success bg-opacity-10 p-3 rounded-3 mb-4 border border-success border-opacity-25">
                                    <div class="fs-1 mb-2">🚀</div>
                                    <p class="small text-dark fw-bold mb-0">تم التعاقد والمشروع قيد التنفيذ الآن.</p>
                                </div>
                                <a href="<?php echo URLROOT; ?>/workspaces/room/<?php echo $order['orderId']; ?>" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm btn-hover-effect">دخول مساحة العمل &rarr;</a>
                            <?php else: ?>
                                <div class="bg-light p-3 rounded-3 text-start mb-0 border">
                                    <p class="small text-muted fw-bold mb-2">👋 أهلاً بك،</p>
                                    <p class="small text-secondary mb-0" style="line-height: 1.6;">
                                        أنت صاحب هذا المشروع. راجع العروض المقدمة بعناية واختَر الأنسب للبدء.<br><br>
                                        <span class="text-danger fw-bold">ملاحظة:</span> بمجرد قبول أي عرض، لا يمكنك إلغاء المشروع وسيتم حجز المبلغ في الخزنة وفتح مساحة العمل فوراً.
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php else: ?>
                        <?php if($myBid && $myBid['status'] == 'pending'): ?>
                            <h5 class="fw-bold text-primary mb-4">✏️ تعديل عرضك الحالي</h5>
                            <form action="<?php echo URLROOT; ?>/orders/edit_bid/<?php echo $myBid['bidId']; ?>/<?php echo $order['orderId']; ?>" method="POST">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary px-1">قيمة العرض المحدثة ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 text-muted ps-3 pr-3">$</span>
                                        <input type="number" name="price" class="form-control bg-light border-0 custom-input fw-bold text-success" value="<?php echo $myBid['price']; ?>" min="1" step="0.01" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-secondary px-1">تفاصيل عرضك</label>
                                    <textarea name="message" class="form-control bg-light border-0 custom-input p-3" rows="5" required><?php echo $myBid['message']; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning w-100 fw-bold py-3 shadow-sm btn-hover-effect">تحديث العرض المالي 🔄</button>
                            </form>
                        
                        <?php elseif($myBid && $myBid['status'] != 'pending'): ?>
                            <div class="text-center">
                                <h5 class="fw-bold mb-4">
                                    <?php echo ($myBid['status'] == 'accepted') ? '🎉 تهانينا!' : 'حالة العرض'; ?>
                                </h5>
                                <div class="p-4 rounded-4 mb-4 <?php echo ($myBid['status'] == 'accepted') ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-danger bg-opacity-10 border border-danger border-opacity-25'; ?>">
                                    <h6 class="fw-bold mb-2">
                                        <?php 
                                            if($myBid['status'] == 'accepted') echo '<span class="text-success fs-5">تم قبول عرضك للعمل ✅</span>';
                                            else if($myBid['status'] == 'rejected') echo '<span class="text-danger fs-5">عذراً، تم رفض عرضك ❌</span>';
                                        ?>
                                    </h6>
                                </div>
                                
                                <?php if($myBid['status'] == 'accepted'): ?>
                                    <p class="small text-muted fw-bold mb-3">تم إيداع المال في الخزنة والعميل بانتظارك.</p>
                                    <a href="<?php echo URLROOT; ?>/workspaces/room/<?php echo $order['orderId']; ?>" class="btn btn-success btn-lg w-100 fw-bold shadow-sm btn-hover-effect">دخول مساحة العمل &rarr;</a>
                                <?php else: ?>
                                    <p class="text-muted small fw-bold mb-0">تم إغلاق هذا المشروع. حظاً أوفر في مشاريعك القادمة!</p>
                                <?php endif; ?>
                            </div>

                        <?php else: ?>
                            <h5 class="fw-bold text-dark mb-4">🚀 قدم عرضك الآن</h5>
                            <form action="<?php echo URLROOT; ?>/orders/show/<?php echo $order['orderId']; ?>" method="POST">
                                <div class="mb-3">
                                    <label for="price" class="form-label small fw-bold text-secondary px-1">قيمة عرضك المالي</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-0 text-success fw-bold ps-3 pr-3">$</span>
                                        <input type="number" name="price" id="price" class="form-control bg-light border-0 custom-input fw-bold text-success" placeholder="مثال: 150" min="1" step="0.01" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="message" class="form-label small fw-bold text-secondary px-1">خطة العمل (ماذا ستقدم؟)</label>
                                    <textarea name="message" id="message" class="form-control bg-light border-0 custom-input p-3" rows="6" placeholder="اشرح للعميل خبرتك وكيف ستنفذ المشروع بمهارة واحترافية..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold py-3 shadow-sm btn-hover-effect">تقديم العرض وإرسال 📤</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        
    </div>
</div>

<style>
/* التأثيرات البصرية המوحدة (SaaS UI) */
.custom-input {
    box-shadow: none !important;
    transition: all 0.3s ease;
}
.custom-input:focus {
    background-color: #fff !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15) !important;
}
.btn-hover-effect {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.btn-hover-effect:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(13, 110, 253, .25)!important;
}
.transition-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.transition-hover:hover {
    transform: translateY(-3px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
}
.hover-link {
    transition: all 0.2s ease;
}
.hover-link:hover {
    background-color: #f8f9fa !important;
    color: #212529 !important;
}
</style>