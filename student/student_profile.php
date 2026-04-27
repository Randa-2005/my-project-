<?php
session_start();
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

// ✅ تعيين بيانات الطالبة مباشرة (بدون تسجيل دخول)
$student_name = "Amira";

$stmt = $conn->prepare("SELECT id, full_name, email, phone, birth_date, subscription_end FROM users WHERE full_name = :name OR full_name LIKE :name_like");
$stmt->execute([':name' => $student_name, ':name_like' => '%' . $student_name . '%']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("الطالبة '$student_name' غير موجودة في قاعدة البيانات");
}

$user_id = $user['id'];
$success_message = '';
$error_message = '';

// ========== حساب حالة الاشتراك ==========
$subscription_end = $user['subscription_end'];
$today = date('Y-m-d');
$subscription_status = '';
$subscription_class = '';
$subscription_warning = '';
$days_remaining = 0;

if ($subscription_end) {
    $end_date = new DateTime($subscription_end);
    $today_date = new DateTime($today);
    $diff = $today_date->diff($end_date);
    $days_remaining = $diff->days;
    
    if ($end_date < $today_date) {
        $subscription_status = 'منتهي';
        $subscription_class = 'status-expired';
        $subscription_warning = '⚠️ اشتراكك منتهي، يرجى التجديد قريباً!';
    } elseif ($days_remaining <= 7) {
        $subscription_status = 'على وشك الانتهاء';
        $subscription_class = 'status-warning';
        $subscription_warning = '⚠️ ينتهي اشتراكك خلال ' . $days_remaining . ' أيام، يرجى التجديد!';
    } else {
        $subscription_status = 'نشط';
        $subscription_class = 'status-active';
        $subscription_warning = '';
    }
} else {
    $subscription_status = 'غير محدد';
    $subscription_class = 'status-expired';
    $subscription_warning = '⚠️ لا يوجد اشتراك نشط، يرجى الاشتراك للمتابعة';
}

// معالجة تحديث البيانات
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $new_password = $_POST['new_pass'] ?? '';
    $confirm_password = $_POST['confirm_pass'] ?? '';
    
    $update_fields = [];
    $params = [':id' => $user_id];
    $has_error = false;
    
    if (!empty($email) && $email != $user['email']) {
        $update_fields[] = "email = :email";
        $params[':email'] = $email;
    }
    
    if (!empty($phone) && $phone != $user['phone']) {
        $update_fields[] = "phone = :phone";
        $params[':phone'] = $phone;
    }
    
    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $update_fields[] = "password = :password";
            $params[':password'] = password_hash($new_password, PASSWORD_DEFAULT);
        } else {
            $error_message = "❌ كلمة السر غير متطابقة";
            $has_error = true;
        }
    }
    
    if (!$has_error && count($update_fields) > 0) {
        $sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute($params)) {
            $success_message = "✅ تم تحديث البيانات بنجاح";
            // إعادة تحميل البيانات
            $stmt = $conn->prepare("SELECT id, full_name, email, phone, birth_date, subscription_end FROM users WHERE id = :id");
            $stmt->execute([':id' => $user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error_message = "❌ حدث خطأ أثناء التحديث";
        }
    } elseif (!$has_error && count($update_fields) == 0 && empty($new_password)) {
        $error_message = "⚠ لم يتم تغيير أي بيانات";
    }
}

include 'student_sidebar.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الملف الشخصي - مدرسة أسرتي</title>
    <link rel="stylesheet" href="student_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f7f6;
            display: flex;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            margin-right: 260px;
        }

        .profile-card {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #1a472a 0%, #2e7d32 100%);
            padding: 25px;
            text-align: center;
            color: white;
        }

        .card-header i {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .success-msg {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            margin: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            margin: 20px;
            border-radius: 10px;
            text-align: center;
        }

        /* تنسيق حالة الاشتراك */
        .subscription-status {
            margin: 20px;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            font-weight: bold;
        }

        .status-active {
            background: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
        }

        .status-warning {
            background: #fff3cd;
            border: 2px solid #ffc107;
            color: #856404;
            animation: pulse 1.5s infinite;
        }

        .status-expired {
            background: #f8d7da;
            border: 2px solid #dc3545;
            color: #721c24;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .subscription-status i {
            font-size: 1.2rem;
            margin-left: 8px;
        }

        .info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 25px;
            border-bottom: 1px solid #eee;
        }

        .info-row label {
            font-weight: bold;
            color: #1a472a;
            width: 140px;
        }

        .info-row span {
            color: #333;
            flex: 1;
        }

        .info-row .edit-input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
        }

        .btn-edit, .btn-save, .btn-cancel {
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            margin: 20px;
        }

        .btn-edit {
            background: #bfa15f;
            color: white;
        }

        .btn-save {
            background: #28a745;
            color: white;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            margin-right: 10px;
        }

        .edit-section {
            display: none;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            padding: 15px 25px;
        }

        .readonly-field {
            background: #f5f5f5;
            padding: 8px 12px;
            border-radius: 8px;
            color: #666;
            flex: 1;
        }

        .toggle-menu-btn {
            display: none;
            position: fixed;
            top: 15px;
            right: 15px;
            background: #2e7d32;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            z-index: 1100;
        }

        @media (max-width: 768px) {
            .toggle-menu-btn { display: block; }
            .main-content { margin-right: 0 !important; padding-top: 60px; }
            .info-row { flex-direction: column; align-items: flex-start; gap: 10px; }
            .info-row label { width: 100%; }
            .info-row span, .info-row .edit-input, .readonly-field { width: 100%; }
        }
    </style>
</head>
<body>

    <?php include 'student_sidebar.php'; ?>
    <button class="toggle-menu-btn" id="open-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <main class="main-content">
        <div class="profile-card">
            <div class="card-header">
                <i class="fas fa-user-cog"></i>
                <h2>إعدادات الحساب الشخصي</h2>
                <p>مرحباً، <?php echo htmlspecialchars($user['full_name']); ?> ✨</p>
            </div>

            <?php if ($success_message): ?>
                <div class="success-msg"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="error-msg"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- حالة الاشتراك -->
            <div class="subscription-status <?php echo $subscription_class; ?>">
                <i class="fas fa-calendar-alt"></i>
                حالة الاشتراك: <strong><?php echo $subscription_status; ?></strong>
                <?php if ($subscription_warning): ?>
                    <br><small><?php echo $subscription_warning; ?></small>
                <?php endif; ?>
                <?php if ($subscription_end): ?>
                    <br><small>تاريخ الانتهاء: <?php echo date('d/m/Y', strtotime($subscription_end)); ?></small>
                <?php endif; ?>
            </div>

            <!-- قسم العرض -->
            <div id="view-section">
                <div class="info-row">
                    <label><i class="fas fa-user"></i> الاسم واللقب:</label>
                    <span><?php echo htmlspecialchars($user['full_name']); ?></span>
                </div>
                <div class="info-row">
                    <label><i class="fas fa-envelope"></i> البريد الإلكتروني:</label>
                    <span id="view_email"><?php echo htmlspecialchars($user['email'] ?? 'غير مدخل'); ?></span>
                </div>
                <div class="info-row">
                    <label><i class="fas fa-phone"></i> رقم الهاتف:</label>
                    <span id="view_phone"><?php echo htmlspecialchars($user['phone'] ?? 'غير مدخل'); ?></span>
                </div>
                <div class="info-row">
                    <label><i class="fas fa-calendar"></i> تاريخ الميلاد:</label>
                    <span><?php echo date('d/m/Y', strtotime($user['birth_date'])); ?></span>
                </div>
                <div class="button-group">
                    <button class="btn-edit" onclick="toggleEdit(true)">
                        <i class="fas fa-user-edit"></i> تعديل بياناتي
                    </button>
                </div>
            </div>

            <!-- قسم التعديل -->
            <div id="edit-section" class="edit-section">
                <form method="POST" onsubmit="return validatePasswords()">
                    <div class="info-row">
                        <label><i class="fas fa-user"></i> الاسم واللقب:</label>
                        <div class="readonly-field"><?php echo htmlspecialchars($user['full_name']); ?></div>
                    </div>
                    <div class="info-row">
                        <label><i class="fas fa-envelope"></i> البريد الإلكتروني:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="edit-input">
                    </div>
                    <div class="info-row">
                        <label><i class="fas fa-phone"></i> رقم الهاتف:</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="edit-input">
                    </div>
                    <div class="info-row">
                        <label><i class="fas fa-lock"></i> كلمة السر الجديدة:</label>
                        <input type="password" id="new_pass" name="new_pass" class="edit-input" placeholder="اتركها فارغة إذا لم ترد التغيير">
                    </div>
                    <div class="info-row">
                        <label><i class="fas fa-lock"></i> تأكيد كلمة السر:</label>
                        <input type="password" id="confirm_pass" name="confirm_pass" class="edit-input" placeholder="أعد كتابة كلمة السر الجديدة">
                    </div>
                    <div class="button-group">
                        <button type="submit" name="update_profile" class="btn-save">
                            <i class="fas fa-save"></i> حفظ التغييرات
                        </button>
                        <button type="button" class="btn-cancel" onclick="toggleEdit(false)">
                            <i class="fas fa-times"></i> إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            if (sidebar) {
                sidebar.classList.toggle('collapsed');
                if (mainContent) mainContent.classList.toggle('expanded');
            }
        }

        function toggleEdit(isEdit) {
            document.getElementById('view-section').style.display = isEdit ? 'none' : 'block';
            document.getElementById('edit-section').style.display = isEdit ? 'block' : 'none';
        }

        function validatePasswords() {
            const newPass = document.getElementById('new_pass').value;
            const confirmPass = document.getElementById('confirm_pass').value;
            if (newPass !== "" && newPass !== confirmPass) {
                alert("❌ كلمات السر غير متطابقة!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>