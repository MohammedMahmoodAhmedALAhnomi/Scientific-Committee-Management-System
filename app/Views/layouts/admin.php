<!DOCTYPE html>
<html dir="rtl" lang="ar" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - اللجنة العلمية</title>
    <link rel="stylesheet" href="<?php echo asset('assets/fonts/fonts.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/vendor/bootstrap/css/bootstrap.rtl.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/vendor/fontawesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/admin.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/responsive.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/darkmode-fixes.css'); ?>">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-logo-wrapper">
                <?php $collegeLogo = \App\Models\Setting::get('college_logo'); ?>
                <?php if ($collegeLogo): ?>
                    <img src="<?php echo asset($collegeLogo); ?>" alt="شعار الكلية" class="sidebar-top-logo">
                <?php endif; ?>
            </div>
            <div class="sidebar-header d-flex align-items-center justify-content-between">
                <a href="<?php echo (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'major_admin', 'manager'], true)) ? url('/admin/dashboard') : url('/'); ?>" class="sidebar-brand">
                    <i class="fas fa-graduation-cap text-primary ms-1"></i>
                    <span>اللجنة العلمية</span>
                </a>
                <button type="button" class="btn-close btn-close-white d-lg-none" id="mobileSidebarClose" aria-label="إغلاق"></button>
            </div>
            <ul class="sidebar-nav">
                <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'major_admin', 'manager'], true)): ?>
                <li class="sidebar-li">
                    <a class="sidebar-a <?php echo is_active_route('/admin') || is_active_route('/admin/dashboard') ? 'active' : ''; ?>" href="<?php echo url('/admin/dashboard'); ?>">
                        <i class="fa-solid fa-chart-pie me-2 text-primary"></i>
                        <span>لوحة التحكم</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'major_admin'], true)): ?>
                <li class="sidebar-li">
                    <a class="sidebar-a <?php echo is_active_route('/admin/requests') ? 'active' : ''; ?>" href="<?php echo url('/admin/requests'); ?>">
                        <i class="fa-solid fa-user-check me-2 text-success"></i>
                        <span>طلبات الانضمام</span>
                        <?php
                            $pendingReqCount = \App\Config\Database::fetch("SELECT COUNT(*) as cnt FROM join_requests WHERE status = 'pending'")->cnt ?? 0;
                            if ($pendingReqCount > 0):
                        ?>
                            <span class="badge bg-warning text-dark me-auto"><?php echo $pendingReqCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <li class="sidebar-li">
                    <a class="sidebar-a <?php echo is_active_route('/admin/majors') ? 'active' : ''; ?>" href="<?php echo url('/admin/majors'); ?>">
                        <i class="fa-solid fa-graduation-cap me-2 text-warning"></i>
                        <span>التخصصات</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'major_admin'], true)): ?>
                <li class="sidebar-li">
                    <a class="sidebar-a <?php echo is_active_route('/admin/levels') ? 'active' : ''; ?>" href="<?php echo url('/admin/levels'); ?>">
                        <i class="fa-solid fa-layer-group me-2 text-info"></i>
                        <span>المستويات</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="sidebar-li">
                    <a class="sidebar-a <?php echo is_active_route('/admin/semesters') ? 'active' : ''; ?>" href="<?php echo url('/admin/semesters'); ?>">
                        <i class="fa-solid fa-calendar-days me-2 text-success"></i>
                        <span>الفصول الدراسية</span>
                    </a>
                </li>
                <li class="sidebar-li">
                    <a class="sidebar-a <?php echo is_active_route('/admin/courses') ? 'active' : ''; ?>" href="<?php echo url('/admin/courses'); ?>">
                        <i class="fa-solid fa-book-bookmark me-2 text-danger"></i>
                        <span>المواد الدراسية</span>
                    </a>
                </li>
                <li class="sidebar-li">
                    <a class="sidebar-a <?php echo is_active_route('/admin/files') ? 'active' : ''; ?>" href="<?php echo url('/admin/files'); ?>">
                        <i class="fa-solid fa-folder-open me-2 text-primary"></i>
                        <span>الملفات</span>
                        <?php
                            $filesLastViewed = $_SESSION['admin_files_last_viewed'] ?? $_COOKIE['admin_files_last_viewed'] ?? null;
                            $badgeCount = $newFilesCount ?? \App\Models\File::getRecentCount(48, null, null, $filesLastViewed);
                            if ($badgeCount > 0):
                        ?>
                            <span class="badge bg-danger">+<?php echo $badgeCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'major_admin', 'manager'], true)): ?>
                <li class="sidebar-li">
                    <a class="sidebar-a <?php echo is_active_route('/admin/users') ? 'active' : ''; ?>" href="<?php echo url('/admin/users'); ?>">
                        <i class="fa-solid fa-users-cog me-2 text-info"></i>
                        <span>إدارة المستخدمين والمناديب</span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="sidebar-li">
                    <a class="sidebar-a <?php echo is_active_route('/admin/announcements') ? 'active' : ''; ?>" href="<?php echo url('/admin/announcements'); ?>">
                        <i class="fa-solid fa-bullhorn me-2 text-warning"></i>
                        <span>الإعلانات</span>
                    </a>
                </li>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <li class="sidebar-li">
                    <a class="sidebar-a <?php echo is_active_route('/admin/settings') ? 'active' : ''; ?>" href="<?php echo url('/admin/settings'); ?>">
                        <i class="fa-solid fa-sliders me-2 text-secondary"></i>
                        <span>الإعدادات</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <div class="sidebar-footer">
                <a href="<?php echo url('/'); ?>" class="btn btn-sm btn-outline-light w-100 mb-2">
                    <i class="fas fa-eye ms-1"></i>عرض الموقع
                </a>
                <a href="<?php echo url('/logout'); ?>" class="btn btn-sm btn-danger w-100">
                    <i class="fas fa-sign-out-alt ms-1"></i>تسجيل خروج
                </a>
            </div>
        </aside>
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <main class="admin-main">
            <nav class="admin-topbar">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="topbar-left">
                        <button class="sidebar-toggle" id="sidebarToggle" title="إظهار/إخفاء القائمة">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    <div class="topbar-right d-flex align-items-center gap-3">
                        <!-- Notification Bell Dropdown -->
                        <?php
                            $currentUserId = $_SESSION['user_id'] ?? null;
                            $currentUserRole = $_SESSION['user_role'] ?? 'guest';
                            $notifTargetUserId = ($currentUserRole === 'admin') ? null : $currentUserId;
                            $unreadNotifCount = \App\Models\Notification::getUnreadCount($notifTargetUserId);
                            $recentNotifications = \App\Models\Notification::getForUser($notifTargetUserId, 6);
                        ?>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary position-relative rounded-circle p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="الإشعارات" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bell"></i>
                                <?php if ($unreadNotifCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                        <?php echo $unreadNotifCount > 9 ? '+9' : $unreadNotifCount; ?>
                                    </span>
                                <?php endif; ?>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0 mt-2" style="width: 320px; max-height: 420px; overflow-y: auto; z-index: 1050;">
                                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold"><i class="fas fa-bell text-primary me-2"></i>الإشعارات</h6>
                                    <?php if ($unreadNotifCount > 0): ?>
                                        <form action="<?php echo url('/notifications/read-all'); ?>" method="POST" class="d-inline">
                                            <button type="submit" class="btn btn-link p-0 text-decoration-none small text-muted" style="font-size: 0.8rem;">تحديد الكل كمقروء</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <div class="list-group list-group-flush">
                                    <?php if (empty($recentNotifications)): ?>
                                        <div class="p-4 text-center text-muted small"><i class="fas fa-inbox fs-4 d-block mb-2 text-secondary opacity-50"></i>لا توجد إشعارات حالياً</div>
                                    <?php else: ?>
                                        <?php foreach ($recentNotifications as $notif): ?>
                                            <div class="list-group-item p-3 <?php echo !$notif->is_read ? 'bg-light border-start border-3 border-primary' : ''; ?>">
                                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                                    <span class="badge bg-<?php echo $notif->type === 'success' ? 'success' : ($notif->type === 'danger' ? 'danger' : ($notif->type === 'warning' ? 'warning' : 'info')); ?>">
                                                        <?php echo escape($notif->title); ?>
                                                    </span>
                                                    <small class="text-muted" style="font-size: 0.7rem;"><?php echo format_date($notif->created_at, 'H:i Y/m/d'); ?></small>
                                                </div>
                                                <p class="mb-1 text-dark small" style="font-size: 0.85rem; line-height: 1.4;"><?php echo escape($notif->message); ?></p>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <?php if ($notif->link): ?>
                                                        <a href="<?php echo $notif->link; ?>" class="small text-primary text-decoration-none fw-bold" style="font-size: 0.78rem;">التفاصيل <i class="fas fa-arrow-left ms-1"></i></a>
                                                    <?php else: ?>
                                                        <span></span>
                                                    <?php endif; ?>
                                                    <?php if (!$notif->is_read): ?>
                                                        <form action="<?php echo url('/notifications/' . $notif->id . '/read'); ?>" method="POST" class="d-inline">
                                                            <button type="submit" class="btn btn-sm btn-link text-muted p-0 text-decoration-none" style="font-size: 0.75rem;"><i class="fas fa-check ms-1"></i>تم القراءة</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <button id="darkModeToggle" class="topbar-icon-btn" title="الوضع الليلي">
                            <i class="fas fa-moon topbar-moon"></i>
                            <i class="fas fa-sun topbar-sun"></i>
                        </button>

                        <!-- User Role Card -->
                        <?php
                            $tbRole = $_SESSION['user_role'] ?? 'student';
                            $tbName = $_SESSION['user_name'] ?? '';
                            $tbRoleMap = [
                                'admin'       => ['icon' => 'fa-user-shield',  'label' => 'مدير عام',        'cls' => 'topbar-role-admin'],
                                'major_admin' => ['icon' => 'fa-shield-alt',   'label' => 'مسؤول تخصص',     'cls' => 'topbar-role-major'],
                                'manager'     => ['icon' => 'fa-award',        'label' => 'مندوب مستوى',    'cls' => 'topbar-role-manager'],
                                'student'     => ['icon' => 'fa-user-graduate','label' => 'طالب',            'cls' => 'topbar-role-student'],
                            ];
                            $tbInfo = $tbRoleMap[$tbRole] ?? $tbRoleMap['student'];
                        ?>
                        <div class="topbar-user-card d-none d-md-flex">
                            <div class="topbar-user-avatar <?php echo $tbInfo['cls']; ?>">
                                <i class="fas <?php echo $tbInfo['icon']; ?>"></i>
                            </div>
                            <div class="topbar-user-info">
                                <div class="topbar-user-name"><?php echo escape($tbName); ?></div>
                                <div class="topbar-user-role"><?php echo $tbInfo['label']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="admin-content">
                <!-- Ambient Background Orbs -->
                <div class="admin-bg-orb orb-1"></div>
                <div class="admin-bg-orb orb-2"></div>

                <!-- Breadcrumb Bar -->
                <div class="admin-breadcrumb-bar">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="<?php echo url('/admin/dashboard'); ?>" class="bc-home">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?php
                                    $uri = $_SERVER['REQUEST_URI'] ?? '/admin';
                                    $path = parse_url($uri, PHP_URL_PATH);
                                    $segments = array_filter(explode('/', trim($path, '/')));
                                    $segments = array_values($segments);
                                    $labels = [
                                        'dashboard'     => ['icon' => 'fa-chart-pie',      'label' => 'لوحة التحكم'],
                                        'users'         => ['icon' => 'fa-users-cog',      'label' => 'المستخدمون والأدوار'],
                                        'courses'       => ['icon' => 'fa-book',           'label' => 'المواد الدراسية'],
                                        'files'         => ['icon' => 'fa-folder-open',    'label' => 'الملفات والمستندات'],
                                        'announcements' => ['icon' => 'fa-bullhorn',       'label' => 'الإعلانات'],
                                        'majors'        => ['icon' => 'fa-university',     'label' => 'التخصصات'],
                                        'levels'        => ['icon' => 'fa-layer-group',    'label' => 'المستويات'],
                                        'semesters'     => ['icon' => 'fa-calendar-alt',   'label' => 'الفصول الدراسية'],
                                        'managers'      => ['icon' => 'fa-user-shield',    'label' => 'المناديب'],
                                        'requests'      => ['icon' => 'fa-inbox',          'label' => 'الطلبات'],
                                        'settings'      => ['icon' => 'fa-sliders',        'label' => 'الإعدادات'],
                                        'create'        => ['icon' => 'fa-plus-circle',    'label' => 'إضافة جديد'],
                                        'edit'          => ['icon' => 'fa-edit',           'label' => 'تعديل'],
                                    ];
                                    $lastSegment = end($segments) ?: 'dashboard';
                                    $info = $labels[$lastSegment] ?? $labels[$segments[1] ?? 'dashboard'] ?? ['icon' => 'fa-circle', 'label' => $lastSegment];
                                    echo '<i class="fas ' . htmlspecialchars($info['icon']) . ' me-1"></i>' . htmlspecialchars($info['label']);
                                ?>
                            </li>
                        </ol>
                    </nav>
                    <div class="bc-time">
                        <i class="fas fa-clock me-1"></i>
                        <?php echo date('H:i  —  d/m/Y'); ?>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?php if (flash_has('success')): ?>
                    <div class="admin-flash admin-flash-success alert-dismissible fade show" role="alert">
                        <div class="flash-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="flash-text"><?php echo flash('success'); ?></div>
                        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if (flash_has('error')): ?>
                    <div class="admin-flash admin-flash-danger alert-dismissible fade show" role="alert">
                        <div class="flash-icon"><i class="fas fa-exclamation-circle"></i></div>
                        <div class="flash-text"><?php echo flash('error'); ?></div>
                        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Page Content Card -->
                <div class="admin-page-card">
                    <?php echo $content; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="<?php echo asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/vendor/chartjs/chart.umd.min.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/admin.js'); ?>"></script>
    <script>
    (function() {
        var saved = localStorage.getItem('darkMode');
        if (saved === 'true') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            document.addEventListener('DOMContentLoaded', function() {
                if (document.body) document.body.classList.add('dark-mode');
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('darkModeToggle');
            if (!btn) return;
            btn.addEventListener('click', function() {
                var html = document.documentElement;
                var body = document.body;
                var isDark = html.getAttribute('data-bs-theme') === 'dark' || (body && body.classList.contains('dark-mode'));
                if (isDark) {
                    html.removeAttribute('data-bs-theme');
                    if (body) body.classList.remove('dark-mode');
                    localStorage.setItem('darkMode', 'false');
                } else {
                    html.setAttribute('data-bs-theme', 'dark');
                    if (body) body.classList.add('dark-mode');
                    localStorage.setItem('darkMode', 'true');
                }
            });
        });
    })();
    </script>
    <script src="<?php echo asset('assets/vendor/instantpage/instantpage.min.js'); ?>" type="module"></script>
</body>
</html>
