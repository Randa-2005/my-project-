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

// جلب قائمة الأفواج للقائمة المنسدلة
$groups = [];
$stmt = $conn->query("SELECT id, group_name, teacher_name FROM groups WHERE status = 'active' ORDER BY group_name");
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

$current_page = 'schedule';
include 'reception_sidebar.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>برنامج الأفواج والقاعات</title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-green: #1a472a; --accent-gold: #ffc107; }
        .main-container { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; }
        
        .status-bar { display: flex; justify-content: space-between; align-items: center; background: var(--primary-green); color: white; padding: 15px 25px; }

        .schedule-table { width: 100%; border-collapse: collapse; }
        .schedule-table th { background: #f8f9fa; color: var(--primary-green); padding: 12px; border: 1px solid #eee; font-size: 0.9rem; }
        .schedule-table td { padding: 10px; border: 1px solid #eee; text-align: center; vertical-align: top; width: 14%; }

        .general-card { background: #f0f4f1; border-bottom: 3px solid var(--primary-green); padding: 8px; border-radius: 4px; margin-bottom: 5px; font-size: 0.85rem; }
        .general-card .group-name { font-weight: bold; color: #333; display: block; }
        .general-card .room-label { color: var(--primary-green); font-weight: bold; font-size: 0.8rem; }
        .general-card .subject { color: #666; font-size: 0.75rem; }

        .special-card { background: #fff3cd; border-right: 4px solid var(--accent-gold); padding: 10px; border-radius: 5px; text-align: right; }
        .special-card .subject { font-weight: bold; color: #333; }
        .special-card .teacher { color: #666; font-size: 0.8rem; }
        
        .time-label { background: #fdfdfd; font-weight: bold; color: var(--primary-green); width: 90px !important; }
        
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

        .loading {
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
        <div class="header" style="padding: 20px 0;">
            <h2><i class="fas fa-chalkboard-teacher"></i> توزيع الأفواج والقاعات</h2>
        </div>

        <div class="content-wrapper" style="padding: 0 0 20px 0;">
            
            <div class="stat-card" style="display: flex; align-items: flex-end; gap: 20px; margin-bottom: 20px; background: white; padding: 20px; border-radius: 12px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">تصفية حسب الفوج (أو اتركها للعرض العام):</label>
                    <select id="groupSelector" class="input-field" style="flex: 1; height: 45px; margin: 0;" onchange="loadSchedule()">
                        <option value="all">-- عرض البرنامج العام (كل الأفواج) --</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?php echo $group['id']; ?>">
                                <?php echo htmlspecialchars($group['group_name']); ?> (أ. <?php echo htmlspecialchars($group['teacher_name']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button class="confirm-btn" onclick="printSchedule()" style="background: #1a472a; color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer;">
                    <i class="fas fa-print"></i> طباعة البرنامج
                </button>
            </div>

            <div class="main-container">
                <div class="status-bar">
                    <div id="viewTitle"><i class="fas fa-globe"></i> البرنامج العام للقاعات</div>
                    <div id="viewDetail">كل الأفواج | السبت - الخميس</div>
                </div>

                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>الوقت</th>
                            <th>السبت</th>
                            <th>الأحد</th>
                            <th>الإثنين</th>
                            <th>الثلاثاء</th>
                            <th>الأربعاء</th>
                            <th>الخميس</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleBody">
                        <tr class="loading">
                            <td colspan="7">⏳ جاري تحميل البرنامج...</td>
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

function printSchedule() {
    window.print();
}

function loadSchedule() {
    const groupId = document.getElementById('groupSelector').value;
    const scheduleBody = document.getElementById('scheduleBody');
    const viewTitle = document.getElementById('viewTitle');
    const viewDetail = document.getElementById('viewDetail');
    
    // إظهار مؤشر تحميل
    scheduleBody.innerHTML = '<tr class="loading"><td colspan="7">⏳ جاري التحميل...</td></tr>';
    
    // جلب البيانات من الخادم
    fetch('get_schedule.php?group_id=' + groupId)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                scheduleBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: red;">❌ ' + data.error + '</td></tr>';
                return;
            }
            
            // تحديث عنوان الصفحة
            if (groupId === 'all') {
                viewTitle.innerHTML = '<i class="fas fa-globe"></i> البرنامج العام للقاعات';
                viewDetail.innerText = 'كل الأفواج | ' + data.week_days;
            } else {
                viewTitle.innerHTML = '<i class="fas fa-user-grad"></i> ' + data.group_name;
                viewDetail.innerText = 'الأستاذ: ' + data.teacher_name;
            }
            
            // بناء الجدول
            let html = '';
            const timeSlots = Object.keys(data.schedule);
            
            for (let i = 0; i < timeSlots.length; i++) {
                const timeSlot = timeSlots[i];
                const days = data.schedule[timeSlot];
                
                html += '<tr>';
                html += '<td class="time-label">' + timeSlot + '</td>';
                
                const dayOrder = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'];
                for (let j = 0; j < dayOrder.length; j++) {
                    const day = dayOrder[j];
                    const session = days[day] || null;
                    
                    if (session && session.length > 0) {
                        if (groupId === 'all') {
                            // عرض عام: كل الأفواج
                            html += '<td>';
                            for (let k = 0; k < session.length; k++) {
                                html += '<div class="general-card">';
                                html += '<span class="group-name">' + session[k].group_name + '</span>';
                                html += '<div class="teacher">الأستاذ: ' + session[k].teacher_name + '</div>';

                                html += '<span class="room-label">' + session[k].room_number + '</span>';
                                html += '</div>';
                            }
                            html += '</td>';
                        } else {
                            // عرض خاص بفوج واحد
                            html += '<td>';
                            for (let k = 0; k < session.length; k++) {
                                html += '<div class="special-card">';
                                
                                html += '<div class="teacher">الأستاذ: ' + session[k].teacher_name + '</div>';
                                html += '<div class="room-label">' + session[k].room_number + '</div>';
                                html += '</div>';
                            }
                            html += '</td>';
                        }
                    } else {
                        html += '<td>---</td>';
                    }
                }
                html += '</tr>';
            }
            
            scheduleBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            scheduleBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: red;">❌ خطأ في تحميل البيانات</td></tr>';
        });
}

// تحميل البرنامج عند فتح الصفحة
window.onload = loadSchedule;
</script>

</body>
</html>