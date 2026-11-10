<?php
$flatSettings = [];
foreach ($settings as $group => $items) {
    foreach ($items as $s) {
        $flatSettings[$s->setting_key] = $s;
    }
}

$siteName        = $flatSettings['site_name']->setting_value ?? 'نظام إدارة الكلية';
$univName        = $flatSettings['university_name']->setting_value ?? 'جامعة صنعاء';
$primaryColor    = $flatSettings['primary_color']->setting_value ?? '#4f46e5';
$secondaryColor  = $flatSettings['secondary_color']->setting_value ?? '#06b6d4';
$maintenanceMode = (bool)($flatSettings['maintenance_mode']->setting_value ?? 0);
$collegeLogo     = $flatSettings['college_logo']->setting_value ?? '';
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="majors-stat-icon icon-primary" style="width: 52px; height: 52px; border-radius: 16px; font-size: 1.4rem;">
            <i class="fas fa-sliders-h"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1" style="color:var(--dark);">إعدادات النظام والمنصة</h4>
            <p class="text-muted small mb-0">تخصيص الهوية البصرية، بيانات الكلية، روابط التواصل، وسياسات الأمان</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="submit" form="settingsMasterForm" class="majors-btn-add shadow-sm">
            <i class="fas fa-save ms-1"></i>حفظ كافة الإعدادات
        </button>
    </div>
</div>

<!-- Quick Statistics Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-primary">
                <i class="fas fa-palette"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle border" style="width: 16px; height: 16px; background: <?php echo escape($primaryColor); ?>;"></span>
                    <span class="rounded-circle border" style="width: 16px; height: 16px; background: <?php echo escape($secondaryColor); ?>;"></span>
                </div>
                <div class="majors-stat-lbl mt-1">الهوية البصرية</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon <?php echo $maintenanceMode ? 'icon-warning' : 'icon-success'; ?>">
                <i class="fas <?php echo $maintenanceMode ? 'fa-tools' : 'fa-check-shield'; ?>"></i>
            </div>
            <div>
                <div class="majors-stat-val" style="font-size:1.1rem;"><?php echo $maintenanceMode ? 'قيد الصيانة' : 'المنصة نشطة'; ?></div>
                <div class="majors-stat-lbl">حالة النظام</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-info">
                <i class="fas fa-university"></i>
            </div>
            <div>
                <div class="majors-stat-val" style="font-size:1.05rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:140px;" title="<?php echo escape($univName); ?>">
                    <?php echo escape($univName); ?>
                </div>
                <div class="majors-stat-lbl">الجامعة المعتمدة</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="majors-stat-card">
            <div class="majors-stat-icon icon-warning">
                <i class="fas fa-code-branch"></i>
            </div>
            <div>
                <div class="majors-stat-val" style="font-size:1.1rem;">v2.5.0 Pro</div>
                <div class="majors-stat-lbl">إصدار المنصة</div>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Tab Bar -->
<div class="settings-tab-bar">
    <button type="button" class="settings-tab-btn active" data-tab="tab-general">
        <i class="fas fa-university"></i>الهوية العامة والكلية
    </button>
    <button type="button" class="settings-tab-btn" data-tab="tab-theme">
        <i class="fas fa-palette"></i>المظهر والألوان
    </button>
    <button type="button" class="settings-tab-btn" data-tab="tab-files">
        <i class="fas fa-folder-open"></i>الملفات والتحميل
    </button>
    <button type="button" class="settings-tab-btn" data-tab="tab-social">
        <i class="fas fa-share-alt"></i>روابط التواصل
    </button>
    <button type="button" class="settings-tab-btn" data-tab="tab-security">
        <i class="fas fa-shield-alt"></i>الأمان والربط الخارجي
    </button>
    <button type="button" class="settings-tab-btn" data-tab="tab-developer">
        <i class="fas fa-code"></i>المطور ومعلومات النظام
    </button>
</div>

<!-- Master Settings Form -->
<form id="settingsMasterForm" action="<?php echo url('/admin/settings/update'); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <!-- TAB 1: General & University Info -->
    <div class="settings-tab-pane" id="tab-general">
        <div class="settings-section-card">
            <div class="settings-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="settings-section-icon" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5;">
                        <i class="fas fa-university"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">بيانات الكلية والهوية العامة</h5>
                        <small class="text-muted">الاسم الرسمي للجامعة والكلية والوصف التعريفي والشعار</small>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <!-- Site Name -->
                <?php if (isset($flatSettings['site_name'])): $s = $flatSettings['site_name']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-globe text-primary"></i>اسم الموقع / المنصة:
                        </label>
                        <input type="text" class="form-control border-0 shadow-none bg-transparent fw-bold" 
                               name="settings[<?php echo $s->id; ?>]" 
                               value="<?php echo escape($s->setting_value ?? ''); ?>"
                               placeholder="مثال: منصة كلية الحاسوب - جامعة صنعاء">
                        <small class="text-muted d-block mt-1">يظهر في رأس الصفحات وتبويب المتصفح</small>
                    </div>
                </div>
                <?php endif; ?>

                <!-- University Name -->
                <?php if (isset($flatSettings['university_name'])): $s = $flatSettings['university_name']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-graduation-cap text-primary"></i>اسم الجامعة / الصرح:
                        </label>
                        <input type="text" class="form-control border-0 shadow-none bg-transparent fw-bold" 
                               name="settings[<?php echo $s->id; ?>]" 
                               value="<?php echo escape($s->setting_value ?? ''); ?>"
                               placeholder="مثال: جامعة صنعاء">
                        <small class="text-muted d-block mt-1">اسم الجامعة المعتمدة التي تتبع لها الكلية</small>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Site Description -->
                <?php if (isset($flatSettings['site_description'])): $s = $flatSettings['site_description']; ?>
                <div class="col-12">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-align-right text-primary"></i>وصف المنصة والكلية:
                        </label>
                        <textarea class="form-control border-0 shadow-none bg-transparent" 
                                  name="settings[<?php echo $s->id; ?>]" 
                                  rows="3" 
                                  placeholder="اكتب نبذة تعريفية مختصرة عن المنصة والخدمات التي تقدمها للطلاب..."><?php echo escape($s->setting_value ?? ''); ?></textarea>
                    </div>
                </div>
                <?php endif; ?>

                <!-- College Logo Upload -->
                <?php if (isset($flatSettings['college_logo'])): $s = $flatSettings['college_logo']; ?>
                <div class="col-12">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1 mb-3">
                            <i class="fas fa-image text-primary"></i>شعار الكلية / المنصة (Logo):
                        </label>
                        <div class="d-flex align-items-center gap-4 flex-wrap">
                            <div class="settings-logo-preview-box" id="logoPreviewContainer">
                                <?php if (!empty($s->setting_value)): ?>
                                    <img src="<?php echo asset(escape($s->setting_value)); ?>" id="logoPreviewImg" alt="شعار الكلية">
                                <?php else: ?>
                                    <i class="fas fa-image fa-2x text-muted" id="logoPlaceholderIcon"></i>
                                    <img src="" id="logoPreviewImg" class="d-none" alt="شعار الكلية">
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" class="form-control rounded-pill" name="settings[<?php echo $s->id; ?>]" id="logoFileInput" accept="image/*">
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle ms-1"></i>الصيغ الموصى بها: PNG شفاف أو WEBP بأبعاد مربعة أو أفقية (الحد الأقصى: 2 ميجابايت).
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- TAB 2: Theme & Styling -->
    <div class="settings-tab-pane d-none" id="tab-theme">
        <div class="settings-section-card">
            <div class="settings-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="settings-section-icon" style="background: rgba(14, 165, 233, 0.1); color: #0284c7;">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">المظهر والهوية اللونية</h5>
                        <small class="text-muted">التحكم في لوحة الألوان الأساسية والثانوية لعناصر النظام والأزرار</small>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Primary Color -->
                <?php if (isset($flatSettings['primary_color'])): $s = $flatSettings['primary_color']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1 mb-2">
                            <i class="fas fa-fill-drip text-primary"></i>اللون الأساسي (Primary Color):
                        </label>
                        <div class="settings-color-swatch-box">
                            <input type="color" class="form-control form-control-color border-0 p-0" 
                                   id="primaryColorPicker"
                                   name="settings[<?php echo $s->id; ?>]" 
                                   value="<?php echo escape($s->setting_value ?? '#4f46e5'); ?>" 
                                   style="width: 44px; height: 44px; cursor: pointer; border-radius: 10px;">
                            <input type="text" class="form-control border-0 bg-transparent fw-bold shadow-none font-monospace" 
                                   id="primaryColorInput"
                                   name="settings_display[<?php echo $s->id; ?>]" 
                                   value="<?php echo escape($s->setting_value ?? '#4f46e5'); ?>">
                        </div>
                        <small class="text-muted d-block mt-2">يُستخدم في الأزرار الرئيسية، الهيدر، والشارات الهامة</small>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Secondary Color -->
                <?php if (isset($flatSettings['secondary_color'])): $s = $flatSettings['secondary_color']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1 mb-2">
                            <i class="fas fa-brush text-info"></i>اللون الثانوي (Secondary Color):
                        </label>
                        <div class="settings-color-swatch-box">
                            <input type="color" class="form-control form-control-color border-0 p-0" 
                                   id="secondaryColorPicker"
                                   name="settings[<?php echo $s->id; ?>]" 
                                   value="<?php echo escape($s->setting_value ?? '#06b6d4'); ?>" 
                                   style="width: 44px; height: 44px; cursor: pointer; border-radius: 10px;">
                            <input type="text" class="form-control border-0 bg-transparent fw-bold shadow-none font-monospace" 
                                   id="secondaryColorInput"
                                   name="settings_display[<?php echo $s->id; ?>]" 
                                   value="<?php echo escape($s->setting_value ?? '#06b6d4'); ?>">
                        </div>
                        <small class="text-muted d-block mt-2">يُستخدم في التدرجات، الشارات الفرعية، واللمسات الجمالية</small>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Live Button & Badge Preview Box -->
                <div class="col-12">
                    <div class="p-4 rounded-4 border" style="background: var(--light, #f8fafc);">
                        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-eye ms-2 text-primary"></i>معاينة مباشرة لعناصر الهوية اللونية:</h6>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <button type="button" id="previewPrimaryBtn" class="btn rounded-pill px-4 fw-bold text-white shadow-sm" style="background: <?php echo escape($primaryColor); ?>;">
                                <i class="fas fa-check-circle ms-1"></i>زر رئيسي أساسي
                            </button>
                            <button type="button" id="previewSecondaryBtn" class="btn rounded-pill px-4 fw-bold text-white shadow-sm" style="background: <?php echo escape($secondaryColor); ?>;">
                                <i class="fas fa-star ms-1"></i>زر ثانوي فرعي
                            </button>
                            <span id="previewBadge" class="badge rounded-pill px-3 py-2 fw-bold" style="background: rgba(79, 70, 229, 0.15); color: <?php echo escape($primaryColor); ?>;">
                                شارة مميزة
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: Files & Storage -->
    <div class="settings-tab-pane d-none" id="tab-files">
        <div class="settings-section-card">
            <div class="settings-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="settings-section-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">إعدادات الملفات وسعة التخزين</h5>
                        <small class="text-muted">التحكم في أحجام الملفات المسموح برفعها والصيغ المعتمدة في الكلية</small>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <!-- Max File Size -->
                <?php if (isset($flatSettings['max_file_size'])): $s = $flatSettings['max_file_size']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-weight-hanging text-success"></i>الحد الأقصى لحجم الملف (بالبايت):
                        </label>
                        <input type="text" class="form-control border-0 shadow-none bg-transparent fw-bold" 
                               name="settings[<?php echo $s->id; ?>]" 
                               value="<?php echo escape($s->setting_value ?? '10485760'); ?>"
                               placeholder="10485760 = 10MB">
                        <small class="text-muted d-block mt-1">10 ميجابايت = <code>10485760</code> | 20 ميجابايت = <code>20971520</code></small>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Allowed Extensions -->
                <?php if (isset($flatSettings['allowed_extensions'])): $s = $flatSettings['allowed_extensions']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-file-code text-success"></i>الامتدادات المسموح برفعها:
                        </label>
                        <input type="text" class="form-control border-0 shadow-none bg-transparent fw-bold" 
                               name="settings[<?php echo $s->id; ?>]" 
                               value="<?php echo escape($s->setting_value ?? 'pdf,doc,docx,ppt,pptx,xls,xlsx,zip,png,jpg'); ?>">
                        <small class="text-muted d-block mt-1">افصل بين الامتدادات بفاصلة (مثال: <code>pdf,docx,pptx,zip</code>)</small>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- TAB 4: Social Links -->
    <div class="settings-tab-pane d-none" id="tab-social">
        <div class="settings-section-card">
            <div class="settings-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="settings-section-icon" style="background: rgba(139, 92, 246, 0.1); color: #7c3aed;">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">روابط منصات التواصل الاجتماعي</h5>
                        <small class="text-muted">الروابط الرسمية لحسابات الكلية والجامعة في الفوتر وصفحة اتصل بنا</small>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <!-- Facebook -->
                <?php if (isset($flatSettings['facebook_url'])): $s = $flatSettings['facebook_url']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-2">
                            <i class="fab fa-facebook text-primary"></i>رابط صفحة فيسبوك:
                        </label>
                        <input type="url" class="form-control border-0 shadow-none bg-transparent" 
                               name="settings[<?php echo $s->id; ?>]" 
                               value="<?php echo escape($s->setting_value ?? ''); ?>"
                               placeholder="https://facebook.com/yourcollege">
                    </div>
                </div>
                <?php endif; ?>

                <!-- Twitter / X -->
                <?php if (isset($flatSettings['twitter_url'])): $s = $flatSettings['twitter_url']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-2">
                            <i class="fab fa-twitter text-info"></i>رابط حساب تويتر / X:
                        </label>
                        <input type="url" class="form-control border-0 shadow-none bg-transparent" 
                               name="settings[<?php echo $s->id; ?>]" 
                               value="<?php echo escape($s->setting_value ?? ''); ?>"
                               placeholder="https://x.com/yourcollege">
                    </div>
                </div>
                <?php endif; ?>

                <!-- Instagram -->
                <?php if (isset($flatSettings['instagram_url'])): $s = $flatSettings['instagram_url']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-2">
                            <i class="fab fa-instagram text-danger"></i>رابط حساب انستغرام:
                        </label>
                        <input type="url" class="form-control border-0 shadow-none bg-transparent" 
                               name="settings[<?php echo $s->id; ?>]" 
                               value="<?php echo escape($s->setting_value ?? ''); ?>"
                               placeholder="https://instagram.com/yourcollege">
                    </div>
                </div>
                <?php endif; ?>

                <!-- YouTube -->
                <?php if (isset($flatSettings['youtube_url'])): $s = $flatSettings['youtube_url']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-2">
                            <i class="fab fa-youtube text-danger"></i>رابط قناة يوتيوب:
                        </label>
                        <input type="url" class="form-control border-0 shadow-none bg-transparent" 
                               name="settings[<?php echo $s->id; ?>]" 
                               value="<?php echo escape($s->setting_value ?? ''); ?>"
                               placeholder="https://youtube.com/@yourcollege">
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- TAB 5: Security & OAuth -->
    <div class="settings-tab-pane d-none" id="tab-security">
        <div class="settings-section-card">
            <div class="settings-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="settings-section-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">الأمان، الربط الخارجي، ووضع الصيانة</h5>
                        <small class="text-muted">مفاتيح تسجيل الدخول بحساب Google OAuth وحالة وضع الصيانة</small>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Maintenance Mode -->
                <?php if (isset($flatSettings['maintenance_mode'])): $s = $flatSettings['maintenance_mode']; ?>
                <div class="col-12">
                    <div class="p-3 rounded-4 border d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: rgba(245, 158, 11, 0.05); border-color: rgba(245, 158, 11, 0.3) !important;">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark"><i class="fas fa-tools text-warning ms-2"></i>وضع الصيانة (Maintenance Mode)</h6>
                            <small class="text-muted">عند تفعيل وضع الصيانة، يتم توجيه الطلاب والزوار لصفحة الصيانة مع إتاحة الدخول للمدراء فقط</small>
                        </div>
                        <div class="form-check form-switch form-switch-lg" style="font-size: 1.3rem;">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                   id="maintenanceModeSwitch"
                                   name="settings[<?php echo $s->id; ?>]" 
                                   value="1" 
                                   <?php echo $s->setting_value ? 'checked' : ''; ?>>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Google Client ID -->
                <?php if (isset($flatSettings['google_client_id'])): $s = $flatSettings['google_client_id']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-2">
                            <i class="fab fa-google text-danger"></i>Google Client ID (معرّف تطبيق قوقل):
                        </label>
                        <input type="text" class="form-control border-0 shadow-none bg-transparent font-monospace" 
                               name="settings[<?php echo $s->id; ?>]" 
                               value="<?php echo escape($s->setting_value ?? ''); ?>"
                               placeholder="apps.googleusercontent.com">
                        <small class="text-muted d-block mt-1">يُستخدم لتمكين ميزة الدخول السريع بنقرة واحدة بحساب قوقل</small>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Google Client Secret -->
                <?php if (isset($flatSettings['google_client_secret'])): $s = $flatSettings['google_client_secret']; ?>
                <div class="col-md-6">
                    <div class="settings-input-group">
                        <label class="form-label fw-bold small text-dark d-flex align-items-center gap-2">
                            <i class="fas fa-key text-warning"></i>Google Client Secret (المفتاح السري):
                        </label>
                        <input type="password" class="form-control border-0 shadow-none bg-transparent font-monospace" 
                               name="settings[<?php echo $s->id; ?>]" 
                               value="<?php echo escape($s->setting_value ?? ''); ?>"
                               placeholder="••••••••••••••••••••">
                        <small class="text-muted d-block mt-1">يُحفظ بأمان ولا يُشارك مع أي جهة خارجية</small>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- TAB 6: Developer & System Info -->
    <div class="settings-tab-pane d-none" id="tab-developer">
        <div class="settings-section-card">
            <div class="settings-section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="settings-section-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff;">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">معلومات المطور والنظام الأكاديمي</h5>
                        <small class="text-muted">بيانات مهندس ومطور المنصة ومعلومات الخادم والبيئة التقنية</small>
                    </div>
                </div>
            </div>

            <!-- Master Developer VIP Card -->
            <div class="p-4 rounded-4 border mb-4" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(245, 158, 11, 0.05) 100%); border-color: rgba(245, 158, 11, 0.3) !important;">
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0" style="width: 72px; height: 72px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); font-size: 2rem;">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold"><i class="fas fa-code ms-1"></i>المطور الوحيد للمنصة</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold">Full-Stack Lead Architect</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">م. محمد محمود الأهنومي</h4>
                        <p class="text-muted small mb-3">مطور ومهندس الأنظمة الأكاديمية والبرمجيات السحابية - جامعة صنعاء</p>
                        
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <a href="https://wa.me/967771135357" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 fw-bold">
                                <i class="fab fa-whatsapp ms-1"></i>واتساب: 771135357
                            </a>
                            <a href="mailto:mohammedalahnomi04@gmail.com" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                <i class="fas fa-envelope ms-1"></i>mohammedalahnomi04@gmail.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Server & Environment Specs -->
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border bg-light">
                        <small class="text-muted d-block">إصدار PHP:</small>
                        <strong class="text-dark"><?php echo phpversion(); ?></strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border bg-light">
                        <small class="text-muted d-block">محرك قاعدة البيانات:</small>
                        <strong class="text-dark">MySQL / MariaDB PDO</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border bg-light">
                        <small class="text-muted d-block">بيئة النظام:</small>
                        <strong class="text-dark">Antigravity Native MVC</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Sticky Save Bar -->
    <div class="d-flex align-items-center justify-content-between p-4 rounded-4 shadow-sm border bg-white mt-4">
        <div>
            <strong class="d-block text-dark">جاهز لحفظ التعديلات؟</strong>
            <small class="text-muted">سيتم تطبيق كافة التغييرات على الفور في كامل النظام والواجهة للطلاب</small>
        </div>
        <button type="submit" class="majors-btn-add px-5 shadow-sm">
            <i class="fas fa-save ms-1"></i>حفظ كافة الإعدادات
        </button>
    </div>
</form>

<!-- Settings Tabs & Live Color/Image Preview Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Tab Switching Logic
    var tabBtns  = document.querySelectorAll('.settings-tab-btn');
    var tabPanes = document.querySelectorAll('.settings-tab-pane');

    tabBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var targetId = btn.getAttribute('data-tab');
            
            tabBtns.forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');

            tabPanes.forEach(function(pane) {
                if (pane.id === targetId) {
                    pane.classList.remove('d-none');
                } else {
                    pane.classList.add('d-none');
                }
            });
        });
    });

    // 2. Real-time Color Sync & Button Preview
    var primaryPicker   = document.getElementById('primaryColorPicker');
    var primaryInput    = document.getElementById('primaryColorInput');
    var secondaryPicker = document.getElementById('secondaryColorPicker');
    var secondaryInput  = document.getElementById('secondaryColorInput');
    var previewPrimary  = document.getElementById('previewPrimaryBtn');
    var previewSec      = document.getElementById('previewSecondaryBtn');
    var previewBadge    = document.getElementById('previewBadge');

    if (primaryPicker && primaryInput) {
        primaryPicker.addEventListener('input', function() {
            primaryInput.value = primaryPicker.value;
            if (previewPrimary) previewPrimary.style.backgroundColor = primaryPicker.value;
            if (previewBadge) {
                previewBadge.style.color = primaryPicker.value;
                previewBadge.style.backgroundColor = primaryPicker.value + '25';
            }
        });
        primaryInput.addEventListener('input', function() {
            primaryPicker.value = primaryInput.value;
            if (previewPrimary) previewPrimary.style.backgroundColor = primaryInput.value;
        });
    }

    if (secondaryPicker && secondaryInput) {
        secondaryPicker.addEventListener('input', function() {
            secondaryInput.value = secondaryPicker.value;
            if (previewSec) previewSec.style.backgroundColor = secondaryPicker.value;
        });
        secondaryInput.addEventListener('input', function() {
            secondaryPicker.value = secondaryInput.value;
            if (previewSec) previewSec.style.backgroundColor = secondaryInput.value;
        });
    }

    // 3. Instant Logo File Preview
    var logoInput = document.getElementById('logoFileInput');
    var logoImg   = document.getElementById('logoPreviewImg');
    var logoIcon  = document.getElementById('logoPlaceholderIcon');

    if (logoInput && logoImg) {
        logoInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(evt) {
                    logoImg.src = evt.target.result;
                    logoImg.classList.remove('d-none');
                    if (logoIcon) logoIcon.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>