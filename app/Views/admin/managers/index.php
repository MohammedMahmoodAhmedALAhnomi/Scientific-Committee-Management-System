<?php
$totalManagers   = count($managers ?? []);
$activeManagers  = count(array_filter($managers ?? [], fn($m) => (bool)$m->is_active));
$inactiveManagers= $totalManagers - $activeManagers;
$uniqueMajors    = count(array_unique(array_filter(array_map(fn($m) => $m->major_name ?? null, $managers ?? []))));
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="majors-stat-icon icon-primary" style="width: 52px; height: 52px; border-radius: 16px; font-size: 1.4rem; background: rgba(139, 92, 246, 0.1); color: #7c3aed;">
            <i class="fas fa-user-tie"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--dark);">إدارة مناديب الدفعات والمستويات</h4>
            <p class="text-muted small mb-0">تعيين وإدارة مناديب المستويات ومتابعة صلاحياتهم ونشاطهم الأكاديمي</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo url('/admin/managers/create'); ?>" class="majors-btn-add" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);">
            <i class="fas fa-plus ms-1"></i>إضافة مندوب جديد
        </a>
    </div>
</div>

<!-- Quick Statistics Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #7c3aed;">
                <i class="fas fa-user-tie"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $totalManagers; ?></div>
                <div class="majors-stat-lbl">إجمالي المناديب</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $activeManagers; ?></div>
                <div class="majors-stat-lbl">مناديب نشطون</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-warning">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $inactiveManagers; ?></div>
                <div class="majors-stat-lbl">غير نشطين</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-primary">
                <i class="fas fa-university"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $uniqueMajors; ?></div>
                <div class="majors-stat-lbl">تخصصات مشمولة</div>
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
            <input type="text" id="mgrSearchInput" class="majors-search-input" placeholder="ابحث باسم المندوب، البريد، أو التخصص..." autocomplete="off">
        </div>

        <!-- Filter Dropdowns & Buttons -->
        <div class="d-flex align-items-center gap-2 flex-wrap flex-grow-1 justify-content-end">
            
            <!-- Major Filter -->
            <?php if (!empty($majors)): ?>
            <select id="mgrMajorFilter" class="form-select form-select-sm rounded-pill px-3 shadow-none border" style="width: auto; max-width: 170px;">
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
            <select id="mgrLevelFilter" class="form-select form-select-sm rounded-pill px-3 shadow-none border" style="width: auto; max-width: 150px;">
                <option value="">جميع المستويات</option>
                <?php foreach ($levels as $lvl): ?>
                    <option value="<?php echo escape(mb_strtolower($lvl->name)); ?>">
                        <?php echo escape($lvl->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>

            <!-- Status Filter Buttons -->
            <div class="majors-filter-group" id="mgrFilterGroup">
                <button type="button" class="majors-filter-btn active" data-filter="all">الكل (<?php echo $totalManagers; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="active">نشط (<?php echo $activeManagers; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="inactive">غير نشط (<?php echo $inactiveManagers; ?>)</button>
            </div>

            <!-- Counter Badge -->
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-bold" id="mgrCounterBadge">
                عرض <?php echo $totalManagers; ?> من <?php echo $totalManagers; ?>
            </span>
        </div>

    </div>
</div>

<?php if (empty($managers)): ?>
    <!-- Database Empty State -->
    <div class="majors-empty-box">
        <div class="majors-empty-icon" style="background: rgba(139, 92, 246, 0.08); color: #7c3aed;">
            <i class="fas fa-user-tie"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">لا يوجد مناديب مسجلين حالياً</h5>
        <p class="text-muted small mb-4">قم بتعيين مناديب للدفعات لإدارة الملفات والمستندات والتواصل المباشر مع الطلاب</p>
        <a href="<?php echo url('/admin/managers/create'); ?>" class="majors-btn-add" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);">
            <i class="fas fa-plus ms-1"></i>إضافة أول مندوب
        </a>
    </div>
<?php else: ?>

    <!-- Managers Cards Grid -->
    <div class="row g-4" id="managersGridContainer">
        <?php 
        $mgrIdx = 0;
        foreach ($managers as $m):
            $mgrIdx++;
            $isActive  = (bool)$m->is_active;
            $majorName = $m->major_name ?? 'غير محدد';
            $levelName = $m->level_name ?? 'غير محدد';
            $lastLogin = $m->last_login ? format_date($m->last_login, 'Y-m-d H:i') : null;
        ?>
        <div class="col-12 col-md-6 col-xl-4 mgr-item-col" 
             data-name="<?php echo escape(mb_strtolower($m->full_name)); ?>"
             data-email="<?php echo escape(mb_strtolower($m->email)); ?>"
             data-major="<?php echo escape(mb_strtolower($majorName)); ?>"
             data-level="<?php echo escape(mb_strtolower($levelName)); ?>"
             data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>">
            
            <div class="manager-modern-card <?php echo $isActive ? '' : 'card-inactive'; ?>">
                
                <!-- Card Header -->
                <div class="major-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-card-avatar-box avatar-box-manager" title="مندوب مستوى">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="major-header-text">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-purple bg-opacity-10 text-purple border border-purple border-opacity-25 rounded-pill px-2 py-1" style="font-size:0.7rem; font-weight:700;">
                                    <i class="fas fa-award ms-1"></i>مندوب
                                </span>
                                <span class="major-id-badge" title="الترقيم التسلسلي">#<?php echo $mgrIdx; ?></span>
                            </div>
                            <h5 class="major-card-title text-truncate" style="max-width: 190px;" title="<?php echo escape($m->full_name); ?>">
                                <?php echo escape($m->full_name); ?>
                            </h5>
                        </div>
                    </div>
                    <div>
                        <span class="major-status-pill <?php echo $isActive ? 'status-pill-active' : 'status-pill-inactive'; ?>">
                            <?php if ($isActive): ?>
                                <span class="pulse-dot"></span> نشط
                            <?php else: ?>
                                <i class="fas fa-pause-circle me-1" style="font-size:0.7rem;"></i> معطل
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <!-- Card Body Content -->
                <div class="major-card-body-content">
                    
                    <!-- Scope Badges -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="manager-scope-badge" title="التخصص المكلف به">
                            <i class="fas fa-university"></i>
                            <span><?php echo escape($majorName); ?></span>
                        </span>
                        <span class="manager-scope-badge" style="background:rgba(14,165,233,0.08); color:#0284c7; border-color:rgba(14,165,233,0.18);" title="المستوى الدراسي">
                            <i class="fas fa-layer-group"></i>
                            <span><?php echo escape($levelName); ?></span>
                        </span>
                    </div>

                    <!-- Contact & Login Details -->
                    <div class="user-card-info-strip">
                        <div class="user-info-row" title="البريد الإلكتروني">
                            <i class="fas fa-envelope text-muted"></i>
                            <a href="mailto:<?php echo escape($m->email); ?>"><?php echo escape($m->email); ?></a>
                        </div>
                        <div class="user-info-row" title="آخر تسجيل دخول">
                            <i class="fas fa-clock text-muted"></i>
                            <span>آخر نشاط: <?php echo $lastLogin ? escape($lastLogin) : 'لم يسجل دخول بعد'; ?></span>
                        </div>
                    </div>

                </div>

                <!-- Action Buttons Footer -->
                <div class="major-card-actions-bar">
                    <a href="<?php echo url('/admin/managers/' . escape($m->id) . '/edit'); ?>" 
                       class="major-act-btn btn-edit-major" 
                       title="تعديل بيانات المندوب">
                        <i class="fas fa-pen ms-1"></i>تعديل
                    </a>

                    <a href="mailto:<?php echo escape($m->email); ?>" 
                       class="major-act-btn btn-icon-major" 
                       title="مراسلة المندوب">
                        <i class="fas fa-envelope"></i>
                    </a>

                    <a href="<?php echo url('/admin/managers/' . escape($m->id) . '/delete?_csrf_token=' . csrf_token()); ?>" 
                       class="major-act-btn btn-icon-major btn-delete-major" 
                       title="حذف المندوب"
                       onclick="return confirm('هل أنت متأكد من حذف المندوب (<?php echo escape($m->full_name); ?>)؟')">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Search No Results State (Hidden by default) -->
    <div id="mgrsNoResultsBox" class="majors-empty-box d-none my-4">
        <div class="majors-empty-icon">
            <i class="fas fa-search-minus"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">لم يتم العثور على أي مندوب مطابق</h5>
        <p class="text-muted small mb-3">جرّب البحث باسم أو بريد أو تخصص آخر، أو قم بإعادة ضبط الفلاتر</p>
        <button type="button" id="resetMgrFiltersBtn" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-redo-alt ms-1"></i>إعادة ضبط البحث والفلاتر
        </button>
    </div>

<?php endif; ?>

<!-- Real-time Live Filter & Instant Multi-Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput  = document.getElementById('mgrSearchInput');
    var majorFilter  = document.getElementById('mgrMajorFilter');
    var levelFilter  = document.getElementById('mgrLevelFilter');
    var filterBtns   = document.querySelectorAll('#mgrFilterGroup .majors-filter-btn');
    var cardCols     = document.querySelectorAll('.mgr-item-col');
    var counterBadge = document.getElementById('mgrCounterBadge');
    var noResultsBox = document.getElementById('mgrsNoResultsBox');
    var resetBtn     = document.getElementById('resetMgrFiltersBtn');
    
    var currentStatus = 'all';
    var totalCards    = cardCols.length;

    function applyFilters() {
        var query       = (searchInput ? searchInput.value : '').trim().toLowerCase();
        var selMajor    = (majorFilter ? majorFilter.value : '').trim().toLowerCase();
        var selLevel    = (levelFilter ? levelFilter.value : '').trim().toLowerCase();
        var visibleCount = 0;

        cardCols.forEach(function(col) {
            var name     = col.getAttribute('data-name') || '';
            var email    = col.getAttribute('data-email') || '';
            var major    = col.getAttribute('data-major') || '';
            var level    = col.getAttribute('data-level') || '';
            var status   = col.getAttribute('data-status') || '';

            var matchesSearch = !query || 
                                name.indexOf(query) !== -1 || 
                                email.indexOf(query) !== -1 || 
                                major.indexOf(query) !== -1 || 
                                level.indexOf(query) !== -1;

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

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (majorFilter) majorFilter.addEventListener('change', applyFilters);
    if (levelFilter) levelFilter.addEventListener('change', applyFilters);

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
