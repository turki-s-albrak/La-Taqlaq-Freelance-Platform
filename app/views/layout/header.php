<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : SITENAME; ?></title>
    <!-- استدعاء بوتستراب المحلي -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bootstrap.min.css">
    <!-- خطوط مخصصة لمظهر احترافي -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<!-- شريط التنقل الذكي (State-Aware Navbar) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <!-- الشعار باليمين دائماً -->
        <a class="navbar-brand fw-bold fs-4" href="<?php echo URLROOT; ?>">لا تقلق</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- الروابط المخصصة للزائر (Guest) كمرحلة أولى -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <!-- هنا ستظهر أزرار التحكم ديناميكياً بناءً على الـ Role لاحقاً -->
            </ul>
            <div class="d-flex gap-2">
                <a href="#" class="btn btn-outline-light btn-sm px-3">تسجيل الدخول</a>
                <a href="#" class="btn btn-primary btn-sm px-3">إنشاء حساب</a>
            </div>
        </div>
    </div>
</nav>

<!-- مكان عرض الرسائل الومضية Flash Messages (سنفعلها لاحقاً) -->
<div class="container mt-3">
    <?php if(isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>