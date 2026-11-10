<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-plus-circle ms-2 text-primary"></i>إضافة ملف أو محتوى دراسي جديد</h4>
        <p class="text-muted small mb-0">رفع محاضرة، ملخص، نموذج اختبار، مرجع، أو تكليف دراسي</p>
    </div>
    <div>
        <a href="<?php echo url('/admin/files'); ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-right ms-1"></i>عودة
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <form action="<?php echo url('/admin/files/store'); ?>" method="POST" enctype="multipart/form-data" id="fileUploadForm">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="course_id" class="form-label fw-bold">المادة الدراسية <span class="text-danger">*</span></label>
                    <select class="form-select rounded-3" id="course_id" name="course_id" required>
                        <option value="">اختر المادة</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo escape($course->id); ?>" <?php echo old('course_id') == $course->id ? 'selected' : ''; ?>>
                                <?php echo escape($course->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="file_type" class="form-label fw-bold">تصنيف المحتوى <span class="text-danger">*</span></label>
                    <select class="form-select rounded-3" id="file_type" name="file_type" required>
                        <option value="">اختر التصنيف</option>
                        <option value="lecture" <?php echo old('file_type') === 'lecture' ? 'selected' : ''; ?>>📚 محاضرة</option>
                        <option value="summary" <?php echo old('file_type') === 'summary' ? 'selected' : ''; ?>>📝 ملخص</option>
                        <option value="assignment" <?php echo old('file_type') === 'assignment' ? 'selected' : ''; ?>>📋 تكليف (صورة، ملف، أو نص مباشر)</option>
                        <option value="model" <?php echo old('file_type') === 'model' ? 'selected' : ''; ?>>📑 نماذج اختبارات</option>
                        <option value="reference" <?php echo old('file_type') === 'reference' ? 'selected' : ''; ?>>📖 مرجع / كتاب</option>
                    </select>
                </div>
                
                <!-- Notice Banner for Assignments -->
                <div class="col-12 d-none" id="assignmentHelpNotice">
                    <div class="p-3 rounded-3 border d-flex align-items-center gap-3" style="background: rgba(245, 158, 11, 0.08); border-color: rgba(245, 158, 11, 0.3) !important;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-warning flex-shrink-0" style="width: 36px; height: 36px; background: rgba(245, 158, 11, 0.15); font-size: 1.1rem;">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div class="small">
                            <strong class="text-dark d-block">خيارات مرنة لإضافة التكاليف:</strong>
                            <span class="text-muted">يمكنك رفع <strong>صورة للتكليف</strong> (PNG, JPG, WEBP)، أو <strong>ملف مستند</strong> (PDF, Word)، أو <strong>كتابة نص التكليف والمطلوب مباشرة</strong> في حقل الوصف بالأسفل بدون الحاجة لرفع ملف!</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="doctor_name" class="form-label fw-bold">اسم الدكتور / المدرس</label>
                    <input type="text" class="form-control rounded-3" id="doctor_name" name="doctor_name" value="<?php echo escape(old('doctor_name')); ?>" placeholder="مثال: د. محمد علي">
                </div>
                <div class="col-md-6">
                    <label for="title" class="form-label fw-bold" id="titleLabel">عنوان الملف <span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-3" id="title" name="title" value="<?php echo escape(old('title')); ?>" placeholder="مثال: المحاضرة الأولى - مقدمة عامة" required>
                </div>
                
                <div class="col-12">
                    <label for="file" class="form-label fw-bold" id="fileInputLabel">
                        الملف المرفق <span class="text-danger" id="fileRequiredStar">*</span>
                    </label>
                    <input type="file" class="form-control rounded-3" id="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.7z,.txt,.jpg,.jpeg,.png,.webp,.gif,.svg">
                    <small class="text-muted d-block mt-1" id="fileInputHelp">
                        <i class="fas fa-info-circle me-1 text-primary"></i>يدعم جميع المستندات والصور (PDF, Word, Excel, PowerPoint, JPG, PNG, WEBP) بحد أقصى <strong>500MB</strong>.
                    </small>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label fw-bold" id="descLabel">الوصف والملاحظات</label>
                    <textarea class="form-control rounded-3" id="description" name="description" rows="4" placeholder="اكتب نبذة أو ملاحظات توضيحية عن الملف..."><?php echo escape(old('description')); ?></textarea>
                    <small class="text-muted d-block mt-1" id="descHelp">يمكنك إضافة تفاصيل أو محاور الملف أو أي تعليمات للطلاب هنا.</small>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved" value="1" <?php echo (old('is_approved') !== '0' && old('is_approved') !== 0) ? 'checked' : ''; ?>>
                        <label class="form-check-label fw-bold" for="is_approved">اعتماد ونشر للطلاب مباشرة</label>
                    </div>
                </div>

                <div class="col-12 pt-2">
                    <button type="submit" class="majors-btn-add shadow-sm">
                        <i class="fas fa-save ms-1"></i>حفظ ونشر
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var fileTypeSelect  = document.getElementById('file_type');
    var fileInput       = document.getElementById('file');
    var fileReqStar     = document.getElementById('fileRequiredStar');
    var assignmentNotice= document.getElementById('assignmentHelpNotice');
    var descTextarea    = document.getElementById('description');
    var descLabel       = document.getElementById('descLabel');
    var descHelp        = document.getElementById('descHelp');
    var titleLabel      = document.getElementById('titleLabel');
    var titleInput      = document.getElementById('title');
    var fileInputLabel  = document.getElementById('fileInputLabel');

    var typeConfigs = {
        lecture: {
            titleLabel: 'عنوان المحاضرة <span class="text-danger">*</span>',
            titlePlaceholder: 'مثال: المحاضرة 1 - مقدمة عامة',
            fileLabel: 'ملف المحاضرة <span class="text-danger">*</span>',
            descLabel: 'محاور المحاضرة والملاحظات',
            descPlaceholder: 'اكتب محاور المحاضرة أو أي ملاحظات توضيحية للطلاب...',
            descHelp: 'يمكنك كتابة نبذة عن مواضيع المحاضرة هنا.',
            isAssignment: false
        },
        summary: {
            titleLabel: 'عنوان الملخص <span class="text-danger">*</span>',
            titlePlaceholder: 'مثال: ملخص شامل للفصل الأول',
            fileLabel: 'ملف الملخص <span class="text-danger">*</span>',
            descLabel: 'ملاحظات الملخص',
            descPlaceholder: 'اكتب ملاحظات حول هذا الملخص والفصول التي يغطيها...',
            descHelp: 'توضيح الفصول أو الوحدات التي يشملها هذا الملخص.',
            isAssignment: false
        },
        assignment: {
            titleLabel: 'عنوان التكليف <span class="text-danger">*</span>',
            titlePlaceholder: 'مثال: تكليف البحث عن الدوال الرياضية وتطبيقاتها',
            fileLabel: 'الملف أو صورة التكليف <span class="text-muted small fw-normal">(اختياري في حال التكليف النصي)</span>',
            descLabel: '📝 نص التكليف والمطلوب من الطلاب <span class="badge bg-warning text-dark ms-1">مهم في حال التكليف النصي</span>',
            descPlaceholder: 'اكتب نص التكليف المطلوب من الطلاب بالتفصيل وموعد التسليم وشروط الحل...',
            descHelp: 'إذا كان التكليف نصياً (مثل: "ابحث عن موضوع كذا واكتب تقريراً")، اكتب التفاصيل هنا دون الحاجة لرفع ملف.',
            isAssignment: true
        },
        model: {
            titleLabel: 'عنوان النموذج / الاختبار <span class="text-danger">*</span>',
            titlePlaceholder: 'مثال: نموذج اختبار نهائي مع الحل لعام 2024',
            fileLabel: 'ملف نموذج الاختبار <span class="text-danger">*</span>',
            descLabel: 'ملاحظات وتفاصيل النموذج',
            descPlaceholder: 'اكتب أي ملاحظات حول النموذج (مثل: اختبار نصفي/نهائي، العام الدراسي)...',
            descHelp: 'توضيح نوع الاختبار وسنة الامتحان إن وجدت.',
            isAssignment: false
        },
        reference: {
            titleLabel: 'عنوان المرجع / الكتاب <span class="text-danger">*</span>',
            titlePlaceholder: 'مثال: مرجع هندسة البرمجيات - الطبعة التاسعة',
            fileLabel: 'ملف الكتاب أو المرجع <span class="text-danger">*</span>',
            descLabel: 'نبذة عن المرجع / الكتاب',
            descPlaceholder: 'اكتب نبذة عن مؤلف الكتاب ومحتواه وأهميته للمادة...',
            descHelp: 'معلومات عن المؤلف أو الفصول المقررة من الكتاب.',
            isAssignment: false
        }
    };

    function updateFormMode() {
        var selectedType = fileTypeSelect.value;
        var config = typeConfigs[selectedType];

        if (config) {
            titleLabel.innerHTML = config.titleLabel;
            titleInput.placeholder = config.titlePlaceholder;
            descLabel.innerHTML = config.descLabel;
            descTextarea.placeholder = config.descPlaceholder;
            descHelp.textContent = config.descHelp;

            if (config.isAssignment) {
                assignmentNotice.classList.remove('d-none');
                fileReqStar.classList.add('d-none');
                fileInput.removeAttribute('required');
                fileInputLabel.innerHTML = config.fileLabel;
            } else {
                assignmentNotice.classList.add('d-none');
                fileReqStar.classList.remove('d-none');
                fileInput.setAttribute('required', 'required');
                fileInputLabel.innerHTML = config.fileLabel;
            }
        } else {
            // Default generic fallback
            titleLabel.innerHTML = 'عنوان الملف <span class="text-danger">*</span>';
            titleInput.placeholder = 'مثال: المحاضرة الأولى - مقدمة عامة';
            descLabel.innerHTML = 'الوصف والملاحظات';
            descTextarea.placeholder = 'اكتب نبذة أو ملاحظات توضيحية عن الملف...';
            descHelp.textContent = 'يمكنك إضافة تفاصيل أو محاور الملف أو أي تعليمات للطلاب هنا.';
            assignmentNotice.classList.add('d-none');
            fileReqStar.classList.remove('d-none');
            fileInput.setAttribute('required', 'required');
            fileInputLabel.innerHTML = 'الملف المرفق <span class="text-danger">*</span>';
        }
    }

    if (fileTypeSelect) {
        fileTypeSelect.addEventListener('change', updateFormMode);
        updateFormMode();
    }

    if (descTextarea) {
        descTextarea.addEventListener('input', function() {
            if (descTextarea.value.trim().length > 0 && fileTypeSelect.value === 'assignment') {
                fileInput.removeAttribute('required');
                fileReqStar.classList.add('d-none');
            }
        });
    }
});
</script>
