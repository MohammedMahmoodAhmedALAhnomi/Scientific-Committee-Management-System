<?php
$totalUsers   = count($users ?? []);
$countAdmin   = 0; $countMajor = 0; $countManager = 0; $countStudent = 0;
foreach (($users ?? []) as $u) {
    $r = $u->role ?? 'student';
    if ($r === 'admin')           $countAdmin++;
    elseif ($r === 'major_admin') $countMajor++;
    elseif ($r === 'manager')     $countManager++;
    else                          $countStudent++;
}
$isAdmin = in_array($_SESSION['user_role'] ?? '', ['admin', 'major_admin'], true);
$promotionInfo = $promotionInfo ?? \App\Models\User::getLastPromotionInfo($scopedMajorId ?? null);
$canPromote = $promotionInfo['can_promote'] ?? true;
$currentYear = $promotionInfo['current_year'] ?? (int)date('Y');
$lastYear = $promotionInfo['last_year'] ?? null;
$lastDate = $promotionInfo['last_date'] ?? null;
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="majors-stat-icon icon-primary" style="width: 52px; height: 52px; border-radius: 16px; font-size: 1.4rem;">
            <i class="fas fa-users-cog"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--dark);">إدارة المستخدمين والأدوار</h4>
            <p class="text-muted small mb-0">عرض وتعيين وتعديل صلاحيات وأدوار جميع الحسابات في النظام</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <?php if ($isAdmin): ?>
            <?php if ($canPromote): ?>
                <button type="button" class="btn btn-promote rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#promoteYearModal">
                    <i class="fas fa-graduation-cap ms-1"></i>🎓 الترقية السنوية (عام <?php echo $currentYear; ?>)
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-success bg-opacity-10 text-success border border-success rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#promoteYearModal">
                    <i class="fas fa-check-circle ms-1"></i>تمت ترقية عام <?php echo $lastYear; ?>
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Stats Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-role-card stat-admin">
            <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
            <div class="stat-num"><?php echo $countAdmin; ?></div>
            <div class="stat-label">مدراء النظام</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-role-card stat-major">
            <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="stat-num"><?php echo $countMajor; ?></div>
            <div class="stat-label">مسؤولو تخصصات</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-role-card stat-manager">
            <div class="stat-icon"><i class="fas fa-award"></i></div>
            <div class="stat-num"><?php echo $countManager; ?></div>
            <div class="stat-label">مناديب مستويات</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-role-card stat-student">
            <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-num"><?php echo $countStudent; ?></div>
            <div class="stat-label">طلاب وزوار</div>
        </div>
    </div>
</div>

<!-- Controls & Search Toolbar -->
<div class="majors-toolbar mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        
        <!-- Live Search -->
        <div class="majors-search-box flex-grow-1" style="max-width: 320px;">
            <i class="fas fa-search"></i>
            <input type="text" id="userSearchInput" class="majors-search-input" placeholder="ابحث بالاسم، البريد، أو التخصص..." autocomplete="off">
        </div>

        <!-- Filter Dropdowns & Buttons -->
        <div class="d-flex align-items-center gap-2 flex-wrap flex-grow-1 justify-content-end">
            
            <!-- Role Selector -->
            <select id="userRoleFilter" class="form-select form-select-sm rounded-pill px-3 shadow-none border" style="width: auto; max-width: 170px;">
                <option value="">جميع الأدوار</option>
                <option value="admin">👑 مدراء النظام</option>
                <option value="major_admin">🛡️ مسؤولو تخصصات</option>
                <option value="manager">🎖️ مناديب مستويات</option>
                <option value="student">👤 طلاب وزوار</option>
            </select>

            <!-- Status Filter Buttons -->
            <div class="majors-filter-group" id="userFilterGroup">
                <button type="button" class="majors-filter-btn active" data-filter="all">الكل (<?php echo $totalUsers; ?>)</button>
                <button type="button" class="majors-filter-btn" data-filter="active">نشط</button>
                <button type="button" class="majors-filter-btn" data-filter="inactive">معطل</button>
            </div>

            <!-- Counter Badge -->
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-bold" id="userCounterBadge">
                عرض <?php echo $totalUsers; ?> من <?php echo $totalUsers; ?>
            </span>
        </div>

    </div>
</div>

<?php if (empty($users)): ?>
    <!-- Empty State -->
    <div class="majors-empty-box">
        <div class="majors-empty-icon">
            <i class="fas fa-users-slash"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">لا يوجد مستخدمين مسجلين في النظام</h5>
        <p class="text-muted small mb-0">لم يتم العثور على أي حسابات مطابقة</p>
    </div>
<?php else: ?>

    <!-- Users Cards Grid -->
    <div class="row g-4" id="usersGridContainer">
        <?php 
        $userIdx = 0;
        foreach ($users as $user):
            $userIdx++;
            $isOwnerRow = \App\Models\User::isSystemOwner($user);
            $r          = $user->role ?? 'student';
            $isActive   = (bool)$user->is_active;
            $majorName  = $user->major_name ?? '';
            $levelName  = $user->level_name ?? '';
            $joinedDate = $user->created_at ? format_date($user->created_at, 'Y-m-d') : '';

            // Avatar & Role specifics
            if ($isOwnerRow) {
                $avatarBoxCls = 'avatar-box-owner';
                $avatarIcon   = '<i class="fas fa-crown"></i>';
                $roleLabel    = 'مالك النظام';
                $roleBadgeCls = 'role-badge-owner';
                $roleIcon     = 'fas fa-crown';
            } elseif ($r === 'admin') {
                $avatarBoxCls = 'avatar-box-admin';
                $avatarIcon   = '<i class="fas fa-user-shield"></i>';
                $roleLabel    = 'مدير عام';
                $roleBadgeCls = 'role-badge-admin';
                $roleIcon     = 'fas fa-user-shield';
            } elseif ($r === 'major_admin') {
                $avatarBoxCls = 'avatar-box-major';
                $avatarIcon   = '<i class="fas fa-shield-alt"></i>';
                $roleLabel    = 'مسؤول تخصص';
                $roleBadgeCls = 'role-badge-major';
                $roleIcon     = 'fas fa-shield-alt';
            } elseif ($r === 'manager') {
                $avatarBoxCls = 'avatar-box-manager';
                $avatarIcon   = '<i class="fas fa-award"></i>';
                $roleLabel    = 'مندوب مستوى';
                $roleBadgeCls = 'role-badge-manager';
                $roleIcon     = 'fas fa-award';
            } else {
                $avatarBoxCls = 'avatar-box-student';
                $avatarIcon   = '<i class="fas fa-user-graduate"></i>';
                $roleLabel    = 'طالب / زائر';
                $roleBadgeCls = 'role-badge-student';
                $roleIcon     = 'fas fa-user';
            }

            $canEditThisRow   = \App\Controllers\UserController::canEditUserTarget($user);
            $canDeleteThisRow = \App\Controllers\UserController::canDeleteUserTarget($user);
        ?>
        <div class="col-12 col-md-6 col-xl-4 user-item-col" 
             data-name="<?php echo escape(mb_strtolower($user->full_name ?? $user->name ?? '')); ?>"
             data-email="<?php echo escape(mb_strtolower($user->email ?? '')); ?>"
             data-phone="<?php echo escape(mb_strtolower($user->phone ?? '')); ?>"
             data-major="<?php echo escape(mb_strtolower($majorName)); ?>"
             data-level="<?php echo escape(mb_strtolower($levelName)); ?>"
             data-role="<?php echo escape($r); ?>"
             data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>">
            
            <div class="user-modern-card <?php echo $isOwnerRow ? 'card-owner-vip' : ($isActive ? '' : 'card-inactive'); ?>">
                
                <!-- Card Header -->
                <div class="major-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-card-avatar-box <?php echo $avatarBoxCls; ?>" title="<?php echo $roleLabel; ?>">
                            <?php echo $avatarIcon; ?>
                        </div>
                        <div class="major-header-text">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="role-badge <?php echo $roleBadgeCls; ?>" style="padding: 2px 8px; font-size: 0.7rem;">
                                    <i class="<?php echo $roleIcon; ?> ms-1"></i><?php echo $roleLabel; ?>
                                </span>
                                <span class="major-id-badge" title="الترقيم التسلسلي">#<?php echo $userIdx; ?></span>
                            </div>
                            <h5 class="major-card-title text-truncate" style="max-width: 190px;" title="<?php echo escape($user->full_name ?? $user->name ?? 'بدون اسم'); ?>">
                                <?php echo escape($user->full_name ?? $user->name ?? 'بدون اسم'); ?>
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
                    
                    <!-- Scope Taxonomy Badges -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <?php if (!empty($majorName)): ?>
                            <span class="course-tax-badge tax-major" title="التخصص">
                                <i class="fas fa-university ms-1"></i><?php echo escape($majorName); ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($levelName)): ?>
                            <span class="course-tax-badge tax-level" title="المستوى">
                                <i class="fas fa-layer-group ms-1"></i><?php echo escape($levelName); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Contact & Join details -->
                    <div class="user-card-info-strip">
                        <div class="user-info-row" title="البريد الإلكتروني">
                            <i class="fas fa-envelope text-muted"></i>
                            <span style="user-select:none;-webkit-user-select:none;"><?php echo escape(mask_email($user->email, (int)$user->id)); ?></span>
                        </div>
                        <?php if (!empty($user->phone)): ?>
                            <div class="user-info-row" title="رقم الهاتف">
                                <i class="fas fa-phone text-muted"></i>
                                <span><?php echo escape($user->phone); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($joinedDate)): ?>
                            <div class="user-info-row" title="تاريخ التسجيل">
                                <i class="fas fa-calendar-alt text-muted"></i>
                                <span>انضم: <?php echo escape($joinedDate); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Action Buttons Footer -->
                <div class="major-card-actions-bar">
                    <?php if ($canEditThisRow): ?>
                        <a href="<?php echo url('/admin/users/' . escape($user->id) . '/edit'); ?>" 
                           class="major-act-btn btn-edit-major" 
                           title="تعديل الدور والصلاحيات">
                            <i class="fas fa-user-edit ms-1"></i>تعديل الدور
                        </a>

                        <?php if ($canDeleteThisRow && (int)$user->id !== (int)($_SESSION['user_id'] ?? 0)): ?>
                            <a href="<?php echo url('/admin/users/' . escape($user->id) . '/delete?_csrf_token=' . csrf_token()); ?>" 
                               class="major-act-btn btn-icon-major btn-delete-major" 
                               title="حذف الحساب"
                               onclick="return confirm('هل أنت متأكد من حذف هذا الحساب نهائياً؟')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge bg-light text-warning border px-3 py-2 rounded-pill w-100 text-center fw-bold" style="font-size:0.75rem;">
                            <i class="fas fa-lock ms-1"></i>حساب محمي ومحصن
                        </span>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Search No Results State (Hidden by default) -->
    <div id="usersNoResultsBox" class="majors-empty-box d-none my-4">
        <div class="majors-empty-icon">
            <i class="fas fa-search-minus"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">لم يتم العثور على أي مستخدم مطابق</h5>
        <p class="text-muted small mb-3">جرّب البحث باسم أو بريد أو تخصص آخر، أو قم بإعادة ضبط الفلاتر</p>
        <button type="button" id="resetUserFiltersBtn" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fas fa-redo-alt ms-1"></i>إعادة ضبط البحث والفلاتر
        </button>
    </div>

<?php endif; ?>

<!-- Modal: Annual Promotion -->
<div class="modal fade" id="promoteYearModal" tabindex="-1" aria-labelledby="promoteYearModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content promote-modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header promote-modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="promoteYearModalLabel">
                    <i class="fas fa-graduation-cap me-2"></i>🎓 الترقية الأكاديمية السنوية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <?php if ($canPromote): ?>
                <form action="<?php echo url('/admin/users/promote-year'); ?>" method="POST">
                    <input type="hidden" name="_csrf_token" value="<?php echo csrf_token(); ?>">
                    <div class="modal-body p-4">
                        <div class="promote-alert mb-3">
                            <i class="fas fa-exclamation-triangle promote-alert-icon"></i>
                            <div>
                                <strong class="d-block mb-1">تنبيه نظام الترقية السنوية (عام <?php echo $currentYear; ?>):</strong>
                                <ul class="mb-0 ps-3 mt-1 small">
                                    <li><strong>ترقية المستويات:</strong> نقل طلاب ومناديب كل مستوى للمستوى التالي تلقائياً (1 ⬅️ 2 ⬅️ 3 ⬅️ 4).</li>
                                    <li><strong>تخرج المستوى الأخير:</strong> تحويل دفعة المستوى الرابع لحالة <strong>الخريجين 🎓</strong>.</li>
                                    <li><strong>حد الترقية:</strong> تتم الترقية <strong>مرة واحدة فقط في السنة</strong> ولن يُسمح بتكرارها إلا في العام القادم.</li>
                                    <li><strong>إشعارات فورية:</strong> إرسال تهاني الترقية والتخرج لجميع الطلاب والمناديب.</li>
                                </ul>
                            </div>
                        </div>
                        <p class="text-muted small mb-0 fw-bold text-center">هل أنت متأكد من تنفيذ الترقية الأكاديمية لعام (<?php echo $currentYear; ?>) الآن؟</p>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-promote rounded-pill px-4 fw-bold shadow-sm">
                            <i class="fas fa-check-circle ms-1"></i>تأكيد وتنفيذ الترقية
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="modal-body p-4">
                    <div class="alert alert-success d-flex align-items-center gap-3 rounded-4 p-3 mb-0 border-0 bg-success bg-opacity-10">
                        <i class="fas fa-check-circle fa-2x text-success flex-shrink-0"></i>
                        <div>
                            <h6 class="fw-bold mb-1 text-success">تمت الترقية الأكاديمية لهذا العام مسبقاً!</h6>
                            <p class="small mb-0 text-muted" style="line-height: 1.6;">
                                تم تنفيذ الترقية بنجاح للعام الأكاديمي <strong>(<?php echo $lastYear; ?>)</strong><?php if ($lastDate): ?> بتاريخ <span dir="ltr"><?php echo escape(format_date($lastDate, 'Y-m-d H:i')); ?></span><?php endif; ?>.
                                <br>
                                النظام يتيح تنفيذ الترقية <strong>مرة واحدة فقط في السنة</strong>، وستكون الترقية القادمة متاحة في العام القادم بإذن الله.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">إغلاق</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Real-time Live Filter & Instant Multi-Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput  = document.getElementById('userSearchInput');
    var roleFilter   = document.getElementById('userRoleFilter');
    var filterBtns   = document.querySelectorAll('#userFilterGroup .majors-filter-btn');
    var cardCols     = document.querySelectorAll('.user-item-col');
    var counterBadge = document.getElementById('userCounterBadge');
    var noResultsBox = document.getElementById('usersNoResultsBox');
    var resetBtn     = document.getElementById('resetUserFiltersBtn');
    
    var currentStatus = 'all';
    var totalCards    = cardCols.length;

    function applyFilters() {
        var query       = (searchInput ? searchInput.value : '').trim().toLowerCase();
        var selRole     = (roleFilter ? roleFilter.value : '').trim().toLowerCase();
        var visibleCount = 0;

        cardCols.forEach(function(col) {
            var name     = col.getAttribute('data-name') || '';
            var email    = col.getAttribute('data-email') || '';
            var phone    = col.getAttribute('data-phone') || '';
            var major    = col.getAttribute('data-major') || '';
            var level    = col.getAttribute('data-level') || '';
            var role     = col.getAttribute('data-role') || '';
            var status   = col.getAttribute('data-status') || '';

            var matchesSearch = !query || 
                                name.indexOf(query) !== -1 || 
                                email.indexOf(query) !== -1 || 
                                phone.indexOf(query) !== -1 || 
                                major.indexOf(query) !== -1 || 
                                level.indexOf(query) !== -1;

            var matchesRole   = !selRole || role === selRole;
            var matchesStatus = (currentStatus === 'all') || (status === currentStatus);

            if (matchesSearch && matchesRole && matchesStatus) {
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
    if (roleFilter)  roleFilter.addEventListener('change', applyFilters);

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
            if (roleFilter)  roleFilter.value = '';
            currentStatus = 'all';
            filterBtns.forEach(function(b) {
                if (b.getAttribute('data-filter') === 'all') b.classList.add('active');
                else b.classList.remove('active');
            });
            applyFilters();
        });
    }

    // Modal clickability and body detachment fix
    var promoteModalEl = document.getElementById('promoteYearModal');
    if (promoteModalEl && promoteModalEl.parentElement !== document.body) {
        document.body.appendChild(promoteModalEl);
    }
});
</script>