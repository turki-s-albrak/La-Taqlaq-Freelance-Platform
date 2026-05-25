<footer class="bg-white border-top border-light py-5 mt-auto">
    <div class="container">
        <div class="d-flex flex-column align-items-center text-center">
            
            <h5 class="fw-bold text-dark mb-2">لا تقلق ✨</h5>
            <p class="text-muted small mb-3">
                &copy; <?php echo date('Y'); ?> منصة لا تقلق - جميع الحقوق محفوظة.
            </p>
            
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.75rem;">
                🛡️ مشروع ويب آمن | مبني بمعمارية MVC المخصصة
            </span>
            
        </div>
    </div>
</footer>

<script src="<?php echo URLROOT; ?>/js/bootstrap.bundle.min.js"></script>

<script>
    window.addEventListener('pageshow', function (event) {
        // إذا كانت الصفحة قد تم جلبها من كاش المتصفح (عند الضغط على زر الرجوع)
        if (event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2)) {
            // إجبار المتصفح على إعادة جلب البيانات الحقيقية من السيرفر فوراً
            window.location.reload();
        }
    });
</script>
</body>
</html>