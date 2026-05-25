<div class="container d-flex flex-column justify-content-center align-items-center my-5" style="min-height: 80vh;">

    <div class="text-center mb-4">
        <h1 class="fw-bold text-primary mb-2">نسيت كلمة المرور؟ 🔒</h1>
        <p class="text-muted">"لا تقلق".. يمكنك استعادة حسابك بثوانٍ عبر كلمتك السرية.</p>
    </div>

    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-lg border-0 p-4 p-md-5 bg-white rounded-4 auth-card">
            <div class="card-body p-0">

                <h4 class="text-center fw-bold text-dark mb-4">🔐 استعادة الحساب</h4>

                <?php if(isset($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger text-center small py-3 rounded-3 border-0 shadow-sm mb-4">
                        <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/users/forgot_password" method="POST">
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary px-1">البريد الإلكتروني المسجل</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0 text-muted ps-3 pr-3">📧</span>
                            <input type="email" name="email" class="form-control bg-light border-0 custom-input" value="<?php echo htmlspecialchars($data['email']); ?>" placeholder="name@example.com" required>
                        </div>
                    </div>

                    <div class="mb-4 p-4 bg-warning bg-opacity-10 rounded-4 border border-warning border-opacity-25">
                        <label class="form-label small fw-bold text-danger px-1">🔑 الكلمة السرية للحساب</label>
                        <input type="text" name="secretWord" class="form-control form-control-lg bg-white border-0 custom-input shadow-sm" placeholder="أدخل الكلمة السرية..." required autocomplete="off">
                    </div>

                    <div class="bg-light p-4 rounded-4 mb-4 border border-secondary border-opacity-10">
                        <h6 class="fw-bold text-dark small mb-3">🔒 تعيين كلمة المرور الجديدة:</h6>
                        
                        <div class="mb-3">
                            <label class="form-label xsmall fw-bold text-secondary px-1">كلمة المرور الجديدة</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 text-muted ps-3 pr-3">🔐</span>
                                <input type="password" name="new_password" class="form-control form-control-lg bg-white border-0 custom-input shadow-sm" placeholder="6 أحرف على الأقل..." required>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label xsmall fw-bold text-secondary px-1">تأكيد كلمة المرور الجديدة</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 text-muted ps-3 pr-3">✓</span>
                                <input type="password" name="confirm_password" class="form-control form-control-lg bg-white border-0 custom-input shadow-sm" placeholder="أعد كتابتها للتأكيد..." required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm btn-hover-effect">
                        حفظ وتحديث كلمة المرور
                    </button>
                    
                    <div class="text-center pt-4 mt-4 border-top border-light">
                        <a href="<?php echo URLROOT; ?>/users/login" class="text-decoration-none text-secondary small fw-bold hover-link">
                            &rarr; العودة لصفحة تسجيل الدخول
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<style>
/* نفس التأثيرات البصرية الموحدة (SaaS UI) */
.custom-input {
    box-shadow: none !important;
    transition: all 0.3s ease;
}
.custom-input:focus {
    background-color: #fff !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15) !important;
}
.btn-hover-effect {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.btn-hover-effect:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(13, 110, 253, .25)!important;
}
.hover-link {
    transition: color 0.2s ease;
}
.hover-link:hover {
    color: #0a58ca !important;
}
</style>