<div class="container my-5">
    <!-- العودة للخلف -->
    <a href="<?php echo URLROOT; ?>/orders" class="btn btn-light btn-sm mb-4 border">&rarr; العودة لتصفح المشاريع</a>

    <div class="row g-4">
        <!-- تفاصيل المشروع (الجانب الأيمن أو العلوي في الموبايل) -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <h2 class="fw-bold text-dark mb-1"><?php echo $order['title']; ?></h2>
                            <small class="text-muted">
                                صاحب المشروع: <strong><?php echo $order['userName']; ?></strong> | 
                                تاريخ النشر: <?php echo date('Y-m-d', strtotime($order['created_at'])); ?>
                            </small>
                        </div>
                        <div class="bg-light text-success fw-bold px-3 py-2 rounded-2 fs-4">
                            ميزانية العميل: $<?php echo number_format($order['price'], 2); ?>
                        </div>
                    </div>
                    <hr>
                    <h5 class="fw-bold text-secondary mb-3">وصف المشروع وشروطه:</h5>
                    <p class="text-secondary" style="line-height: 1.8; white-space: pre-line;"><?php echo $order['description']; ?></p>
                </div>
            </div>

            <!-- قائمة العروض الحالية المقدمة على هذا المشروع -->
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3">
                <div class="card-body">
                    <h4 class="fw-bold text-dark mb-4">العروض المقدمة حالياً (<?php echo count($bids); ?>)</h4>
                    
                    <?php if(empty($bids)): ?>
                        <p class="text-muted small">لا توجد عروض مقدمة على هذا المشروع حتى الآن. كن أول من يقدم عرضاً!</p>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach($bids as $bid): ?>
                                <div class="p-3 bg-light rounded-3 border <?php echo ($bid['status'] === 'accepted') ? 'border-success border-2' : ''; ?>">
                                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                                        <div>
                                            <strong class="text-primary"><?php echo $bid['userName']; ?></strong>
                                            <?php if($bid['status'] === 'accepted'): ?>
                                                <span class="badge bg-success ms-2">تم قبول هذا العرض</span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="fw-bold text-success">$<?php echo number_format($bid['price'], 2); ?></span>
                                    </div>
                                    <p class="text-secondary small mb-1 mb-0" style="white-space: pre-line;"><?php echo $bid['message']; ?></p>
                                    
                                     <!-- أزرار التحكم بالعرض تظهر للعميل صاحب المشروع فقط وللعروض المعلقة -->
                                    <?php if($order['clientId'] == $_SESSION['user_id'] && $bid['status'] === 'pending'): ?>
                                        <div class="mt-3 d-flex justify-content-end">
                                            <form action="<?php echo URLROOT; ?>/orders/accept/<?php echo $bid['bidId']; ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من قبول هذا العرض؟ سيتم خصم قيمة العرض من رصيدك وحجزها في الخزنة فوراً.');">
                                                <button type="submit" class="btn btn-success btn-sm fw-bold px-3">
                                                    ✓ قبول هذا العرض وبدء العمل
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- فوروم تقديم العرض للمستقل (الجانب الأيسر أو السفلي في الموبايل) -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h4 class="fw-bold text-dark mb-3">قدم عرضك الآن</h4>
                    
                    <!-- فحص Edge Case بالواجهة: منع العميل من تقديم عرض على مشروعه -->
                    <?php if($order['clientId'] == $_SESSION['user_id']): ?>
                        <div class="alert alert-warning small border-0 mb-0" role="alert">
                            أنت صاحب هذا المشروع. يمكنك مراجعة عروض المستقلين واختيار العرض الأنسب لبدء العمل.
                        </div>
                    <?php else: ?>
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
                                <textarea name="message" id="message" class="form-control" rows="5" placeholder="اشرح للعميل خبرتك وكيف ستنفذ المشروع..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">تقديم العرض المالي</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>