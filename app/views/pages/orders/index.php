<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><?php echo $page_title; ?></h2>
            <p class="text-muted small mb-0">ابحث عن المشروع المناسب لمهاراتك وقدم عرضك الآن بأمان</p>
        </div>
        <!-- زر سريع لنشر مشروع جديد -->
        <a href="<?php echo URLROOT; ?>/orders/create" class="btn btn-primary fw-bold shadow-sm">➕ نشر مشروع جديد</a>
    </div>

    <!-- فحص Edge Case: إذا كان السوق فارغاً ولا توجد مشاريع منشورة بعد -->
    <?php if(empty($orders)): ?>
        <div class="alert alert-warning text-center p-5 border-0 shadow-sm rounded-3">
            <h4 class="fw-bold mb-2">لا توجد مشاريع متاحة حالياً!</h4>
            <p class="text-muted mb-0">كن أول من ينشر مشروعاً في منصة "لا تقلق" الآن.</p>
        </div>
    <?php else: ?>
        
        <!-- شبكة عرض المشاريع المتجاوبة -->
        <div class="row g-4">
            <?php foreach($orders as $order): ?>
                <div class="col-12">
                    <div class="card shadow-sm border-0 p-4 bg-white rounded-3 h-100 transition-hover border-start border-primary border-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                                <div>
                                    <h4 class="fw-bold text-dark mb-1"><?php echo $order['title']; ?></h4>
                                    <small class="text-muted">
                                        نشر بواسطة: <strong><?php echo $order['userName']; ?></strong> 
                                        | بتاريخ: <?php echo date('Y-m-d', strtotime($order['created_at'])); ?>
                                    </small>
                                </div>
                                <!-- الميزانية بشكل بارز وعريض -->
                                <div class="bg-light text-success fw-bold px-3 py-2 rounded-2 fs-5">
                                    $<?php echo number_format($order['price'], 2); ?>
                                </div>
                            </div>
                            
                            <!-- وصف المشروع (قص النص إذا كان طويلاً جداً للبساطة في التصفح) -->
                            <p class="text-secondary small mb-4 text-justify" style="line-height: 1.7;">
                                <?php echo nl2br($order['description']); ?>
                            </p>
                            
                            <div class="d-flex justify-content-end">
                                <!-- رابط تفاصيل المشروع وتقديم العروض (سنبرمجه الخطوة القادمة) -->
                                <a href="<?php echo URLROOT; ?>/orders/show/<?php echo $order['orderId']; ?>" class="btn btn-outline-primary btn-sm px-4 fw-bold">
                                    تفاصيل المشروع وتقديم عرض &larr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>