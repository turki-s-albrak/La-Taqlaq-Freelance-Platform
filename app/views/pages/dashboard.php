<div class="container my-5">
    <div class="row g-4">
        
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 h-100 border-top border-4 border-success">
                <div class="card-body d-flex flex-column justify-content-between p-0">
                    <div>
                        <h5 class="fw-bold text-secondary small mb-3"> رصيدك الحالي في الخزنة</h5>
                        <h2 class="display-6 fw-bold text-success mb-3">$<?php echo number_format($balance, 2); ?></h2>
                        <p class="text-muted xsmall mb-4" style="line-height: 1.6;">
                            * هذا رصيد تجريبي مخصص لاختبار الحماية والعمليات المالية داخل المنصة (إيداع، تعاقد، استلام).
                        </p>
                    </div>
                    
                    <div class="bg-light p-3 rounded-3 border border-dashed mt-auto">
                        <form action="<?php echo URLROOT; ?>/users/deposit" method="POST">
                            <label class="form-label xsmall fw-bold text-secondary">إضافة رصيد تجريبي:</label>
                            <div class="input-group mb-2 input-group-sm">
                                <input type="number" name="amount" class="form-control border-start-0" placeholder="المبلغ (مثال: 500)" min="1" max="5000" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold btn-sm py-2 shadow-sm">شحن الرصيد فوراً</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 h-100">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-4">
                        <div>
                            <h2 class="fw-bold text-dark mb-2">مرحباً بك، <?php echo $_SESSION['user_name']; ?> 👋</h2>
                            <p class="text-muted small">مركز العمليات الخاص بك. راقب إحصائياتك وانطلق لإنجاز مهامك.</p>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold text-secondary mb-3">📊 نظرة عامة على نشاطك:</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <div class="p-3 bg-light border rounded-3 position-relative transition-hover text-center h-100">
                                <div class="fs-1 mb-2">📁</div>
                                <h3 class="fw-bold text-primary mb-1"><?php echo $projectsCount; ?></h3>
                                <span class="text-secondary small fw-bold">مشاريع نشرتها (كعميل)</span>
                                <a href="<?php echo URLROOT; ?>/orders/my_projects" class="stretched-link"></a>
                            </div>
                        </div>
                        
                        <div class="col-12 col-sm-6">
                            <div class="p-3 bg-light border rounded-3 position-relative transition-hover text-center h-100">
                                <div class="fs-1 mb-2">💼</div>
                                <h3 class="fw-bold text-success mb-1"><?php echo $worksCount; ?></h3>
                                <span class="text-secondary small fw-bold">أعمال تنفذها (كمستقل)</span>
                                <a href="<?php echo URLROOT; ?>/workspaces/my_work" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25 mb-4">

                    <h6 class="fw-bold text-secondary mb-3">🚀 إجراءات سريعة:</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?php echo URLROOT; ?>/orders/create" class="btn btn-outline-dark fw-bold px-4 py-2">➕ نشر مشروع جديد</a>
                        <a href="<?php echo URLROOT; ?>/orders" class="btn btn-outline-dark fw-bold px-4 py-2"> تصفح سوق المشاريع</a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* تأثير حركي خفيف للبطاقات الإحصائية */
.transition-hover {
    transition: all 0.2s ease-in-out;
}
.transition-hover:hover {
    background-color: #f8f9fa !important;
    border-color: #dee2e6 !important;
    transform: translateY(-3px);
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
}
.border-dashed {
    border-style: dashed !important;
}
</style>