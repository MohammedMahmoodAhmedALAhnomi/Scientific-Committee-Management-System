<?php
$totalFiles    = count($files ?? []);
$approvedCount = count(array_filter($files ?? [], fn($f) => (bool)$f->is_approved));
$pendingCount  = $totalFiles - $approvedCount;
$totalDownloads= array_sum(array_map(fn($f) => (int)($f->download_count ?? 0), $files ?? []));
$isAdmin       = (($_SESSION['user_role'] ?? '') === 'admin');

// File types definition
$typeLabels = [
    'lecture'    => 'محاضرة', 
    'summary'    => 'ملخص', 
    'assignment' => 'تكليف', 
    'model'      => 'نماذج أسئلة', 
    'reference'  => 'مرجع'
];
$typeIcons = [
    'lecture'    => 'fas fa-chalkboard-teacher', 
    'summary'    => 'fas fa-file-alt', 
    'assignment' => 'fas fa-clipboard-list', 
    'model'      => 'fas fa-tasks', 
    'reference'  => 'fas fa-atlas'
];
$typeColors = [
    'lecture'    => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25',
    'summary'    => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
    'assignment' => 'bg-warning bg-opacity-15 text-dark border border-warning border-opacity-25',
    'model'      => 'bg-info bg-opacity-10 text-info border border-info border-opacity-25',
    'reference'  => 'bg-purple bg-opacity-10 text-purple border border-purple border-opacity-25'
];
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="majors-stat-icon icon-info" style="width: 52px; height: 52px; border-radius: 16px; font-size: 1.4rem;">
            <i class="fas fa-file-alt"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--dark);">إدارة الملفات والمستندات</h4>
            <p class="text-muted small mb-0">إدارة واعتماد وتصنيف المستندات والملازم والمحاضرات التعليمية</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo url('/admin/files/create'); ?>" class="majors-btn-add">
            <i class="fas fa-plus ms-1"></i>إضافة ملف جديد
        </a>
    </div>
</div>

<!-- Quick Statistics Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-info">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $totalFiles; ?></div>
                <div class="majors-stat-lbl">إجمالي الملفات</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $approvedCount; ?></div>
                <div class="majors-stat-lbl">ملفات معتمدة</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-warning">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $pendingCount; ?></div>
                <div class="majors-stat-lbl">بانتظار الاعتماد</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-primary">
                <i class="fas fa-download"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $totalDownloads; ?></div>
                <div class="majors-stat-lbl">إجمالي التحميلات</div>
            </div>
        </div>
    </div>
</div>

<!-- Controls & Search Toolbar -->
<div class="majors-toolbar mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        
        <!-- Live Search -->
        <div class="majors-search-box flex-grow-1" style="max-width: 320px;">
            <i class="fas fa-search"></i>
            <input type="text" id="fileSearchInput" class="majors-search-input" placeholder="ابحث باسم الملف، المادة، الدكتور، أو الرافع..." autocomplete="off">
        </div>

        <!-- Filter Dropdowns & Buttons -->
        <div class="d-flex align-items-center gap-2 flex-wrap flex-grow-1 justify-content-end">
            
            <!-- Major Filter -->
            <?php if (!empty($majors)): ?>
            <select id="fileMajorFilter" class="form-select form-select-sm rounded-pill px-3 shadow-none border" style="width: auto; max-width: 160px;">
                <option value="">جميع التخصصات</option>
                <?php foreach ($majors as $m): ?>
                    <option value="<?php echo escape(mb_strtolower($m->name)); ?>">
                        <?php echo escape($m->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>

            <!-- Course Filter -->
            <?php if (!empty($courses)): ?>
            <select id="fileCourseFilter" class="form-select form-select-sm rounded-pill px-3 shadow-none border" style="width: auto; max-width: 170px;">
                <option value="">جميع المواد</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?php echo escape(mb_strtolower($c->name)); ?>" <?php echo (isset($_GET['course_id']) && $_GET['course_id'] == $c->id) ? 'selected' : ''; ?>>
                        <?php echo escape($c->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>

            <!-- Type Filter -->
            <select id="fileTypeFilter" class="form-select form-select-sm rounded-pill px-3 shadow-none border" style="width: auto; max-width: 150px;">
                <option value="">جميع التصنيفات</option>
                <option value="lecture">محاضرة</option>
                <option value="summary">ملخص</option>
                <option value="assignment">تكليف</option>
                <option value="model">نماذج أسئلة</option>
                <option value="reference">مرجع</option>
            </select>

            <!-- Status Filter Buttons -->
            <div class="majors-filter-group" id="fileFilterGroup">
                <button type="button" class="majors-filter-btn active" data-filter="all">الكل (<?php echo $totalFiles; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="approved">معتمدة (<?php echo $approvedCount; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="pending">انتظار (<?php echo $pendingCount; ?>)</button>
            </div>

            <!-- Counter Badge -->
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-bold" id="fileCounterBadge">
                عرض <?php echo $totalFiles; ?> من <?php echo $totalFiles; ?>
            </span>
        </div>

    </div>
</div>

<?php if (empty($files)): ?>
    <!-- Database Empty State -->
    <div class="majors-empty-box">
        <div class="majors-empty-icon" style="background: rgba(14, 165, 233, 0.08); color: #0284c7;">
            <i class="fas fa-file-upload"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">لا توجد ملفات مرفوعة حتى الآن</h5>
        <p class="text-muted small mb-4">ابدأ برفع أول مستند تعليمي لتوفير المحتوى والملازم الدراسية للطلاب</p>
        <a href="<?php echo url('/admin/files/create'); ?>" class="majors-btn-add">
            <i class="fas fa-plus ms-1"></i>رفع ملف الآن
        </a>
    </div>
<?php else: ?>

    <!-- Files Cards Grid -->
    <div class="row g-4" id="filesGridContainer">
        <?php 
        $fileIdx = 0;
        foreach ($files as $file):
            $fileIdx++;
            $isApproved = (bool)$file->is_approved;
            $fExt       = strtolower(pathinfo($file->file_path ?? '', PATHINFO_EXTENSION) ?: ($file->file_extension ?? 'file'));
            $fType      = $file->file_type ?? 'lecture';
            $fSize      = $file->file_size ?? '';
            $dCount     = (int)($file->download_count ?? 0);
            $courseName = $file->course_name ?? 'عام';
            $majorName  = $file->major_name ?? '';
            $docName    = $file->doctor_name ?? '';
            $uploader   = $file->uploader_name ?? 'الربط المباشر';
            $createdDate= $file->created_at ? format_date($file->created_at, 'Y-m-d') : '';

            // Determine extension CSS class & icon
            $extIconClass = match($fExt) {
                'pdf'           => ['cls' => 'file-ext-pdf', 'icon' => 'fas fa-file-pdf'],
                'doc', 'docx'   => ['cls' => 'file-ext-word', 'icon' => 'fas fa-file-word'],
                'xls', 'xlsx'   => ['cls' => 'file-ext-excel', 'icon' => 'fas fa-file-excel'],
                'ppt', 'pptx'   => ['cls' => 'file-ext-powerpoint', 'icon' => 'fas fa-file-powerpoint'],
                'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg' => ['cls' => 'file-ext-image', 'icon' => 'fas fa-file-image'],
                'zip', 'rar', '7z' => ['cls' => 'file-ext-zip', 'icon' => 'fas fa-file-archive'],
                'text', 'txt'   => ['cls' => 'file-ext-text', 'icon' => 'fas fa-align-right'],
                default         => ['cls' => 'file-ext-default', 'icon' => 'fas fa-file-alt']
            };

            $typeLabel = $typeLabels[$fType] ?? 'محاضرة';
            $typeIcon  = $typeIcons[$fType] ?? 'fas fa-file-alt';
            $typeColor = $typeColors[$fType] ?? 'bg-primary bg-opacity-10 text-primary';
        ?>
        <div class="col-12 col-md-6 col-xl-4 file-item-col" 
             data-name="<?php echo escape(mb_strtolower($file->title)); ?>"
             data-course="<?php echo escape(mb_strtolower($courseName)); ?>"
             data-major="<?php echo escape(mb_strtolower($majorName)); ?>"
             data-doctor="<?php echo escape(mb_strtolower($docName)); ?>"
             data-uploader="<?php echo escape(mb_strtolower($uploader)); ?>"
             data-type="<?php echo escape($fType); ?>"
             data-ext="<?php echo escape($fExt); ?>"
             data-status="<?php echo $isApproved ? 'approved' : 'pending'; ?>">
            
            <div class="file-modern-card <?php echo $isApproved ? '' : 'card-pending'; ?>">
                
                <!-- Card Header -->
                <div class="major-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="file-ext-icon-box <?php echo $extIconClass['cls']; ?>" title="<?php echo strtoupper($fExt); ?>">
                            <i class="<?php echo $extIconClass['icon']; ?>"></i>
                        </div>
                        <div class="major-header-text">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="course-code-tag"><?php echo strtoupper($fExt); ?></span>
                                <span class="major-id-badge" title="الترقيم التسلسلي">#<?php echo $fileIdx; ?></span>
                            </div>
                            <h5 class="major-card-title text-truncate" style="max-width: 190px;" title="<?php echo escape($file->title); ?>">
                                <?php echo escape($file->title); ?>
                            </h5>
                        </div>
                    </div>
                    <div>
                        <span class="major-status-pill <?php echo $isApproved ? 'status-pill-active' : 'status-pill-inactive'; ?>">
                            <?php if ($isApproved): ?>
                                <span class="pulse-dot"></span> معتمد
                            <?php else: ?>
                                <i class="fas fa-hourglass-half me-1" style="font-size:0.7rem;"></i> انتظار
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <!-- Card Body Content -->
                <div class="major-card-body-content">
                    
                    <!-- Course & Category Strip -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <span class="fw-bold text-dark small" title="المادة الدراسية">
                            <i class="fas fa-book-open text-primary ms-1"></i><?php echo escape($courseName); ?>
                        </span>
                        <span class="badge rounded-pill px-3 py-1 <?php echo $typeColor; ?>" style="font-size: 0.72rem;">
                            <i class="<?php echo $typeIcon; ?> ms-1"></i><?php echo escape($typeLabel); ?>
                        </span>
                    </div>

                    <!-- Meta details: Doctor, Uploader, Date, Size -->
                    <div class="file-meta-row mt-2">
                        <?php if (!empty($docName)): ?>
                            <div class="file-meta-item" title="مدرس المادة">
                                <i class="fas fa-user-md text-muted"></i>
                                <span><?php echo escape($docName); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="file-meta-item" title="رافع الملف">
                            <i class="fas fa-user-circle text-muted"></i>
                            <span><?php echo escape($uploader); ?></span>
                        </div>

                        <?php if (!empty($fSize)): ?>
                            <div class="file-meta-item" title="حجم الملف">
                                <i class="fas fa-hdd text-muted"></i>
                                <span><?php echo escape($fSize); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($createdDate)): ?>
                            <div class="file-meta-item" title="تاريخ الرفع">
                                <i class="fas fa-calendar-alt text-muted"></i>
                                <span><?php echo escape($createdDate); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Metrics / Downloads & Preview Strip -->
                    <div class="major-metrics-strip mt-2">
                        <span class="major-metric-chip">
                            <i class="fas fa-download"></i>
                            <span><?php echo $dCount; ?> تحميلة</span>
                        </span>
                        
                        <?php if (!empty($file->file_path)): ?>
                        <a href="<?php echo url('/download/file/' . escape($file->id)); ?>" 
                           class="major-dept-chip text-decoration-none" 
                           title="تنزيل الملف مباشرة">
                            <i class="fas fa-cloud-download-alt"></i> تنزيل
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action Buttons Footer -->
                <div class="major-card-actions-bar">
                    <a href="<?php echo url('/admin/files/' . escape($file->id) . '/edit'); ?>" 
                       class="major-act-btn btn-edit-major" 
                       title="تعديل بيانات الملف">
                        <i class="fas fa-pen ms-1"></i>تعديل
                    </a>

                    <?php if (!empty($file->file_path)): ?>
                    <a href="<?php echo url('/download/file/' . escape($file->id)); ?>" 
                       class="major-act-btn btn-icon-major" 
                       title="تحميل الملف">
                        <i class="fas fa-arrow-down"></i>
                    </a>
                    <?php endif; ?>

                    <a href="<?php echo url('/admin/files/' . escape($file->id) . '/delete?_csrf_token=' . csrf_token()); ?>" 
                       class="major-act-btn btn-icon-major btn-delete-major" 
                       title="حذف الملف"
                       onclick="return confirm('هل أنت متأكد من حذف ملف (<?php echo escape($file->title); ?>)؟')">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Search No Results State (Hidden by default) -->
    <div id="filesNoResultsBox" class="majors-empty-box d-none my-4">
        <div class="majors-empty-icon">
            <i class="fas fa-search-minus"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">لم يتم العثور على أي ملفات مطابقة</h5>
        <p class="text-muted small mb-3">جرّب البحث باسم ملف، مادة، أو تصنيف آخر، أو قم بإعادة ضبط الفلاتر المحددة</p>
        <button type="button" id="resetFileFiltersBtn" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-redo-alt ms-1"></i>إعادة ضبط البحث والفلاتر
        </button>
    </div>

<?php endif; ?>

<!-- Real-time Live Filter & Instant Multi-Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput  = document.getElementById('fileSearchInput');
    var majorFilter  = document.getElementById('fileMajorFilter');
    var courseFilter = document.getElementById('fileCourseFilter');
    var typeFilter   = document.getElementById('fileTypeFilter');
    var filterBtns   = document.querySelectorAll('#fileFilterGroup .majors-filter-btn');
    var cardCols     = document.querySelectorAll('.file-item-col');
    var counterBadge = document.getElementById('fileCounterBadge');
    var noResultsBox = document.getElementById('filesNoResultsBox');
    var resetBtn     = document.getElementById('resetFileFiltersBtn');
    
    var currentStatus = 'all';
    var totalCards    = cardCols.length;

    function applyFilters() {
        var query       = (searchInput ? searchInput.value : '').trim().toLowerCase();
        var selMajor    = (majorFilter ? majorFilter.value : '').trim().toLowerCase();
        var selCourse   = (courseFilter ? courseFilter.value : '').trim().toLowerCase();
        var selType     = (typeFilter ? typeFilter.value : '').trim().toLowerCase();
        var visibleCount = 0;

        cardCols.forEach(function(col) {
            var name     = col.getAttribute('data-name') || '';
            var course   = col.getAttribute('data-course') || '';
            var major    = col.getAttribute('data-major') || '';
            var doctor   = col.getAttribute('data-doctor') || '';
            var uploader = col.getAttribute('data-uploader') || '';
            var fType    = col.getAttribute('data-type') || '';
            var fExt     = col.getAttribute('data-ext') || '';
            var status   = col.getAttribute('data-status') || '';

            var matchesSearch = !query || 
                                name.indexOf(query) !== -1 || 
                                course.indexOf(query) !== -1 || 
                                major.indexOf(query) !== -1 || 
                                doctor.indexOf(query) !== -1 || 
                                uploader.indexOf(query) !== -1 ||
                                fExt.indexOf(query) !== -1;

            var matchesMajor  = !selMajor || major.indexOf(selMajor) !== -1;
            var matchesCourse = !selCourse || course.indexOf(selCourse) !== -1;
            var matchesType   = !selType || fType === selType;
            var matchesStatus = (currentStatus === 'all') || (status === currentStatus);

            if (matchesSearch && matchesMajor && matchesCourse && matchesType && matchesStatus) {
                col.style.display = '';
                visibleCount++;
            } else {
                col.style.display = 'none';
            }
        });

        if (counterBadge) {
            counterBadge.textContent = 'عرض ' + visibleCount + ' من ' + totalCards;
        }

        if (noResultsBox) {
            if (visibleCount === 0 && totalCards > 0) {
                noResultsBox.classList.remove('d-none');
            } else {
                noResultsBox.classList.add('d-none');
            }
        }
    }

    if (searchInput)  searchInput.addEventListener('input', applyFilters);
    if (majorFilter)  majorFilter.addEventListener('change', applyFilters);
    if (courseFilter) courseFilter.addEventListener('change', applyFilters);
    if (typeFilter)   typeFilter.addEventListener('change', applyFilters);

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            currentStatus = btn.getAttribute('data-filter') || 'all';
            applyFilters();
        });
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput)  searchInput.value = '';
            if (majorFilter)  majorFilter.value = '';
            if (courseFilter) courseFilter.value = '';
            if (typeFilter)   typeFilter.value = '';
            currentStatus = 'all';
            filterBtns.forEach(function(b) {
                if (b.getAttribute('data-filter') === 'all') b.classList.add('active');
                else b.classList.remove('active');
            });
            applyFilters();
        });
    }
});
</script>