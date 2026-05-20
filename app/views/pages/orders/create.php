<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-7">
            <div class="card shadow-sm border-0 p-4 bg-white rounded-3">
                <div class="card-body">
                    <h2 class="fw-bold text-dark mb-2"><?php echo $page_title; ?></h2>
                    <p class="text-muted small mb-4">أدخل تفاصيل مشروعك بوضوح لتجذب أفضل المستقلين في المنصة.</p>
                    
                    <form action="<?php echo URLROOT; ?>/orders/create" method="POST">
                        <!-- عنوان المشروع -->
                        <div class="mb-3">
                            <label for="title" class="form-label small fw-bold text-secondary">عنوان المشروع</label>
                            <input type="text" name="title" id="title" class="form-control form-control-md" value="<?php echo $title; ?>" placeholder="مثال: تصميم متجر إلكتروني متكامل" required>
                        </div>

                        <!-- وصف المشروع -->
                        <div class="mb-3">
                            <label for="description" class="form-label small fw-bold text-secondary">وصف تفصيلي للمشروع</label>
                            <textarea name="description" id="description" class="form-control" rows="6" placeholder="اكتب شروطك، تفاصيل العمل، والمهارات المطلوبة بدقة..." required><?php echo $description; ?></textarea>
                        </div>

                        <!-- الميزانية المتوقعة -->
                        <div class="mb-4">
                            <label for="price" class="form-label small fw-bold text-secondary">الميزانية المقترحة</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">$</span>
                                <input type="number" name="price" id="price" class="form-control" value="<?php echo $price; ?>" placeholder="أدخل مبلغ الميزانية" min="1" step="0.01" required>
                            </div>
                            <div class="form-text text-muted xsmall" style="font-size: 0.75rem;">
                                * الحد المتوقع للميزانية هو 1 دولار ، وسيتم مراجعة العروض المقدمة بناءً عليها.
                            </div>
                        </div>

                        <!-- أزرار التحكم -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">نشر المشروع الآن</button>
                            <a href="<?php echo URLROOT; ?>/dashboard" class="btn btn-outline-secondary px-3">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>