<!-- Dashboard Header -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="majors-stat-icon icon-primary" style="width: 52px; height: 52px; border-radius: 16px; font-size: 1.4rem;">
            <i class="fas fa-tachometer-alt"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--dark);">لوحة التحكم الرئيسية</h4>
            <p class="text-muted small mb-0"><?php echo date('l، d F Y'); ?> &bull; مرحباً بك في بوابة إدارة المنصة الأكاديمية</p>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap align-items-center">
        <a href="<?php echo url('/admin/courses/create'); ?>" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-bold">
            <i class="fas fa-plus ms-1"></i>إضافة مادة
        </a>
        <a href="<?php echo url('/admin/files/create'); ?>" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm fw-bold">
            <i class="fas fa-upload ms-1"></i>رفع ملف
        </a>
        <a href="<?php echo url('/admin/announcements/create'); ?>" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm fw-bold">
            <i class="fas fa-bullhorn ms-1"></i>إعلان جديد
        </a>
        <a href="<?php echo url('/'); ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3" target="_blank" title="معاينة الواجهة">
            <i class="fas fa-external-link-alt ms-1"></i>الموقع
        </a>
    </div>
</div>

<!-- 4 Master Unified Stat Cards -->
<div class="row g-3 mb-4">
    <!-- Card 1: Users -->
    <div class="col-6 col-lg-3">
        <a href="<?php echo url('/admin/users'); ?>" class="text-decoration-none">
            <div class="majors-stat-card">
                <div class="majors-stat-icon icon-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="majors-stat-val"><?php echo escape($usersCount ?? 0); ?></div>
                    <div class="majors-stat-lbl">مستخدمي النظام</div>
                    <small class="text-success fw-bold d-block mt-1" style="font-size: 0.73rem;">
                        <i class="fas fa-user-check me-1"></i><?php echo escape($activeUsersCount ?? $usersCount); ?> حساب نشط
                    </small>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 2: Downloads -->
    <div class="col-6 col-lg-3">
        <a href="<?php echo url('/admin/files'); ?>" class="text-decoration-none">
            <div class="majors-stat-card">
                <div class="majors-stat-icon icon-info">
                    <i class="fas fa-cloud-download-alt"></i>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="majors-stat-val"><?php echo escape($totalDownloads ?? 0); ?></div>
                    <div class="majors-stat-lbl">عمليات التنزيل</div>
                    <small class="text-info fw-bold d-block mt-1" style="font-size: 0.73rem;">
                        <i class="fas fa-chart-line me-1"></i>تحميل واستفادة علمية
                    </small>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 3: Files -->
    <div class="col-6 col-lg-3">
        <a href="<?php echo url('/admin/files'); ?>" class="text-decoration-none">
            <div class="majors-stat-card">
                <div class="majors-stat-icon icon-success">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="majors-stat-val"><?php echo escape($filesCount ?? 0); ?></div>
                    <div class="majors-stat-lbl">الملفات المرفوعة</div>
                    <small class="text-muted fw-bold d-block mt-1" style="font-size: 0.73rem;">
                        <i class="fas fa-hdd me-1"></i>الحجم: <?php echo escape($totalFileSize ?? '0 B'); ?>
                    </small>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 4: Courses & Majors -->
    <div class="col-6 col-lg-3">
        <a href="<?php echo url('/admin/courses'); ?>" class="text-decoration-none">
            <div class="majors-stat-card">
                <div class="majors-stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #7c3aed;">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="majors-stat-val"><?php echo escape($coursesCount ?? 0); ?></div>
                    <div class="majors-stat-lbl">المواد والتخصصات</div>
                    <small class="fw-bold d-block mt-1" style="font-size: 0.73rem; color: #7c3aed !important;">
                        <i class="fas fa-university me-1"></i>في <?php echo escape($majorsCount ?? 0); ?> تخصصات معتمدة
                    </small>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Academic Navigation Quick Scope Strip -->
<div class="row g-2 mb-4">
    <div class="col-4 col-md-2">
        <a href="<?php echo (($_SESSION['user_role'] ?? '') === 'admin') ? url('/admin/majors') : '#'; ?>" class="text-decoration-none">
            <div class="p-2 rounded-3 text-center border" class="dash-quick-card">
                <div class="fw-bold text-primary" style="font-size: 1.15rem;"><?php echo escape($majorsCount ?? 0); ?></div>
                <small class="text-muted" style="font-size: 0.74rem;"><i class="fas fa-university me-1"></i>تخصصات</small>
            </div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="<?php echo in_array($_SESSION['user_role'] ?? '', ['admin', 'major_admin'], true) ? url('/admin/levels') : '#'; ?>" class="text-decoration-none">
            <div class="p-2 rounded-3 text-center border" class="dash-quick-card">
                <div class="fw-bold text-info" style="font-size: 1.15rem;"><?php echo escape($levelsCount ?? 0); ?></div>
                <small class="text-muted" style="font-size: 0.74rem;"><i class="fas fa-layer-group me-1"></i>مستويات</small>
            </div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="<?php echo url('/admin/semesters'); ?>" class="text-decoration-none">
            <div class="p-2 rounded-3 text-center border" class="dash-quick-card">
                <div class="fw-bold" style="font-size: 1.15rem; color: #7c3aed;"><?php echo escape($semestersCount ?? 0); ?></div>
                <small class="text-muted" style="font-size: 0.74rem;"><i class="fas fa-calendar-alt me-1"></i>فصول</small>
            </div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="<?php echo url('/admin/courses'); ?>" class="text-decoration-none">
            <div class="p-2 rounded-3 text-center border" class="dash-quick-card">
                <div class="fw-bold text-success" style="font-size: 1.15rem;"><?php echo escape($coursesCount ?? 0); ?></div>
                <small class="text-muted" style="font-size: 0.74rem;"><i class="fas fa-book me-1"></i>مواد</small>
            </div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="<?php echo url('/admin/files'); ?>" class="text-decoration-none">
            <div class="p-2 rounded-3 text-center border" class="dash-quick-card">
                <div class="fw-bold text-primary" style="font-size: 1.15rem;"><?php echo escape($filesCount ?? 0); ?></div>
                <small class="text-muted" style="font-size: 0.74rem;"><i class="fas fa-file-alt me-1"></i>ملفات</small>
            </div>
        </a>
    </div>
    <div class="col-4 col-md-2">
        <a href="<?php echo url('/admin/announcements'); ?>" class="text-decoration-none">
            <div class="p-2 rounded-3 text-center border" class="dash-quick-card">
                <div class="fw-bold text-warning" style="font-size: 1.15rem;"><?php echo escape($announcementsCount ?? 0); ?></div>
                <small class="text-muted" style="font-size: 0.74rem;"><i class="fas fa-bullhorn me-1"></i>إعلانات</small>
            </div>
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-chart-bar text-primary ms-1"></i>المواد حسب التخصص</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">التخصص</th>
                                <th>عدد المواد</th>
                                <th class="pe-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($courseCountsByMajor)): ?>
                                <tr><td colspan="3" class="text-center text-muted py-3">لا توجد مواد</td></tr>
                            <?php else: ?>
                                <?php
                                $maxCount = 0;
                                foreach ($courseCountsByMajor as $m) { if ($m->total > $maxCount) $maxCount = $m->total; }
                                foreach ($courseCountsByMajor as $m):
                                ?>
                                <tr>
                                    <td class="ps-3 fw-bold"><?php echo escape($m->name); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 8px; max-width: 200px;">
                                                <div class="progress-bar rounded-pill bg-primary" style="width: <?php echo $maxCount > 0 ? round(($m->total / $maxCount) * 100) : 0; ?>%"></div>
                                            </div>
                                            <span class="badge bg-primary rounded-pill"><?php echo escape($m->total); ?></span>
                                        </div>
                                    </td>
                                    <td class="pe-3">
                                        <a href="<?php echo url('/admin/courses?major_id=' . $m->id); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">عرض المواد</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($newFilesCount > 0): ?>
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-3 py-3 mb-4">
        <div class="rounded-circle bg-info bg-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
            <i class="fas fa-bell text-info"></i>
        </div>
        <div class="flex-grow-1">
            <strong>إشعار:</strong> تم إضافة <strong><?php echo $newFilesCount; ?></strong> ملف جديد خلال الـ 48 ساعة الماضية.
            <a href="<?php echo url('/admin/files'); ?>" class="btn btn-sm btn-info rounded-pill px-3 ms-2">عرض الملفات</a>
        </div>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-file text-primary ms-1"></i>أحدث الملفات</h6>
                <a href="<?php echo url('/admin/files'); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">عرض الكل</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">العنوان</th>
                                <th>المادة</th>
                                <th>الدكتور</th>
                                <th>النوع</th>
                                <th class="pe-3">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentFiles)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">لا توجد ملفات حديثة</td></tr>
                            <?php else: ?>
                                <?php foreach ($recentFiles as $file): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-alt text-primary ms-2"></i>
                                                <span><?php echo escape($file->title); ?></span>
                                            </div>
                                        </td>
                                        <td><small class="text-muted"><?php echo escape($file->course_name ?? '-'); ?></small></td>
                                        <td><small class="text-muted"><?php echo escape($file->doctor_name ?? '-'); ?></small></td>
                                        <td>
                                            <span class="badge rounded-pill px-3 py-1 <?php
                                                $t = $file->file_type ?? '';
                                                $lbls = ['lecture' => 'محاضرة', 'summary' => 'ملخص', 'assignment' => 'تكليف', 'model' => 'نماذج', 'reference' => 'مرجع'];
                                                $cls = match($t) {
                                                    'summary' => 'bg-success text-white',
                                                    'assignment' => 'bg-warning text-dark border border-warning fw-bold',
                                                    'model' => 'bg-info text-dark',
                                                    'reference' => 'bg-secondary text-white',
                                                    default => 'bg-primary text-white',
                                                };
                                                echo $cls;
                                            ?>"><?php echo escape($lbls[$t] ?? $t); ?></span>
                                        </td>
                                        <td class="pe-3"><small class="text-muted"><?php echo escape(format_date($file->created_at ?? '', 'Y-m-d')); ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-history text-info ms-1"></i>آخر النشاطات</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentLogs)): ?>
                    <div class="text-center text-muted py-4">لا توجد نشاطات حديثة</div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentLogs as $log): ?>
                            <div class="list-group-item border-0 border-bottom py-3 px-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center ms-3 flex-shrink-0" style="width: 36px; height: 36px;">
                                        <i class="fas fa-circle text-<?php echo $log->action === 'login' ? 'success' : ($log->action === 'logout' ? 'secondary' : 'primary'); ?> small"></i>
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <small class="fw-bold d-block text-truncate"><?php echo escape($log->action ?? ''); ?></small>
                                        <small class="text-muted">
                                            <?php echo escape($log->user_name ?? 'نظام'); ?>
                                            &middot; <?php echo escape(format_date($log->created_at ?? '', 'Y-m-d H:i')); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-chart-pie text-primary ms-1"></i>توزيع الملفات</h6>
            </div>
            <div class="card-body">
                <?php if (empty($fileTypes)): ?>
                    <p class="text-muted text-center py-3 mb-0">لا توجد ملفات</p>
                <?php else: ?>
                    <?php foreach ($fileTypes as $ft): ?>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas <?php echo escape($ft['icon']); ?> ms-2" style="color: <?php echo escape($ft['color']); ?>"></i>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span><?php echo escape($ft['label']); ?></span>
                                    <span class="fw-bold"><?php echo escape($ft['count']); ?></span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar rounded-pill" style="width: <?php echo max(1, min(100, ($filesCount > 0 ? ($ft['count'] / $filesCount) * 100 : 0))); ?>%; background: <?php echo escape($ft['color']); ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-users text-warning ms-1"></i>المسؤولين</h6>
            </div>
            <div class="card-body">
                <?php if (empty($userRoles)): ?>
                    <p class="text-muted text-center py-3 mb-0">لا يوجد مستخدمين</p>
                <?php else: ?>
                    <?php foreach ($userRoles as $ur): ?>
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-light">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle ms-2 text-<?php echo $ur->role === 'admin' ? 'danger' : ($ur->role === 'manager' ? 'warning' : 'info'); ?>"></i>
                                <span><?php echo $ur->role === 'admin' ? 'مدير' : ($ur->role === 'manager' ? 'مندوب' : 'زائر'); ?></span>
                            </div>
                            <span class="badge bg-<?php echo $ur->role === 'admin' ? 'danger' : ($ur->role === 'manager' ? 'warning' : 'info'); ?> bg-opacity-10 text-<?php echo $ur->role === 'admin' ? 'danger' : ($ur->role === 'manager' ? 'warning' : 'info'); ?> rounded-pill px-3"><?php echo escape($ur->total); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-database text-success ms-1"></i>سعة التخزين</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                <div class="position-relative mb-3" style="width: 120px; height: 120px;">
                    <canvas id="storageChart" width="120" height="120"></canvas>
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <h4 class="fw-bold mb-0"><?php echo (!empty($storageLimit) && $storageLimit > 0 && $totalSizeBytes > 0) ? round(min(100, ($totalSizeBytes / $storageLimit) * 100)) : 0; ?>%</h4>
                    </div>
                </div>
                <h6 class="fw-bold mb-1"><?php echo escape($totalFileSize ?? '0 B'); ?></h6>
                <small class="text-muted">من <?php echo (!empty($storageLimit) && $storageLimit >= 1073741824) ? round($storageLimit / 1073741824) . ' GB' : ((!empty($storageLimit) && $storageLimit >= 1048576) ? round($storageLimit / 1048576) . ' MB' : '500 MB'); ?></small>
                <div class="progress w-100 mt-2" style="height: 4px;">
                    <div class="progress-bar rounded-pill <?php echo (!empty($storageLimit) && $storageLimit > 0 && ($totalSizeBytes / $storageLimit) > 0.8) ? 'bg-danger' : ((!empty($storageLimit) && $storageLimit > 0 && ($totalSizeBytes / $storageLimit) > 0.5) ? 'bg-warning' : 'bg-success'); ?>" style="width: <?php echo (!empty($storageLimit) && $storageLimit > 0) ? round(min(100, ($totalSizeBytes / $storageLimit) * 100)) : 0; ?>%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-chart-bar text-success ms-1"></i>أكثر المواد تحميلاً</h6>
            </div>
            <div class="card-body">
                <canvas id="downloadsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var c = document.getElementById('storageChart');
    if (!c) return;
    var ctx = c.getContext('2d');
    var pct = <?php echo (!empty($storageLimit) && $storageLimit > 0 && $totalSizeBytes > 0) ? round(min(100, ($totalSizeBytes / $storageLimit) * 100)) : 0; ?> / 100;
    var cx = 60, cy = 60, r = 50;
    ctx.clearRect(0, 0, 120, 120);
    ctx.beginPath();
    ctx.arc(cx, cy, r, 0, Math.PI * 2);
    ctx.strokeStyle = '#e5e7eb';
    ctx.lineWidth = 8;
    ctx.stroke();
    ctx.beginPath();
    ctx.arc(cx, cy, r, -Math.PI / 2, -Math.PI / 2 + Math.PI * 2 * pct);
    ctx.strokeStyle = pct > 0.8 ? '#dc3545' : (pct > 0.5 ? '#f59e0b' : '#10b981');
    ctx.lineWidth = 8;
    ctx.stroke();
})();

document.addEventListener('DOMContentLoaded', function() {
    var el = document.getElementById('downloadsChart');
    if (!el || typeof Chart === 'undefined') return;
    new Chart(el, {
        type: 'bar',
        data: {
            labels: [<?php foreach ($downloadStats as $d): ?>'<?php echo escape(addslashes($d->name)); ?>',<?php endforeach; ?>],
            datasets: [{
                label: 'عدد التحميلات',
                data: [<?php foreach ($downloadStats as $d): ?><?php echo (int)$d->total_downloads; ?>,<?php endforeach; ?>],
                backgroundColor: 'rgba(20, 184, 166, 0.7)',
                borderColor: '#14b8a6',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { stepSize: 1 } },
                y: { ticks: { font: { size: 11 } } }
            }
        }
    });
});
</script>
