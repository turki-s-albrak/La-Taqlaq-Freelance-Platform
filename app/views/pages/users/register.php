<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3">
                <div class="card-body">
                    <h2 class="text-center fw-bold text-dark mb-4"><?php echo $title; ?></h2>
                    <p class="text-center text-muted small mb-4">انضم إلى مجتمع "لا تقلق" وأنجز أعمالك بأمان</p>
                    
                    <form action="<?php echo URLROOT; ?>/users/register" method="POST">
                        <!-- الاسم الكامل -->
                        <div class="mb-3">
                            <label for="userName" class="form-label small fw-bold text-secondary">الاسم الكامل</label>
                            <input type="text" name="userName" id="userName" class="form-control form-control-md" value="<?php echo $userName; ?>" placeholder="أدخل اسمك الثلاثي" required>
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold text-secondary">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" class="form-control form-control-md" value="<?php echo $email; ?>" placeholder="name@example.com" required>
                        </div>

                        <!-- كلمة المرور -->
                        <div class="mb-3">
                            <label for="password" class="form-label small fw-bold text-secondary">كلمة المرور</label>
                            <input type="password" name="password" id="password" class="form-control form-control-md" placeholder="••••••••" required>
                        </div>

                        <!-- تأكيد كلمة المرور -->
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label small fw-bold text-secondary">تأكيد كلمة المرور</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-md" placeholder="••••••••" required>
                        </div>

                        <!-- الكلمة السرية الخفية للاستعادة الذاتية -->
                        <div class="mb-4 p-3 bg-light rounded-2 border">
                            <label for="secretWord" class="form-label small fw-bold text-danger">الكلمة السرية الخاصة (للاستعادة الذاتية)</label>
                            <input type="text" name="secretWord" id="secretWord" class="form-control form-control-md" value="<?php echo $secretWord; ?>" placeholder="كلمة أو جملة مميزة لن تنساها" required>
                            <div class="form-text text-muted xsmall" style="font-size: 0.75rem; margin-top: 5px;">
                                * هام جداً: احفظ هذه الكلمة جيداً! ستستخدمها لاسترجاع حسابك ذاتياً وبشكل آمن تماماً في حال فقدان كلمة المرور دون الحاجة لسيرفر إرسال إيميلات خارجي.
                            </div>
                        </div>

                        <!-- زر الإرسال -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">إنشاء الحساب</button>

                        <!-- زر اذا كان لديك حساب بالفعل -->
                        <div class="text-center border-top pt-3 mt-4">
                        <p class="small text-muted mb-0">لديك حساب بالفعل؟ <a href="<?php echo URLROOT; ?>/users/login" class="text-decoration-none fw-bold">سجل دخولك من هنا</a></p>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>