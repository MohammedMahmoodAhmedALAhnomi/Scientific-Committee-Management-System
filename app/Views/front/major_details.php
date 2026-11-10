<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0 fs-6">
        <li class="breadcrumb-item"><a href="<?php echo url('/?home=1'); ?>" class="text-decoration-none fw-bold"><i class="fas fa-home ms-1"></i>الرئيسية</a></li>
        <?php if ($selectedLevel): ?>
            <li class="breadcrumb-item"><a href="<?php echo url('/majors/' . $major->id); ?>" class="text-decoration-none fw-bold"><?php echo escape($major->name); ?></a></li>
            <li class="breadcrumb-item active fw-bold text-primary"><?php echo escape($selectedLevel->name); ?></li>
        <?php else: ?>
            <li class="breadcrumb-item active fw-bold text-primary"><?php echo escape($major->name); ?></li>
        <?php endif; ?>
    </ol>
</nav>

<!-- Major Hero / Level Header Banner -->
<div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.07) 0%, rgba(6, 182, 212, 0.07) 100%);">
    <div class="card-body p-3 p-md-5">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3 gap-md-4">
                <div class="major-hero-icon rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0" style="width: 76px; height: 76px; background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); font-size: 2rem;">
                    <i class="fas <?php echo $selectedLevel ? 'fa-layer-group' : 'fa-university'; ?>"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold">
                            <?php echo escape($major->name); ?>
                        </span>
                        <?php if ($selectedLevel): ?>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold">
                                <i class="fas fa-check-circle ms-1"></i><?php echo escape($selectedLevel->name); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h2 class="fw-bold mb-1 text-dark fs-3 fs-md-2">
                        <?php echo $selectedLevel ? escape($selectedLevel->name) . ' - ' . escape($major->name) : escape($major->name); ?>
                    </h2>
                    <p class="text-muted small mb-0">
                        <?php echo $selectedLevel ? 'استعراض الفصول والمقررات والملازم والتكاليف الأكاديمية لهذا المستوى' : escape($major->description ?? 'تصفح المستويات والمواد الدراسية والمذكرات العلمية'); ?>
                    </p>
                </div>
            </div>

            <?php if ($selectedLevel): ?>
                <div class="w-100 w-md-auto mt-2 mt-md-0">
                    <a href="<?php echo url('/majors/' . $major->id); ?>" class="btn btn-primary rounded-pill px-3 px-md-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2 w-100 justify-content-center">
                        <i class="fas fa-arrow-right ms-1"></i>
                        <span>الرجوع لجميع المستويات</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- When on General Major page (No Level Selected), Show the 4 Master Level Cards -->
<?php if (!$selectedLevel && !empty($levels)): ?>
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-layer-group text-primary"></i>اختر المستوى الدراسي لدخول فصوله ومواده:
                </h5>
                <p class="text-muted small mb-0">انقر على المستوى المطلوب لعرض الفصول والمقررات والمذكرات المخصصة له</p>
            </div>
        </div>

        <div class="row g-2 g-md-3">
            <?php 
            $levelThemes = [
                1 => ['color' => '#4f46e5', 'bg' => 'rgba(79, 70, 229, 0.08)', 'border' => 'rgba(79, 70, 229, 0.2)', 'icon' => 'fas fa-seedling', 'year' => 'السنة الأولى'],
                2 => ['color' => '#059669', 'bg' => 'rgba(5, 150, 105, 0.08)', 'border' => 'rgba(5, 150, 105, 0.2)', 'icon' => 'fas fa-book-reader', 'year' => 'السنة الثانية'],
                3 => ['color' => '#d97706', 'bg' => 'rgba(217, 119, 6, 0.08)', 'border' => 'rgba(217, 119, 6, 0.2)', 'icon' => 'fas fa-laptop-code', 'year' => 'السنة الثالثة'],
                4 => ['color' => '#7c3aed', 'bg' => 'rgba(124, 58, 237, 0.08)', 'border' => 'rgba(124, 58, 237, 0.2)', 'icon' => 'fas fa-graduation-cap', 'year' => 'سنة التخرج'],
            ];

            foreach ($levels as $lv): 
                $lvNum   = (int)($lv->level_number ?? 1);
                $theme   = $levelThemes[$lvNum] ?? ['color' => '#0284c7', 'bg' => 'rgba(2, 132, 199, 0.08)', 'border' => 'rgba(2, 132, 199, 0.2)', 'icon' => 'fas fa-layer-group', 'year' => 'المستوى ' . $lvNum];
                $cCount  = $levelCourseMap[$lv->id] ?? 0;
            ?>
                <div class="col-6 col-md-3">
                    <a href="<?php echo url('/majors/' . $major->id . '?level=' . $lv->id); ?>" class="text-decoration-none d-block h-100">
                        <div class="card major-level-card border rounded-4 text-center p-3 h-100 bg-white position-relative shadow-sm" 
                             style="transition: all 0.25s ease; border-color: <?php echo $theme['border']; ?> !important;"
                             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 12px 28px rgba(15,23,42,0.1)';"
                             onmouseout="this.style.transform='none'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)';">
                            
                            <!-- Year Badge -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 mb-2 mb-md-3">
                                <span class="badge rounded-pill px-2 py-1 fw-bold" style="background: <?php echo $theme['bg']; ?>; color: <?php echo $theme['color']; ?>; font-size: 0.72rem;">
                                    <?php echo escape($theme['year']); ?>
                                </span>
                                <span class="badge bg-light text-muted border rounded-pill d-none d-sm-inline-block" style="font-size: 0.68rem;">فصلين دراسيين</span>
                            </div>

                            <!-- Level Icon -->
                            <div class="rounded-circle mx-auto mb-2 mb-md-3 d-flex align-items-center justify-content-center shadow-sm" 
                                 style="width: 58px; height: 58px; background: <?php echo $theme['bg']; ?>; color: <?php echo $theme['color']; ?>; font-size: 1.45rem; border: 1px solid <?php echo $theme['border']; ?>;">
                                <i class="<?php echo $theme['icon']; ?>"></i>
                            </div>

                            <!-- Title -->
                            <h5 class="fw-bold mb-1 text-dark"><?php echo escape($lv->name); ?></h5>

                            <!-- Course count info -->
                            <small class="text-muted d-block mb-2 mb-md-3" style="font-size: 0.8rem;">
                                <i class="fas fa-book-open text-primary ms-1"></i>
                                <?php echo $cCount > 0 ? $cCount . ' مواد دراسية' : 'المقررات الدراسية'; ?>
                            </small>

                            <!-- Action Link Button -->
                            <div class="mt-auto">
                                <span class="btn btn-sm w-100 rounded-pill fw-bold shadow-sm" 
                                      style="background: <?php echo $theme['bg']; ?>; color: <?php echo $theme['color']; ?>; font-size: 0.74rem; padding: 4px 6px;">
                                    دخول المواد <i class="fas fa-arrow-left ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Semesters & Courses Sections -->
<?php if (empty($grouped)): ?>
    <div class="card shadow-sm border-0 rounded-4 text-center py-5 my-4 bg-white">
        <div class="card-body">
            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; font-size: 2rem; color: #94a3b8;">
                <i class="fas fa-folder-open"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">لا توجد مواد مضافة لهذا المستوى حتى الآن</h5>
            <p class="text-muted small mb-0">يرجى مراجعة إدارة الكلية أو اختيار مستوى دراسي آخر</p>
        </div>
    </div>
<?php else: ?>
    <?php
    $currentLevel = null;
    $semGradients = [
        1 => ['header' => 'linear-gradient(135deg, #4f46e5 0%, #4338ca 100%)', 'badge' => 'الفصل الأول', 'icon' => 'fas fa-calendar-check'],
        2 => ['header' => 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)', 'badge' => 'الفصل الثاني', 'icon' => 'fas fa-calendar-alt'],
        3 => ['header' => 'linear-gradient(135deg, #059669 0%, #047857 100%)', 'badge' => 'الفصل الصيفي', 'icon' => 'fas fa-sun'],
    ];

    foreach ($grouped as $g):
        if ($currentLevel !== $g['level_number']):
            if ($currentLevel !== null) echo '</div></div>';
            $currentLevel = $g['level_number'];
    ?>
    <!-- Level Container (When viewing all or selected) -->
    <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-primary shadow-sm flex-shrink-0" style="width: 44px; height: 44px; background: rgba(79, 70, 229, 0.1); font-size: 1.25rem;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark"><?php echo escape($g['level_name']); ?></h5>
                    <small class="text-muted">الخطة الدراسية والمقررات المعتمدة</small>
                </div>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold">
                <?php echo count($g['courses']); ?> مواد
            </span>
        </div>
        <div class="card-body p-3 p-md-4 bg-light bg-opacity-50">
    <?php endif; ?>

            <?php
                $sNum = (int)($g['semester_number'] ?? 1);
                $semStyle = $semGradients[$sNum] ?? ['header' => 'linear-gradient(135deg, #4f46e5 0%, #4338ca 100%)', 'badge' => 'الفصل ' . $sNum, 'icon' => 'fas fa-calendar-alt'];
            ?>
            <!-- Semester Card Hub -->
            <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden bg-white">
                
                <!-- Semester Header Bar -->
                <div class="p-2.5 p-md-3 px-3 px-md-4 text-white d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: <?php echo $semStyle['header']; ?>;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="<?php echo $semStyle['icon']; ?> fs-5"></i>
                        <h6 class="fw-bold mb-0 text-white"><?php echo escape($g['semester_name']); ?></h6>
                    </div>
                    <span class="badge rounded-pill px-3 py-1.5 fw-bold shadow-sm" style="background: rgba(255, 255, 255, 0.22); color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.35); backdrop-filter: blur(4px); font-size: 0.8rem;">
                        <i class="fas fa-book-open ms-1"></i><?php echo count($g['courses']); ?> مواد دراسية
                    </span>
                </div>

                <!-- Courses Grid within this Semester -->
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3">
                        <?php foreach ($g['courses'] as $course): 
                            $fCount = (int)($course->files_count ?? 0);
                            $rating = (float)($course->avg_rating ?? 0);
                        ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="<?php echo url('/courses/' . $course->id); ?>" class="text-decoration-none d-block h-100">
                                    <div class="card border rounded-4 h-100 p-3 bg-white shadow-sm d-flex flex-column" 
                                         style="transition: all 0.25s ease; border-color: rgba(226, 232, 240, 0.9) !important;"
                                         onmouseover="this.style.transform='translateY(-4px)'; this.style.borderColor='#4f46e5'; this.style.boxShadow='0 10px 24px rgba(79, 70, 229, 0.12)';"
                                         onmouseout="this.style.transform='none'; this.style.borderColor='rgba(226, 232, 240, 0.9)'; this.style.boxShadow='0 2px 6px rgba(0,0,0,0.03)';">
                                        
                                        <!-- Course Header -->
                                        <div class="d-flex align-items-start gap-3 mb-2">
                                            <div class="rounded-3 d-flex align-items-center justify-content-center text-primary flex-shrink-0" style="width: 44px; height: 44px; background: rgba(79, 70, 229, 0.08); font-size: 1.2rem;">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <h6 class="fw-bold text-dark mb-1 text-truncate" title="<?php echo escape($course->name); ?>">
                                                    <?php echo escape($course->name); ?>
                                                </h6>
                                                <small class="text-muted d-block text-truncate">
                                                    <?php echo escape($course->description ?? 'مقرر دراسي معتمد'); ?>
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Metrics Row: Files Count & Rating -->
                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-auto gap-2 flex-wrap">
                                            <span class="badge bg-light text-primary border rounded-pill px-2 py-1 small">
                                                <i class="fas fa-file-alt ms-1"></i><?php echo $fCount; ?> ملف ومذكرة
                                            </span>
                                            
                                            <?php if ($rating > 0): ?>
                                                <span class="badge bg-warning bg-opacity-10 text-dark rounded-pill px-2 py-1 small fw-bold">
                                                    <i class="fas fa-star text-warning ms-1"></i><?php echo number_format($rating, 1); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-primary small fw-bold">
                                                    دخول للمادة <i class="fas fa-arrow-left ms-1"></i>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
    <?php endforeach; ?>
    <?php if ($currentLevel !== null) echo '</div></div>'; ?>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.AppPref) {
        window.AppPref.save(
            '<?php echo $major->id; ?>',
            '<?php echo escape($major->name); ?>',
            '<?php echo $selectedLevel ? $selectedLevel->id : ''; ?>',
            '<?php echo $selectedLevel ? escape($selectedLevel->name) : 'جميع المستويات'; ?>'
        );
    }
});
</script>
