<?php
$totalMajors   = count($majors ?? []);
$activeCount   = count(array_filter($majors ?? [], fn($m) => (bool)$m->is_active));
$inactiveCount = $totalMajors - $activeCount;
$totalCourses  = array_sum(array_map(fn($m) => (int)($m->courses_count ?? 0), $majors ?? []));
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="majors-stat-icon icon-primary" style="width: 52px; height: 52px; border-radius: 16px; font-size: 1.4rem;">
            <i class="fas fa-university"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--dark);">التخصصات الأكاديمية</h4>
            <p class="text-muted small mb-0">كلية الحاسوب وتقنية المعلومات — إدارة التخصصات والخطط الدراسية</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo url('/admin/majors/create'); ?>" class="majors-btn-add">
            <i class="fas fa-plus ms-1"></i>إضافة تخصص جديد
        </a>
    </div>
</div>

<!-- Quick Statistics Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-primary">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $totalMajors; ?></div>
                <div class="majors-stat-lbl">إجمالي التخصصات</div>
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
                <div class="majors-stat-lbl">تخصصات نشطة</div>
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
                <div class="majors-stat-lbl">تخصصات موقوفة</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-info">
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
            <input type="text" id="majorSearchInput" class="majors-search-input" placeholder="ابحث باسم التخصص، الرمز، أو الوصف..." autocomplete="off">
        </div>

        <!-- Filter Buttons & Counter -->
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="majors-filter-group" id="majorFilterGroup">
                <button type="button" class="majors-filter-btn active" data-filter="all">الكل (<?php echo $totalMajors; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="active">نشطة (<?php echo $activeCount; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="inactive">موقوفة (<?php echo $inactiveCount; ?>)</button>
            </div>
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-bold" id="majorCounterBadge">
                عرض <?php echo $totalMajors; ?> من <?php echo $totalMajors; ?>
            </span>
        </div>
    </div>
</div>

<?php if (empty($majors)): ?>
    <!-- Database Empty State -->
    <div class="majors-empty-box">
        <div class="majors-empty-icon">
            <i class="fas fa-university"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">لا توجد تخصصات مسجّلة حتى الآن</h5>
        <p class="text-muted small mb-4">ابدأ بإضافة أول تخصص أكاديمي لإتاحة تسجيل الخطط والمقررات الدراسية</p>
        <a href="<?php echo url('/admin/majors/create'); ?>" class="majors-btn-add">
            <i class="fas fa-plus ms-1"></i>إضافة تخصص الآن
        </a>
    </div>
<?php else: ?>

    <!-- Majors Cards Grid -->
    <div class="row g-4" id="majorsGridContainer">
        <?php 
        $majorIdx = 0;
        foreach ($majors as $major):
            $majorIdx++;
            $iconClass = get_major_icon($major->name, $major->icon ?? 'fas fa-university');
            $codeTag   = get_major_badge_code($major->name);
            $isActive  = (bool)$major->is_active;
            $courses   = (int)($major->courses_count ?? 0);
        ?>
        <div class="col-12 col-md-6 col-xl-4 major-item-col" 
             data-name="<?php echo escape(mb_strtolower($major->name)); ?>"
             data-code="<?php echo escape(mb_strtolower($codeTag)); ?>"
             data-desc="<?php echo escape(mb_strtolower($major->description ?? '')); ?>"
             data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>">
            
            <div class="major-modern-card <?php echo $isActive ? '' : 'card-inactive'; ?>">
                
                <!-- Card Header -->
                <div class="major-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="major-icon-box" title="<?php echo escape($major->name); ?>">
                            <i class="<?php echo escape($iconClass); ?>"></i>
                        </div>
                        <div class="major-header-text">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <?php if ($codeTag): ?>
                                    <span class="major-code-badge"><?php echo $codeTag; ?></span>
                                <?php endif; ?>
                                <span class="major-id-badge" title="الترقيم">#<?php echo $majorIdx; ?></span>
                            </div>
                            <h5 class="major-card-title"><?php echo escape($major->name); ?></h5>
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
                    <p class="major-card-description" title="<?php echo escape($major->description ?? ''); ?>">
                        <?php echo !empty($major->description) 
                            ? escape($major->description) 
                            : 'تخصص أكاديمي معتمد ومتاح للطلاب في المنصة التعليمية.'; ?>
                    </p>

                    <!-- Metrics / Info Strip -->
                    <div class="major-metrics-strip">
                        <a href="<?php echo url('/admin/courses?major_id=' . escape($major->id)); ?>" 
                           class="major-metric-chip" 
                           title="عرض المواد الدراسية التابعة لهذا التخصص">
                            <i class="fas fa-book-open"></i>
                            <span><?php echo $courses; ?> مادة دراسية</span>
                        </a>
                        <span class="major-dept-chip">
                            <i class="fas fa-graduation-cap"></i> معتمد
                        </span>
                    </div>
                </div>

                <!-- Action Buttons Footer -->
                <div class="major-card-actions-bar">
                    <a href="<?php echo url('/admin/majors/' . escape($major->id) . '/edit'); ?>" 
                       class="major-act-btn btn-edit-major" 
                       title="تعديل بيانات التخصص">
                        <i class="fas fa-pen ms-1"></i>تعديل
                    </a>

                    <a href="<?php echo url('/admin/courses?major_id=' . escape($major->id)); ?>" 
                       class="major-act-btn btn-icon-major" 
                       title="إدارة مواد التخصص">
                        <i class="fas fa-layer-group"></i>
                    </a>

                    <a href="<?php echo url('/majors/' . escape($major->id)); ?>" 
                       target="_blank"
                       class="major-act-btn btn-icon-major" 
                       title="معاينة التخصص في البوابة العامة">
                        <i class="fas fa-external-link-alt"></i>
                    </a>

                    <a href="<?php echo url('/admin/majors/' . escape($major->id) . '/delete?_csrf_token=' . csrf_token()); ?>" 
                       class="major-act-btn btn-icon-major btn-delete-major" 
                       title="حذف التخصص"
                       onclick="return confirm('هل أنت متأكد من حذف تخصص (<?php echo escape($major->name); ?>)؟\nتنبيه: سيتم حذف جميع المواد المرتبطة به إن وجدت.')">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Search No Results State (Hidden by default) -->
    <div id="noMajorResults" class="majors-empty-box d-none mt-3">
        <div class="majors-empty-icon" style="background: rgba(239, 68, 68, 0.08); color: #ef4444;">
            <i class="fas fa-search"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">لا توجد تخصصات مطابقة لبحثك</h5>
        <p class="text-muted small mb-3">حاول البحث بكلمات أخرى أو قم بإلغاء التصفية</p>
        <button type="button" class="btn btn-outline-primary rounded-pill px-4 btn-sm" id="btnResetMajorSearch">
            <i class="fas fa-redo ms-1"></i>إعادة تعيين البحث
        </button>
    </div>

<?php endif; ?>

<!-- Real-time Filter & Search JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('majorSearchInput');
    const filterButtons = document.querySelectorAll('.majors-filter-btn');
    const cards = document.querySelectorAll('.major-item-col');
    const counterBadge = document.getElementById('majorCounterBadge');
    const noResultsBox = document.getElementById('noMajorResults');
    const resetBtn = document.getElementById('btnResetMajorSearch');

    let currentFilter = 'all';
    let currentQuery = '';

    function filterMajors() {
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const code = card.getAttribute('data-code') || '';
            const desc = card.getAttribute('data-desc') || '';
            const status = card.getAttribute('data-status') || '';

            const matchesFilter = (currentFilter === 'all') || (status === currentFilter);
            const matchesQuery = !currentQuery || 
                                 name.includes(currentQuery) || 
                                 code.includes(currentQuery) || 
                                 desc.includes(currentQuery);

            if (matchesFilter && matchesQuery) {
                card.classList.remove('d-none');
                visibleCount++;
            } else {
                card.classList.add('d-none');
            }
        });

        if (counterBadge) {
            counterBadge.textContent = 'عرض ' + visibleCount + ' من ' + cards.length;
        }

        if (noResultsBox) {
            if (visibleCount === 0 && cards.length > 0) {
                noResultsBox.classList.remove('d-none');
            } else {
                noResultsBox.classList.add('d-none');
            }
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            currentQuery = this.value.trim().toLowerCase();
            filterMajors();
        });
    }

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter') || 'all';
            filterMajors();
        });
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            currentQuery = '';
            currentFilter = 'all';
            filterButtons.forEach(b => {
                b.classList.toggle('active', b.getAttribute('data-filter') === 'all');
            });
            filterMajors();
        });
    }
});
</script>
