<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<?php
// تعريف مؤقت للحالة حتى تجهز قاعدة البيانات
$payment_status = 'danger'; 
?>
<button class="fixed-toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars" id="fixedToggleIcon"></i>
</button>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <button id="close-sidebar" class="close-sidebar-btn" onclick="toggleSidebar()">
         <i class="fas fa-arrow-right" id="toggleIcon"></i>
      </button>
        
        <div class="sidebar-header" style="padding: 25px 15px; text-align: center; border-bottom: 1px solid rgba(191, 161, 95, 0.2);">
    
    <i class="fas fa-book-reader" style="font-size: 45px; color: #bfa15f; margin-bottom: 12px; display: block; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));"></i>
    
    <h3 style="color: #bfa15f; font-size: 20px; font-family: 'Cairo', sans-serif; font-weight: bold; margin: 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
        لوحة الطالب
    </h3>
</div>
    </div>   

    <ul class="nav-links">
       <li onclick="location.href='student_dashboard.php'" 
         class="<?php echo ($current_page == 'student_dashboard.php') ? 'active-gold' : ''; ?>">
         <i class="fas fa-home"></i> الرئيسية
      </li>
      <li onclick="location.href='student_progress.php'" 
         class="<?php echo ($current_page == 'student_progress.php') ? 'active-gold' : ''; ?>">
         <i class="fas fa-book-reader"></i> سجل حفظي
      </li>
      <li onclick="location.href='student_results.php'" 
         class="<?php echo (basename($_SERVER['PHP_SELF']) == 'student_results.php') ? 'active-gold' : ''; ?>">
         <i class="fas fa-poll-h"></i>  كشف نقاطي
       </li>
      <li onclick="location.href='student_schedule.php'" 
         class="<?php echo ($current_page == 'student_schedule.php') ? 'active-gold' : ''; ?>">
         <i class="fas fa-calendar-alt"></i> توقيت الحلقات 
       </li>
   
       <li onclick="location.href='announcements.php'" 
         class="<?php echo ($current_page == 'announcements.php') ? 'active-gold' : ''; ?>">
         <i class="fas fa-bullhorn"></i> الإعلانات
       </li>
       <li onclick="location.href='student_profile.php'" 
          class="<?php echo ($current_page == 'student_profile.php') ? 'active-gold' : ''; ?> <?php echo ($payment_status == 'danger') ? 'danger-alert-bg' : ''; ?>" >
          <i class="fas fa-user-cog"></i> <span>الملف الشخصي</span>
          <?php if ($payment_status == 'danger'): ?>
        <i class="fas fa-exclamation-triangle pulse-icon" style="color: #ffc107; margin-right: 10px;"></i>
         <?php endif; ?>
       </li>       
        
       <li><i class="fas fa-sign-out-alt"></i> خروج</li>
    </ul> 
</aside>
</aside>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    const icon = document.getElementById('toggleIcon');

    if (sidebar) {
        sidebar.classList.toggle('collapsed');
        
        // تغيير شكل الأيقونة
        if (icon) {
            if (sidebar.classList.contains('collapsed')) {
                icon.className = 'fas fa-bars';
            } else {
                icon.className = 'fas fa-arrow-right';
            }
        }
    }

    // هنا نتأكد من وجود mainContent قبل محاولة تغييره
    if (mainContent) {
        mainContent.classList.toggle('expanded');
    }
}
</script>