<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><?php echo $page_title; ?></h2>
            <p class="text-muted small mb-0">نظرة عامة على أداء ومؤشرات منصة "لا تقلق" حالياً</p>
        </div>
        <span class="badge bg-danger p-2 fw-bold">لوحة تحكم عليا</span>
    </div>

    <!-- شبكة الإحصائيات السريعة المتجاوبة -->
    <div class="row g-4 mb-5">
        <!-- إجمالي المستخدمين -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 bg-white p-4 rounded-3 border-start border-primary border-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-secondary fw-bold small mb-2">إجمالي الأعضاء</h6>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $stats['users']; ?></h2>
                    </div>
                    <div class="bg-light p-3 rounded-3 text-primary fw-bold fs-4">👥</div>
                </div>
            </div>
        </div>

        <!-- إجمالي العروض المفتوحة -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 bg-white p-4 rounded-3 border-start border-success border-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-secondary fw-bold small mb-2">العروض المعلقة (سوق العمل)</h6>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $stats['pending_bids']; ?></h2>
                    </div>
                    <div class="bg-light p-3 rounded-3 text-success fw-bold fs-4">💼</div>
                </div>
            </div>
        </div>

        <!-- عدد النزاعات المفتوحة -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 bg-white p-4 rounded-3 border-start border-danger border-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-secondary fw-bold small mb-2">النزاعات النشطة حالياً</h6>
                        <h2 class="fw-bold text-dark mb-0"><?php echo $stats['open_disputes']; ?></h2>
                    </div>
                    <div class="bg-light p-3 rounded-3 text-danger fw-bold fs-4">⚖️</div>
                </div>
            </div>
        </div>
    </div>

    <!-- روابط الوصول السريع للأدمن -->
    <div class="card shadow-sm border-0 p-4 bg-white rounded-3">
        <div class="card-body">
            <h5 class="fw-bold text-dark mb-3">إجراءات الرقابة والإشراف السريع:</h5>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?php echo URLROOT; ?>/admin/users" class="btn btn-outline-primary fw-bold btn-sm px-4">إدارة مستخدمي المنصة</a>
                <a href="<?php echo URLROOT; ?>/admin/projects" class="btn btn-outline-success fw-bold btn-sm px-4">مراقبة وحذف المشاريع</a>
                <a href="<?php echo URLROOT; ?>/admin/disputes" class="btn btn-outline-danger fw-bold btn-sm px-4">غرفة حل النزاعات المالية</a>
            </div>
        </div>
    </div>
</div>