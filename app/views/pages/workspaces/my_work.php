<div class="container my-5">
    
    <div class="mb-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">💼 أعمالي ومساحات العمل</h2>
            <p class="text-muted small mb-0">تابع مشاريعك الحالية، وتصفح سجل أعمالك السابقة بكل سهولة.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/orders" class="btn btn-primary fw-bold shadow-sm px-4 py-2 rounded-pill btn-hover-effect">
            🔍 تصفح مشاريع جديدة
        </a>
    </div>

    <?php if(empty($workspaces)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
            <div class="fs-1 mb-3">💼</div>
            <h4 class="fw-bold text-dark mb-2">لا توجد أعمال حالية!</h4>
            <p class="text-muted mb-4">لم تقم بتنفيذ أي مشاريع حتى الآن. ابدأ بتقديم عروضك على المشاريع المتاحة لتكسب أول عقد لك.</p>
            <div>
                <a href="<?php echo URLROOT; ?>/orders" class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-pill">تصفح سوق العمل الآن</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($workspaces as $workspace): ?>
                <?php 
                    // تحديد الألوان والنصوص المعمارية بناءً على الحالة (لم يتم المساس بها)
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
                    <div class="card h-100 shadow-sm border-0 rounded-4 border-start border-4 border-<?php echo $statusColor; ?> project-card">
                        <div class="card-body p-4 d-flex flex-column">
                            
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-<?php echo $statusColor; ?> bg-gradient px-3 py-2 rounded-pill shadow-sm small fw-bold">
                                    <?php echo $statusText; ?>
                                </span>
                                <span class="text-success fw-bold fs-5 bg-success bg-opacity-10 px-3 py-1 rounded-pill border border-success border-opacity-25">
                                    $<?php echo number_format($workspace['price'], 2); ?>
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-3"><?php echo $workspace['orderTitle']; ?></h5>
                            
                            <div class="bg-light p-3 rounded-3 mb-4 mt-auto border border-secondary border-opacity-10">
                                <div class="mb-2 small fw-bold">
                                    <span class="text-muted">👤 العميل:</span> <strong class="text-primary"><?php echo $workspace['clientName']; ?></strong>
                                </div>
                                <div class="small fw-bold text-muted">
                                    📅 تاريخ المشروع: <?php echo date('Y-m-d', strtotime($workspace['orderDate'])); ?>
                                </div>
                            </div>

                            <a href="<?php echo URLROOT; ?>/workspaces/room/<?php echo $workspace['orderId']; ?>" class="btn btn-<?php echo $statusColor; ?> w-100 fw-bold py-2 rounded-pill shadow-sm btn-hover-effect">
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
/* التأثيرات البصرية الموحدة (SaaS UI) */
.project-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1)!important;
}
.btn-hover-effect {
    transition: all 0.2s ease;
}
.btn-hover-effect:hover {
    transform: translateY(-2px);
    box-shadow: 0 .25rem .5rem rgba(0, 0, 0, .15)!important;
}
</style>