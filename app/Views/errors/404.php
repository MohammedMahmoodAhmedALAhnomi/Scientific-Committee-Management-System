<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة غير موجودة</title>
    <link rel="stylesheet" href="<?php echo asset('assets/fonts/fonts.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/vendor/bootstrap/css/bootstrap.rtl.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/vendor/fontawesome/css/all.min.css'); ?>">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center" style="min-height: 100vh;">
            <div class="col-md-6 text-center d-flex flex-column justify-content-center">
                <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
                <h1 class="display-1 fw-bold text-primary">404</h1>
                <h3 class="mb-3">الصفحة غير موجودة</h3>
                <p class="text-muted mb-4">عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها.</p>
                <div>
                    <a href="<?php echo url('/'); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-home ms-1"></i>
                        العودة إلى الرئيسية
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>