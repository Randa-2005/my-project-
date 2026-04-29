<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar" id="sidebar">
    <button id="close-sidebar" class="close-sidebar-btn" onclick="toggleSidebar()">
        <i class="fas fa-arrow-right"></i>
    </button>

    <div class="sidebar-header">
        <i class="fas fa-concierge-bell"></i>
        <h3>مكتب الاستقبال</h3>
    </div>

    <ul class="nav-links">
        <li class="<?php echo ($current_page == 'reception_dash.php') ? 'active-gold' : ''; ?>">
            <a href="reception_dash.php">
                <i class="fas fa-home"></i>
                <span>الرئيسية</span>
            </a>
        </li>
        <li class="<?php echo ($current_page == 'student_registration.php') ? 'active-gold' : ''; ?>">
            <a href="student_registration.php">
                <i class="fas fa-user-plus"></i>
                <span>تسجيل جديد</span>
            </a>
        </li>
        <li class="<?php echo ($current_page == 'student_payments.php') ? 'active-gold' : ''; ?>">
            <a href="student_payments.php">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>مداخيل التلاميذ</span>
            </a>
        </li>
        <li class="<?php echo ($current_page == 'student_list.php') ? 'active-gold' : ''; ?>">
            <a href="student_list.php">
                <i class="fas fa-users"></i>
                <span>قائمة التلاميذ</span>
            </a>
        </li>
        <li onclick="location.href='schedule.php'"
            class="<?php echo ($current_page == 'schedule.php') ? 'active-gold' : ''; ?>">
                <i class="fas fa-calendar-alt"></i>
                <span>توقيت الأقسام</span>
            </a>
        </li>
         <li onclick="location.href='reception_leave.php'" 
             class="<?php echo ($current_page == 'reception_leave.php') ? 'active-gold' : ''; ?>">
             <i class="fas fa-calendar-times"></i>
             <span>طلب عطلة</span>
        </li>
        <li class="<?php echo ($current_page == 'announcements.php') ? 'active-gold' : ''; ?>">
            <a href="announcements.php">
                <i class="fas fa-bullhorn"></i>
                <span>الإعلانات</span>
            </a>
        </li>
        <li>
        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>خروج</span>
        </a>
    </li>
    </ul>
</aside>

<script>
// وظيفة فتح وإغلاق السايدبار
function toggleSidebar() {
    document.body.classList.toggle('sidebar-closed');
}
function toggleSidebar() {
    document.body.classList.toggle('sidebar-closed');
}
</script>