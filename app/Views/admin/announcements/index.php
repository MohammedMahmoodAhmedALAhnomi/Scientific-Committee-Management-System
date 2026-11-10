<?php
$totalAnnouncements = count($announcements ?? []);
$pinnedCount        = count(array_filter($announcements ?? [], fn($a) => (bool)$a->is_pinned));
$activeCount        = count(array_filter($announcements ?? [], fn($a) => (bool)$a->is_active));
$inactiveCount      = $totalAnnouncements - $activeCount;
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="majors-stat-icon icon-warning" style="width: 52px; height: 52px; border-radius: 16px; font-size: 1.4rem; background: rgba(245, 158, 11, 0.1); color: #d97706;">
            <i class="fas fa-bullhorn"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--dark);">إدارة الإعلانات والأخبار</h4>
            <p class="text-muted small mb-0">نشر ومتابعة الإعلانات وتثبيتها للطلاب والزوار في المنصة التعليمية</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?php echo url('/admin/announcements/create'); ?>" class="majors-btn-add" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <i class="fas fa-plus ms-1"></i>إضافة إعلان جديد
        </a>
    </div>
</div>

<!-- Quick Statistics Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-warning" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $totalAnnouncements; ?></div>
                <div class="majors-stat-lbl">إجمالي الإعلانات</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-primary">
                <i class="fas fa-thumbtack"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $pinnedCount; ?></div>
                <div class="majors-stat-lbl">إعلانات مثبتة</div>
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
                <div class="majors-stat-lbl">إعلانات نشطة</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-info">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div>
                <div class="majors-stat-val"><?php echo $inactiveCount; ?></div>
                <div class="majors-stat-lbl">إعلانات موقوفة</div>
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
            <input type="text" id="annSearchInput" class="majors-search-input" placeholder="ابحث بعنوان الإعلان، المحتوى، أو الناشر..." autocomplete="off">
        </div>

        <!-- Filter Dropdowns & Buttons -->
        <div class="d-flex align-items-center gap-2 flex-wrap flex-grow-1 justify-content-end">
            
            <!-- Pin Filter -->
            <select id="annPinFilter" class="form-select form-select-sm rounded-pill px-3 shadow-none border" style="width: auto; max-width: 150px;">
                <option value="">جميع الإعلانات</option>
                <option value="pinned">📌 المثبتة فقط</option>
                <option value="unpinned">العادية فقط</option>
            </select>

            <!-- Status Filter Buttons -->
            <div class="majors-filter-group" id="annFilterGroup">
                <button type="button" class="majors-filter-btn active" data-filter="all">الكل (<?php echo $totalAnnouncements; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="active">نشطة (<?php echo $activeCount; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="inactive">موقوفة (<?php echo $inactiveCount; ?>)</button>
            </div>

            <!-- Counter Badge -->
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-bold" id="annCounterBadge">
                عرض <?php echo $totalAnnouncements; ?> من <?php echo $totalAnnouncements; ?>
            </span>
        </div>

    </div>
</div>

<?php if (empty($announcements)): ?>
    <!-- Database Empty State -->
    <div class="majors-empty-box">
        <div class="majors-empty-icon" style="background: rgba(245, 158, 11, 0.08); color: #d97706;">
            <i class="fas fa-bullhorn"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">لا توجد إعلانات منشورة حتى الآن</h5>
        <p class="text-muted small mb-4">ابدأ بإنشاء أول إعلان أو تعميم أكاديمي لإعلام الطلاب بآخر المستجدات</p>
        <a href="<?php echo url('/admin/announcements/create'); ?>" class="majors-btn-add" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <i class="fas fa-plus ms-1"></i>إضافة إعلان الآن
        </a>
    </div>
<?php else: ?>

    <!-- Announcements Cards Grid -->
    <div class="row g-4" id="announcementsGridContainer">
        <?php 
        $annIdx = 0;
        foreach ($announcements as $a):
            $annIdx++;
            $isActive   = (bool)$a->is_active;
            $isPinned   = (bool)$a->is_pinned;
            $creator    = $a->created_by_name ?? ($a->creator_name ?? 'الإدارة');
            $createdDate= $a->created_at ? format_date($a->created_at, 'Y-m-d') : '';
            $startsAt   = $a->starts_at ? format_date($a->starts_at, 'Y-m-d') : null;
            $expiresAt  = $a->expires_at ? format_date($a->expires_at, 'Y-m-d') : null;
            $content    = strip_tags($a->content ?? '');
        ?>
        <div class="col-12 col-md-6 col-xl-4 ann-item-col" 
             data-title="<?php echo escape(mb_strtolower($a->title)); ?>"
             data-content="<?php echo escape(mb_strtolower($content)); ?>"
             data-creator="<?php echo escape(mb_strtolower($creator)); ?>"
             data-pinned="<?php echo $isPinned ? 'pinned' : 'unpinned'; ?>"
             data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>">
            
            <div class="announcement-modern-card <?php echo $isPinned ? 'card-pinned' : ''; ?> <?php echo $isActive ? '' : 'card-inactive'; ?>">
                
                <!-- Card Header -->
                <div class="major-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="announcement-icon-box" title="<?php echo $isPinned ? 'إعلان مثبت' : 'إعلان'; ?>">
                            <i class="<?php echo $isPinned ? 'fas fa-thumbtack' : 'fas fa-bullhorn'; ?>"></i>
                        </div>
                        <div class="major-header-text">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <?php if ($isPinned): ?>
                                    <span class="announcement-pin-badge">
                                        <i class="fas fa-thumbtack ms-1"></i>مثبت
                                    </span>
                                <?php endif; ?>
                                <span class="major-id-badge" title="الترقيم التسلسلي">#<?php echo $annIdx; ?></span>
                            </div>
                            <h5 class="major-card-title text-truncate" style="max-width: 190px;" title="<?php echo escape($a->title); ?>">
                                <?php echo escape($a->title); ?>
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
                    
                    <!-- Content Preview -->
                    <p class="announcement-content-preview" title="<?php echo escape($content); ?>">
                        <?php echo !empty($content) ? escape($content) : 'لا يوجد تفاصيل إضافية لهذا الإعلان.'; ?>
                    </p>

                    <!-- Meta Details: Creator, Created Date, Expiry -->
                    <div class="file-meta-row mt-2">
                        <div class="file-meta-item" title="الناشر">
                            <i class="fas fa-user-edit text-muted"></i>
                            <span><?php echo escape($creator); ?></span>
                        </div>

                        <?php if (!empty($createdDate)): ?>
                            <div class="file-meta-item" title="تاريخ النشر">
                                <i class="fas fa-calendar-alt text-muted"></i>
                                <span><?php echo escape($createdDate); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($expiresAt)): ?>
                            <div class="file-meta-item" title="تاريخ انتهاء الصلاحية">
                                <i class="fas fa-hourglass-end text-muted"></i>
                                <span>ينتهي: <?php echo escape($expiresAt); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Action Buttons Footer -->
                <div class="major-card-actions-bar">
                    <a href="<?php echo url('/admin/announcements/' . escape($a->id) . '/edit'); ?>" 
                       class="major-act-btn btn-edit-major" 
                       title="تعديل الإعلان">
                        <i class="fas fa-pen ms-1"></i>تعديل
                    </a>

                    <a href="<?php echo url('/announcements/' . escape($a->id)); ?>" 
                       target="_blank"
                       class="major-act-btn btn-icon-major" 
                       title="معاينة في الموقع">
                        <i class="fas fa-external-link-alt"></i>
                    </a>

                    <a href="<?php echo url('/admin/announcements/' . escape($a->id) . '/delete?_csrf_token=' . csrf_token()); ?>" 
                       class="major-act-btn btn-icon-major btn-delete-major" 
                       title="حذف الإعلان"
                       onclick="return confirm('هل أنت متأكد من حذف إعلان (<?php echo escape($a->title); ?>)؟')">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Search No Results State (Hidden by default) -->
    <div id="annNoResultsBox" class="majors-empty-box d-none my-4">
        <div class="majors-empty-icon">
            <i class="fas fa-search-minus"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">لم يتم العثور على أي إعلانات مطابقة</h5>
        <p class="text-muted small mb-3">جرّب البحث بعنوان أو كلمة أخرى، أو قم بإعادة ضبط الفلاتر</p>
        <button type="button" id="resetAnnFiltersBtn" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-redo-alt ms-1"></i>إعادة ضبط البحث والفلاتر
        </button>
    </div>

<?php endif; ?>

<!-- Real-time Live Filter & Instant Multi-Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput  = document.getElementById('annSearchInput');
    var pinFilter    = document.getElementById('annPinFilter');
    var filterBtns   = document.querySelectorAll('#annFilterGroup .majors-filter-btn');
    var cardCols     = document.querySelectorAll('.ann-item-col');
    var counterBadge = document.getElementById('annCounterBadge');
    var noResultsBox = document.getElementById('annNoResultsBox');
    var resetBtn     = document.getElementById('resetAnnFiltersBtn');
    
    var currentStatus = 'all';
    var totalCards    = cardCols.length;

    function applyFilters() {
        var query       = (searchInput ? searchInput.value : '').trim().toLowerCase();
        var selPin      = (pinFilter ? pinFilter.value : '').trim().toLowerCase();
        var visibleCount = 0;

        cardCols.forEach(function(col) {
            var title    = col.getAttribute('data-title') || '';
            var content  = col.getAttribute('data-content') || '';
            var creator  = col.getAttribute('data-creator') || '';
            var pinned   = col.getAttribute('data-pinned') || '';
            var status   = col.getAttribute('data-status') || '';

            var matchesSearch = !query || 
                                title.indexOf(query) !== -1 || 
                                content.indexOf(query) !== -1 || 
                                creator.indexOf(query) !== -1;

            var matchesPin    = !selPin || pinned === selPin;
            var matchesStatus = (currentStatus === 'all') || (status === currentStatus);

            if (matchesSearch && matchesPin && matchesStatus) {
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
    if (pinFilter)   pinFilter.addEventListener('change', applyFilters);

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
            if (pinFilter)   pinFilter.value = '';
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