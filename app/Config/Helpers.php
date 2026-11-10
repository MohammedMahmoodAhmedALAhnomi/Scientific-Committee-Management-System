<?php

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }
        switch (strtolower($value)) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'null':
                return null;
        }
        return $value;
    }
}

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        $base = dirname(__DIR__);
        return $path ? $base . DIRECTORY_SEPARATOR . ltrim($path, '/\\') : $base;
    }
}

if (!function_exists('view_path')) {
    function view_path(string $path = ''): string
    {
        return base_path('Views' . DIRECTORY_SEPARATOR . ltrim($path, '/\\'));
    }
}

if (!function_exists('public_path')) {
    function public_path(string $path = ''): string
    {
        $base = dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public';
        return $path ? $base . DIRECTORY_SEPARATOR . ltrim($path, '/\\') : $base;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(dirname($scriptName), '/\\');
        if ($basePath === '.' || $basePath === '/' || $basePath === '\\') {
            $basePath = '';
        }
        $url = $basePath . '/' . ltrim($path, '/\\');
        $filePath = public_path($path);
        if (file_exists($filePath)) {
            $mtime = @filemtime($filePath);
            if ($mtime) {
                $url .= '?v=' . $mtime;
            }
        }
        return $url;
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(dirname($scriptName), '/\\');
        if ($basePath === '.' || $basePath === '/' || $basePath === '\\') {
            $basePath = '';
        }
        return $basePath . '/' . ltrim($path, '/\\');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = '')
    {
        return $_SESSION['_old_input'][$key] ?? $default;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(): bool
    {
        $token = $_POST['_csrf_token'] ?? ($_GET['_csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''));
        $sessionToken = $_SESSION['_csrf_token'] ?? '';
        return !empty($sessionToken) && !empty($token) && hash_equals($sessionToken, $token);
    }
}

if (!function_exists('flash')) {
    function flash(string $key, ?string $value = null)
    {
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return;
        }
        $val = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $val;
    }
}

if (!function_exists('flash_has')) {
    function flash_has(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }
}

if (!function_exists('str_slug')) {
    function str_slug(string $string): string
    {
        $string = mb_strtolower($string, 'UTF-8');
        $string = preg_replace('/[^\w\s\-]/u', '', $string);
        $string = preg_replace('/[\s\-]+/', '-', $string);
        return trim($string, '-');
    }
}

if (!function_exists('truncate')) {
    function truncate(?string $text, int $length = 100, string $append = '...'): string
    {
        $text = $text ?? '';
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . $append;
    }
}

if (!function_exists('escape')) {
    function escape(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('format_date')) {
    function format_date(?string $date, string $format = 'Y-m-d'): string
    {
        if (!$date) return '';
        $dt = new DateTime($date);
        return $dt->format($format);
    }
}

if (!function_exists('is_active_route')) {
    function is_active_route(string $path): string
    {
        $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $basePathNoPublic = preg_replace('#/public$#i', '', $basePath);
        $normalized = str_replace([$basePath, $basePathNoPublic], '', $currentPath);
        $normalized = '/' . trim($normalized, '/');

        if ($path === '/admin' || $path === '/admin/dashboard') {
            return ($normalized === '/admin' || $normalized === '/admin/dashboard') ? 'active' : '';
        }

        if ($normalized === $path) return 'active';
        if ($path !== '/' && str_starts_with($normalized, $path . '/')) return 'active';
        return '';
    }
}

if (!function_exists('log_activity')) {
    function log_activity(string $action, ?string $table = null, ?int $recordId = null, ?string $details = null): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        \App\Config\Database::insert('activity_logs', [
            'user_id' => $userId,
            'action' => $action,
            'table_name' => $table,
            'record_id' => $recordId,
            'details' => $details,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);
    }
}

if (!function_exists('get_managed_level_id')) {
    function get_managed_level_id(): ?int
    {
        $role = $_SESSION['user_role'] ?? '';
        if ($role === 'manager' && isset($_SESSION['managed_level_id'])) {
            return (int)$_SESSION['managed_level_id'];
        }
        return null;
    }
}

if (!function_exists('get_managed_major_id')) {
    function get_managed_major_id(): ?int
    {
        $role = $_SESSION['user_role'] ?? '';
        if (in_array($role, ['manager', 'major_admin']) && isset($_SESSION['managed_major_id'])) {
            return (int)$_SESSION['managed_major_id'];
        }
        return null;
    }
}

if (!function_exists('is_manager')) {
    function is_manager(): bool
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'manager';
    }
}

if (!function_exists('get_major_icon')) {
    function get_major_icon(?string $majorName, string $defaultIcon = 'fas fa-university'): string
    {
        if (empty($majorName)) return $defaultIcon;
        $name = mb_strtolower($majorName);
        if (str_contains($name, 'بيانات') || str_contains($name, 'data') || str_contains($name, 'ds')) {
            return 'fas fa-database';
        }
        if (str_contains($name, 'جراف') || str_contains($name, 'غراف') || str_contains($name, 'تصميم') || str_contains($name, 'graphics') || str_contains($name, 'gr') || str_contains($name, 'وسائط')) {
            return 'fas fa-palette';
        }
        if (str_contains($name, 'نظم') || str_contains($name, 'is') || str_contains($name, 'إدارية')) {
            return 'fas fa-network-wired';
        }
        if (str_contains($name, 'تقنية') || str_contains($name, 'it') || str_contains($name, 'تكنولوجيا')) {
            return 'fas fa-laptop-code';
        }
        if (str_contains($name, 'علوم') || str_contains($name, 'cs') || str_contains($name, 'حاسوب')) {
            return 'fas fa-code';
        }
        if (str_contains($name, 'أمن') || str_contains($name, 'سيبراني') || str_contains($name, 'cyber')) {
            return 'fas fa-shield-alt';
        }
        if (str_contains($name, 'برمجيات') || str_contains($name, 'se') || str_contains($name, 'هندسة')) {
            return 'fas fa-drafting-compass';
        }
        if (str_contains($name, 'ذكاء') || str_contains($name, 'ai')) {
            return 'fas fa-brain';
        }
        return $defaultIcon;
    }
}

if (!function_exists('get_major_badge_code')) {
    function get_major_badge_code(?string $majorName): string
    {
        if (empty($majorName)) return 'MAJOR';
        $name = mb_strtolower($majorName);
        if (str_contains($name, 'تقنية') || str_contains($name, 'تقنيه') || str_contains($name, 'it')) return 'IT';
        if (str_contains($name, 'نظم') || str_contains($name, 'is')) return 'IS';
        if (str_contains($name, 'علوم') || str_contains($name, 'حاسوب') || str_contains($name, 'cs')) return 'CS';
        if (str_contains($name, 'شبكات') || str_contains($name, 'net')) return 'NET';
        if (str_contains($name, 'أمن') || str_contains($name, 'امن') || str_contains($name, 'سيبراني') || str_contains($name, 'cyber')) return 'CYBER';
        if (str_contains($name, 'ذكاء') || str_contains($name, 'اصطناعي') || str_contains($name, 'ai')) return 'AI';
        if (str_contains($name, 'برمجيات') || str_contains($name, 'se')) return 'SE';
        if (str_contains($name, 'جراف') || str_contains($name, 'غراف') || str_contains($name, 'وسائط') || str_contains($name, 'graph')) return 'GRAPH';
        if (str_contains($name, 'بيانات') || str_contains($name, 'ds')) return 'DS';
        return strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $majorName) ?: 'MAJOR', 0, 5));
    }
}

if (!function_exists('get_major_palette')) {
    function get_major_palette(?string $majorName): array
    {
        $code = get_major_badge_code($majorName);
        return match ($code) {
            'CS' => [
                'from'     => '#4f46e5',
                'to'       => '#7c3aed',
                'primary'  => '#4f46e5',
                'light'    => 'rgba(79, 70, 229, 0.08)',
                'border'   => 'rgba(79, 70, 229, 0.22)',
                'text'     => '#4338ca',
                'icon_bg'  => 'linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%)',
                'shadow'   => 'rgba(79, 70, 229, 0.28)'
            ],
            'IT' => [
                'from'     => '#0284c7',
                'to'       => '#06b6d4',
                'primary'  => '#0284c7',
                'light'    => 'rgba(2, 132, 199, 0.08)',
                'border'   => 'rgba(2, 132, 199, 0.22)',
                'text'     => '#0369a1',
                'icon_bg'  => 'linear-gradient(135deg, #0284c7 0%, #06b6d4 100%)',
                'shadow'   => 'rgba(2, 132, 199, 0.28)'
            ],
            'IS' => [
                'from'     => '#059669',
                'to'       => '#10b981',
                'primary'  => '#059669',
                'light'    => 'rgba(5, 150, 105, 0.08)',
                'border'   => 'rgba(5, 150, 105, 0.22)',
                'text'     => '#047857',
                'icon_bg'  => 'linear-gradient(135deg, #059669 0%, #10b981 100%)',
                'shadow'   => 'rgba(5, 150, 105, 0.28)'
            ],
            'AI' => [
                'from'     => '#9333ea',
                'to'       => '#c026d3',
                'primary'  => '#9333ea',
                'light'    => 'rgba(147, 51, 234, 0.08)',
                'border'   => 'rgba(147, 51, 234, 0.22)',
                'text'     => '#7e22ce',
                'icon_bg'  => 'linear-gradient(135deg, #9333ea 0%, #c026d3 100%)',
                'shadow'   => 'rgba(147, 51, 234, 0.28)'
            ],
            'CYBER' => [
                'from'     => '#e11d48',
                'to'       => '#f43f5e',
                'primary'  => '#e11d48',
                'light'    => 'rgba(225, 29, 72, 0.08)',
                'border'   => 'rgba(225, 29, 72, 0.22)',
                'text'     => '#be123c',
                'icon_bg'  => 'linear-gradient(135deg, #e11d48 0%, #f43f5e 100%)',
                'shadow'   => 'rgba(225, 29, 72, 0.28)'
            ],
            'GRAPH' => [
                'from'     => '#ea580c',
                'to'       => '#f59e0b',
                'primary'  => '#ea580c',
                'light'    => 'rgba(234, 88, 12, 0.08)',
                'border'   => 'rgba(234, 88, 12, 0.22)',
                'text'     => '#c2410c',
                'icon_bg'  => 'linear-gradient(135deg, #ea580c 0%, #f59e0b 100%)',
                'shadow'   => 'rgba(234, 88, 12, 0.28)'
            ],
            'NET' => [
                'from'     => '#2563eb',
                'to'       => '#3b82f6',
                'primary'  => '#2563eb',
                'light'    => 'rgba(37, 99, 235, 0.08)',
                'border'   => 'rgba(37, 99, 235, 0.22)',
                'text'     => '#1d4ed8',
                'icon_bg'  => 'linear-gradient(135deg, #2563eb 0%, #3b82f6 100%)',
                'shadow'   => 'rgba(37, 99, 235, 0.28)'
            ],
            'SE' => [
                'from'     => '#0d9488',
                'to'       => '#14b8a6',
                'primary'  => '#0d9488',
                'light'    => 'rgba(13, 148, 136, 0.08)',
                'border'   => 'rgba(13, 148, 136, 0.22)',
                'text'     => '#0f766e',
                'icon_bg'  => 'linear-gradient(135deg, #0d9488 0%, #14b8a6 100%)',
                'shadow'   => 'rgba(13, 148, 136, 0.28)'
            ],
            'DS' => [
                'from'     => '#4338ca',
                'to'       => '#6366f1',
                'primary'  => '#4338ca',
                'light'    => 'rgba(67, 56, 202, 0.08)',
                'border'   => 'rgba(67, 56, 202, 0.22)',
                'text'     => '#3730a3',
                'icon_bg'  => 'linear-gradient(135deg, #4338ca 0%, #6366f1 100%)',
                'shadow'   => 'rgba(67, 56, 202, 0.28)'
            ],
            default => [
                'from'     => '#1e3a5f',
                'to'       => '#3b82f6',
                'primary'  => '#1e3a5f',
                'light'    => 'rgba(30, 58, 95, 0.08)',
                'border'   => 'rgba(30, 58, 95, 0.22)',
                'text'     => '#1e3a5f',
                'icon_bg'  => 'linear-gradient(135deg, #1e3a5f 0%, #3b82f6 100%)',
                'shadow'   => 'rgba(30, 58, 95, 0.28)'
            ],
        };
    }
}

if (!function_exists('mask_email')) {
    function mask_email(?string $email, int $targetUserId = 0): string
    {
        if (empty($email)) return '-';

        $currentUserId = (int)($_SESSION['user_id'] ?? 0);
        $currentRole = $_SESSION['user_role'] ?? '';

        // Super admin or account owner can see full email
        if ($currentRole === 'admin' || ($targetUserId > 0 && $currentUserId === $targetUserId)) {
            return $email;
        }

        $parts = explode('@', $email);
        if (count($parts) !== 2) return '******';

        $name = $parts[0];
        $domain = $parts[1];

        $len = mb_strlen($name);
        if ($len <= 2) {
            $maskedName = mb_substr($name, 0, 1) . '***';
        } elseif ($len <= 4) {
            $maskedName = mb_substr($name, 0, 1) . '***' . mb_substr($name, -1);
        } else {
            $maskedName = mb_substr($name, 0, 2) . '***' . mb_substr($name, -1);
        }

        return $maskedName . '@' . $domain;
    }
}

if (!function_exists('format_file_size')) {
    function format_file_size($bytes, int $precision = 1): string
    {
        $bytes = (float)$bytes;
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = (int)floor(log($bytes, 1024));
        $i = max(0, min($i, count($units) - 1));
        $val = round($bytes / pow(1024, $i), $precision);
        return $val . ' ' . $units[$i];
    }
}

