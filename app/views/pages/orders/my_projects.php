<div class="container my-5">
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">📁 مشاريعي وإدارة العقود</h2>
            <p class="text-muted small mb-0">تابع حالة مشاريعك التي قمت بنشرها، راجع العروض، وادخل مساحات العمل من مكان واحد.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/orders/create" class="btn btn-primary fw-bold shadow-sm">➕ نشر مشروع جديد</a>
    </div>

    <?php if(empty($projects)): ?>
        <div class="alert alert-info text-center p-5 border-0 shadow-sm rounded-3">
            <h4 class="fw-bold mb-2">لا توجد لديك مشاريع منشورة!</h4>
            <p class="text-muted mb-4">يبدو أنك لم تقم بنشر أي مشروع في المنصة حتى الآن.</p>
            <a href="<?php echo URLROOT; ?>/orders/create" class="btn btn-primary px-4 fw-bold">ابدأ بنشر مشروعك الأول</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($projects as $project): ?>
                <?php 
                    // المعالجة المعمارية الذكية للحالة والتوجيه
                    $statusColor = '';
                    $statusText = '';
                    $actionLink = '';
                    $btnText = '';
                    $btnColor = '';
                    
                    if (empty($project['escrowStatus'])) {
                        // لم يتم التعاقد بعد (ينتظر عروض)
                        $statusColor = 'warning';
                        $statusText  = '⏳ بانتظار العروض والموافقة';
                        $actionLink  = URLROOT . '/orders/show/' . $project['orderId'];
                        $btnText     = 'مراجعة العروض وإدارة المشروع &rarr;';
                        $btnColor    = 'outline-warning text-dark';
                    } else {
                        // تم التعاقد (يوجد خزنة ومساحة عمل)
                        $actionLink = URLROOT . '/workspaces/room/' . $project['orderId'];
                        $btnText    = 'دخول مساحة العمل &rarr;';
                        
                        if($project['escrowStatus'] === 'in_progress') {
                            $statusColor = 'primary';
                            $statusText  = '🚀 قيد التنفيذ';
                            $btnColor    = 'primary';
                        } elseif($project['escrowStatus'] === 'completed') {
                            $statusColor = 'success';
                            $statusText  = '✅ مكتمل ومسلم';
                            $btnColor    = 'success';
                        } elseif($project['escrowStatus'] === 'disputed') {
                            $statusColor = 'danger';
                            $statusText  = '🚨 تحت النزاع';
                            $btnColor    = 'danger';
                        } elseif($project['escrowStatus'] === 'cancelled') {
                            $statusColor = 'secondary';
                            $statusText  = '❌ ملغى';
                            $btnColor    = 'secondary';
                        }
                    }
                ?>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 rounded-3 border-start border-4 border-<?php echo $statusColor; ?> transition-hover">
                        <div class="card-body p-4 d-flex flex-column">
                            
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-<?php echo $statusColor; ?> bg-opacity-10 text-<?php echo ($statusColor=='warning')?'dark':$statusColor; ?> px-2 py-1 rounded-2 small fw-bold">
                                    <?php echo $statusText; ?>
                                </span>
                                <span class="text-success fw-bold fs-5">
                                    $<?php echo number_format(!empty($project['finalPrice']) ? $project['finalPrice'] : $project['price'], 2); ?>
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2"><?php echo $project['title']; ?></h5>
                            
                            <div class="text-muted small mb-4">
                                <div class="mb-1">
                                    <?php if(!empty($project['freelancerName'])): ?>
                                        👤 المنفذ: <strong class="text-primary"><?php echo $project['freelancerName']; ?></strong>
                                    <?php else: ?>
                                        👤 المنفذ: <span class="text-muted fst-italic">لم يتم التحديد بعد</span>
                                    <?php endif; ?>
                                </div>
                                <div>📅 تاريخ النشر: <?php echo date('Y-m-d', strtotime($project['created_at'])); ?></div>
                            </div>

                            <a href="<?php echo $actionLink; ?>" class="btn btn-<?php echo $btnColor; ?> w-100 fw-bold mt-auto shadow-sm">
                                <?php echo $btnText; ?>
                            </a>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.transition-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>