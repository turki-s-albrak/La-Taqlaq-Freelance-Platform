<div class="container my-5">
    <!-- قسم البطل Hero Section -->
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm border">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-5 fw-bold text-dark mb-3"><?php echo $title; ?></h1>
            <p class="col-md-8 fs-5 mx-auto text-muted"><?php echo $description; ?></p>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="#" class="btn btn-primary btn-lg px-4">ابدأ كمستقل الآن</a>
                <a href="#" class="btn btn-outline-secondary btn-lg px-4">أضف مشروعك الأول</a>
            </div>
        </div>
    </div>

    <!-- قسم الميزات الموزع بشبكة متجاوبة (Bootstrap Grid) -->
    <div class="row g-4 text-center mt-4">
        <!-- ميزة 1 -->
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm p-3">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-primary mb-3">حماية أموالك (Escrow)</h3>
                    <p class="text-muted small">نظام خزنة متطور يحفظ حقوق العميل والمستقل حتى إتمام العمل بالكامل دون قلق.</p>
                </div>
            </div>
        </div>
        <!-- ميزة 2 -->
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm p-3">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-primary mb-3">فض النزاعات الصارم</h3>
                    <p class="text-muted small">لوحة تحكم إدارة متكاملة للفصل في النزاعات العالقة بناءً على وثائق ومحادثات مساحة العمل.</p>
                </div>
            </div>
        </div>
        <!-- ميزة 3 -->
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm p-3">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-primary mb-3">حماية ضد الاختراق</h3>
                    <p class="text-muted small">تأمين شامل ضد ثغرات SQL Injection و XSS ومعالجة دقيقة لكافة الحالات الاستثنائية.</p>
                </div>
            </div>
        </div>
    </div>
</div>