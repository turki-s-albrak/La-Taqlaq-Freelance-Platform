<div class="container my-5">
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">💼 أعمالي ومساحات العمل</h2>
            <p class="text-muted small mb-0">تابع مشاريعك الحالية، وتصفح سجل أعمالك السابقة بكل سهولة.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/orders" class="btn btn-primary fw-bold shadow-sm"> تصفح مشاريع جديدة</a>
    </div>

    <?php if(empty($workspaces)): ?>
        <div class="alert alert-info text-center p-5 border-0 shadow-sm rounded-3">
            <h4 class="fw-bold mb-2">لا توجد أعمال حالية!</h4>
            <p class="text-muted mb-0">لم تقم بتنفيذ أي مشاريع حتى الآن. ابدأ بتقديم عروضك على المشاريع المتاحة لتكسب أول عقد لك.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($workspaces as $workspace): ?>
                <?php 
                    // تحديد الألوان والنصوص المعمارية بناءً على الحالة
                    $statusColor = '';
                    $statusText = '';
                    
                    if($workspace['status'] === 'in_progress') {
                        $statusColor = 'primary';
                        $statusText = '⏳ قيد التنفيذ (مفتوح)';
                    } elseif($workspace['status'] === 'completed') {
                        $statusColor = 'success';
                        $statusText = '✅ مكتمل ومسلم';
                    } elseif($workspace['status'] === 'disputed') {
                        $statusColor = 'danger';
                        $statusText = '🚨 تحت النزاع';
                    } elseif($workspace['status'] === 'cancelled') {
                        $statusColor = 'secondary';
                        $statusText = '❌ ملغى';
                    }
                ?>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 rounded-3 border-start border-4 border-<?php echo $statusColor; ?> transition-hover">
                        <div class="card-body p-4 d-flex flex-column">
                            
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-<?php echo $statusColor; ?> bg-opacity-10 text-<?php echo $statusColor; ?> px-2 py-1 rounded-2 small fw-bold">
                                    <?php echo $statusText; ?>
                                </span>
                                <span class="text-success fw-bold fs-5">$<?php echo number_format($workspace['price'], 2); ?></span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2"><?php echo $workspace['orderTitle']; ?></h5>
                            
                            <div class="text-muted small mb-4">
                                <div class="mb-1">👤 العميل: <strong class="text-secondary"><?php echo $workspace['clientName']; ?></strong></div>
                                <div>📅 تاريخ المشروع: <?php echo date('Y-m-d', strtotime($workspace['orderDate'])); ?></div>
                            </div>

                            <a href="<?php echo URLROOT; ?>/workspaces/room/<?php echo $workspace['orderId']; ?>" class="btn btn-<?php echo $statusColor; ?> w-100 fw-bold mt-auto shadow-sm">
                                دخول مساحة العمل &rarr;
                            </a>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* لمسة UI بسيطة لجعل البطاقات ترتفع عند مرور الماوس (Hover) */
.transition-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>