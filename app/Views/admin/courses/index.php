<?php
$totalCourses  = count($courses ?? []);
$activeCount   = count(array_filter($courses ?? [], fn($c) => (bool)$c->is_active));
$inactiveCount = $totalCourses - $activeCount;
$totalFiles    = array_sum(array_map(fn($c) => (int)($c->files_count ?? 0), $courses ?? []));
$isAdmin       = (($_SESSION['user_role'] ?? '') === 'admin');
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="majors-stat-icon icon-primary" style="width: 52px; height: 52px; border-radius: 16px; font-size: 1.4rem;">
            <i class="fas fa-book-open"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--dark);">المواد والمقررات الدراسية</h4>
            <p class="text-muted small mb-0">إدارة المقررات وتوزيعها حسب التخصصات والمستويات والفصول الدراسية</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo url('/admin/courses/create'); ?>" class="majors-btn-add">
            <i class="fas fa-plus ms-1"></i>إضافة مادة جديدة
        </a>
    </div>
</div>

<!-- Quick Statistics Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-primary">
                <i class="fas fa-book-open"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $totalCourses; ?></div>
                <div class="majors-stat-lbl">إجمالي المواد</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $activeCount; ?></div>
                <div class="majors-stat-lbl">مواد نشطة</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-warning">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $inactiveCount; ?></div>
                <div class="majors-stat-lbl">مواد موقوفة</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-info">
                <i class="fas fa-paperclip"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $totalFiles; ?></div>
                <div class="majors-stat-lbl">إجمالي الملفات</div>
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
            <input type="text" id="courseSearchInput" class="majors-search-input" placeholder="ابحث باسم المادة، الرمز، أو التخصص..." autocomplete="off">
        </div>

        <!-- Filter Dropdowns & Buttons -->
        <div class="d-flex align-items-center gap-2 flex-wrap flex-grow-1 justify-content-end">
            
            <!-- Major Filter -->
            <?php if (!empty($majors)): ?>
            <select id="courseMajorFilter" class="form-select form-select-sm rounded-pill px-3 shadow-none border" style="width: auto; max-width: 170px;">
                <option value="">جميع التخصصات</option>
                <?php foreach ($majors as $m): ?>
                    <option value="<?php echo escape(mb_strtolower($m->name)); ?>">
                        <?php echo escape($m->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>

            <!-- Level Filter -->
            <?php if (!empty($levels)): ?>
            <select id="courseLevelFilter" class="form-select form-select-sm rounded-pill px-3 shadow-none border" style="width: auto; max-width: 150px;">
                <option value="">جميع المستويات</option>
                <?php foreach ($levels as $lvl): ?>
                    <option value="<?php echo escape(mb_strtolower($lvl->name)); ?>">
                        <?php echo escape($lvl->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>

            <!-- Status Filter Buttons -->
            <div class="majors-filter-group" id="courseFilterGroup">
                <button type="button" class="majors-filter-btn active" data-filter="all">الكل (<?php echo $totalCourses; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="active">نشطة (<?php echo $activeCount; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="inactive">موقوفة (<?php echo $inactiveCount; ?>)</button>
            </div>

            <!-- Counter Badge -->
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-bold" id="courseCounterBadge">
                عرض <?php echo $totalCourses; ?> من <?php echo $totalCourses; ?>
            </span>
        </div>

    </div>
</div>

<?php if (empty($courses)): ?>
    <!-- Database Empty State -->
    <div class="majors-empty-box">
        <div class="majors-empty-icon" style="background: rgba(79, 70, 229, 0.08); color: #4f46e5;">
            <i class="fas fa-book-open"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">لا توجد مواد دراسية مسجّلة حتى الآن</h5>
        <p class="text-muted small mb-4">ابدأ بإضافة أول مقرر دراسي لربطه بالتخصصات والمستويات والملفات التعليمية</p>
        <a href="<?php echo url('/admin/courses/create'); ?>" class="majors-btn-add">
            <i class="fas fa-plus ms-1"></i>إضافة مادة الآن
        </a>
    </div>
<?php else: ?>

    <!-- Courses Cards Grid -->
    <div class="row g-4" id="coursesGridContainer">
        <?php 
        $courseIdx = 0;
        foreach ($courses as $course):
            $courseIdx++;
            $isActive    = (bool)$course->is_active;
            $filesCount  = (int)($course->files_count ?? 0);
            $majorName   = $course->major_name ?? 'عام';
            $levelName   = $course->level_name ?? 'غير محدد';
            $semName     = $course->semester_name ?? 'غير محدد';
            $courseCode  = $course->code ?? '';
        ?>
        <div class="col-12 col-md-6 col-xl-4 course-item-col" 
             data-name="<?php echo escape(mb_strtolower($course->name)); ?>"
             data-code="<?php echo escape(mb_strtolower($courseCode)); ?>"
             data-major="<?php echo escape(mb_strtolower($majorName)); ?>"
             data-level="<?php echo escape(mb_strtolower($levelName)); ?>"
             data-semester="<?php echo escape(mb_strtolower($semName)); ?>"
             data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>">
            
            <div class="course-modern-card <?php echo $isActive ? '' : 'card-inactive'; ?>">
                
                <!-- Card Header -->
                <div class="major-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="course-icon-box" title="<?php echo escape($course->name); ?>">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="major-header-text">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <?php if (!empty($courseCode)): ?>
                                    <span class="course-code-tag"><?php echo escape($courseCode); ?></span>
                                <?php endif; ?>
                                <span class="major-id-badge" title="الترقيم التسلسلي">#<?php echo $courseIdx; ?></span>
                            </div>
                            <h5 class="major-card-title"><?php echo escape($course->name); ?></h5>
                        </div>
                    </div>
                    <div>
                        <span class="major-status-pill <?php echo $isActive ? 'status-pill-active' : 'status-pill-inactive'; ?>">
                            <?php if ($isActive): ?>
                                <span class="pulse-dot"></span> نشط
                            <?php else: ?>
                                <i class="fas fa-pause-circle me-1" style="font-size:0.7rem;"></i> موقوف
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <!-- Card Body Content -->
                <div class="major-card-body-content">
                    
                    <!-- Taxonomy Badges (Major, Level, Semester) -->
                    <div class="course-taxonomy-grid">
                        <span class="course-tax-badge tax-major" title="التخصص">
                            <i class="fas fa-university ms-1"></i><?php echo escape($majorName); ?>
                        </span>
                        <span class="course-tax-badge tax-level" title="المستوى الدراسي">
                            <i class="fas fa-layer-group ms-1"></i><?php echo escape($levelName); ?>
                        </span>
                        <span class="course-tax-badge tax-semester" title="الفصل الدراسي">
                            <i class="fas fa-calendar-alt ms-1"></i><?php echo escape($semName); ?>
                        </span>
                    </div>

                    <!-- Metrics / Info Strip -->
                    <div class="major-metrics-strip">
                        <a href="<?php echo url('/admin/files?course_id=' . escape($course->id)); ?>" 
                           class="major-metric-chip" 
                           title="عرض وتصفح ملفات المادة">
                            <i class="fas fa-paperclip"></i>
                            <span><?php echo $filesCount; ?> ملفات مرفوعة</span>
                        </a>
                        <a href="<?php echo url('/course/' . escape($course->id)); ?>" 
                           target="_blank"
                           class="major-dept-chip text-decoration-none" 
                           title="معاينة المادة في الواجهة الرئيسية">
                            <i class="fas fa-external-link-alt"></i> معاينة
                        </a>
                    </div>
                </div>

                <!-- Action Buttons Footer -->
                <div class="major-card-actions-bar">
                    <a href="<?php echo url('/admin/courses/' . escape($course->id) . '/edit'); ?>" 
                       class="major-act-btn btn-edit-major" 
                       title="تعديل بيانات المادة">
                        <i class="fas fa-pen ms-1"></i>تعديل
                    </a>

                    <a href="<?php echo url('/admin/files?course_id=' . escape($course->id)); ?>" 
                       class="major-act-btn btn-icon-major" 
                       title="إدارة ملفات المادة">
                        <i class="fas fa-folder-open"></i>
                    </a>

                    <a href="<?php echo url('/admin/courses/' . escape($course->id) . '/delete?_csrf_token=' . csrf_token()); ?>" 
                       class="major-act-btn btn-icon-major btn-delete-major" 
                       title="حذف المادة"
                       onclick="return confirm('هل أنت متأكد من حذف مادة (<?php echo escape($course->name); ?>)؟')">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Search No Results State (Hidden by default) -->
    <div id="coursesNoResultsBox" class="majors-empty-box d-none my-4">
        <div class="majors-empty-icon">
            <i class="fas fa-search-minus"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">لم يتم العثور على أي مواد مطابقة</h5>
        <p class="text-muted small mb-3">جرّب البحث باسم أو تخصص أو مستوى آخر، أو قم بإلغاء الفلاتر المحددة</p>
        <button type="button" id="resetCourseFiltersBtn" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-redo-alt ms-1"></i>إعادة ضبط البحث والفلاتر
        </button>
    </div>

<?php endif; ?>

<!-- Real-time Live Filter & Instant Multi-Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput   = document.getElementById('courseSearchInput');
    var majorFilter   = document.getElementById('courseMajorFilter');
    var levelFilter   = document.getElementById('courseLevelFilter');
    var filterBtns    = document.querySelectorAll('#courseFilterGroup .majors-filter-btn');
    var cardCols      = document.querySelectorAll('.course-item-col');
    var counterBadge  = document.getElementById('courseCounterBadge');
    var noResultsBox  = document.getElementById('coursesNoResultsBox');
    var resetBtn      = document.getElementById('resetCourseFiltersBtn');
    
    var currentStatus = 'all';
    var totalCards    = cardCols.length;

    function applyFilters() {
        var query       = (searchInput ? searchInput.value : '').trim().toLowerCase();
        var selMajor    = (majorFilter ? majorFilter.value : '').trim().toLowerCase();
        var selLevel    = (levelFilter ? levelFilter.value : '').trim().toLowerCase();
        var visibleCount = 0;

        cardCols.forEach(function(col) {
            var name     = col.getAttribute('data-name') || '';
            var code     = col.getAttribute('data-code') || '';
            var major    = col.getAttribute('data-major') || '';
            var level    = col.getAttribute('data-level') || '';
            var semester = col.getAttribute('data-semester') || '';
            var status   = col.getAttribute('data-status') || '';

            var matchesSearch = !query || 
                                name.indexOf(query) !== -1 || 
                                code.indexOf(query) !== -1 || 
                                major.indexOf(query) !== -1 || 
                                level.indexOf(query) !== -1 || 
                                semester.indexOf(query) !== -1;

            var matchesMajor  = !selMajor || major.indexOf(selMajor) !== -1;
            var matchesLevel  = !selLevel || level.indexOf(selLevel) !== -1;
            var matchesStatus = (currentStatus === 'all') || (status === currentStatus);

            if (matchesSearch && matchesMajor && matchesLevel && matchesStatus) {
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
    if (levelFilter)  levelFilter.addEventListener('change', applyFilters);

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
            if (searchInput) searchInput.value = '';
            if (majorFilter) majorFilter.value = '';
            if (levelFilter) levelFilter.value = '';
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
