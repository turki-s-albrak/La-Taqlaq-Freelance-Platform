<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-lg-8">
            
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark mb-2">➕ <?php echo $page_title; ?></h2>
                <p class="text-muted">أدخل تفاصيل مشروعك بوضوح لتجذب أفضل المستقلين في المنصة.</p>
            </div>

            <div class="card shadow-sm border-0 p-4 p-md-5 bg-white rounded-4 border-top border-primary border-4">
                <div class="card-body p-0">
                    
                    <form action="<?php echo URLROOT; ?>/orders/create" method="POST">
                        
                        <div class="mb-4">
                            <label for="title" class="form-label small fw-bold text-secondary px-1">عنوان المشروع</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-0 text-muted ps-3 pr-3">📝</span>
                                <input type="text" name="title" id="title" class="form-control bg-light border-0 custom-input" value="<?php echo $title; ?>" placeholder="مثال: تصميم متجر إلكتروني متكامل" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label small fw-bold text-secondary px-1">وصف تفصيلي للمشروع</label>
                            <textarea name="description" id="description" class="form-control bg-light border-0 custom-input p-3" rows="7" placeholder="اكتب شروطك، تفاصيل العمل، والمهارات المطلوبة بدقة..." required><?php echo $description; ?></textarea>
                        </div>

                        <div class="mb-5 p-4 bg-success bg-opacity-10 rounded-4 border border-success border-opacity-25">
                            <label for="price" class="form-label small fw-bold text-success px-1">💰 الميزانية المقترحة</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white border-0 text-success fw-bold ps-3 pr-3">$</span>
                                <input type="number" name="price" id="price" class="form-control bg-white border-0 custom-input shadow-sm" value="<?php echo $price; ?>" placeholder="أدخل مبلغ الميزانية" min="1" step="0.01" required>
                            </div>
                            <div class="form-text text-dark xsmall mt-2" style="font-size: 0.75rem; line-height: 1.6;">
                                * الحد الأدنى للميزانية هو 1 دولار، سيتم نشر المشروع فوراً ليتمكن المستقلون من تقديم عروضهم بناءً على هذه الميزانية.
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-3 align-items-center border-top border-light pt-4 mt-2">
                            <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow-sm btn-hover-effect">
                                نشر المشروع الآن 🚀
                            </button>
                            <a href="<?php echo URLROOT; ?>/dashboard" class="btn btn-light btn-lg px-4 fw-bold text-muted border hover-link">
                                إلغاء
                            </a>
                        </div>

                    </form>

                </div>
            </div>
            
        </div>
    </div>
</div>

<style>
/* التأثيرات البصرية الموحدة (SaaS UI) */
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
    transition: all 0.2s ease;
}
.hover-link:hover {
    background-color: #e9ecef !important;
    color: #495057 !important;
}
</style>