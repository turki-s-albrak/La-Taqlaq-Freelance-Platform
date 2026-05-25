<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #cacbca; /* لون خلفية أهدأ وأكثر احترافية */
        }
        
        /* تأثيرات الناف بار المخصصة (SaaS UI) */
        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .nav-link-custom {
            color: #495057 !important;
            font-weight: 600;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        .nav-link-custom:hover {
            color: #0d6efd !important;
            background-color: rgba(13, 110, 253, 0.05);
        }
        /* الشعار */
        .brand-text {
            color: #212529;
            letter-spacing: -0.5px;
        }
        .brand-text:hover { color: #0d6efd; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-light navbar-custom shadow-sm sticky-top py-3">
    <div class="container">
        
        <?php if(!Controller::isLoggedIn()): ?>
            <a class="navbar-brand fw-bold fs-4 brand-text" href="<?php echo URLROOT; ?>">لا تقلق ✨</a>
        <?php elseif(Controller::getUserRole() === 'admin'): ?>
            <a class="navbar-brand fw-bold fs-4 brand-text" href="<?php echo URLROOT; ?>/admin/dashboard">
                لا تقلق <span class="badge bg-danger bg-gradient fs-6 ms-1 rounded-pill">الإدارة</span>
            </a>
        <?php else: ?>
            <a class="navbar-brand fw-bold fs-4 brand-text" href="<?php echo URLROOT; ?>/dashboard">لا تقلق ✨</a>
        <?php endif; ?>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <ul class="navbar-nav me-auto ms-auto mb-2 mb-lg-0 gap-1 align-items-lg-center">
                
                <?php if(Controller::isLoggedIn() && Controller::getUserRole() === 'user'): ?>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="<?php echo URLROOT; ?>/dashboard">المركز</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="<?php echo URLROOT; ?>/orders">السوق</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="<?php echo URLROOT; ?>/orders/my_projects">مشاريعي</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="<?php echo URLROOT; ?>/workspaces/my_work">أعمالي</a></li>
                    
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-primary btn-sm fw-bold px-4 py-2 rounded-pill shadow-sm" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'" href="<?php echo URLROOT; ?>/orders/create">
                            ➕ أضف مشروعاً
                        </a>
                    </li>
                
                <?php elseif(Controller::isLoggedIn() && Controller::getUserRole() === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="<?php echo URLROOT; ?>/admin/users">الأعضاء</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="<?php echo URLROOT; ?>/admin/projects">المشاريع</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="<?php echo URLROOT; ?>/admin/disputes">محكمة المنصة ⚖️</a></li>
                <?php endif; ?>
                
            </ul>

            <div class="d-flex gap-2 align-items-center mt-3 mt-lg-0 border-top border-lg-0 pt-3 pt-lg-0">
                <?php if(Controller::isLoggedIn()): ?>
                    
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-dark fw-bold" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                👤
                            </div>
                            <?php echo $_SESSION['user_name']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3" aria-labelledby="userDropdown">
                            <li><h6 class="dropdown-header text-muted small">حسابك الشخصي</h6></li>
                            
                            <?php if(Controller::getUserRole() === 'user'): ?>
                                <li><a class="dropdown-item fw-bold text-secondary py-2" href="<?php echo URLROOT; ?>/dashboard">لوحة التحكم</a></li>
                            <?php endif; ?>
                            
                            <li><hr class="dropdown-divider opacity-10"></li>
                            <li>
                                <a class="dropdown-item fw-bold text-danger py-2" href="<?php echo URLROOT; ?>/users/logout">
                                    <span class="me-2">🚪</span> تسجيل الخروج
                                </a>
                            </li>
                        </ul>
                    </div>

                <?php else: ?>
                    <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-light text-dark fw-bold px-4 py-2 border shadow-sm rounded-pill">دخول</a>
                    <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-primary fw-bold px-4 py-2 shadow-sm rounded-pill">حساب جديد</a>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?php if(isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 fw-bold" role="alert">
            🚨 <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 fw-bold" role="alert">
            ✅ <?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>