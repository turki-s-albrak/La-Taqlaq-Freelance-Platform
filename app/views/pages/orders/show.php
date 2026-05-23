<div class="container my-5">
    <a href="<?php echo URLROOT; ?>/orders" class="btn btn-light btn-sm mb-4 border">&rarr; العودة لتصفح المشاريع</a>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <h2 class="fw-bold text-dark mb-1"><?php echo $order['title']; ?></h2>
                            <small class="text-muted">
                                صاحب المشروع: <strong class="text-primary"><?php echo $order['userName']; ?></strong> | 
                                تاريخ النشر: <?php echo date('Y-m-d', strtotime($order['created_at'])); ?>
                            </small>
                            
                            <?php 
                            // زر حذف المشروع (يظهر للعميل فقط بشرط عدم وجود تعاقد)
                            if($order['clientId'] == $_SESSION['user_id'] && !$hasActiveEscrow): 
                            ?>
                                <div class="mt-2">
                                    <form action="<?php echo URLROOT; ?>/orders/delete/<?php echo $order['orderId']; ?>" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من إلغاء وحذف هذا المشروع نهائياً؟ لا يمكن التراجع عن هذا الإجراء.');">
                                        <button type="submit" class="btn btn-outline-danger btn-sm fw-bold">🗑️ إلغاء المشروع نهائياً</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="bg-light text-success fw-bold px-3 py-2 rounded-2 fs-4">
                            ميزانية العميل: $<?php echo number_format($order['price'], 2); ?>
                        </div>
                    </div>
                    <hr>
                    <h5 class="fw-bold text-secondary mb-3">وصف المشروع وشروطه:</h5>
                    <p class="text-dark" style="line-height: 1.8; text-align: justify;">
                        <?php echo nl2br($order['description']); ?>
                    </p>
                </div>
            </div>

            <h4 class="fw-bold mb-3">العروض المقدمة (<?php echo count($bids); ?>)</h4>
            <?php if(empty($bids)): ?>
                <div class="alert alert-info text-center shadow-sm border-0 py-4">لا توجد عروض مقدمة على هذا المشروع حتى الآن. كن أول من يغتنم الفرصة!</div>
            <?php else: ?>
                <?php foreach($bids as $bid): ?>
                    <div class="card shadow-sm border-0 mb-3 <?php echo ($bid['freelancerId'] == $_SESSION['user_id']) ? 'border-start border-4 border-primary' : ''; ?>">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">
                                        <?php echo $bid['userName']; ?> 
                                        <?php echo ($bid['freelancerId'] == $_SESSION['user_id']) ? '<span class="badge bg-primary ms-1">عرضي</span>' : ''; ?>
                                    </h6>
                                    <small class="text-muted"><?php echo date('Y-m-d H:i', strtotime($bid['created_at'])); ?></small>
                                </div>
                                <div class="text-success fw-bold fs-5">
                                    $<?php echo number_format($bid['price'], 2); ?>
                                </div>
                            </div>
                            <p class="text-secondary small mb-3" style="line-height: 1.6; text-align: justify;">
                                <?php echo nl2br($bid['message']); ?>
                            </p>
                            
                            <?php if($order['clientId'] == $_SESSION['user_id']): ?>
                                <?php if($bid['status'] == 'pending' && !$hasActiveEscrow): ?>
                                    <form action="<?php echo URLROOT; ?>/orders/accept/<?php echo $bid['bidId']; ?>" method="POST" onsubmit="return confirm('تأكيد مالي: سيتم سحب مبلغ $<?php echo number_format($bid['price'], 2); ?> من رصيدك وحجزه في الخزنة. هل أنت متأكد؟');">
                                        <button type="submit" class="btn btn-success btn-sm fw-bold px-4 shadow-sm">✓ قبول هذا العرض والتعاقد</button>
                                    </form>
                                <?php elseif($bid['status'] == 'accepted'): ?>
                                    <span class="badge bg-success px-3 py-2">تم التعاقد مع هذا العرض ✓</span>
                                <?php elseif($bid['status'] == 'rejected'): ?>
                                    <span class="badge bg-danger px-3 py-2">مرفوض</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php if($order['clientId'] != $_SESSION['user_id'] && $bid['status'] == 'accepted'): ?>
                                <span class="badge bg-success px-3 py-2">هذا هو العرض الفائز بالمشروع ✓</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 sticky-top" style="top: 20px;">
                <div class="card-body p-0">
                    <?php if($order['clientId'] == $_SESSION['user_id']): ?>
                        <div class="alert alert-info text-center border-0 shadow-sm p-4 mb-0">
                            <h5 class="fw-bold text-success mb-3">🛠️ إدارة المشروع</h5>
                            <?php if($hasActiveEscrow): ?>
                                <p class="small text-dark mb-3">تم التعاقد مع أحد المستقلين والمشروع قيد التنفيذ حالياً.</p>
                                <a href="<?php echo URLROOT; ?>/workspaces/room/<?php echo $order['orderId']; ?>" class="btn btn-primary w-100 fw-bold shadow-sm">الذهاب لمساحة العمل &rarr;</a>
                            <?php else: ?>
                                <p class="small text-muted mb-0">
                                    أنت صاحب هذا المشروع. راجع عروض المستقلين بعناية واختَر الأنسب للبدء. 
                                    <br><br>ملاحظة: بمجرد قبول أي عرض، لا يمكنك إلغاء المشروع وسيتم فتح مساحة العمل فوراً.
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <?php if($myBid && $myBid['status'] == 'pending'): ?>
                            <h5 class="fw-bold text-primary mb-3">✏️ تعديل عرضك الحالي</h5>
                            <form action="<?php echo URLROOT; ?>/orders/edit_bid/<?php echo $myBid['bidId']; ?>/<?php echo $order['orderId']; ?>" method="POST">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">قيمة العرض المحدثة ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">$</span>
                                        <input type="number" name="price" class="form-control" value="<?php echo $myBid['price']; ?>" min="1" step="0.01" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">تفاصيل عرضك</label>
                                    <textarea name="message" class="form-control" rows="6" required><?php echo $myBid['message']; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning w-100 fw-bold py-2 shadow-sm">تحديث العرض</button>
                            </form>
                        
                        <?php elseif($myBid && $myBid['status'] != 'pending'): ?>
                            <div class="alert alert-info text-center border-0 shadow-sm p-4 mb-0">
                                <h5 class="fw-bold mb-2">
                                    <?php echo ($myBid['status'] == 'accepted') ? '🎉 مبروك!' : 'حالة العرض'; ?>
                                </h5>
                                <h6 class="fw-bold text-dark mb-3">
                                    <?php 
                                        if($myBid['status'] == 'accepted') echo '<span class="text-success">تم قبول عرضك للعمل</span>';
                                        else if($myBid['status'] == 'rejected') echo '<span class="text-danger">عذراً، تم رفض عرضك</span>';
                                    ?>
                                </h6>
                                <?php if($myBid['status'] == 'accepted'): ?>
                                    <p class="small text-muted mb-3">العميل بانتظارك الآن للبدء.</p>
                                    <a href="<?php echo URLROOT; ?>/workspaces/room/<?php echo $order['orderId']; ?>" class="btn btn-primary btn-sm w-100 fw-bold">الذهاب لمساحة العمل &rarr;</a>
                                <?php else: ?>
                                    <p class="text-muted small mb-0">تم إغلاق هذا المشروع. حظاً أوفر في مشاريعك القادمة!</p>
                                <?php endif; ?>
                            </div>

                        <?php else: ?>
                            <h5 class="fw-bold text-dark mb-3">🚀 قدم عرضك الآن</h5>
                            <form action="<?php echo URLROOT; ?>/orders/show/<?php echo $order['orderId']; ?>" method="POST">
                                <div class="mb-3">
                                    <label for="price" class="form-label small fw-bold text-secondary">قيمة عرضك المالي ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">$</span>
                                        <input type="number" name="price" id="price" class="form-control" placeholder="مثال: 150" min="1" step="0.01" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="message" class="form-label small fw-bold text-secondary">تفاصيل عرضك (ماذا ستقدم؟)</label>
                                    <textarea name="message" id="message" class="form-control" rows="5" placeholder="اشرح للعميل خبرتك وكيف ستنفذ المشروع بمهارة..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">تقديم العرض المالي</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>