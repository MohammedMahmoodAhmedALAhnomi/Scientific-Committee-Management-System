<?php
$totalSemesters = count($semesters ?? []);
$activeCount    = count(array_filter($semesters ?? [], fn($s) => (bool)$s->is_active));
$inactiveCount  = $totalSemesters - $activeCount;
$totalCourses   = array_sum(array_map(fn($s) => (int)($s->courses_count ?? 0), $semesters ?? []));
$totalFiles     = array_sum(array_map(fn($s) => (int)($s->files_count ?? 0), $semesters ?? []));
$isAdmin        = (($_SESSION['user_role'] ?? '') === 'admin');
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="majors-stat-icon" style="width: 52px; height: 52px; border-radius: 16px; font-size: 1.4rem; background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--dark);">الفصول الدراسية</h4>
            <p class="text-muted small mb-0">إدارة وتوزيع الفصول والتقويم الأكاديمي وربط المقررات والمحتوى الدراسي</p>
        </div>
    </div>
    <?php if ($isAdmin): ?>
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo url('/admin/semesters/create'); ?>" class="majors-btn-add" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);">
            <i class="fas fa-plus ms-1"></i>إضافة فصل جديد
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Quick Statistics Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon" style="background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $totalSemesters; ?></div>
                <div class="majors-stat-lbl">إجمالي الفصول</div>
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
                <div class="majors-stat-lbl">فصول نشطة</div>
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
                <div class="majors-stat-lbl">فصول موقوفة</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-primary">
                <i class="fas fa-book-open"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $totalCourses; ?></div>
                <div class="majors-stat-lbl">إجمالي المقررات</div>
            </div>
        </div>
    </div>
</div>

<!-- Controls & Search Toolbar -->
<div class="majors-toolbar mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <!-- Live Search -->
        <div class="majors-search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="semesterSearchInput" class="majors-search-input" placeholder="ابحث باسم الفصل أو رقمه..." autocomplete="off">
        </div>

        <!-- Filter Buttons & Counter -->
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="majors-filter-group" id="semesterFilterGroup">
                <button type="button" class="majors-filter-btn active" data-filter="all">الكل (<?php echo $totalSemesters; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="active">نشطة (<?php echo $activeCount; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="inactive">موقوفة (<?php echo $inactiveCount; ?>)</button>
            </div>
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-bold" id="semesterCounterBadge">
                عرض <?php echo $totalSemesters; ?> من <?php echo $totalSemesters; ?>
            </span>
        </div>
    </div>
</div>

<?php if (empty($semesters)): ?>
    <!-- Database Empty State -->
    <div class="majors-empty-box">
        <div class="majors-empty-icon" style="background: rgba(139, 92, 246, 0.08); color: #7c3aed;">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">لا توجد فصول دراسية مسجّلة حتى الآن</h5>
        <p class="text-muted small mb-4">ابدأ بإضافة أول فصل دراسي لتنظيم الخطة والجداول والمقررات التعليمية</p>
        <?php if ($isAdmin): ?>
        <a href="<?php echo url('/admin/semesters/create'); ?>" class="majors-btn-add" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);">
            <i class="fas fa-plus ms-1"></i>إضافة فصل الآن
        </a>
        <?php endif; ?>
    </div>
<?php else: ?>

    <!-- Semesters Cards Grid -->
    <div class="row g-4" id="semestersGridContainer">
        <?php 
        $semIdx = 0;
        foreach ($semesters as $semester):
            $semIdx++;
            $isActive = (bool)$semester->is_active;
            $courses  = (int)($semester->courses_count ?? 0);
            $files    = (int)($semester->files_count ?? 0);
            $semNum   = $semester->semester_number ?? $semester->id;
        ?>
        <div class="col-12 col-md-6 col-xl-4 semester-item-col" 
             data-name="<?php echo escape(mb_strtolower($semester->name)); ?>"
             data-num="<?php echo escape($semNum); ?>"
             data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>">
            
            <div class="semester-modern-card <?php echo $isActive ? '' : 'card-inactive'; ?>">
                
                <!-- Card Header -->
                <div class="major-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="semester-icon-box" title="<?php echo escape($semester->name); ?>">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="major-header-text">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="semester-num-badge">الفصل #<?php echo escape($semNum); ?></span>
                                <span class="major-id-badge" title="الترقيم">#<?php echo $semIdx; ?></span>
                            </div>
                            <h5 class="major-card-title"><?php echo escape($semester->name); ?></h5>
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
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="semester-order-tag">
                            <i class="fas fa-sort-numeric-down text-muted"></i>
                            ترتيب العرض: <strong class="text-dark ms-1"><?php echo escape($semester->sort_order ?? 0); ?></strong>
                        </span>
                        <span class="semester-order-tag">
                            <i class="fas fa-clock text-muted"></i>
                            فترة أكاديمية
                        </span>
                    </div>

                    <!-- Metrics / Info Strip -->
                    <div class="major-metrics-strip">
                        <a href="<?php echo url('/admin/courses?semester_id=' . escape($semester->id)); ?>" 
                           class="major-metric-chip" 
                           title="عرض المواد الدراسية التابعة لهذا الفصل">
                            <i class="fas fa-book-open"></i>
                            <span><?php echo $courses; ?> مواد دراسية</span>
                        </a>
                        <span class="major-dept-chip">
                            <i class="fas fa-file-alt"></i> <?php echo $files; ?> ملفات
                        </span>
                    </div>
                </div>

                <!-- Action Buttons Footer -->
                <div class="major-card-actions-bar">
                    <?php if ($isAdmin): ?>
                        <a href="<?php echo url('/admin/semesters/' . escape($semester->id) . '/edit'); ?>" 
                           class="major-act-btn btn-edit-major" 
                           title="تعديل بيانات الفصل">
                            <i class="fas fa-pen ms-1"></i>تعديل
                        </a>

                        <a href="<?php echo url('/admin/courses?semester_id=' . escape($semester->id)); ?>" 
                           class="major-act-btn btn-icon-major" 
                           title="عرض مواد هذا الفصل">
                            <i class="fas fa-book-bookmark"></i>
                        </a>

                        <a href="<?php echo url('/admin/semesters/' . escape($semester->id) . '/delete?_csrf_token=' . csrf_token()); ?>" 
                           class="major-act-btn btn-icon-major btn-delete-major" 
                           title="حذف الفصل الدراسي"
                           onclick="return confirm('هل أنت متأكد من حذف (<?php echo escape($semester->name); ?>)؟')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo url('/admin/courses?semester_id=' . escape($semester->id)); ?>" 
                           class="major-act-btn btn-edit-major" 
                           title="عرض مواد هذا الفصل">
                            <i class="fas fa-book-open ms-1"></i>عرض المقررات
                        </a>
                        <span class="badge bg-secondary bg-opacity-10 text-muted border rounded-pill px-3 py-2 small">
                            <i class="fas fa-lock ms-1 text-warning"></i>للعرض فقط
                        </span>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Search No Results State (Hidden by default) -->
    <div id="semestersNoResultsBox" class="majors-empty-box d-none my-4">
        <div class="majors-empty-icon">
            <i class="fas fa-search-minus"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">لم يتم العثور على أي نتائج مطابقة</h5>
        <p class="text-muted small mb-3">جرّب البحث بكلمة أو رقم فصل مختلف، أو قم بإلغاء الفلاتر المحددة</p>
        <button type="button" id="resetSemesterFiltersBtn" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-redo-alt ms-1"></i>إعادة ضبط البحث
        </button>
    </div>

<?php endif; ?>

<!-- Real-time Live Filter & Instant Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput   = document.getElementById('semesterSearchInput');
    var filterBtns    = document.querySelectorAll('#semesterFilterGroup .majors-filter-btn');
    var cardCols      = document.querySelectorAll('.semester-item-col');
    var counterBadge  = document.getElementById('semesterCounterBadge');
    var noResultsBox  = document.getElementById('semestersNoResultsBox');
    var resetBtn      = document.getElementById('resetSemesterFiltersBtn');
    
    var currentFilter = 'all';
    var totalCards    = cardCols.length;

    function applyFilter() {
        var query = (searchInput ? searchInput.value : '').trim().toLowerCase();
        var visibleCount = 0;

        cardCols.forEach(function(col) {
            var name   = col.getAttribute('data-name') || '';
            var num    = col.getAttribute('data-num') || '';
            var status = col.getAttribute('data-status') || '';

            var matchesSearch = !query || name.indexOf(query) !== -1 || num.indexOf(query) !== -1;
            var matchesStatus = (currentFilter === 'all') || (status === currentFilter);

            if (matchesSearch && matchesStatus) {
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

    if (searchInput) {
        searchInput.addEventListener('input', applyFilter);
    }

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            currentFilter = btn.getAttribute('data-filter') || 'all';
            applyFilter();
        });
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            currentFilter = 'all';
            filterBtns.forEach(function(b) {
                if (b.getAttribute('data-filter') === 'all') b.classList.add('active');
                else b.classList.remove('active');
            });
            applyFilter();
        });
    }
});
</script>
