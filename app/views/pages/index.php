<style>
    .hero-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e0ebff 100%);
        border-radius: 2rem;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(13,110,253,0.05) 0%, transparent 60%);
        z-index: 0;
    }
    .hero-content {
        position: relative;
        z-index: 1;
    }
    .feature-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 1rem 2rem rgba(0,0,0,0.1) !important;
        border-color: rgba(13, 110, 253, 0.2);
    }
    .feature-icon-wrapper {
        width: 80px; height: 80px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 1rem;
        margin: 0 auto;
    }
    .btn-hero {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
</style>

<div class="container my-5">
    
    <div class="hero-section shadow-sm border p-4 p-md-5 mb-5">
        <div class="container-fluid py-5 text-center hero-content">
            
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">✨ أهلاً بك في منصة العمل الحر الآمنة</span>
            
            <h1 class="display-4 fw-bold text-dark mb-4" style="line-height: 1.4;">
                <?php echo $title; ?> <br>
                <span class="text-primary">بأمان، ثقة، واحترافية</span>
            </h1>
            
            <p class="col-md-8 fs-5 mx-auto text-secondary mb-5" style="line-height: 1.8;">
                <?php echo $description; ?>
                هنا تلتقي الأفكار الطموحة بالمهارات الاستثنائية، في بيئة تضمن حقوق الجميع منذ اللحظة الأولى وحتى استلام المشروع.
            </p>
            
            <div class="d-flex justify-content-center flex-wrap gap-3 mt-4">
                <a href="<?php echo URLROOT; ?>/orders" class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-bold btn-hero shadow-sm">
                    💼 أنجز ما تتقن واكسب الآن
                </a>
                <a href="<?php echo URLROOT; ?>/orders/create" class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-bold btn-hero shadow-sm">
                    🚀 اطلب ما تتمنى ونفذه فوراً
                </a>
            </div>
            
        </div>
    </div>

    <div class="text-center mb-5 mt-5 pt-4">
        <h2 class="fw-bold text-dark">لماذا "لا تقلق" هي خيارك الأول؟</h2>
        <p class="text-muted">بنينا هذه المنصة بمعمارية قوية لتركز أنت على عملك، ونحن نتكفل بالباقي.</p>
    </div>

    <div class="row g-4 text-center">
        
        <div class="col-12 col-md-4">
            <div class="card h-100 bg-white feature-card rounded-4 p-4">
                <div class="card-body">
                    <div class="feature-icon-wrapper bg-success bg-opacity-10 mb-4">
                        <span class="fs-1">💰</span>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">حماية أموالك (Escrow)</h4>
                    <p class="text-secondary small" style="line-height: 1.7;">
                        لا تدفع مقدماً ولا تعمل مجاناً. نظام الخزنة الذكي يحجز الأموال بأمان، ويضمن تسليمها فور اكتمال العمل بدقة وموافقة الطرفين.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="card h-100 bg-white feature-card rounded-4 p-4">
                <div class="card-body">
                    <div class="feature-icon-wrapper bg-danger bg-opacity-10 mb-4">
                        <span class="fs-1">⚖️</span>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">تحكيم عادل وصارم</h4>
                    <p class="text-secondary small" style="line-height: 1.7;">
                        حدث خلاف؟ لا تقلق! فريق الإدارة يتدخل فوراً لفض النزاعات عبر التدقيق في سجلات المحادثة ومساحة العمل لضمان عودة الحق لصاحبه.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="card h-100 bg-white feature-card rounded-4 p-4">
                <div class="card-body">
                    <div class="feature-icon-wrapper bg-primary bg-opacity-10 mb-4">
                        <span class="fs-1">🛡️</span>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">حماية سيبرانية فائقة</h4>
                    <p class="text-secondary small" style="line-height: 1.7;">
                        بياناتك خلف حصون منيعة. المنصة مبنية بأحدث معايير الأمان (MVC) لسد كافة الثغرات وحمايتك من أي محاولات اختراق أو تلاعب.
                    </p>
                </div>
            </div>
        </div>
        
    </div>

    <div class="mt-5 pt-5 pb-4 text-center">
        <div class="bg-dark rounded-4 p-5 shadow-lg position-relative overflow-hidden">
            <div class="position-relative z-index-1">
                <h3 class="text-white fw-bold mb-3">هل أنت مستعد لتجربة العمل الحر الحقيقي؟</h3>
                <p class="text-light opacity-75 mb-4">انضم إلى مئات المستقلين والعملاء الذين ينجزون أعمالهم بثقة مطلقة.</p>
                <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-primary btn-lg rounded-pill fw-bold px-5 shadow-sm btn-hero">
                    أنشئ حسابك المجاني الآن
                </a>
            </div>
        </div>
    </div>

</div>