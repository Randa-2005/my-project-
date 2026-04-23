<?php
session_start();

// 1. إعدادات قاعدة البيانات
$host = 'localhost';
$dbname = 'smart_quran_schooli';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("فشل الاتصال بالقاعدة: " . $e->getMessage());
}

// التحقق من أن الموظف مسجل دخول
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'موظف') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['full_name'];

// معالجة إرسال الطلب
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_leave'])) {
    $days = intval($_POST['days']);
    $start_date = $_POST['start'];
    $end_date = $_POST['end'];
    $reason = trim($_POST['reason']);
    $document_path = null;
    
    // معالجة رفع الملف
    if (isset($_FILES['doc']) && $_FILES['doc']['error'] == 0) {
        $upload_dir = 'uploads/leaves/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        $file_ext = strtolower(pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed)) {
            $file_name = 'leave_' . $user_id . '_' . time() . '.' . $file_ext;
            $document_path = $upload_dir . $file_name;
            move_uploaded_file($_FILES['doc']['tmp_name'], $document_path);
        }
    }
    
    // حفظ في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO leave_requests (user_id, user_name, days, start_date, end_date, reason, document_path) 
                            VALUES (:user_id, :user_name, :days, :start_date, :end_date, :reason, :document_path)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':user_name' => $user_name,
        ':days' => $days,
        ':start_date' => $start_date,
        ':end_date' => $end_date,
        ':reason' => $reason,
        ':document_path' => $document_path
    ]);
    
    echo "<script>alert('✅ تم إرسال طلب العطلة بنجاح!'); window.location.href = 'leave_status.php';</script>";
    exit();
}

$current_page = 'dash';
include 'reception_sidebar.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقديم طلب عطلة</title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #1a472a; --accent: #ffc107; }
        
        .leave-card { 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            max-width: 900px; 
            margin: 30px auto;
            border-top: 8px solid var(--primary);
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px; }
        .full-width { grid-column: span 2; }

        .input-group { display: flex; flex-direction: column; gap: 10px; }
        .input-group label { font-weight: bold; color: var(--primary); font-size: 0.95rem; }

        .input-field { 
            padding: 12px 15px; 
            border: 2px solid #e1e1e1; 
            border-radius: 10px; 
            width: 100%;
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif;
        }

        textarea.input-field { height: 120px; resize: none; }

        .upload-zone {
            border: 2px dashed #ccc;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .upload-zone:hover {
            border-color: var(--primary);
            background: #f0f4f1;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            width: 100%;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .submit-btn, .cancel-btn {
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            border: none;
        }

        .submit-btn {
            flex: 2;
            background-color: var(--primary);
            color: white;
        }

        .submit-btn:hover {
            background-color: #2e7d32;
        }

        .cancel-btn {
            flex: 1;
            background-color: #f4f4f4;
            color: #666;
            border: 1px solid #ddd;
        }

        .cancel-btn:hover {
            background-color: #e0e0e0;
        }

        .open-sidebar-btn {
            display: none;
            position: fixed;
            top: 15px;
            right: 15px;
            background: #1a472a;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2rem;
            z-index: 1001;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            .open-sidebar-btn {
                display: block;
            }
            .main-content {
                margin-right: 0 !important;
                padding-top: 60px;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .full-width {
                grid-column: span 1;
            }
            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <main class="main-content">
        <button id="open-sidebar" class="open-sidebar-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="header" style="padding: 20px 20px 0 20px;">
            <h2><i class="fas fa-calendar-alt"></i> طلب غياب رسمي</h2>
        </div>

        <div class="content-wrapper">
            <div class="leave-card">
                <form method="POST" enctype="multipart/form-data">
                    
                    <div class="form-grid">
                        <div class="input-group full-width">
                            <label><i class="fas fa-user"></i> الاسم واللقب:</label>
                            <input type="text" class="input-field" style="background:#f9f9f9;" value="<?php echo htmlspecialchars($user_name); ?>" readonly>
                        </div>

                        <div class="input-group full-width">
                            <label><i class="fas fa-hourglass-half"></i> مدة العطلة (بالأيام):</label>
                            <input type="number" name="days" class="input-field" placeholder="أدخل عدد الأيام" required>
                        </div>

                        <div class="input-group">
                            <label><i class="fas fa-calendar-day"></i> تاريخ البداية:</label>
                            <input type="date" name="start" class="input-field" required>
                        </div>
                        <div class="input-group">
                            <label><i class="fas fa-calendar-check"></i> تاريخ النهاية:</label>
                            <input type="date" name="end" class="input-field" required>
                        </div>
                        
                        <div class="input-group full-width">
                            <label><i class="fas fa-pen"></i> سبب طلب العطلة بالتفصيل:</label>
                            <textarea name="reason" class="input-field" placeholder="اكتب تبريرك الوافي هنا..." required></textarea>
                        </div>

                        <div class="input-group full-width">
                            <label><i class="fas fa-paperclip"></i> إرفاق الوثيقة (اختياري - PDF, JPG, PNG):</label>
                            <div class="upload-zone" onclick="document.getElementById('fileInp').click()">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--primary);"></i>
                                <p id="fileMsg">اضغط لتحميل الشهادة الطبية أو المبرر</p>
                                <input type="file" id="fileInp" name="doc" accept=".jpg,.jpeg,.png,.pdf" hidden onchange="checkFile()">
                            </div>
                        </div>

                        <div class="full-width btn-group">
                            <button type="submit" name="submit_leave" class="submit-btn">
                                <i class="fas fa-paper-plane"></i> تأكيد وإرسال الطلب
                            </button>
                            <button type="button" class="cancel-btn" onclick="history.back()">
                                <i class="fas fa-times"></i> إلغاء العملية
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if(sidebar) {
                sidebar.classList.toggle('active');
            }
        }

        function checkFile() {
            const inp = document.getElementById('fileInp');
            const msg = document.getElementById('fileMsg');
            if(inp.files.length > 0) {
                msg.innerHTML = "<i class='fas fa-check-circle' style='color: #1a472a;'></i> <b>تم اختيار:</b> " + inp.files[0].name;
                msg.style.color = "#1a472a";
            }
        }
    </script>
</body>
</html>