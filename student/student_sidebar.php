<?php
// student_sidebar.php

// الاتصال بقاعدة البيانات
$host = 'localhost';
$dbname = 'smart_quran_schooli';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // لا نعرض الخطأ
}

// ✅ حساب حالة الاشتراك للطالب الحالي (Amira)
$student_name = "Amira";
$subscription_warning_status = false;

$stmt = $conn->prepare("SELECT subscription_end FROM users WHERE full_name = :name OR full_name LIKE :name_like");
$stmt->execute([':name' => $student_name, ':name_like' => '%' . $student_name . '%']);
$user_sub = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user_sub && $user_sub['subscription_end']) {
    $end_date = new DateTime($user_sub['subscription_end']);
    $today = new DateTime();
    $diff = $today->diff($end_date);
    $diff_days = $diff->days;
    
    if ($end_date < $today) {
        $subscription_warning_status = true;
    } elseif ($diff_days <= 7) {
        $subscription_warning_status = true;
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <button id="close-sidebar" class="close-sidebar-btn" onclick="toggleSidebar()">
            <i class="fas fa-arrow-right"></i>
        </button>
        <div style="padding: 25px 15px; text-align: center; border-bottom: 1px solid rgba(191, 161, 95, 0.2);">
            <i class="fas fa-user-graduate" style="font-size: 45px; color: #bfa15f; margin-bottom: 12px; display: block;"></i>
            <h3 style="color: #bfa15f; font-size: 20px;">لوحة الطالب</h3>
        </div>
    </div>
    <ul class="nav-links">
        <li onclick="location.href='student_dashboard.php'" class="<?php echo ($current_page == 'student_dashboard.php') ? 'active-gold' : ''; ?>">
            <i class="fas fa-home"></i> الرئيسية
        </li>
        <li onclick="location.href='student_progress.php'" class="<?php echo ($current_page == 'student_progress.php') ? 'active-gold' : ''; ?>">
            <i class="fas fa-book-reader"></i> سجل حفظي
        </li>
        <li onclick="location.href='student_results.php'" class="<?php echo ($current_page == 'student_results.php') ? 'active-gold' : ''; ?>">
            <i class="fas fa-file-invoice"></i> كشف نقاطي
        </li>
        <li onclick="location.href='student_schedule.php'" class="<?php echo ($current_page == 'student_schedule.php') ? 'active-gold' : ''; ?>">
            <i class="fas fa-calendar-alt"></i> توقيت الحلقات
        </li>
        <li onclick="location.href='announcements.php'" class="<?php echo ($current_page == 'announcements.php') ? 'active-gold' : ''; ?>">
            <i class="fas fa-bullhorn"></i> الإعلانات
        </li>
        
        <!-- ✅ الملف الشخصي مع رمز تحذير إذا لزم الأمر -->
        <li onclick="location.href='student_profile.php'" class="<?php echo ($current_page == 'student_profile.php') ? 'active-gold' : ''; ?>">
            <i class="fas fa-user-cog"></i>
            <span>الملف الشخصي</span>
            <?php if ($subscription_warning_status): ?>
                <span class="warning-icon">⚠️</span>
            <?php endif; ?>
        </li>
        
        <li onclick="location.href='../logout.php'">
            <i class="fas fa-sign-out-alt"></i> خروج
        </li>
    </ul>
</aside>

<style>
    .warning-icon {
        color: #ff9800;
        font-size: 1rem;
        margin-right: 5px;
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 0.5; }
        50% { opacity: 1; }
        100% { opacity: 0.5; }
    }
    
    .nav-links li {
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .nav-links li:hover {
        background: rgba(191, 161, 95, 0.3);
        border-radius: 8px;
    }
    
    .active-gold {
        background: #bfa15f;
        color: #0d3c1a;
        border-radius: 8px;
    }
</style>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        if (sidebar) {
            sidebar.classList.toggle('collapsed');
            if (mainContent) mainContent.classList.toggle('expanded');
        }
    }
</script>