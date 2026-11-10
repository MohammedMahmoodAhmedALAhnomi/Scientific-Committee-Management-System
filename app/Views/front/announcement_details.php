<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?php echo url('/?home=1'); ?>"><i class="fas fa-home ms-1"></i>الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('/announcements'); ?>">الإعلانات</a></li>
            <li class="breadcrumb-item active"><?php echo escape($announcement->title); ?></li>
        </ol>
    </nav>
    <div>
        <a href="<?php echo url('/announcements'); ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold shadow-sm">
            <i class="fas fa-arrow-right ms-1"></i>الرجوع للإعلانات
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4 <?php echo !empty($announcement->is_pinned) ? 'border-top border-warning border-4' : ''; ?>">
    <?php if (!empty($announcement->is_pinned)): ?>
        <div class="card-header bg-warning bg-opacity-10 border-0 py-2 px-4">
            <span class="badge bg-warning text-dark fw-bold">
                <i class="fas fa-thumbtack ms-1"></i>
                إعلان مثبت
            </span>
        </div>
    <?php endif; ?>
    
    <div class="card-body p-4 p-md-5">
        <h3 class="fw-bold text-dark mb-3"><?php echo escape($announcement->title); ?></h3>
        
        <div class="d-flex align-items-center gap-3 text-muted small mb-4 pb-3 border-bottom flex-wrap">
            <?php if (!empty($announcement->creator_name)): ?>
                <span><i class="fas fa-user-edit ms-1 text-primary"></i><?php echo escape($announcement->creator_name); ?></span>
            <?php endif; ?>
            <span><i class="fas fa-calendar-alt ms-1 text-primary"></i><?php echo escape(format_date($announcement->created_at, 'Y-m-d H:i')); ?></span>
        </div>

        <?php if (!empty($announcement->image_path)): ?>
            <div class="mb-4 text-center">
                <img src="<?php echo asset(escape($announcement->image_path)); ?>" alt="<?php echo escape($announcement->title); ?>" class="img-fluid rounded-4 shadow-sm" style="max-height: 450px; object-fit: cover;">
            </div>
        <?php endif; ?>

        <div class="announcement-content text-dark fs-5" style="line-height: 1.8;">
            <?php echo nl2br(escape($announcement->content)); ?>
        </div>
    </div>
</div>
