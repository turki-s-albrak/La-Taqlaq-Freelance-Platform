<div class="container d-flex flex-column justify-content-center align-items-center my-5" style="min-height: 80vh;">

    <div class="text-center mb-4">
        <h1 class="fw-bold text-primary mb-2">ابدأ رحلتك معنا 🚀</h1>
        <p class="text-muted">انضم إلى مجتمع "لا تقلق" وأنجز أعمالك بأمان وموثوقية</p>
    </div>

    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-lg border-0 p-4 p-md-5 bg-white rounded-4 auth-card">
            <div class="card-body p-0">

                <h4 class="text-center fw-bold text-dark mb-4"><?php echo $title; ?></h4>

                <form action="<?php echo URLROOT; ?>/users/register" method="POST">
                    
                    <div class="mb-3">
                        <label for="userName" class="form-label small fw-bold text-secondary px-1">الاسم الكامل</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0 text-muted ps-3 pr-3">👤</span>
                            <input type="text" name="userName" id="userName" class="form-control bg-light border-0 custom-input" value="<?php echo $userName; ?>" placeholder="أدخل اسمك الثلاثي" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold text-secondary px-1">البريد الإلكتروني</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0 text-muted ps-3 pr-3">📧</span>
                            <input type="email" name="email" id="email" class="form-control bg-light border-0 custom-input" value="<?php echo $email; ?>" placeholder="name@example.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold text-secondary px-1">كلمة المرور</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0 text-muted ps-3 pr-3">🔒</span>
                            <input type="password" name="password" id="password" class="form-control bg-light border-0 custom-input" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label small fw-bold text-secondary px-1">تأكيد كلمة المرور</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0 text-muted ps-3 pr-3">🔐</span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control bg-light border-0 custom-input" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="mb-4 p-4 bg-warning bg-opacity-10 rounded-4 border border-warning border-opacity-25">
                        <label for="secretWord" class="form-label small fw-bold text-danger px-1">🔑 الكلمة السرية الخاصة (للاستعادة الذاتية)</label>
                        <input type="text" name="secretWord" id="secretWord" class="form-control form-control-lg bg-white border-0 custom-input shadow-sm" value="<?php echo $secretWord; ?>" placeholder="كلمة أو جملة مميزة لن تنساها" required>
                        <div class="form-text text-dark xsmall mt-2" style="font-size: 0.75rem; line-height: 1.5;">
                            * <strong class="text-danger">هام جداً:</strong> احفظ هذه الكلمة جيداً! ستستخدمها لاسترجاع حسابك ذاتياً وبشكل آمن تماماً في حال فقدان كلمة المرور دون الحاجة لبريد إلكتروني.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold mt-2 shadow-sm btn-hover-effect">
                        إنشاء الحساب وانطلاق &rarr;
                    </button>
                </form>

                <div class="text-center pt-4 mt-4 border-top border-light">
                    <p class="small text-muted mb-0">
                        لديك حساب بالفعل؟ 
                        <a href="<?php echo URLROOT; ?>/users/login" class="text-decoration-none text-primary fw-bold ms-1 hover-link">سجل دخولك من هنا</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* تأثيرات بصرية موحدة مع صفحة الدخول */
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
    text-decoration: underline !important;
}
</style>