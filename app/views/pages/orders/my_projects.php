<div class="container my-5">
    
    <div class="mb-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">📁 مشاريعي وإدارة العقود</h2>
            <p class="text-muted small mb-0">تابع حالة مشاريعك التي قمت بنشرها، راجع العروض، وادخل مساحات العمل من مكان واحد.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/orders/create" class="btn btn-primary fw-bold shadow-sm px-4 py-2 rounded-pill btn-hover-effect">
            ➕ نشر مشروع جديد
        </a>
    </div>

    <?php if(empty($projects)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
            <div class="fs-1 mb-3">📭</div>
            <h4 class="fw-bold text-dark mb-2">لا توجد لديك مشاريع منشورة!</h4>
            <p class="text-muted mb-4">يبدو أنك لم تقم بنشر أي مشروع في المنصة حتى الآن.</p>
            <div>
                <a href="<?php echo URLROOT; ?>/orders/create" class="btn btn-primary px-4 py-2 fw-bold shadow-sm rounded-pill">ابدأ بنشر مشروعك الأول</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($projects as $project): ?>
                <?php 
                    // المعالجة المعمارية الذكية للحالة والتوجيه (لم يتم المساس بها)
                    $statusColor = '';
                    $statusText = '';
                    $actionLink = '';
                    $btnText = '';
                    $btnColor = '';
                    
                    if (empty($project['escrowStatus'])) {
                        $statusColor = 'warning';
                        $statusText  = '⏳ بانتظار العروض';
                        $actionLink  = URLROOT . '/orders/show/' . $project['orderId'];
                        $btnText     = 'مراجعة العروض وإدارة المشروع &rarr;';
                        $btnColor    = 'outline-warning text-dark border-warning';
                    } else {
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
                    <div class="card h-100 shadow-sm border-0 rounded-4 border-start border-4 border-<?php echo $statusColor; ?> project-card">
                        <div class="card-body p-4 d-flex flex-column">
                            
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-<?php echo $statusColor; ?> bg-gradient bg-opacity-10 text-<?php echo ($statusColor=='warning')?'dark':'white'; ?> px-3 py-2 rounded-pill shadow-sm small fw-bold">
                                    <?php echo $statusText; ?>
                                </span>
                                <span class="text-success fw-bold fs-5 bg-success bg-opacity-10 px-3 py-1 rounded-pill border border-success border-opacity-25">
                                    $<?php echo number_format(!empty($project['finalPrice']) ? $project['finalPrice'] : $project['price'], 2); ?>
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-3"><?php echo $project['title']; ?></h5>
                            
                            <div class="bg-light p-3 rounded-3 mb-4 mt-auto border border-secondary border-opacity-10">
                                <div class="mb-2 small fw-bold">
                                    <?php if(!empty($project['freelancerName'])): ?>
                                        <span class="text-muted">👤 المنفذ:</span> <strong class="text-primary"><?php echo $project['freelancerName']; ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">👤 المنفذ:</span> <span class="text-muted fst-italic">لم يتم التحديد بعد</span>
                                    <?php endif; ?>
                                </div>
                                <div class="small fw-bold text-muted">
                                    📅 تاريخ النشر: <?php echo date('Y-m-d', strtotime($project['created_at'])); ?>
                                </div>
                            </div>

                            <a href="<?php echo $actionLink; ?>" class="btn btn-<?php echo $btnColor; ?> w-100 fw-bold py-2 rounded-pill shadow-sm btn-hover-effect">
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