<div class="container d-flex flex-column justify-content-center align-items-center my-5" style="min-height: 75vh;">
    
    <div class="text-center mb-4">
        <h1 class="fw-bold text-primary mb-2">مرحباً بعودتك!</h1>
        <p class="text-muted">سجل دخولك لمتابعة أعمالك ومشاريعك "</p>
    </div>

    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-lg border-0 p-4 p-md-5 bg-white rounded-4 auth-card">
            <div class="card-body p-0 ">
                
                <h4 class="text-center fw-bold text-dark mb-4"><?php echo $title; ?></h4>
                
                <form action="<?php echo URLROOT; ?>/users/login" method="POST">
                    
                    <div class="mb-4">
                        <label for="email" class="form-label small fw-bold text-secondary px-1">البريد الإلكتروني</label>
                        <div class="input-group input-group-lg">
                            <input type="email" name="email" id="email" class="form-control bg-light border-0 custom-input" value="<?php echo $email; ?>" placeholder="name@example.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                            <label for="password" class="form-label small fw-bold text-secondary mb-0">كلمة المرور</label>
                        </div>
                        <div class="input-group input-group-lg">
                            <input type="password" name="password" id="password" class="form-control bg-light border-0 custom-input" placeholder="••••••••" required>
                        </div>
                            <a href="<?php echo URLROOT; ?>/users/forgot_password" class="small text-decoration-none text-primary fw-bold hover-link">نسيت كلمة المرور؟</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold mt-2 shadow-sm btn-hover-effect">
                        تسجيل الدخول &rarr;
                    </button>
                </form>
                
                <div class="text-center pt-4 mt-4 border-top border-light">
                    <p class="small text-muted mb-0">
                        ليس لديك حساب بعد؟ 
                        <a href="<?php echo URLROOT; ?>/users/register" class="text-decoration-none text-primary fw-bold ms-1 hover-link">أنشئ حسابك الآن مجاناً</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* تأثيرات بصرية خاصة بصفحات الدخول (SaaS UI) */
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