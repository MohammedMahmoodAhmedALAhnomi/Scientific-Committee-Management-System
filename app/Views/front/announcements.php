<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h3 class="mb-0 fw-bold"><i class="fas fa-bullhorn ms-2 text-primary"></i>الإعلانات والتنبيهات</h3>
</div>

<?php if (empty($announcements)): ?>
    <div class="card shadow-sm border-0 rounded-4 text-center py-5 bg-white">
        <div class="card-body">
            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; font-size: 2rem; color: #94a3b8;">
                <i class="fas fa-bullhorn"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">لا توجد إعلانات حالياً</h5>
            <p class="text-muted small mb-0">سيتم إضافة الإعلانات والأخبار الأكاديمية قريباً</p>
        </div>
    </div>
<?php else: ?>
    <div class="row g-3 g-md-4">
        <?php foreach ($announcements as $announcement): ?>
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden bg-white <?php echo $announcement->is_pinned ? 'border-end border-warning border-4' : ''; ?>">
                    <div class="card-body p-3 p-md-4">
                        <?php if ($announcement->is_pinned): ?>
                            <div class="mb-2">
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold small">
                                    <i class="fas fa-thumbtack ms-1"></i>إعلان مثبت
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <h5 class="card-title fw-bold text-dark mb-2"><?php echo escape($announcement->title); ?></h5>
                        <p class="card-text text-muted" style="word-break: break-word; line-height: 1.7; font-size: 0.95rem;">
                            <?php echo nl2br(escape($announcement->content)); ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3 flex-wrap small text-muted">
                                <?php if (isset($announcement->creator_name)): ?>
                                    <span class="d-inline-flex align-items-center">
                                        <i class="fas fa-user-circle text-secondary ms-1"></i>
                                        <?php echo escape($announcement->creator_name); ?>
                                    </span>
                                <?php endif; ?>
                                <span class="d-inline-flex align-items-center">
                                    <i class="fas fa-calendar-alt text-primary ms-1"></i>
                                    <?php echo escape(format_date($announcement->created_at, 'Y-m-d H:i')); ?>
                                </span>
                            </div>
                            <?php if (!empty($announcement->image)): ?>
                                <a href="<?php echo asset('uploads/' . escape($announcement->image)); ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" target="_blank">
                                    <i class="fas fa-paperclip ms-1"></i>عرض المرفق
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>