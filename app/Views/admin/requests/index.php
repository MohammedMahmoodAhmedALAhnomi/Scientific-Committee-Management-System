<!-- Page Header -->
<div class="req-page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="req-page-icon">
            <i class="fas fa-inbox"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0 page-title-main">طلبات الانضمام وتحديد الصلاحيات</h4>
            <p class="text-muted small mb-0">مراجعة واعتماد طلبات المندوبين ومسؤولي التخصصات</p>
        </div>
    </div>
    <?php
        $pending  = array_filter($requests ?? [], fn($r) => $r->status === 'pending');
        $approved = array_filter($requests ?? [], fn($r) => $r->status === 'approved');
        $rejected = array_filter($requests ?? [], fn($r) => $r->status === 'rejected');
    ?>
    <div class="d-flex gap-2 flex-wrap">
        <span class="req-stat-pill req-stat-pending">
            <i class="fas fa-hourglass-half"></i> انتظار: <?php echo count($pending); ?>
        </span>
        <span class="req-stat-pill req-stat-approved">
            <i class="fas fa-check-circle"></i> موافق: <?php echo count($approved); ?>
        </span>
        <span class="req-stat-pill req-stat-rejected">
            <i class="fas fa-times-circle"></i> مرفوض: <?php echo count($rejected); ?>
        </span>
    </div>
</div>

<!-- Requests Grid -->
<?php if (empty($requests)): ?>
    <div class="req-empty-state">
        <div class="req-empty-icon">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <h5 class="fw-bold mb-1">لا توجد طلبات انضمام حالياً</h5>
        <p class="text-muted small mb-0">ستظهر طلبات الانضمام هنا فور إرسالها من المستخدمين</p>
    </div>
<?php else: ?>

    <!-- Pending Section -->
    <?php if (!empty($pending)): ?>
    <div class="req-section-header req-section-pending mb-3">
        <i class="fas fa-hourglass-half"></i>
        <span>الطلبات بانتظار الموافقة (<?php echo count($pending); ?>)</span>
    </div>
    <div class="row g-3 mb-4">
        <?php foreach ($pending as $req): ?>
            <?php
                $isPending = true;
                $canApprove = \App\Controllers\JoinRequestController::canApproveRequest($req);
                $isRep = $req->account_type === 'representative';
            ?>
            <div class="col-md-6 col-xl-4">
                <div class="req-card req-card-pending">
                    <!-- Card Header -->
                    <div class="req-card-top">
                        <div class="req-avatar req-avatar-<?php echo $isRep ? 'rep' : 'major'; ?>">
                            <i class="fas <?php echo $isRep ? 'fa-award' : 'fa-shield-alt'; ?>"></i>
                        </div>
                        <div class="req-card-type">
                            <?php if ($isRep): ?>
                                <span class="req-type-badge req-type-rep">
                                    <i class="fas fa-user-graduate"></i> مندوب مستوى
                                </span>
                            <?php else: ?>
                                <span class="req-type-badge req-type-major">
                                    <i class="fas fa-user-shield"></i> مسؤول تخصص
                                </span>
                            <?php endif; ?>
                        </div>
                        <span class="req-status-dot dot-pending"></span>
                    </div>

                    <!-- User Info -->
                    <div class="req-card-body">
                        <h6 class="req-username"><?php echo escape($req->user_name); ?></h6>
                        <div class="req-info-row" style="user-select:none;-webkit-user-select:none;" title="بريد إلكتروني محمي">
                            <i class="fas fa-envelope req-info-icon"></i>
                            <span><?php echo escape(mask_email($req->user_email, (int)($req->user_id ?? 0))); ?></span>
                        </div>
                        <div class="req-info-row">
                            <i class="fas fa-university req-info-icon"></i>
                            <span class="fw-bold"><?php echo escape($req->major_name); ?></span>
                        </div>
                        <?php if ($isRep && !empty($req->level_name)): ?>
                        <div class="req-info-row">
                            <i class="fas fa-layer-group req-info-icon"></i>
                            <span><?php echo escape($req->level_name); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="req-info-row">
                            <i class="fas fa-clock req-info-icon"></i>
                            <span class="text-muted"><?php echo escape(format_date($req->created_at, 'Y-m-d H:i')); ?></span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="req-card-footer">
                        <?php if ($canApprove): ?>
                            <a href="<?php echo url('/admin/requests/' . $req->id . '/approve?_csrf_token=' . csrf_token()); ?>"
                               class="req-btn req-btn-approve"
                               onclick="return confirm('هل أنت متأكد من الموافقة على الطلب ومنح الصلاحيات؟')">
                                <i class="fas fa-check ms-1"></i>موافقة
                            </a>
                            <a href="<?php echo url('/admin/requests/' . $req->id . '/reject?_csrf_token=' . csrf_token()); ?>"
                               class="req-btn req-btn-reject"
                               onclick="return confirm('هل أنت متأكد من رفض هذا الطلب؟')">
                                <i class="fas fa-times ms-1"></i>رفض
                            </a>
                        <?php else: ?>
                            <span class="req-protected" title="هذا الطلب محمي ولا يتاح لك الموافقة عليه">
                                <i class="fas fa-lock text-warning ms-1"></i>طلب محمي 🔒
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Approved Section -->
    <?php if (!empty($approved)): ?>
    <div class="req-section-header req-section-approved mb-3">
        <i class="fas fa-check-circle"></i>
        <span>الطلبات الموافق عليها (<?php echo count($approved); ?>)</span>
    </div>
    <div class="row g-3 mb-4">
        <?php foreach ($approved as $req): ?>
            <?php $isRep = $req->account_type === 'representative'; ?>
            <div class="col-md-6 col-xl-4">
                <div class="req-card req-card-approved">
                    <div class="req-card-top">
                        <div class="req-avatar <?php echo $isRep ? 'req-avatar-rep' : 'req-avatar-major'; ?> req-avatar-done">
                            <i class="fas <?php echo $isRep ? 'fa-award' : 'fa-shield-alt'; ?>"></i>
                        </div>
                        <div class="req-card-type">
                            <?php if ($isRep): ?>
                                <span class="req-type-badge req-type-rep">
                                    <i class="fas fa-user-graduate"></i> مندوب مستوى
                                </span>
                            <?php else: ?>
                                <span class="req-type-badge req-type-major">
                                    <i class="fas fa-user-shield"></i> مسؤول تخصص
                                </span>
                            <?php endif; ?>
                        </div>
                        <span class="req-status-dot dot-approved"></span>
                    </div>
                    <div class="req-card-body">
                        <h6 class="req-username"><?php echo escape($req->user_name); ?></h6>
                        <div class="req-info-row" style="user-select:none;" title="بريد محمي">
                            <i class="fas fa-envelope req-info-icon"></i>
                            <span><?php echo escape(mask_email($req->user_email, (int)($req->user_id ?? 0))); ?></span>
                        </div>
                        <div class="req-info-row">
                            <i class="fas fa-university req-info-icon"></i>
                            <span class="fw-bold"><?php echo escape($req->major_name); ?></span>
                        </div>
                        <?php if ($isRep && !empty($req->level_name)): ?>
                        <div class="req-info-row">
                            <i class="fas fa-layer-group req-info-icon"></i>
                            <span><?php echo escape($req->level_name); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="req-info-row">
                            <i class="fas fa-clock req-info-icon"></i>
                            <span class="text-muted"><?php echo escape(format_date($req->created_at, 'Y-m-d H:i')); ?></span>
                        </div>
                    </div>
                    <div class="req-card-footer req-footer-done">
                        <i class="fas <?php echo $isRep ? 'fa-award' : 'fa-shield-alt'; ?> me-1"></i>
                        <?php echo $isRep ? 'تمت الموافقة — مندوب مستوى' : 'تمت الموافقة — مسؤول تخصص'; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Rejected Section -->
    <?php if (!empty($rejected)): ?>
    <div class="req-section-header req-section-rejected mb-3">
        <i class="fas fa-times-circle"></i>
        <span>الطلبات المرفوضة (<?php echo count($rejected); ?>)</span>
    </div>
    <div class="row g-3">
        <?php foreach ($rejected as $req): ?>
            <?php $isRep = $req->account_type === 'representative'; ?>
            <div class="col-md-6 col-xl-4">
                <div class="req-card req-card-rejected">
                    <div class="req-card-top">
                        <div class="req-avatar <?php echo $isRep ? 'req-avatar-rep' : 'req-avatar-major'; ?> req-avatar-reject-overlay">
                            <i class="fas <?php echo $isRep ? 'fa-award' : 'fa-shield-alt'; ?>"></i>
                        </div>
                        <div class="req-card-type">
                            <?php if ($isRep): ?>
                                <span class="req-type-badge req-type-rep">
                                    <i class="fas fa-user-graduate"></i> مندوب مستوى
                                </span>
                            <?php else: ?>
                                <span class="req-type-badge req-type-major">
                                    <i class="fas fa-user-shield"></i> مسؤول تخصص
                                </span>
                            <?php endif; ?>
                        </div>
                        <span class="req-status-dot dot-rejected"></span>
                    </div>
                    <div class="req-card-body">
                        <h6 class="req-username"><?php echo escape($req->user_name); ?></h6>
                        <div class="req-info-row" style="user-select:none;" title="بريد محمي">
                            <i class="fas fa-envelope req-info-icon"></i>
                            <span><?php echo escape(mask_email($req->user_email, (int)($req->user_id ?? 0))); ?></span>
                        </div>
                        <div class="req-info-row">
                            <i class="fas fa-university req-info-icon"></i>
                            <span class="fw-bold"><?php echo escape($req->major_name); ?></span>
                        </div>
                        <?php if ($isRep && !empty($req->level_name)): ?>
                        <div class="req-info-row">
                            <i class="fas fa-layer-group req-info-icon"></i>
                            <span><?php echo escape($req->level_name); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="req-info-row">
                            <i class="fas fa-clock req-info-icon"></i>
                            <span class="text-muted"><?php echo escape(format_date($req->created_at, 'Y-m-d H:i')); ?></span>
                        </div>
                    </div>
                    <div class="req-card-footer req-footer-rejected">
                        <i class="fas <?php echo $isRep ? 'fa-award' : 'fa-shield-alt'; ?> me-1"></i>
                        <?php echo $isRep ? 'تم الرفض — مندوب مستوى' : 'تم الرفض — مسؤول تخصص'; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

<?php endif; ?>
