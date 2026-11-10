<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?php echo url('/?home=1'); ?>"><i class="fas fa-home ms-1"></i>الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('/majors/' . escape($course->major_id)); ?>"><?php echo escape($course->major_name ?? ''); ?></a></li>
            <?php if (!empty($course->level_name)): ?>
                <li class="breadcrumb-item active"><?php echo escape($course->level_name); ?></li>
            <?php endif; ?>
            <li class="breadcrumb-item active"><?php echo escape($course->name); ?></li>
        </ol>
    </nav>
    <div>
        <a href="<?php echo url('/majors/' . escape($course->major_id) . ($course->level_id ? '?level=' . escape($course->level_id) : '')); ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold shadow-sm">
            <i class="fas fa-arrow-right ms-1"></i>الرجوع للمواد
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-book text-primary ms-2"></i>
                <?php echo escape($course->name); ?>
            </h4>
            <?php if (!empty($course->semester_name)): ?>
                <span class="badge rounded-pill px-3 py-2" style="background: rgba(168, 85, 247, 0.15) !important; color: #9333ea !important; font-weight: 700 !important;">
                    <i class="fas fa-calendar-alt ms-1"></i>
                    <?php echo escape($course->semester_name); ?>
                </span>
            <?php endif; ?>
        </div>
        <?php if (!empty($course->description)): ?>
            <p class="text-muted mt-3 mb-0"><?php echo escape($course->description); ?></p>
        <?php endif; ?>
        <div class="d-flex align-items-start gap-3 mt-3 border-top pt-3 flex-wrap">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <small class="text-muted fw-bold">قيم المادة:</small>
                    <div class="stars-container" data-course-id="<?php echo escape($course->id); ?>" data-current="<?php echo escape($course->avg_rating ?? 0); ?>" style="direction: ltr; display: inline-flex;">
                        <?php $labels = ['', 'ضعيف', 'مقبول', 'جيد', 'جيد جداً', 'ممتاز']; ?>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star-rating"
                               data-value="<?php echo $i; ?>"
                               title="<?php echo $labels[$i]; ?>"
                               style="cursor: pointer; font-size: 1.6rem; line-height: 1; color: <?php echo $i <= round($course->avg_rating ?? 0) ? '#ffc107' : '#bbb'; ?>; transition: color 0.15s; user-select: none;">
                               ★
                            </span>
                        <?php endfor; ?>
                    </div>
                    <small class="text-muted text-nowrap" id="ratingInfo">
                        <?php if (($course->rating_count ?? 0) > 0): ?>
                            <?php echo escape($course->avg_rating); ?> / 5 (<?php echo escape($course->rating_count); ?> تقييم)
                        <?php else: ?>
                            لم يتم التقييم بعد
                        <?php endif; ?>
                    </small>
                    <small class="text-success fw-bold d-none" id="userRatingBadge">
                        <i class="fas fa-check-circle ms-1"></i>
                        <span id="userRatingText"></span>
                    </small>
                </div>
                <?php if (!empty($ratingDistribution) && ($course->rating_count ?? 0) > 0): ?>
                    <div class="mt-2" style="max-width: 280px;">
                        <?php 
                        $dist = [];
                        foreach ($ratingDistribution as $r) { $dist[$r->rating] = $r->cnt; }
                        ?>
                        <?php for ($s = 5; $s >= 1; $s--): ?>
                            <?php $cnt = (int)($dist[$s] ?? 0); ?>
                            <div class="d-flex align-items-center gap-2 small text-muted" style="line-height: 1.4;">
                                <span style="width: 14px;"><?php echo $s; ?></span>
                                <span style="color: #ffc107;">★</span>
                                <div class="progress flex-grow-1" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: <?php echo ($course->rating_count > 0) ? ($cnt / $course->rating_count * 100) : 0; ?>%;"></div>
                                </div>
                                <span style="width: 30px; text-align: left;"><?php echo $cnt; ?></span>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div>
        <?php
        $typeLabels = ['lecture' => 'محاضرات', 'summary' => 'ملخصات', 'assignment' => 'تكاليف', 'model' => 'نماذج', 'reference' => 'مراجع'];
        $typeIcons = ['lecture' => 'fa-book', 'summary' => 'fa-file-lines', 'assignment' => 'fa-clipboard-list', 'model' => 'fa-clipboard', 'reference' => 'fa-bookmark'];
        $typeColors = ['lecture' => 'primary', 'summary' => 'success', 'assignment' => 'warning', 'model' => 'info', 'reference' => 'secondary'];
        $grouped = [];
        foreach ($files as $file) {
            $t = $file->file_type ?? 'lecture';
            $grouped[$t][] = $file;
        }
        ?>
        <?php if (empty($files)): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                    <p>لا توجد ملفات لهذه المادة بعد</p>
                </div>
            </div>
        <?php else: ?>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <ul class="nav nav-tabs mb-0" id="fileTabs" role="tablist">
                    <?php $first = true; foreach ($typeLabels as $key => $label): if (empty($grouped[$key])) continue; ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $first ? 'active' : ''; ?>" id="tab-<?php echo $key; ?>" data-bs-toggle="tab" data-bs-target="#content-<?php echo $key; ?>" type="button" role="tab">
                                <i class="fas <?php echo $typeIcons[$key]; ?> ms-1"></i>
                                <?php echo $label; ?>
                                <span class="badge bg-<?php echo $typeColors[$key]; ?> ms-1"><?php echo count($grouped[$key]); ?></span>
                            </button>
                        </li>
                    <?php $first = false; endforeach; ?>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <small class="text-muted"><i class="fas fa-sort ms-1"></i>ترتيب:</small>
                    <a href="<?php echo url('/courses/' . escape($course->id) . '?sort=downloads'); ?>" class="btn btn-sm <?php echo ($currentSort ?? 'newest') === 'downloads' ? 'btn-primary' : 'btn-outline-secondary'; ?> rounded-pill px-3">الأكثر تحميلاً</a>
                </div>
            </div>
            <div class="tab-content" id="fileTabsContent">
                <?php $first = true; foreach ($typeLabels as $key => $label): if (empty($grouped[$key])) continue; ?>
                    <div class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>" id="content-<?php echo $key; ?>" role="tabpanel">
                        <!-- Modern Luxury Files Cards Grid -->
                        <div class="row g-3">
                            <?php 
                            $fileCounter = 1;
                            foreach ($grouped[$key] as $file): 
                                $fExt = strtolower($file->file_extension ?? '');
                                $isTextAssig  = ($fExt === 'text' || empty($file->file_path));
                                $isImageAssig = in_array($fExt, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
                                $isOfficeDoc  = in_array($fExt, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']);
                                $isPreviewable = !in_array($fExt, ['zip', 'rar', '7z', 'tar', 'gz']);

                                $extClass = match($fExt) {
                                    'pdf' => ['cls' => 'front-ext-pdf', 'icon' => 'fas fa-file-pdf'],
                                    'doc', 'docx' => ['cls' => 'front-ext-word', 'icon' => 'fas fa-file-word'],
                                    'xls', 'xlsx' => ['cls' => 'front-ext-excel', 'icon' => 'fas fa-file-excel'],
                                    'ppt', 'pptx' => ['cls' => 'front-ext-powerpoint', 'icon' => 'fas fa-file-powerpoint'],
                                    'jpg', 'jpeg', 'png', 'webp', 'gif', 'svg' => ['cls' => 'front-ext-image', 'icon' => 'fas fa-file-image'],
                                    'zip', 'rar', '7z' => ['cls' => 'front-ext-zip', 'icon' => 'fas fa-file-archive'],
                                    'text', 'txt' => ['cls' => 'front-ext-text', 'icon' => 'fas fa-align-right'],
                                    default => ['cls' => 'front-ext-default', 'icon' => 'fas fa-file-alt']
                                };

                                $formattedSize = !empty($file->file_size) ? format_file_size((int)$file->file_size) : '';
                            ?>
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="front-file-card">
                                        <!-- Top Row: Icon Box, Extension Tag, Serial Index, and Bookmark Button -->
                                        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="front-file-icon-box <?php echo $extClass['cls']; ?>" title="<?php echo strtoupper($fExt ?: 'FILE'); ?>">
                                                    <i class="<?php echo $extClass['icon']; ?>"></i>
                                                </div>
                                                <div>
                                                    <div class="d-flex align-items-center gap-1 mb-1">
                                                        <span class="badge bg-light text-dark border rounded-pill px-2 py-1 small fw-bold">
                                                            <?php echo strtoupper($fExt ?: 'FILE'); ?>
                                                        </span>
                                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1 small fw-bold">
                                                            #<?php echo $fileCounter++; ?>
                                                        </span>
                                                    </div>
                                                    <small class="text-muted d-block" style="font-size: 0.76rem;">
                                                        <i class="fas fa-calendar-alt ms-1"></i><?php echo escape(format_date($file->created_at ?? '', 'Y-m-d')); ?>
                                                    </small>
                                                </div>
                                            </div>
                                            
                                            <!-- Bookmark Button -->
                                            <button type="button" class="btn btn-outline-warning btn-sm rounded-circle toggle-bookmark-btn flex-shrink-0 shadow-sm" 
                                                    style="width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center;" 
                                                    data-file-id="<?php echo escape($file->id); ?>" 
                                                    title="حفظ للمفضلة">
                                                <i class="fas fa-bookmark"></i>
                                            </button>
                                        </div>

                                        <!-- File Title -->
                                        <h6 class="front-file-title mb-2 text-truncate-2" title="<?php echo escape($file->title); ?>" style="min-height: 2.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?php echo escape($file->title); ?>
                                        </h6>

                                        <!-- Doctor & Uploader Metadata -->
                                        <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                                            <?php if (!empty($file->doctor_name)): ?>
                                                <span class="front-meta-chip" title="أستاذ المادة">
                                                    <i class="fas fa-user-tie text-primary"></i>
                                                    <span><?php echo escape($file->doctor_name); ?></span>
                                                </span>
                                            <?php endif; ?>

                                            <?php if (!empty($formattedSize)): ?>
                                                <span class="front-meta-chip" title="حجم الملف">
                                                    <i class="fas fa-hdd text-info"></i>
                                                    <span><?php echo $formattedSize; ?></span>
                                                </span>
                                            <?php endif; ?>

                                            <span class="front-meta-chip" title="عدد مرات التحميل">
                                                <i class="fas fa-cloud-download-alt text-success"></i>
                                                <span><?php echo (int)($file->download_count ?? 0); ?> تحميل</span>
                                            </span>
                                        </div>

                                        <!-- Image Preview if image assignment/file -->
                                        <?php if ($isImageAssig && !empty($file->file_path)): ?>
                                            <div class="mb-3 text-center rounded-3 overflow-hidden border p-1 bg-light position-relative" style="max-height: 180px;">
                                                <img src="<?php echo asset($file->file_path); ?>" class="img-fluid rounded-2" style="max-height: 170px; object-fit: contain; cursor: pointer;" onclick="openImageModal('<?php echo asset($file->file_path); ?>', '<?php echo escape($file->title); ?>')" alt="<?php echo escape($file->title); ?>">
                                                <button type="button" class="btn btn-sm btn-dark bg-opacity-75 rounded-pill position-absolute bottom-0 start-50 translate-middle-x mb-2 px-3 shadow" onclick="openImageModal('<?php echo asset($file->file_path); ?>', '<?php echo escape($file->title); ?>')">
                                                    <i class="fas fa-search-plus ms-1"></i>تكبير الصورة
                                                </button>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Text instructions if text assignment or description -->
                                        <?php if (!empty($file->description)): ?>
                                            <div class="p-3 rounded-3 border bg-light bg-opacity-50 mb-3 flex-grow-1" style="font-size: 0.86rem; line-height: 1.6; max-height: 120px; overflow-y: auto; color: inherit;">
                                                <?php echo nl2br(escape($file->description)); ?>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Card Footer Actions -->
                                        <div class="d-flex align-items-center justify-content-between pt-3 border-top gap-2 mt-auto">
                                            <div class="d-flex align-items-center gap-1 flex-grow-1">
                                                <?php if ($isImageAssig && !empty($file->file_path)): ?>
                                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold flex-grow-1" onclick="openImageModal('<?php echo asset($file->file_path); ?>', '<?php echo escape($file->title); ?>')">
                                                        <i class="fas fa-eye ms-1"></i>معاينة
                                                    </button>
                                                <?php elseif ($isPreviewable && !$isOfficeDoc && !$isTextAssig && !empty($file->file_path)): ?>
                                                    <a href="<?php echo url('/files/' . escape($file->id) . '/preview'); ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold flex-grow-1">
                                                        <i class="fas fa-eye ms-1"></i>معاينة
                                                    </a>
                                                <?php elseif ($isPreviewable && $isOfficeDoc && !empty($file->file_path)): ?>
                                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold flex-grow-1 preview-office-btn"
                                                        data-title="<?php echo escape($file->title); ?>"
                                                        data-ext="<?php echo escape(strtoupper($fExt)); ?>"
                                                        data-download="<?php echo url('/files/' . escape($file->id) . '/download'); ?>">
                                                        <i class="fas fa-eye ms-1"></i>معاينة
                                                    </button>
                                                <?php elseif ($isTextAssig && !empty($file->description)): ?>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold flex-grow-1" onclick="copyAssignmentText(<?php echo htmlspecialchars(json_encode($file->description), ENT_QUOTES, 'UTF-8'); ?>)" title="نسخ نص التكليف">
                                                        <i class="fas fa-copy ms-1"></i>نسخ النص
                                                    </button>
                                                <?php endif; ?>

                                                <a href="<?php echo url('/files/' . escape($file->id) . '/download'); ?>" class="btn btn-success btn-sm rounded-pill px-3 fw-bold flex-grow-1 d-inline-flex align-items-center justify-content-center gap-1 shadow-sm" data-confirm-download="1" data-title="<?php echo escape($file->title); ?>">
                                                    <i class="fas fa-download ms-1"></i>
                                                    <span><?php echo $isTextAssig ? 'تنزيل كنص' : 'تحميل'; ?></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php $first = false; endforeach; ?>
            </div>
            <?php if ($totalPages > 1): ?>
                <nav class="mt-3">
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo url('/courses/' . escape($course->id) . '?sort=' . urlencode($currentSort ?? 'newest') . '&amp;page=' . $i); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div id="ratingToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="fas fa-check-circle fa-lg"></i>
                <span id="toastMessage">تم تسجيل تقييمك بنجاح</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var container = document.querySelector('.stars-container');
    if (!container) return;
    var stars = container.querySelectorAll('.star-rating');
    var courseId = container.getAttribute('data-course-id');
    var labels = ['', 'ضعيف', 'مقبول', 'جيد', 'جيد جداً', 'ممتاز'];
    var storedKey = 'rated_' + courseId;

    var userRating = localStorage.getItem(storedKey);
    if (userRating) {
        var badge = document.getElementById('userRatingBadge');
        if (badge) {
            badge.classList.remove('d-none');
            document.getElementById('userRatingText').textContent = 'تقييمك: ' + labels[parseInt(userRating)] + ' (' + userRating + ' نجوم)';
        }
        stars.forEach(function(s) { s.style.cursor = 'default'; });
    }

    function showToast(msg) {
        var el = document.getElementById('ratingToast');
        document.getElementById('toastMessage').textContent = msg;
        var toast = new bootstrap.Toast(el, { delay: 3000 });
        toast.show();
    }

    function setStars(value) {
        stars.forEach(function(s) {
            var val = parseInt(s.getAttribute('data-value'));
            s.style.color = val <= value && value > 0 ? '#ffc107' : '#bbb';
        });
    }

    stars.forEach(function(star) {
        star.addEventListener('mouseenter', function() {
            if (userRating) return;
            var hoverVal = parseInt(this.getAttribute('data-value'));
            stars.forEach(function(s) {
                var val = parseInt(s.getAttribute('data-value'));
                s.style.color = val <= hoverVal ? '#ffc107' : '#bbb';
            });
        });
        star.addEventListener('mouseleave', function() {
            if (userRating) return;
            var currentAvg = parseFloat(container.getAttribute('data-current') || '0');
            setStars(Math.round(currentAvg));
        });
        star.addEventListener('click', function() {
            if (userRating) {
                showToast('لقد قيمت هذه المادة من قبل بمقدار ' + userRating + ' نجوم');
                return;
            }
            var value = parseInt(this.getAttribute('data-value'));
            var formData = new FormData();
            formData.append('course_id', courseId);
            formData.append('rating', value);

            fetch('<?php echo url('/courses/' . escape($course->id) . '/rate'); ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    localStorage.setItem(storedKey, value);
                    userRating = value;
                    var avg = parseFloat(data.avg_rating);
                    container.setAttribute('data-current', avg);
                    setStars(Math.round(avg));
                    document.getElementById('ratingInfo').textContent =
                        data.avg_rating + ' / 5 (' + data.rating_count + ' تقييم)';
                    var badge = document.getElementById('userRatingBadge');
                    if (badge) {
                        badge.classList.remove('d-none');
                        document.getElementById('userRatingText').textContent = 'تقييمك: ' + labels[value] + ' (' + value + ' نجوم)';
                    }
                    stars.forEach(function(s) { s.style.cursor = 'default'; });
                    showToast('شكراً لك! تم تسجيل تقييمك (' + labels[value] + ')');
                } else {
                    showToast(data.message);
                }
            })
            .catch(function() {
                showToast('حدث خطأ أثناء تسجيل التقييم');
            });
        });
    });

    // Bookmark toggle handler
    document.querySelectorAll('.toggle-bookmark-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var fileId = this.getAttribute('data-file-id');
            var buttonEl = this;
            var formData = new FormData();
            formData.append('file_id', fileId);

            fetch('<?php echo url('/api/bookmarks/toggle'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    if (data.bookmarked) {
                        buttonEl.classList.remove('btn-outline-warning');
                        buttonEl.classList.add('btn-warning', 'text-dark');
                        showToast(data.message || 'تم حفظ الملف للمفضلة ⭐');
                    } else {
                        buttonEl.classList.remove('btn-warning', 'text-dark');
                        buttonEl.classList.add('btn-outline-warning');
                        showToast(data.message || 'تم إزالة الملف من المفضلة');
                    }
                } else {
                    showToast(data.message || 'يرجى تسجيل الدخول أولاً لإضافة الملف للمفضلة');
                }
            })
            .catch(function() {
                showToast('حدث خطأ أثناء الاتصال بالخادم');
            });
        });
    });
});

// Image Viewer Modal & Copy Text Helpers
function openImageModal(imgSrc, title) {
    var modalEl = document.getElementById('assignmentImageModal');
    if (!modalEl) return;
    document.getElementById('assignmentModalImg').src = imgSrc;
    document.getElementById('assignmentModalTitle').textContent = title || 'معاينة صورة التكليف';
    document.getElementById('assignmentModalDownload').href = imgSrc;
    var modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function copyAssignmentText(text) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(function() {
        var el = document.getElementById('ratingToast');
        if (el) {
            document.getElementById('toastMessage').textContent = 'تم نسخ نص التكليف بنجاح إلى الحافظة 📋';
            var toast = new bootstrap.Toast(el, { delay: 3000 });
            toast.show();
        }
    }).catch(function() {
        alert('تعذر النسخ التلقائي، يمكنك تحديد النص ونسخه يدوياً.');
    });
}
</script>

<!-- Assignment Image Viewer Modal -->
<div class="modal fade" id="assignmentImageModal" tabindex="-1" aria-labelledby="assignmentModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-dark text-white py-3">
                <h6 class="modal-title fw-bold" id="assignmentModalTitle"><i class="fas fa-image ms-2 text-warning"></i>معاينة صورة التكليف</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2 text-center bg-black bg-opacity-10">
                <img src="" id="assignmentModalImg" class="img-fluid rounded-3 shadow" style="max-height: 75vh; object-fit: contain;" alt="صورة التكليف">
            </div>
            <div class="modal-footer border-0 bg-light py-2">
                <a href="#" id="assignmentModalDownload" download class="btn btn-primary rounded-pill px-4 btn-sm fw-bold">
                    <i class="fas fa-download ms-1"></i>تنزيل الصورة
                </a>
                <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

