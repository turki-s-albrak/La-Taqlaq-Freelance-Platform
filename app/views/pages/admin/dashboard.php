<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1"><?php echo $page_title; ?></h2>
            <p class="text-muted small mb-0">نظرة عامة على أداء ومؤشرات منصة "لا تقلق" حالياً</p>
        </div>
        <span class="badge bg-danger p-2 px-3 fw-bold shadow-sm rounded-pill">لوحة تحكم عليا</span>
    </div>

    <div class="row g-4 mb-5">
        
        <div class="col-12 col-md-12">
            <div class="card shadow-sm border-0 bg-white p-4 rounded-3 border-start border-primary border-4 transition-hover position-relative h-100">
                <div class="card-body d-flex justify-content-between align-items-center p-0">
                    <div>
                        <h6 class="text-secondary fw-bold small mb-2">إجمالي الأعضاء</h6>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $stats['users']; ?></h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary fw-bold fs-4">👥</div>
                </div>
                <a href="<?php echo URLROOT; ?>/admin/users" class="stretched-link"></a>
            </div>
        </div>

        <div class="col-12 col-md-12">
            <div class="card shadow-sm border-0 bg-white p-4 rounded-3 border-start border-success border-4 transition-hover position-relative h-100">
                <div class="card-body d-flex justify-content-between align-items-center p-0">
                    <div>
                        <h6 class="text-secondary fw-bold small mb-2">العروض المعلقة (سوق العمل)</h6>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $stats['pending_bids']; ?></h2>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success fw-bold fs-4">💼</div>
                </div>
                <a href="<?php echo URLROOT; ?>/admin/projects" class="stretched-link"></a>
            </div>
        </div>

        <div class="col-12 col-md-12">
            <div class="card shadow-sm border-0 bg-white p-4 rounded-3 border-start border-danger border-4 transition-hover position-relative h-100">
                <div class="card-body d-flex justify-content-between align-items-center p-0">
                    <div>
                        <h6 class="text-secondary fw-bold small mb-2">النزاعات النشطة حالياً</h6>
                        <h2 class="fw-bold text-danger mb-0"><?php echo $stats['open_disputes']; ?></h2>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-3 text-danger fw-bold fs-4">⚖️</div>
                </div>
                <a href="<?php echo URLROOT; ?>/admin/disputes" class="stretched-link"></a>
            </div>
        </div>

    </div>

</div>

<style>
/* تأثير تفاعلي خفيف يشعر المدير بالاستجابة عند تمرير الماوس */
.transition-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.transition-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    background-color: #f8f9fa !important;
}
</style>