<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3">
                <div class="card-body">
                    <h2 class="text-center fw-bold text-dark mb-4"><?php echo $title; ?></h2>
                    
                    <form action="<?php echo URLROOT; ?>/users/login" method="POST">
                        <!-- البريد الإلكتروني -->
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold text-secondary">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>" placeholder="name@example.com" required>
                        </div>

                        <!-- كلمة المرور -->
                        <div class="mb-4">
                            <label for="password" class="form-label small fw-bold text-secondary">كلمة المرور</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <!-- زر الإرسال -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3">تسجيل الدخول</button>
                    </form>
                    
                    <div class="text-center border-top pt-3 mt-3">
                        <p class="small text-muted mb-0">ليس لديك حساب؟ <a href="<?php echo URLROOT; ?>/users/register" class="text-decoration-none fw-bold">أنشئ حسابك الآن</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>