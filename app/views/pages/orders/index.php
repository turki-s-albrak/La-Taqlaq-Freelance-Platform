<div class="container my-5">
    
    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">🔍 <?php echo $page_title; ?></h2>
            <p class="text-muted small mb-0">ابحث عن المشروع المناسب لمهاراتك، وقدم عرضك الآن بأمان وموثوقية.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/orders/create" class="btn btn-primary fw-bold shadow-sm px-4 py-2 btn-hover-effect">
            ➕ نشر مشروع جديد
        </a>
    </div>

    <?php if(empty($orders)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
            <div class="fs-1 mb-3">📭</div>
            <h4 class="fw-bold text-dark mb-2">السوق هادئ حالياً!</h4>
            <p class="text-muted mb-4">لا توجد مشاريع متاحة في الوقت الحالي. كن أنت المبادر وانشر أول مشروع.</p>
            <div>
                <a href="<?php echo URLROOT; ?>/orders/create" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">نشر مشروع جديد الآن</a>
            </div>
        </div>
    <?php else: ?>
        
        <div class="row g-4">
            <?php foreach($orders as $order): ?>
                <div class="col-12">
                    <div class="card shadow-sm border-0 p-4 bg-white rounded-4 h-100 project-card border-start border-primary border-4">
                        <div class="card-body p-0">
                            
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                                <div>
                                    <h4 class="fw-bold text-dark mb-2"><?php echo $order['title']; ?></h4>
                                    <div class="d-flex gap-3 align-items-center text-muted small fw-bold">
                                        <span>👤 الناشر: <span class="text-primary"><?php echo $order['userName']; ?></span></span>
                                        <span>📅 منذ: <?php echo date('Y-m-d', strtotime($order['created_at'])); ?></span>
                                    </div>
                                </div>
                                
                                <div class="bg-success bg-opacity-10 text-success fw-bold px-4 py-2 rounded-pill fs-5 border border-success border-opacity-25 shadow-sm">
                                    $<?php echo number_format($order['price'], 2); ?>
                                </div>
                            </div>
                            
                            <p class="text-secondary small mb-4" style="line-height: 1.8;">
                                <?php echo nl2br($order['description']); ?>
                            </p>
                            
                            <div class="d-flex justify-content-end border-top border-light pt-3 mt-auto">
                                <a href="<?php echo URLROOT; ?>/orders/show/<?php echo $order['orderId']; ?>" class="btn btn-outline-primary px-4 py-2 fw-bold btn-hover-effect rounded-pill">
                                    التفاصيل وتقديم عرض &larr;
                                </a>
                            </div>
                            
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<style>
/* تأثيرات بصرية احترافية للمشاريع (SaaS UI) */
.project-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.project-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1)!important;
    background-color: #fcfcfc !important;
}
.btn-hover-effect {
    transition: all 0.2s ease;
}
.btn-hover-effect:hover {
    transform: translateY(-2px);
    box-shadow: 0 .25rem .5rem rgba(13, 110, 253, .15)!important;
}
</style>