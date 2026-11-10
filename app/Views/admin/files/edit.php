<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-edit ms-2 text-warning"></i>تعديل المحتوى / التكليف</h4>
        <p class="text-muted small mb-0">تعديل بيانات الملف أو صورة التكليف أو النص المطلوب</p>
    </div>
    <div>
        <a href="<?php echo url('/admin/files'); ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-right ms-1"></i>عودة
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <form action="<?php echo url('/admin/files/' . escape($file->id) . '/update'); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo escape($file->id); ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="course_id" class="form-label fw-bold">المادة الدراسية <span class="text-danger">*</span></label>
                    <select class="form-select rounded-3" id="course_id" name="course_id" required>
                        <option value="">اختر المادة</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo escape($course->id); ?>" <?php echo (old('course_id', $file->course_id) == $course->id) ? 'selected' : ''; ?>>
                                <?php echo escape($course->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="file_type" class="form-label fw-bold">تصنيف المحتوى <span class="text-danger">*</span></label>
                    <select class="form-select rounded-3" id="file_type" name="file_type" required>
                        <option value="">اختر التصنيف</option>
                        <option value="lecture" <?php echo (old('file_type', $file->file_type ?? '') === 'lecture') ? 'selected' : ''; ?>>📚 محاضرة</option>
                        <option value="summary" <?php echo (old('file_type', $file->file_type ?? '') === 'summary') ? 'selected' : ''; ?>>📝 ملخص</option>
                        <option value="assignment" <?php echo (old('file_type', $file->file_type ?? '') === 'assignment') ? 'selected' : ''; ?>>📋 تكليف (صورة، ملف، أو نص مباشر)</option>
                        <option value="model" <?php echo (old('file_type', $file->file_type ?? '') === 'model') ? 'selected' : ''; ?>>📑 نماذج اختبارات</option>
                        <option value="reference" <?php echo (old('file_type', $file->file_type ?? '') === 'reference') ? 'selected' : ''; ?>>📖 مرجع / كتاب</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="doctor_name" class="form-label fw-bold">اسم الدكتور / المدرس</label>
                    <input type="text" class="form-control rounded-3" id="doctor_name" name="doctor_name" value="<?php echo escape(old('doctor_name', $file->doctor_name ?? '')); ?>" placeholder="مثال: د. محمد علي">
                </div>
                <div class="col-md-6">
                    <label for="title" class="form-label fw-bold">العنوان <span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-3" id="title" name="title" value="<?php echo escape(old('title', $file->title)); ?>" required>
                </div>
                <div class="col-12">
                    <label for="file" class="form-label fw-bold">تغيير الملف المرفق (اترك فارغاً للاحتفاظ بالملف الحالي)</label>
                    <?php if (!empty($file->file_path) && in_array(strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'])): ?>
                        <div class="mb-2">
                            <img src="<?php echo asset($file->file_path); ?>" class="img-thumbnail rounded-3" style="max-height: 120px;" alt="معاينة الصورة">
                        </div>
                    <?php elseif ($file->file_extension === 'text' || empty($file->file_path)): ?>
                        <div class="mb-2">
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fas fa-align-right ms-1"></i>هذا تكليف نصي مباشر</span>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control rounded-3" id="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.7z,.txt,.jpg,.jpeg,.png,.webp,.gif,.svg">
                </div>
                <div class="col-12">
                    <label for="description" class="form-label fw-bold">الوصف والملاحظات</label>
                    <textarea class="form-control rounded-3" id="description" name="description" rows="4"><?php echo escape(old('description', $file->description ?? '')); ?></textarea>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" value="1" <?php echo (old('is_approved', $file->is_approved ?? '1')) ? 'checked' : ''; ?>>
                        <label class="form-check-label fw-bold" for="is_approved">معتمد ومنشور للطلاب</label>
                    </div>
                </div>
                <div class="col-12 pt-2">
                    <button type="submit" class="majors-btn-add shadow-sm">
                        <i class="fas fa-save ms-1"></i>حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>