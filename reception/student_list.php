<?php 
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

// جلب قائمة الأفواج من قاعدة البيانات
$groups = [];
$stmt = $conn->query("SELECT id, group_name, teacher_name FROM groups WHERE status = 'active' ORDER BY group_name");
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

$current_page = 'dash'; 
include 'reception_sidebar.php'; 
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قوائم التلاميذ</title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-green: #1a472a; --bg-gray: #f4f7f6; --sidebar-width: 260px; }
        
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: var(--bg-gray); 
            margin: 0; 
            display: flex;
        }

        .main-content { 
            flex: 1; 
            margin-right: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .main-container { 
            max-width: 1000px; 
            margin: auto; 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            padding: 25px; 
        }

        .controls-section { 
            display: flex; 
            align-items: flex-end; 
            gap: 15px; 
            margin-bottom: 25px; 
            background: #fff; 
            padding: 20px; 
            border-radius: 10px; 
            border: 1px solid #eee; 
        }

        .filter-group { flex: 1; }
        .filter-group label { display: block; margin-bottom: 8px; font-weight: bold; color: var(--primary-green); }
        .input-field { width: 100%; height: 45px; border: 1px solid #ddd; border-radius: 8px; padding: 0 10px; }

        .btn-print { 
            background: var(--primary-green); 
            color: white; 
            border: none; 
            height: 45px; 
            padding: 0 25px; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: bold;
            display: flex; 
            align-items: center; 
            gap: 10px;
        }

        .list-header-info { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            background: var(--primary-green); 
            color: white; 
            padding: 15px 25px; 
            border-radius: 8px 8px 0 0;
            margin-top: 20px;
        }

        .students-table { width: 100%; border-collapse: collapse; background: white; border: 1px solid #eee; }
        .students-table th { background: #f8f9fa; color: var(--primary-green); padding: 15px; border: 1px solid #eee; font-size: 0.95rem; }
        .students-table td { padding: 12px; border: 1px solid #eee; text-align: center; color: #333; }

        @media print {
            .main-content { margin-right: 0 !important; padding: 0 !important; }
            .sidebar, .controls-section, .btn-print, .reception-sidebar { display: none !important; }
            body { background: white; }
            .main-container { box-shadow: none; width: 100%; max-width: 100%; }
        }

        @media (max-width: 768px) {
            .main-content { margin-right: 0; padding: 15px; }
            .controls-section { flex-direction: column; align-items: stretch; }
            .btn-print { width: 100%; justify-content: center; }
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
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>

    <?php include 'reception_sidebar.php'; ?>

    <main class="main-content">
        <button id="open-sidebar" class="open-sidebar-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="main-container">
            <h2 style="color: var(--primary-green); border-right: 5px solid var(--primary-green); padding-right: 15px; margin-bottom: 25px;">
                <i class="fas fa-list-ul"></i> قوائم تلاميذ الأفواج
            </h2>

            <div class="controls-section">
                <div class="filter-group">
                    <label>تصفية حسب الفوج:</label>
                    <select id="groupSelect" class="input-field" onchange="loadStudentList()">
                        <option value="">-- اختر الفوج لعرض القائمة --</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?php echo $group['id']; ?>">
                                <?php echo htmlspecialchars($group['group_name']); ?> (أ. <?php echo htmlspecialchars($group['teacher_name']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> طباعة القائمة
                </button>
            </div>

            <div id="listInfo" style="display: none;">
                <div class="list-header-info">
                    <div id="displayGroupName"><i class="fas fa-users"></i> الفوج: ---</div>
                    <div id="displayTeacherName"><i class="fas fa-chalkboard-teacher"></i> الأستاذ: ---</div>
                </div>

                <table class="students-table">
                    <thead>
                        <tr>
                            <th style="width: 20%;">رقم التسجيل</th>
                            <th style="width: 50%;">الاسم واللقب</th>
                            <th style="width: 30%;">تاريخ الميلاد</th>
                        </tr>
                    </thead>
                    <tbody id="studentsBody">
                        <tr class="empty-message">
                            <td colspan="3">📋 يرجى اختيار فوج لعرض القائمة</td>
                        </tr>
                    </tbody>
                </table>
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

function loadStudentList() {
    const groupId = document.getElementById('groupSelect').value;
    const listInfo = document.getElementById('listInfo');
    const studentsBody = document.getElementById('studentsBody');
    
    if (!groupId) {
        listInfo.style.display = 'none';
        return;
    }
    
    // إظهار مؤشر تحميل
    studentsBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">⏳ جاري التحميل...</td></tr>';
    listInfo.style.display = 'block';
    
    // جلب بيانات الفوج والتلاميذ عبر AJAX
    fetch('get_group_students.php?group_id=' + groupId)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                studentsBody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: red;">❌ ' + data.error + '</td></tr>';
                return;
            }
            
            // عرض معلومات الفوج
            document.getElementById('displayGroupName').innerHTML = '<i class="fas fa-users"></i> الفوج: ' + data.group_name;
            document.getElementById('displayTeacherName').innerHTML = '<i class="fas fa-chalkboard-teacher"></i> الأستاذ: ' + data.teacher_name;
            
            // عرض قائمة التلاميذ
            if (data.students.length === 0) {
                studentsBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">📭 لا يوجد تلاميذ مسجلين في هذا الفوج</td></tr>';
            } else {
                studentsBody.innerHTML = data.students.map(student => `
                    <tr>
                        <td><strong>#${student.id}</strong></td>
                        <td style="text-align: right; padding-right: 25px;">${student.full_name}</td>
                        <td>${student.birth_date}</td>
                    </tr>
                `).join('');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            studentsBody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: red;">❌ خطأ في تحميل البيانات</td></tr>';
        });
}
</script>

</body>
</html>