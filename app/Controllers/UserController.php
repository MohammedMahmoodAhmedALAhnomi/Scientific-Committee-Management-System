<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Major;
use App\Models\Level;

class UserController extends Controller
{
    private function canManage(): bool
    {
        $role = $_SESSION['user_role'] ?? '';
        return in_array($role, ['admin', 'major_admin', 'manager'], true);
    }

    public static function canEditUserTarget($targetUser): bool
    {
        if (!$targetUser) return false;
        $currentRole = $_SESSION['user_role'] ?? '';
        $currentUserId = (int)($_SESSION['user_id'] ?? 0);
        $currentUserEmail = strtolower(trim($_SESSION['user_email'] ?? ''));

        // System owner protection check
        if (User::isSystemOwner($targetUser)) {
            return ($currentUserId === (int)($targetUser->id ?? 0)) || ($currentUserEmail === 'mohammedalahnomi04@gmail.com');
        }

        // Super Admin (admin) can edit all non-owner users
        if ($currentRole === 'admin') {
            return true;
        }

        // Level Representative (manager) has NO edit permissions (ALL accounts protected)
        if ($currentRole === 'manager') {
            return false;
        }

        // Major Admin (major_admin) can ONLY edit delegates (manager role) in their assigned major
        if ($currentRole === 'major_admin') {
            $targetRole = is_object($targetUser) ? ($targetUser->role ?? 'student') : ($targetUser['role'] ?? 'student');
            
            // Must be a delegate (manager role)
            if ($targetRole !== 'manager') {
                return false;
            }

            $currentUser = User::find($currentUserId);
            $scopedMajorId = $currentUser ? ($currentUser->managed_major_id ?: $currentUser->major_id) : null;

            $targetManagedMajor = is_object($targetUser) ? ($targetUser->managed_major_id ?? null) : ($targetUser['managed_major_id'] ?? null);
            $targetMajor = is_object($targetUser) ? ($targetUser->major_id ?? null) : ($targetUser['major_id'] ?? null);
            $effectiveMajorId = $targetManagedMajor ?: $targetMajor;

            if ($scopedMajorId && (int)$effectiveMajorId === (int)$scopedMajorId) {
                return true;
            }
        }

        return false;
    }

    public static function canDeleteUserTarget($targetUser): bool
    {
        if (!$targetUser) return false;
        $currentRole = $_SESSION['user_role'] ?? '';

        // Managers (level delegates) CANNOT delete any accounts
        if ($currentRole === 'manager') {
            return false;
        }

        return self::canEditUserTarget($targetUser);
    }

    public function index(): void
    {
        if (!$this->canManage()) {
            flash('error', 'لا تملك صلاحية الوصول');
            redirect(url('/admin/courses'));
        }

        $currentRole = $_SESSION['user_role'] ?? '';
        $currentUserId = $_SESSION['user_id'] ?? 0;
        $scopedMajorId = null;

        if (in_array($currentRole, ['major_admin', 'manager'], true)) {
            $currentUser = User::find($currentUserId);
            $scopedMajorId = $currentUser ? ($currentUser->managed_major_id ?: $currentUser->major_id) : null;
        }

        $search = trim($_GET['search'] ?? '');
        $role = trim($_GET['role'] ?? 'all');

        $users = User::getFilteredUsers($search, $role, null);
        $promotionInfo = User::getLastPromotionInfo($scopedMajorId);

        $this->view('admin/users/index', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'currentRole' => $currentRole,
            'scopedMajorId' => $scopedMajorId,
            'promotionInfo' => $promotionInfo,
        ]);
    }

    public function create(): void
    {
        if (!$this->canManage()) {
            flash('error', 'لا تملك صلاحية الوصول');
            redirect(url('/admin/courses'));
        }
        $majors = Major::getActive();
        $levels = Level::getActive();
        $this->view('admin/users/create', ['majors' => $majors, 'levels' => $levels]);
    }

    public function store(): void
    {
        if (!$this->canManage()) {
            flash('error', 'لا تملك صلاحية الوصول');
            redirect(url('/admin/courses'));
        }
        if (!$this->isPost() || !verify_csrf()) {
            redirect(url('/admin/users'));
        }

        $currentRole = $_SESSION['user_role'] ?? '';
        $currentUserId = $_SESSION['user_id'] ?? 0;
        $scopedMajorId = null;

        if ($currentRole === 'major_admin') {
            $currentUser = User::find($currentUserId);
            $scopedMajorId = $currentUser ? ($currentUser->managed_major_id ?: $currentUser->major_id) : null;
        }

        $requestedRole = $this->postParam('role', 'manager');

        if ($currentRole === 'major_admin') {
            if ($requestedRole === 'admin' || $requestedRole === 'major_admin') {
                $requestedRole = 'manager';
            }
            $majorId = $scopedMajorId;
        } else {
            $majorId = $this->postParam('major_id', '');
            if ($currentRole !== 'admin' && $requestedRole === 'admin') {
                $requestedRole = 'manager';
            }
        }

        $levelId = $this->postParam('managed_level_id', '');

        $data = [
            'full_name' => trim($this->postParam('full_name', '')),
            'email' => trim($this->postParam('email', '')),
            'phone' => trim($this->postParam('phone', '')),
            'password' => $this->postParam('password', ''),
            'role' => $requestedRole,
            'major_id' => !empty($majorId) ? (int)$majorId : null,
            'managed_major_id' => !empty($majorId) ? (int)$majorId : null,
            'managed_level_id' => !empty($levelId) ? (int)$levelId : null,
            'is_active' => $this->postParam('is_active', '1') ? 1 : 0,
        ];

        $errors = $this->validate($data, [
            'full_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (!empty($errors)) {
            flash('error', 'يرجى التحقق من الحقول المطلوبة');
            $majors = Major::getActive();
            $levels = Level::getActive();
            $this->view('admin/users/create', [
                'errors' => $errors,
                'data' => $data,
                'majors' => $majors,
                'levels' => $levels,
                'currentRole' => $currentRole,
                'scopedMajorId' => $scopedMajorId,
            ]);
            return;
        }

        $existingUser = User::findByEmail($data['email']);
        if ($existingUser) {
            flash('error', 'البريد الإلكتروني مستخدم بالفعل');
            $majors = Major::getActive();
            $levels = Level::getActive();
            $this->view('admin/users/create', [
                'errors' => ['email' => ['البريد الإلكتروني مستخدم بالفعل']],
                'data' => $data,
                'majors' => $majors,
                'levels' => $levels,
                'currentRole' => $currentRole,
                'scopedMajorId' => $scopedMajorId,
            ]);
            return;
        }

        User::ensureColumns();
        $data['password'] = User::hashPassword($data['password']);
        $userId = User::create($data);
        log_activity('create', 'users', $userId, 'إضافة مستخدم جديد: ' . $data['full_name']);
        flash('success', 'تم إضافة المستخدم بنجاح');
        redirect(url('/admin/users'));
    }

    public function edit($id = 0): void
    {
        if (!$this->canManage()) {
            flash('error', 'لا تملك صلاحية الوصول');
            redirect(url('/admin/courses'));
        }
        $id = (int)($id ?: $this->getParam('id'));

        $user = User::find($id);

        if (!$user) {
            flash('error', 'المستخدم غير موجود');
            redirect(url('/admin/users'));
        }

        if (!self::canEditUserTarget($user)) {
            flash('error', 'هذا الحساب محمي، ولا يمكن تعديله إلا إذا كان مناديب مستويات في نفس التخصص المحدد لك.');
            redirect(url('/admin/users'));
            return;
        }

        $currentRole = $_SESSION['user_role'] ?? '';
        $currentUserId = (int)($_SESSION['user_id'] ?? 0);
        $scopedMajorId = null;

        if (in_array($currentRole, ['major_admin', 'manager'], true)) {
            $currentUser = User::find($currentUserId);
            $scopedMajorId = $currentUser ? ($currentUser->managed_major_id ?: $currentUser->major_id) : null;
        }

        $majors = Major::getActive();
        $levels = Level::getActive();
        $this->view('admin/users/edit', [
            'user' => $user,
            'majors' => $majors,
            'levels' => $levels,
            'currentRole' => $currentRole,
            'scopedMajorId' => $scopedMajorId,
            'isOwnerTarget' => User::isSystemOwner($user),
        ]);
    }

    public function update($id = 0): void
    {
        if (!$this->canManage()) {
            flash('error', 'لا تملك صلاحية الوصول');
            redirect(url('/admin/courses'));
        }
        if (!$this->isPost() || !verify_csrf()) {
            redirect(url('/admin/users'));
        }

        $id = (int)($id ?: $this->postParam('id', $this->getParam('id', 0)));

        $user = User::find($id);

        if (!$user) {
            flash('error', 'المستخدم غير موجود');
            redirect(url('/admin/users'));
        }

        if (!self::canEditUserTarget($user)) {
            flash('error', 'هذا الحساب محمي، ولا يمكن تعديله إلا إذا كان مناديب مستويات في نفس التخصص المحدد لك.');
            redirect(url('/admin/users'));
            return;
        }

        $currentRole = $_SESSION['user_role'] ?? '';
        $currentUserId = (int)($_SESSION['user_id'] ?? 0);
        $scopedMajorId = null;

        if (in_array($currentRole, ['major_admin', 'manager'], true)) {
            $currentUser = User::find($currentUserId);
            $scopedMajorId = $currentUser ? ($currentUser->managed_major_id ?: $currentUser->major_id) : null;
        }

        $requestedRole = $this->postParam('role', $user->role);

        if (User::isSystemOwner($user)) {
            $requestedRole = 'admin';
        } elseif (in_array($currentRole, ['major_admin', 'manager'], true)) {
            if ($requestedRole === 'admin' || $requestedRole === 'major_admin') {
                $requestedRole = 'manager';
            }
            $majorId = $scopedMajorId;
        } else {
            $majorId = $this->postParam('major_id', $user->major_id);
            if ($currentRole !== 'admin' && $requestedRole === 'admin') {
                $requestedRole = $user->role;
            }
        }

        $levelId = $this->postParam('managed_level_id', $user->managed_level_id);

        $data = [
            'full_name' => trim($this->postParam('full_name', '')),
            'email' => trim($this->postParam('email', '')),
            'phone' => trim($this->postParam('phone', '')),
            'role' => $requestedRole,
            'major_id' => !empty($majorId) ? (int)$majorId : null,
            'managed_major_id' => !empty($majorId) ? (int)$majorId : null,
            'managed_level_id' => !empty($levelId) ? (int)$levelId : null,
            'is_active' => User::isSystemOwner($user) ? 1 : ($this->postParam('is_active', '1') ? 1 : 0),
        ];

        $errors = $this->validate($data, [
            'full_name' => 'required',
            'email' => 'required|email',
        ]);

        $password = $this->postParam('password', '');
        if (!empty($password)) {
            if (mb_strlen($password) < 8) {
                $errors['password'][] = 'حقل كلمة المرور يجب أن يكون 8 حروف على الأقل';
            }
        }

        if (!empty($errors)) {
            flash('error', 'يرجى التحقق من الحقول المطلوبة');
            $majors = Major::getActive();
            $levels = Level::getActive();
            $this->view('admin/users/edit', [
                'user' => $user,
                'errors' => $errors,
                'majors' => $majors,
                'levels' => $levels,
                'currentRole' => $currentRole,
                'scopedMajorId' => $scopedMajorId,
                'isOwnerTarget' => User::isSystemOwner($user),
            ]);
            return;
        }

        if ($data['email'] !== $user->email) {
            $existingUser = User::findByEmail($data['email']);
            if ($existingUser) {
                flash('error', 'البريد الإلكتروني مستخدم بالفعل');
                $majors = Major::getActive();
                $levels = Level::getActive();
                $this->view('admin/users/edit', [
                    'user' => $user,
                    'errors' => ['email' => ['البريد الإلكتروني مستخدم بالفعل']],
                    'majors' => $majors,
                    'levels' => $levels,
                    'currentRole' => $currentRole,
                    'scopedMajorId' => $scopedMajorId,
                    'isOwnerTarget' => User::isSystemOwner($user),
                ]);
                return;
            }
        }

        if (!empty($password)) {
            $data['password'] = User::hashPassword($password);
        }

        User::ensureColumns();
        User::updateRecord($id, $data);
        log_activity('update', 'users', $id, 'تحديث المستخدم: ' . $data['full_name']);
        flash('success', 'تم تحديث حساب ودور المستخدم بنجاح');
        redirect(url('/admin/users'));
    }

    public function delete($id = 0): void
    {
        if (!$this->canManage()) {
            flash('error', 'لا تملك صلاحية الوصول');
            redirect(url('/admin/courses'));
        }
        if (!verify_csrf()) {
            flash('error', 'طلب غير صالح أو انتهت مهلة الجلسة');
            redirect(url('/admin/users'));
        }
        $id = (int)($id ?: $this->getParam('id'));

        if ($id === 1 || User::isSystemOwner($id)) {
            flash('error', 'لا يمكن حذف حساب مالك النظام الرئيسي فهو محمي نهائياً');
            redirect(url('/admin/users'));
        }

        if ($id == $_SESSION['user_id']) {
            flash('error', 'لا يمكنك حذف حسابك الخاص');
            redirect(url('/admin/users'));
        }

        $user = User::find($id);

        if (!$user) {
            flash('error', 'المستخدم غير موجود');
            redirect(url('/admin/users'));
        }

        if (!self::canDeleteUserTarget($user)) {
            flash('error', 'صلاحية حذف الحسابات مقتصرة على المشرفين والمدراء فقط، ولا تتاح للمناديب.');
            redirect(url('/admin/users'));
            return;
        }

        User::deleteRecord($id);
        log_activity('delete', 'users', $id, 'حذف المستخدم: ' . $user->full_name);
        flash('success', 'تم حذف المستخدم بنجاح');
        redirect(url('/admin/users'));
    }

    public function promoteYear(): void
    {
        $currentRole = $_SESSION['user_role'] ?? '';
        if (!in_array($currentRole, ['admin', 'major_admin'], true)) {
            flash('error', 'صلاحية تنفيذ الترقية الأكاديمية مقتصرة على مدراء النظام ومسؤولي التخصصات فقط.');
            redirect(url('/admin/users'));
            return;
        }
        if (!verify_csrf()) {
            flash('error', 'طلب غير صالح أو انتهت مهلة الجلسة');
            redirect(url('/admin/users'));
        }

        $currentUserId = $_SESSION['user_id'] ?? 0;
        $scopedMajorId = null;

        if ($currentRole === 'major_admin') {
            $currentUser = User::find($currentUserId);
            $scopedMajorId = $currentUser ? ($currentUser->managed_major_id ?: $currentUser->major_id) : null;
        }

        $result = User::promoteAcademicYear($scopedMajorId);

        if (empty($result['success'])) {
            $errorMsg = $result['message'] ?? 'تعذر تنفيذ الترقية الأكاديمية.';
            flash('warning', $errorMsg);
            redirect(url('/admin/users'));
            return;
        }

        $promotedCount = $result['promoted'] ?? 0;
        $graduatedCount = $result['graduated'] ?? 0;
        $year = $result['year'] ?? date('Y');

        log_activity('update', 'users', $currentUserId, 'تنفيذ الترقية السنوية لعام ' . $year . ': ترقية ' . $promotedCount . ' وتخرج ' . $graduatedCount);
        
        $msg = '🎉 تم تنفيذ الترقية الأكاديمية السنوية لعام (' . $year . ') بنجاح!';
        if ($promotedCount > 0) {
            $msg .= ' تم ترقية ' . $promotedCount . ' طالباً ومندوباً للمستوى التالي.';
        }
        if ($graduatedCount > 0) {
            $msg .= ' وتخرج ' . $graduatedCount . ' طالباً بنجاح! 🎓';
        }
        if ($promotedCount === 0 && $graduatedCount === 0) {
            $msg .= ' (لم يتم العثور على طلاب أو مناديب بمستويات بحاجة للترقية حالياً).';
        }
        
        flash('success', $msg);
        redirect(url('/admin/users'));
    }
}
