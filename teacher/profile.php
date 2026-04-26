<?php
$host = 'localhost';
$dbname = 'smart_quran_schooli';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}

// تثبيت اسم الأستاذة مباشرة (ثابت لا يتغير)
$teacher_name = "أ. فاطمة الزهراء";
$success_message = '';
$error_message = '';

// جلب بيانات الأستاذة من قاعدة البيانات
$stmt = $conn->prepare("SELECT id, full_name, email, phone, birth_date FROM users WHERE full_name = :name");
$stmt->execute([':name' => $teacher_name]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("الأستاذة 'أ. فاطمة الزهراء' غير موجودة في قاعدة البيانات");
}

$user_id = $user['id'];

// معالجة تحديث البيانات (البريد الإلكتروني ورقم الهاتف فقط)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $new_password = $_POST['new_pass'] ?? '';
    $confirm_password = $_POST['confirm_pass'] ?? '';
    
    $update_fields = [];
    $params = [':id' => $user_id];
    $has_error = false;
    
    // تحديث البريد الإلكتروني إذا تغير
    if (!empty($email) && $email != $user['email']) {
        $update_fields[] = "email = :email";
        $params[':email'] = $email;
    }
    
    // تحديث رقم الهاتف إذا تغير
    if (!empty($phone) && $phone != $user['phone']) {
        $update_fields[] = "phone = :phone";
        $params[':phone'] = $phone;
    }
    
    // ✅ التحقق من كلمة السر فقط إذا تم إدخالها (وليست فارغة)
    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $update_fields[] = "password = :password";
            $params[':password'] = password_hash($new_password, PASSWORD_DEFAULT);
        } else {
            $error_message = "❌ كلمة السر غير متطابقة";
            $has_error = true;
        }
    }
    
    // إذا لم يكن هناك خطأ ولدينا تحديثات
    if (!$has_error && count($update_fields) > 0) {
        $sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute($params)) {
            $success_message = "✅ تم تحديث البيانات بنجاح";
            // إعادة تحميل البيانات
            $stmt = $conn->prepare("SELECT id, full_name, email, phone, birth_date FROM users WHERE id = :id");
            $stmt->execute([':id' => $user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error_message = "❌ حدث خطأ أثناء التحديث";
        }
    } elseif (!$has_error && count($update_fields) == 0 && empty($new_password)) {
        // لا توجد تغييرات
        $error_message = "⚠ لم يتم تغيير أي بيانات";
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الملف الشخصي - مدرسة أسرتي القرآنية</title>
    <link rel="stylesheet" href="teacher_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --main-green: #0d3c1a; --gold: #bfa15f; --bg-gray: #f4f4f4; }
        body { font-family: 'Cairo', sans-serif; background-color: var(--bg-gray); margin: 0; display: flex; }
        
        .sidebar {
            width: 250px;
            position: fixed;
            height: 100vh;
            transition: all 0.4s ease;
            z-index: 1000;
            background-color: #0d3c1a;
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            background-blend-mode: multiply;
            background-repeat: repeat;
            background-size: 100px;
        }
        
        .sidebar.sidebar-closed { transform: translateX(100%); width: 0; overflow: hidden; }
        
        .main-content { flex: 1; margin-right: 260px; transition: 0.4s; padding: 20px; }
        .main-content.full-width { margin-right: 0; }
        
        .profile-card { max-width: 650px; margin: 40px auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .card-header { background: var(--main-green); padding: 15px 25px; display: flex; align-items: center; gap: 15px; border-bottom: 4px solid var(--gold); }
        
        .info-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; }
        .info-row label { color: var(--gold); font-weight: bold; }
        .info-row span { color: var(--main-green); font-weight: 500; }
        
        .input-field { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px; box-sizing: border-box; }
        .input-field[readonly] { background: #f5f5f5; cursor: not-allowed; }
        
        .btn-edit { background: var(--gold); color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .btn-save { background: var(--main-green); color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; }
        
        .success-msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center; }
        .error-msg { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center; }
        
        .toggle-btn { display: none; border: none; background: none; }
        
        @media (max-width: 768px) {
            .main-content { margin-right: 0; }
            .sidebar { transform: translateX(100%); }
            .toggle-btn { display: block; }
        }
    </style>
</head>
<body class="dashboard-body">

    <aside class="sidebar" id="sidebar">
        <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
        <div class="sidebar-header">
            <button id="close-sidebar" class="close-sidebar-btn" style="background: none; border: none; color: #bfa15f; font-size: 1.2rem; cursor: pointer; margin: 10px;">
                <i class="fas fa-arrow-right"></i>
            </button>
            <div style="padding: 25px 15px; text-align: center; border-bottom: 1px solid rgba(191, 161, 95, 0.2);">
                <i class="fas fa-chalkboard-teacher" style="font-size: 45px; color: #bfa15f; margin-bottom: 12px; display: block;"></i>
                <h3 style="color: #bfa15f; font-size: 20px;">لوحة الأستاذ</h3>
            </div>
        </div>   
        <ul class="nav-links" style="list-style: none; padding: 0;">
            <li onclick="location.href='teacher_groups.php'" style="padding: 12px 20px; cursor: pointer; color: white; display: flex; align-items: center; gap: 10px;"><i class="fas fa-home"></i> الرئيسية</li>
            <li onclick="location.href='groups_list.php'" style="padding: 12px 20px; cursor: pointer; color: white; display: flex; align-items: center; gap: 10px;"><i class="fas fa-users"></i> قوائم التلاميذ</li>
            <li onclick="location.href='exam_history.php'" style="padding: 12px 20px; cursor: pointer; color: white; display: flex; align-items: center; gap: 10px;"><i class="fas fa-file-invoice"></i> سجل الامتحانات</li>
            <li onclick="location.href='general_schedule.php'" style="padding: 12px 20px; cursor: pointer; color: white; display: flex; align-items: center; gap: 10px;"><i class="fas fa-calendar-alt"></i> البرنامج العام</li>
            <li onclick="location.href='announcements.php'" style="padding: 12px 20px; cursor: pointer; color: white; display: flex; align-items: center; gap: 10px;"><i class="fas fa-bullhorn"></i> الإعلانات</li>
            <li onclick="location.href='teacher_leave.php'" style="padding: 12px 20px; cursor: pointer; color: white; display: flex; align-items: center; gap: 10px;"><i class="fas fa-calendar-times"></i> طلب عطلة</li>
            <li onclick="location.href='profile.php'" class="active-gold" style="padding: 12px 20px; cursor: pointer; background: #bfa15f; color: #0d3c1a; display: flex; align-items: center; gap: 10px; border-radius: 8px;"><i class="fas fa-user-cog"></i> الملف الشخصي</li>
            <li style="padding: 12px 20px; cursor: pointer; color: white; display: flex; align-items: center; gap: 10px;"><i class="fas fa-sign-out-alt"></i> خروج</li>
        </ul>
    </aside>

    <main class="main-content" id="mainContent">
        <button id="open-sidebar-btn" class="toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="profile-card">
            <div class="card-header">
                <i class="fas fa-user-circle" style="color: var(--gold); font-size: 2rem;"></i>
                <h3 style="color: var(--gold); margin: 0; font-size: 1.2rem;">إعدادات الحساب الشخصي</h3>
            </div>

            <?php if ($success_message): ?>
                <div class="success-msg"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="error-msg"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- عرض البيانات -->
            <div id="view-section" style="padding: 30px;">
                <div class="info-row"><label>الاسم واللقب:</label><span><?php echo htmlspecialchars($user['full_name']); ?></span></div>
                <div class="info-row"><label>تاريخ الازدياد:</label><span><?php echo date('d/m/Y', strtotime($user['birth_date'])); ?></span></div>
                <div class="info-row"><label>البريد الإلكتروني:</label><span><?php echo htmlspecialchars($user['email'] ?? 'غير مدخل'); ?></span></div>
                <div class="info-row"><label>رقم الهاتف:</label><span><?php echo htmlspecialchars($user['phone'] ?? 'غير مدخل'); ?></span></div>
                <div class="info-row" style="border:none;"><label>تاريخ الانضمام:</label><span>---</span></div>

                <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                    <button class="btn-edit" onclick="toggleEdit(true)">
                        <i class="fas fa-user-edit"></i> تعديل بياناتي
                    </button>
                </div>
            </div>

            <!-- نموذج التعديل (البريد والهاتف فقط + كلمة السر اختياري) -->
            <div id="edit-section" style="padding: 30px; display: none;">
                <h4 style="color: var(--main-green); margin-bottom: 20px;">تحديث معلومات التواصل</h4>
                <form method="POST" onsubmit="return validateForm()">
                    <!-- الاسم ثابت (غير قابل للتعديل) -->
                    <div style="margin-bottom: 15px;">
                        <label>الاسم واللقب (ثابت)</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['full_name']); ?>" class="input-field" readonly>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="input-field">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>رقم الهاتف</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="input-field">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                        <div>
                            <label>كلمة السر الجديدة (اختياري)</label>
                            <input type="password" id="new_pass" name="new_pass" class="input-field" placeholder="اتركها فارغة إذا لم ترد التغيير">
                        </div>
                        <div>
                            <label>تأكيد كلمة السر</label>
                            <input type="password" id="confirm_pass" name="confirm_pass" class="input-field" placeholder="أعد كتابة كلمة السر الجديدة">
                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" onclick="toggleEdit(false)" style="padding:10px 20px; border:none; border-radius:8px; cursor:pointer;">إلغاء</button>
                        <button type="submit" name="update_profile" class="btn-save">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const closeBtn = document.getElementById('close-sidebar');
        const openBtn = document.getElementById('open-sidebar-btn');

        function toggleSidebar() {
            if (sidebar.classList.contains('sidebar-closed')) {
                sidebar.classList.remove('sidebar-closed');
                mainContent.classList.remove('full-width');
                openBtn.style.display = 'none';
            } else {
                sidebar.classList.add('sidebar-closed');
                mainContent.classList.add('full-width');
                openBtn.style.display = 'block';
            }
        }

        if (closeBtn) {
            closeBtn.onclick = function() {
                sidebar.classList.add('sidebar-closed');
                mainContent.classList.add('full-width');
                if (openBtn) openBtn.style.display = 'block';
            };
        }

        if (openBtn) {
            openBtn.onclick = function() {
                sidebar.classList.remove('sidebar-closed');
                mainContent.classList.remove('full-width');
                this.style.display = 'none';
            };
        }

        function toggleEdit(isEdit) {
            document.getElementById('view-section').style.display = isEdit ? 'none' : 'block';
            document.getElementById('edit-section').style.display = isEdit ? 'block' : 'none';
        }

        // ✅ دالة التحقق من صحة النموذج - لا تظهر خطأ إلا إذا تم إدخال كلمة سر غير متطابقة
        function validateForm() {
            const newPass = document.getElementById('new_pass').value;
            const confirmPass = document.getElementById('confirm_pass').value;
            
            // فقط إذا تم إدخال كلمة سر جديدة (ليست فارغة) نتحقق من تطابقها
            if (newPass !== "" && newPass !== confirmPass) {
                alert("❌ كلمات السر غير متطابقة!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>