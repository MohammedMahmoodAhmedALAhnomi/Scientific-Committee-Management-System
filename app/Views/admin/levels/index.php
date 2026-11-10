<?php
$totalLevels   = count($levels ?? []);
$activeCount   = count(array_filter($levels ?? [], fn($l) => (bool)$l->is_active));
$inactiveCount = $totalLevels - $activeCount;
$totalCourses  = array_sum(array_map(fn($l) => (int)($l->courses_count ?? 0), $levels ?? []));
$isAdmin       = (($_SESSION['user_role'] ?? '') === 'admin');
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="majors-stat-icon icon-info" style="width: 52px; height: 52px; border-radius: 16px; font-size: 1.4rem;">
            <i class="fas fa-layer-group"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--dark);">المستويات الدراسية</h4>
            <p class="text-muted small mb-0">إدارة المراحل والمستويات الأكاديمية وربط المقررات والخطط الدراسية</p>
        </div>
    </div>
    <?php if ($isAdmin): ?>
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo url('/admin/levels/create'); ?>" class="majors-btn-add">
            <i class="fas fa-plus ms-1"></i>إضافة مستوى جديد
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Quick Statistics Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-info">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $totalLevels; ?></div>
                <div class="majors-stat-lbl">إجمالي المستويات</div>
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
                <div class="majors-stat-lbl">مستويات نشطة</div>
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
                <div class="majors-stat-lbl">مستويات موقوفة</div>
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
            <input type="text" id="levelSearchInput" class="majors-search-input" placeholder="ابحث باسم المستوى أو رقمه..." autocomplete="off">
        </div>

        <!-- Filter Buttons & Counter -->
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="majors-filter-group" id="levelFilterGroup">
                <button type="button" class="majors-filter-btn active" data-filter="all">الكل (<?php echo $totalLevels; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="active">نشطة (<?php echo $activeCount; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="inactive">موقوفة (<?php echo $inactiveCount; ?>)</button>
            </div>
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-bold" id="levelCounterBadge">
                عرض <?php echo $totalLevels; ?> من <?php echo $totalLevels; ?>
            </span>
        </div>
    </div>
</div>

<?php if (empty($levels)): ?>
    <!-- Database Empty State -->
    <div class="majors-empty-box">
        <div class="majors-empty-icon" style="background: rgba(14, 165, 233, 0.08); color: #0284c7;">
            <i class="fas fa-layer-group"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">لا توجد مستويات مسجّلة حتى الآن</h5>
        <p class="text-muted small mb-4">ابدأ بإضافة أول مستوى دراسي لتنظيم وتوزيع المقررات الأكاديمية</p>
        <?php if ($isAdmin): ?>
        <a href="<?php echo url('/admin/levels/create'); ?>" class="majors-btn-add">
            <i class="fas fa-plus ms-1"></i>إضافة مستوى الآن
        </a>
        <?php endif; ?>
    </div>
<?php else: ?>

    <!-- Levels Cards Grid -->
    <div class="row g-4" id="levelsGridContainer">
        <?php 
        $lvlIdx = 0;
        foreach ($levels as $level):
            $lvlIdx++;
            $isActive = (bool)$level->is_active;
            $courses  = (int)($level->courses_count ?? 0);
            $lvlNum   = $level->level_number ?? $level->id;
        ?>
        <div class="col-12 col-md-6 col-xl-4 level-item-col" 
             data-name="<?php echo escape(mb_strtolower($level->name)); ?>"
             data-num="<?php echo escape($lvlNum); ?>"
             data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>">
            
            <div class="level-modern-card <?php echo $isActive ? '' : 'card-inactive'; ?>">
                
                <!-- Card Header -->
                <div class="major-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="level-icon-box" title="<?php echo escape($level->name); ?>">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="major-header-text">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="level-num-badge">المستوى #<?php echo escape($lvlNum); ?></span>
                                <span class="major-id-badge" title="الترقيم">#<?php echo $lvlIdx; ?></span>
                            </div>
                            <h5 class="major-card-title"><?php echo escape($level->name); ?></h5>
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
                        <span class="level-order-tag">
                            <i class="fas fa-sort-numeric-down text-muted"></i>
                            ترتيب العرض: <strong class="text-dark ms-1"><?php echo escape($level->sort_order ?? 0); ?></strong>
                        </span>
                        <span class="level-order-tag">
                            <i class="fas fa-calendar-alt text-muted"></i>
                            فصلين دراسيين
                        </span>
                    </div>

                    <!-- Metrics / Info Strip -->
                    <div class="major-metrics-strip">
                        <a href="<?php echo url('/admin/courses?level_id=' . escape($level->id)); ?>" 
                           class="major-metric-chip" 
                           title="عرض المواد الدراسية التابعة لهذا المستوى">
                            <i class="fas fa-book-open"></i>
                            <span><?php echo $courses; ?> مواد دراسية</span>
                        </a>
                        <span class="major-dept-chip">
                            <i class="fas fa-graduation-cap"></i> مرحلة أكاديمية
                        </span>
                    </div>
                </div>

                <!-- Action Buttons Footer -->
                <div class="major-card-actions-bar">
                    <?php if ($isAdmin): ?>
                        <a href="<?php echo url('/admin/levels/' . escape($level->id) . '/edit'); ?>" 
                           class="major-act-btn btn-edit-major" 
                           title="تعديل بيانات المستوى">
                            <i class="fas fa-pen ms-1"></i>تعديل
                        </a>

                        <a href="<?php echo url('/admin/courses?level_id=' . escape($level->id)); ?>" 
                           class="major-act-btn btn-icon-major" 
                           title="عرض مواد هذا المستوى">
                            <i class="fas fa-book-bookmark"></i>
                        </a>

                        <a href="<?php echo url('/admin/levels/' . escape($level->id) . '/delete?_csrf_token=' . csrf_token()); ?>" 
                           class="major-act-btn btn-icon-major btn-delete-major" 
                           title="حذف المستوى"
                           onclick="return confirm('هل أنت متأكد من حذف (<?php echo escape($level->name); ?>)؟')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo url('/admin/courses?level_id=' . escape($level->id)); ?>" 
                           class="major-act-btn btn-edit-major" 
                           title="عرض مواد هذا المستوى">
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
    <div id="noLevelResults" class="majors-empty-box d-none mt-3">
        <div class="majors-empty-icon" style="background: rgba(239, 68, 68, 0.08); color: #ef4444;">
            <i class="fas fa-search"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">لا توجد مستويات مطابقة لبحثك</h5>
        <p class="text-muted small mb-3">حاول البحث بكلمات أخرى أو قم بإلغاء التصفية</p>
        <button type="button" class="btn btn-outline-primary rounded-pill px-4 btn-sm" id="btnResetLevelSearch">
            <i class="fas fa-redo ms-1"></i>إعادة تعيين البحث
        </button>
    </div>

<?php endif; ?>

<!-- Real-time Filter & Search JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('levelSearchInput');
    const filterButtons = document.querySelectorAll('#levelFilterGroup .majors-filter-btn');
    const cards = document.querySelectorAll('.level-item-col');
    const counterBadge = document.getElementById('levelCounterBadge');
    const noResultsBox = document.getElementById('noLevelResults');
    const resetBtn = document.getElementById('btnResetLevelSearch');

    let currentFilter = 'all';
    let currentQuery = '';

    function filterLevels() {
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const num = card.getAttribute('data-num') || '';
            const status = card.getAttribute('data-status') || '';

            const matchesStatus = (currentFilter === 'all') || (status === currentFilter);
            const matchesQuery = !currentQuery || name.includes(currentQuery) || num.includes(currentQuery);

            if (matchesStatus && matchesQuery) {
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
            filterLevels();
        });
    }

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter') || 'all';
            filterLevels();
        });
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            currentQuery = '';
            filterButtons.forEach(b => {
                b.classList.toggle('active', b.getAttribute('data-filter') === 'all');
            });
            currentFilter = 'all';
            filterLevels();
        });
    }
});
</script>
