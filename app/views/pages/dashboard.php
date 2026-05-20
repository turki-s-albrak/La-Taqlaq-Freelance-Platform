<div class="container my-5">
    <div class="row g-4">
        
        <!-- بطاقة الرصيد والشحن التجريبي -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="fw-bold text-secondary small mb-3">رصيدك الحالي</h4>
                        <h2 class="display-5 fw-bold text-success mb-4">$<?php echo number_format($balance, 2); ?></h2>
                        <p class="text-muted small">* هذا رصيد تجريبي مخصص لاختبار الحماية والعمليات المالية داخل المنصة.</p>
                    </div>
                    
                    <!-- فوروم الشحن السريع بـ Bootstrap -->
                    <form action="<?php echo URLROOT; ?>/users/deposit" method="POST" class="mt-3">
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-light">$</span>
                            <input type="number" name="amount" class="form-control" placeholder="المبلغ" min="1" max="5000" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold btn-sm py-2">شحن رصيد تجريبي سريع</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- بطاقة الترحيب والإحصائيات السريعة -->
        <div class="col-12 col-md-8">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3 h-100">
                <div class="card-body">
                    <h2 class="fw-bold text-dark mb-2">مرحباً بك، <?php echo $_SESSION['user_name']; ?>!</h2>
                    <p class="text-muted">من هنا يمكنك إدارة مشاريعك وعروضك الماليّة بكل سهولة وبأعلى مستويات الأمان.</p>
                    
                    <div class="alert alert-info mt-4 border-0 small" role="alert">
                        <strong>خطوتنا التالية:</strong> بعد التأكد من عمل نظام الشحن وتحديث الرصيد، سنقوم بإنشاء جداول المشاريع والعروض برمجياً لنبدأ في تفعيل سوق العمل الحر الخاص بك.
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>