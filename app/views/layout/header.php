<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: hsl(180, 2%, 75%);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        
        <?php if(!Controller::isLoggedIn()): ?>
            <a class="navbar-brand fw-bold fs-4" href="<?php echo URLROOT; ?>">لا تقلق</a>
        <?php elseif(Controller::getUserRole() === 'admin'): ?>
            <a class="navbar-brand fw-bold fs-4" href="<?php echo URLROOT; ?>/admin/dashboard">لا تقلق <span class="badge bg-danger fs-6">الإدارة</span></a>
        <?php else: ?>
            <a class="navbar-brand fw-bold fs-4" href="<?php echo URLROOT; ?>/dashboard">لا تقلق</a>
        <?php endif; ?>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if(Controller::isLoggedIn() && Controller::getUserRole() === 'user'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/dashboard">لوحة التحكم</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/orders">تصفح المشاريع</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/orders/my_projects">مشاريعي</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/workspaces/my_work">اعمالي</a></li>
                
                <?php elseif(Controller::isLoggedIn() && Controller::getUserRole() === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/admin/users">إدارة المستخدمين</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/admin/projects">إدارة المشاريع</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/admin/disputes">إدارة النزاعات</a></li>

                <?php endif; ?>
            </ul>

            <div class="d-flex gap-2 align-items-center">
                <?php if(Controller::isLoggedIn()): ?>
                    <span class="text-light small me-2">
                        مرحباً، <strong><?php echo $_SESSION['user_name']; ?></strong>
                    </span>
                    <a href="<?php echo URLROOT; ?>/users/logout" class="btn btn-danger btn-sm px-3">تسجيل الخروج</a>
                <?php else: ?>
                    <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-light btn-sm px-3">تسجيل الدخول</a>
                    <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-primary btn-sm px-3">إنشاء حساب</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</nav>

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