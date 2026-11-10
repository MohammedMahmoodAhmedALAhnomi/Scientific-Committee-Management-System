<div class="hero-section text-center">
    <div class="container position-relative" style="z-index: 2;">
        <div class="mb-4">
            <span class="hero-badge-pill">
                <span class="hero-pulse-dot"></span>
                <i class="fas fa-graduation-cap text-warning me-1"></i>اللجنة العلمية — كلية الحاسوب وتقنية المعلومات
            </span>
        </div>
        <h1 class="hero-title mb-3">منصتك الموحدة للمحتوى الأكاديمي والتعلم الذكي</h1>
        <p class="hero-subtitle mb-4">نوفر لك جميع المواد الدراسية، الملخصات الشاملة، النماذج الامتحانية، والمراجع العلمية بضغطة زر واحدة</p>

        <!-- Integrated Fast Search Bar -->
        <div class="hero-search-container mb-4">
            <form class="hero-search-box d-flex align-items-center" action="<?php echo url('/search'); ?>" method="GET">
                <i class="fas fa-search text-white-50 ms-3 fs-5"></i>
                <input type="search" name="q" class="form-control hero-search-input" placeholder="ابحث عن مادة، ملخص، أو نموذج امتحان..." required>
                <button type="submit" class="btn hero-search-btn text-white me-1">
                    <i class="fas fa-search ms-1"></i>بحث
                </button>
            </form>
        </div>

        <div class="d-flex justify-content-center gap-3 flex-wrap mb-4">
            <a href="#majors-section" class="btn btn-light btn-lg rounded-pill px-4 fw-bold shadow-sm hero-btn-primary">
                <i class="fas fa-university ms-2 text-primary"></i>استكشف التخصصات
            </a>
            <a href="<?php echo url('/announcements'); ?>" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold">
                <i class="fas fa-bullhorn ms-2 text-warning"></i>آخر الإعلانات
            </a>
        </div>

        <div class="hero-features-strip">
            <span class="hero-feature-tag"><i class="fas fa-check-circle text-success ms-1"></i>محتوى معتمد ومحدث</span>
            <span class="hero-feature-tag"><i class="fas fa-layer-group text-warning ms-1"></i>مصنف بحسب المستوى والترم</span>
            <span class="hero-feature-tag"><i class="fas fa-bolt text-info ms-1"></i>تحميل سريع ومباشر</span>
        </div>
    </div>
    <div class="hero-wave position-absolute bottom-0 start-0 end-0" style="z-index: 1;">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path d="M0,30 C360,60 720,0 1440,30 L1440,60 L0,60 Z" fill="#f8f9fa"></path>
        </svg>
    </div>
</div>

<div class="container home-main-container" style="margin-top: -2.5rem; position: relative; z-index: 3;">
    <!-- Saved User Preference Banner -->
    <div id="homeSavedPrefBanner" class="alert alert-info home-pref-banner border-0 shadow rounded-4 d-none p-3 mb-4 align-items-center justify-content-between flex-wrap gap-3" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary bg-opacity-10 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                <i class="fas fa-graduation-cap text-primary fs-4"></i>
            </div>
            <div>
                <small class="text-muted d-block" style="font-size: 0.8rem;">تخصصك ومستواك المحفوظ لدينا:</small>
                <strong id="homeBannerPrefText" class="text-primary fs-6"></strong>
            </div>
        </div>
        <div class="d-flex gap-2 pref-actions flex-wrap flex-sm-nowrap">
            <a href="#" id="homeBannerGoBtn" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm fw-bold">
                <i class="fas fa-book-open ms-1"></i>الانتقال لموادك مباشرة
            </a>
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#preferenceModal">
                <i class="fas fa-sliders-h ms-1"></i>تغيير
            </button>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row g-3 mb-5">
        <div class="col-6 col-md-4 col-lg">
            <div class="stat-card-modern text-center p-3 p-md-4 bg-white shadow-sm rounded-4 border-0 h-100">
                <div class="stat-icon-modern mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(99, 102, 241, 0.12) !important; color: #6366f1 !important;">
                    <i class="fas fa-users fs-4"></i>
                </div>
                <div class="stat-number-modern fs-2 fw-bold text-dark"><?php echo $userCount ?? 0; ?></div>
                <div class="stat-label-modern small text-muted font-weight-medium">مستخدم ومستفيد</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="stat-card-modern text-center p-3 p-md-4 bg-white shadow-sm rounded-4 border-0 h-100">
                <div class="stat-icon-modern mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(236, 72, 153, 0.12) !important; color: #ec4899 !important;">
                    <i class="fas fa-download fs-4"></i>
                </div>
                <div class="stat-number-modern fs-2 fw-bold text-dark"><?php echo $totalDownloads ?? 0; ?></div>
                <div class="stat-label-modern small text-muted font-weight-medium">إجمالي التنزيلات</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="stat-card-modern text-center p-3 p-md-4 bg-white shadow-sm rounded-4 border-0 h-100">
                <div class="stat-icon-modern mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(79, 70, 229, 0.12) !important; color: #4f46e5 !important;">
                    <i class="fas fa-book-open fs-4"></i>
                </div>
                <div class="stat-number-modern fs-2 fw-bold text-dark"><?php echo $courseCount; ?></div>
                <div class="stat-label-modern small text-muted font-weight-medium">مادة دراسية</div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-lg">
            <div class="stat-card-modern text-center p-3 p-md-4 bg-white shadow-sm rounded-4 border-0 h-100">
                <div class="stat-icon-modern mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(16, 185, 129, 0.12) !important; color: #10b981 !important;">
                    <i class="fas fa-file-alt fs-4"></i>
                </div>
                <div class="stat-number-modern fs-2 fw-bold text-dark"><?php echo $fileCount; ?></div>
                <div class="stat-label-modern small text-muted font-weight-medium">ملف تعليمي</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg">
            <div class="stat-card-modern text-center p-3 p-md-4 bg-white shadow-sm rounded-4 border-0 h-100">
                <div class="stat-icon-modern mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(245, 158, 11, 0.12) !important; color: #d97706 !important;">
                    <i class="fas fa-layer-group fs-4"></i>
                </div>
                <div class="stat-number-modern fs-2 fw-bold text-dark"><?php echo $majorCount; ?></div>
                <div class="stat-label-modern small text-muted font-weight-medium">تخصص علمي</div>
            </div>
        </div>
    </div>

    <!-- Majors Section -->
    <section id="majors-section" class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1 d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-circle p-2 d-inline-flex"><i class="fas fa-graduation-cap text-white fs-5"></i></span>
                    التخصصات العلمية
                </h2>
                <p class="text-muted mb-0">اختر تخصصك الأكاديمي لتصفح المقررات الدراسية والملفات</p>
            </div>
        </div>
        <?php if (!empty($majors)): ?>
            <div class="row g-4">
                <?php foreach ($majors as $major): ?>
                    <?php
                        $iconClass = get_major_icon($major->name, $major->icon ?? 'fas fa-university');
                        $codeTag = get_major_badge_code($major->name);
                        $palette = get_major_palette($major->name);
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 major-card-premium shadow-sm border-0" 
                             style="--major-accent: <?php echo $palette['primary']; ?>; --major-gradient: <?php echo $palette['icon_bg']; ?>; --major-light: <?php echo $palette['light']; ?>;"
                             role="button" data-bs-toggle="modal" data-bs-target="#levelModal" data-major-id="<?php echo $major->id; ?>" data-major-name="<?php echo escape($major->name); ?>">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="major-icon-box flex-shrink-0 ms-3" style="background: <?php echo $palette['light']; ?>; color: <?php echo $palette['primary']; ?>;">
                                        <i class="<?php echo escape($iconClass); ?>"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                            <h5 class="fw-bold mb-0 text-dark"><?php echo escape($major->name); ?></h5>
                                            <?php if ($codeTag): ?>
                                                <span class="badge rounded-pill px-2 py-1 fs-6 fw-bold" style="background: <?php echo $palette['primary']; ?> !important; color: #ffffff !important;"><?php echo $codeTag; ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="badge rounded-pill px-3 py-1" style="background: <?php echo $palette['light']; ?> !important; color: <?php echo $palette['primary']; ?> !important; font-weight: 700;">
                                            <i class="fas fa-book-open ms-1"></i><?php echo escape($major->courses_count ?? 0); ?> مادة
                                        </span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0" style="line-height: 1.6;"><?php echo escape(truncate($major->description, 120)); ?></p>
                            </div>
                            <div class="card-footer bg-white border-0 pt-0 px-4 pb-4">
                                <span class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-bold py-2 major-explore-btn">
                                    <i class="fas fa-arrow-left ms-1"></i>تصفح المواد والمستويات
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">لا توجد تخصصات متاحة حالياً</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Platform Features Showcase -->
    <section class="features-section-modern mb-5">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold mb-2">مميزات المنصة الأكاديمية</span>
                <h3 class="fw-bold mb-2">كل ما تحتاجه لنجاحك وتفوقك الأكاديمي</h3>
                <p class="text-muted">صُممت المنصة لتوفير تجربة تعليمية سلسة ومنظمة تساعد الطلاب في الوصول للمحتوى بأسرع وسيلة</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box-card">
                        <div class="feature-box-icon feature-icon-indigo">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h5 class="fw-bold mb-2">محتوى منظّم ودقيق</h5>
                        <p class="text-muted small mb-0">جميع المحاضرات والملخصات مصنفة بدقة حسب التخصص، المستوى الدراسي، والترم.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box-card">
                        <div class="feature-box-icon feature-icon-emerald">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h5 class="fw-bold mb-2">نماذج امتحانات محلولة</h5>
                        <p class="text-muted small mb-0">بنك شامل لأهم نماذج الاختبارات النصفية والنهائية للتدريب والتطبيق الذاتي.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box-card">
                        <div class="feature-box-icon feature-icon-amber">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h5 class="fw-bold mb-2">تحميل سريع ومباشر</h5>
                        <p class="text-muted small mb-0">تنزيل جميع الملفات التعليمية بصيغ PDF والمراجع بضغطة زر وبأقصى سرعة.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box-card">
                        <div class="feature-box-icon feature-icon-rose">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-2">تصفح متجاوب وذكي</h5>
                        <p class="text-muted small mb-0">واجهة فائقة السرعة متوافقة تماماً مع كافة الهواتف والأجهزة اللوحية والمحمول.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Announcements Section -->
    <?php if (!empty($announcements)): ?>
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="fas fa-bullhorn text-primary"></i>آخر الإعلانات والتنبيهات
                    </h3>
                    <p class="text-muted small mb-0">متابعة فورية لكل المستجدات والأحداث الأكاديمية</p>
                </div>
                <a href="<?php echo url('/announcements'); ?>" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                    عرض كافة الإعلانات <i class="fas fa-arrow-left ms-1"></i>
                </a>
            </div>
            <div class="row g-4">
                <?php foreach ($announcements as $announcement): ?>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 card-hover overflow-hidden rounded-4 <?php echo $announcement->is_pinned ? 'border-end border-warning border-5' : ''; ?>">
                            <?php if ($announcement->is_pinned): ?>
                                <div class="position-absolute top-0 start-0 bg-warning text-dark px-3 py-1 fw-bold small" style="border-radius: 0 0 12px 0; z-index: 1;">
                                    <i class="fas fa-thumbtack ms-1"></i>إعلان مثبت
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-2 text-dark"><?php echo escape($announcement->title); ?></h5>
                                <p class="card-text text-muted small" style="line-height: 1.6;"><?php echo escape(truncate($announcement->content, 150)); ?></p>
                                <div class="d-flex align-items-center text-muted small mt-3 pt-2 border-top">
                                    <span class="d-flex align-items-center">
                                        <i class="fas fa-calendar-alt text-primary ms-1"></i>
                                        <?php echo escape(format_date($announcement->created_at, 'Y-m-d')); ?>
                                    </span>
                                    <?php if (isset($announcement->created_by_name)): ?>
                                        <span class="d-flex align-items-center me-3">
                                            <i class="fas fa-user-circle text-secondary ms-1"></i>
                                            <?php echo escape($announcement->created_by_name); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<!-- Level Selection Modal -->
<div class="modal fade" id="levelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-layer-group ms-2 text-primary"></i><span id="levelModalTitle">اختر المستوى</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted mb-3 small">اختر المستوى الدراسي لعرض المقررات والملازم المخصصة له:</p>
                <div class="d-flex flex-column gap-2" id="levelList">
                    <?php 
                    $modalIcons = [
                        1 => ['icon' => 'fas fa-seedling', 'cls' => 'text-primary', 'badge' => 'السنة الأولى'],
                        2 => ['icon' => 'fas fa-book-reader', 'cls' => 'text-success', 'badge' => 'السنة الثانية'],
                        3 => ['icon' => 'fas fa-laptop-code', 'cls' => 'text-warning', 'badge' => 'السنة الثالثة'],
                        4 => ['icon' => 'fas fa-graduation-cap', 'cls' => 'text-purple', 'badge' => 'سنة التخرج'],
                    ];
                    foreach ($levels as $level): 
                        $lNum = (int)($level->level_number ?? 1);
                        $cfg = $modalIcons[$lNum] ?? ['icon' => 'fas fa-layer-group', 'cls' => 'text-primary', 'badge' => 'المستوى ' . $lNum];
                    ?>
                        <a href="#" class="btn btn-light border btn-lg d-flex align-items-center justify-content-between px-3 py-2 level-link text-decoration-none shadow-sm gap-2"
                           data-level-id="<?php echo $level->id; ?>"
                           style="border-radius: 14px; transition: all 0.2s ease;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm flex-shrink-0" style="width: 38px; height: 38px;">
                                    <i class="<?php echo $cfg['icon']; ?> <?php echo $cfg['cls']; ?>"></i>
                                </div>
                                <span class="fw-bold text-dark fs-6"><?php echo escape($level->name); ?></span>
                            </div>
                            <span class="badge bg-light text-muted border rounded-pill small flex-shrink-0"><?php echo $cfg['badge']; ?></span>
                        </a>
                    <?php endforeach; ?>
                    <hr class="my-2">
                    <a href="#" class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center gap-2 py-2 level-link-all shadow-sm fw-bold"
                       style="border-radius: 14px;">
                        <i class="fas fa-th-large ms-1"></i>
                        عرض جميع المستويات
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Check for automatic redirection if user has a saved preference
    var urlParams = new URLSearchParams(window.location.search);
    var isForced = urlParams.has('home') || urlParams.has('choose') || urlParams.has('all') || urlParams.has('change');
    
    if (window.AppPref) {
        var pref = window.AppPref.get();
        if (pref && pref.majorId) {
            var banner = document.getElementById('homeSavedPrefBanner');
            var bannerText = document.getElementById('homeBannerPrefText');
            var bannerBtn = document.getElementById('homeBannerGoBtn');
            if (banner && bannerText && bannerBtn) {
                var text = pref.majorName + (pref.levelName && pref.levelName !== 'جميع المستويات' ? ' • ' + pref.levelName : '');
                bannerText.textContent = text;
                bannerBtn.href = '<?php echo url('/majors/'); ?>' + pref.majorId + (pref.levelId ? '?level=' + pref.levelId : '');
                banner.classList.remove('d-none');
                banner.classList.add('d-flex');
            }

            if (!isForced) {
                var targetUrl = '<?php echo url('/majors/'); ?>' + pref.majorId + (pref.levelId ? '?level=' + pref.levelId : '');
                window.location.replace(targetUrl);
                return;
            }
        }
    }

    // 2. Level Modal Handler for manual major cards click
    var modal = document.getElementById('levelModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function(event) {
        var card = event.relatedTarget;
        if (!card) return;
        var majorId = card.getAttribute('data-major-id');
        var majorName = card.getAttribute('data-major-name');
        document.getElementById('levelModalTitle').textContent = majorName;

        var links = modal.querySelectorAll('.level-link, .level-link-all');
        links.forEach(function(link) {
            var levelId = link.getAttribute('data-level-id');
            var levelName = link.textContent.trim();
            
            link.addEventListener('click', function(e) {
                if (window.AppPref) {
                    window.AppPref.save(majorId, majorName, levelId || '', levelId ? levelName : 'جميع المستويات');
                }
            });

            if (levelId) {
                link.href = '<?php echo url('/'); ?>majors/' + majorId + '?level=' + levelId;
            } else {
                link.href = '<?php echo url('/'); ?>majors/' + majorId;
            }
        });
    });
});
</script>
