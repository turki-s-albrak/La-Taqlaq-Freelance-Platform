<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card shadow border-0 rounded-3 bg-white p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-dark mb-1">🔐 استعادة الحساب</h2>
                        <p class="text-muted small">أدخل بريدك وكلمتك السرية لتعيين كلمة مرور جديدة فوراً</p>
                    </div>

                    <?php if(isset($_SESSION['flash_error'])): ?>
                        <div class="alert alert-danger text-center small py-2"><?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
                    <?php endif; ?>
                    
                    <form action="<?php echo URLROOT; ?>/users/forgot_password" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">البريد الإلكتروني المسجل:</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data['email']); ?>" placeholder="name@example.com" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">الكلمة السرية للحساب:</label>
                            <input type="text" name="secretWord" class="form-control" placeholder="أدخل الكلمة السرية..." required autocomplete="off">
                        </div>

                        <hr class="text-muted my-4">

                        <div class="bg-light p-3 rounded-3 mb-4 border border-dashed">
                            <h6 class="fw-bold text-dark small mb-3">🔒 تعيين كلمة المرور الجديدة:</h6>
                            
                            <div class="mb-3">
                                <label class="form-label xsmall text-secondary">كلمة المرور الجديدة:</label>
                                <input type="password" name="new_password" class="form-control form-control-sm" placeholder="6 أحرف على الأقل..." required>
                            </div>

                            <div class="mb-0">
                                <label class="form-label xsmall text-secondary">تأكيد كلمة المرور الجديدة:</label>
                                <input type="password" name="confirm_password" class="form-control form-control-sm" placeholder="أعد كتابتها للتأكيد..." required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3">حفظ وتحديث كلمة المرور</button>
                        
                        <div class="text-center">
                            <a href="<?php echo URLROOT; ?>/users/login" class="text-decoration-none small fw-bold text-secondary">&larr; العودة لصفحة تسجيل الدخول</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>