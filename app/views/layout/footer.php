<footer class="bg-dark text-light py-4 mt-5">
    <div class="container text-center">
        <p class="mb-1">&copy; <?php echo date('Y'); ?> منصة لا تقلق - جميع الحقوق محفوظة.</p>
        <small class="text-muted">مشروع ويب آمن مبني بمعمارية MVC المخصصة</small>
    </div>
</footer>

<!-- استدعاء جافاسكريبت بوتستراب المحلي المدمج -->
<script src="<?php echo URLROOT; ?>/js/bootstrap.bundle.min.js"></script>

<!-- سد ثغرة زر الرجوع في المتصفح (BFCache Eviction) -->
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