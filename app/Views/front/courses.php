<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h3 class="mb-0 fw-bold"><i class="fas fa-book ms-2 text-primary"></i>المواد الدراسية</h3>
    <div class="d-flex gap-2">
        <a href="<?php echo url('/search'); ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
            <i class="fas fa-search ms-1"></i>بحث متقدم
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="<?php echo url('/courses'); ?>" class="row g-3 align-items-end">
            <div class="col-12 col-md-5 col-lg-4">
                <label for="major_id" class="form-label fw-bold small text-muted">تصفية حسب التخصص:</label>
                <select name="major_id" id="major_id" class="form-select rounded-3" onchange="this.form.submit()">
                    <option value="">جميع التخصصات</option>
                    <?php foreach ($majors ?? [] as $major): ?>
                        <option value="<?php echo escape($major->id); ?>" <?php echo (isset($_GET['major_id']) && $_GET['major_id'] == $major->id) ? 'selected' : ''; ?>>
                            <?php echo escape($major->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive rounded-4 shadow-sm">
    <table class="table table-hover align-middle bg-white mb-0">
        <thead class="table-primary">
            <tr>
                <th class="py-3 text-nowrap">اسم المادة</th>
                <th class="py-3 text-nowrap">التخصص</th>
                <th class="py-3 text-nowrap">المستوى</th>
                <th class="py-3 text-nowrap">الفصل</th>
                <th class="py-3 text-nowrap text-center" style="width: 100px;">الإجراء</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($courses)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="fas fa-book-open fa-2x mb-2 d-block opacity-50"></i>
                        لا توجد مواد دراسية متاحة
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td class="fw-bold text-dark text-nowrap py-3">
                            <i class="fas fa-book-open text-primary ms-2"></i><?php echo escape($course->name); ?>
                        </td>
                        <td class="text-nowrap py-3">
                            <span class="badge rounded-pill px-2 px-md-3 py-1 py-md-2" style="background: rgba(79, 70, 229, 0.12) !important; color: #4f46e5 !important; font-weight: 600;">
                                <i class="<?php echo get_major_icon($course->major_name ?? ''); ?> ms-1"></i><?php echo escape($course->major_name ?? 'عام'); ?>
                            </span>
                        </td>
                        <td class="text-nowrap py-3">
                            <span class="badge rounded-pill px-2 px-md-3 py-1 py-md-2" style="background: rgba(6, 182, 212, 0.12) !important; color: #0891b2 !important; font-weight: 600;">
                                <i class="fas fa-layer-group ms-1"></i><?php echo escape($course->level_name ?? '-'); ?>
                            </span>
                        </td>
                        <td class="text-nowrap py-3">
                            <span class="badge rounded-pill px-2 px-md-3 py-1 py-md-2" style="background: rgba(168, 85, 247, 0.12) !important; color: #9333ea !important; font-weight: 600;">
                                <i class="fas fa-calendar-alt ms-1"></i><?php echo escape($course->semester_name ?? '-'); ?>
                            </span>
                        </td>
                        <td class="text-nowrap text-center py-3">
                            <a href="<?php echo url('/courses/' . escape($course->id)); ?>" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold text-nowrap shadow-sm">
                                <i class="fas fa-eye ms-1"></i>تفاصيل
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>