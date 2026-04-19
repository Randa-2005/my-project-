<?php
include 'db1.php'; // استدعاء الاتصال

// استعلام لجلب الأساتذة، العمال، والطلاب الذين تم تفعيلهم فقط
$sql = "SELECT * FROM users 
        WHERE role IN ('أستاذ', 'عامل') 
        OR (role = 'طالب' AND is_verified = 1)";

$result = mysqli_query($conn, $sql);
?><!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نظام إدارة مدرسة أسرتي الذكي - لوحة السيادة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Amiri:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-green: hsl(116, 62%, 18%);
            --gold: hsl(0, 0%, 97%);
            --light-bg: hsl(120, 75%, 16%);
            --cream: #ffffff;
            --danger: #e74c3c;
        }

        body { margin: 0; font-family: 'Cairo', sans-serif; background-color: var(--light-bg); display: flex; height: 100vh; overflow: hidden; }

        /* 🟢 القائمة الجانبية المتطورة (Sidebar) */
       .sidebar {
            width: 280px;
            background-color: var( #22480e);
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            color: white; height: 100vh; position: fixed; border-left: 5px solid var(--gold);overflow-y:auto;
        }

        .sidebar-header { padding: 30px; text-align: center; background: hsla(67, 72%, 85%, 0.60); }

        .admin-logo-icon { font-size: 70px; color: var(--primary-green); margin-bottom: 15px; display: block; }

        .nav-links { list-style: none; padding: 0; margin: 0; }

        .nav-links li a { 
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 25px; color: #eee; text-decoration: none; 
            font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: 0.3s;
        }

        .nav-links li a:hover { background-color: rgba(212, 175, 55, 0.15); color: var(--gold); }
        .nav-links li a.active { background-color: rgba(212, 175, 55, 0.2); color: var(--gold); }

        /* ⚪ الميدان الأبيض الرئيسي (المحتوى) */
        .main-content { margin-right: 280px; width: calc(100% - 280px); padding: 40px; overflow-y: auto; height: 100vh; box-sizing: border-box; }
        
        /* تصميم الأكورديون الفاخر */
        .task-header {
            background-color: var(--cream);
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            padding: 20px; border-radius: 12px; margin-bottom: 5px;
            border-right: 10px solid var(--gold); box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            display: flex; align-items: center; justify-content: space-between;
            cursor: pointer; transition: 0.3s;
        }
        .task-header:hover { transform: scale(1.01); box-shadow: 0 6px 12px rgba(0,0,0,0.1); }
        .task-header h2 { margin: 0; font-family: 'Amiri'; color: var(--primary-green); font-size: 22px; }

        .task-content { 
            display: none; background: white; padding: 25px; border-radius: 0 0 12px 12px; 
            margin-bottom: 25px; border: 1px solid #eee; border-top: none;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        /* الجداول والتنسيقات */
        table { width: 100%; border-collapse: separate; border-spacing: 0 8px; margin-top: 10px; }
        th { background-color: var(--primary-green); color: var(--gold); padding: 15px; text-align: right; border-radius: 5px; cursor: pointer; }
        td { background-color: #fcfcfc; padding: 15px; border-bottom: 1px solid #eee; font-size: 15px; }
        
        .admin-select { padding: 8px; border-radius: 5px; border: 1px solid var(--gold); cursor: pointer; width: 100%; font-family: 'Cairo'; }.search-box { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--gold); margin-bottom: 15px; font-family: 'Cairo'; }.badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; color: white; margin-left: 5px; }
        .teacher-bg { background: #4a5568; } .student-bg { background: #3182ce; } .staff-bg {background: #718096; }

        .status-active { color: #2f855a; font-weight: bold; }
        .status-suspended { color: #d69e2e; font-weight: bold; }
        .status-closed { color: var(--danger); font-weight: bold; }

        /* ✨ تنسيق الأزرار الأنيق (New Elegant Buttons) ✨ */
        .quick-action-cell {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        .btn-lux {
            border: none;
            border-radius: 8px;
            padding: 8px 15px;
            color: white;
            font-family: 'Cairo', sans-serif;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-lux:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
            filter: brightness(1.1);
        }

        .btn-warn { background: linear-gradient(135deg, #f39c12, #e67e22); } /* إنذار ذهبي */
        .btn-renew { background: linear-gradient(135deg, #27ae60, #2ecc71); } /* تجديد أخضر */
        .btn-evict { background: linear-gradient(135deg, #e74c3c, #c0392b); } /* طرد أحمر */

    </style>
</head>
<body>

     <?php include 'sidebar.php'; ?>
      

    <main class="main-content">
        <h1 style="font-family: 'Amiri'; color: var( #ffffff); margin-bottom: 35px; border-bottom: 2px solid var(--gold); display: inline-block; padding-bottom: 10px;">مركز الإدارة والتحكم المركزي</h1>

        <div class="task-header" onclick="toggleTask('accountsControl')">
            <h2>🔐 إدارة الحسابات والبحث المتقدم</h2>
            <i class="fas fa-user-lock"></i>
        </div>
        <div id="accountsControl" class="task-content">
            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <input type="text" id="accSearch" onkeyup="masterFilter('accSearch', 'accRole', 'accTableBody')" class="search-box" placeholder="🔍 ابحث عن اسم (طالب، أستاذ، موظف)...">
                <select id="accRole" onchange="masterFilter('accSearch', 'accRole', 'accTableBody')" style="padding: 10px; border-radius: 8px; border: 1px solid var(--gold); height: 48px;">
                    <option value="all">كل الفئات</option>
                    <option value="teacher">الأساتذة</option>
                    <option value="student">الطلاب</option>
                    <option value="staff">العمال</option>
                </select>
            </div>
          <table id="accTable">
    <thead>
        <tr>
            <th onclick="sortTable('accTable', 0)">الاسم (أبجدي) ↕️</th>
            <th>الصفة</th>
            <th>الحالة</th>
            <th>صلاحية المدير</th>
        </tr>
    </thead>
    <tbody id="accTableBody">
    <?php 
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            // 1. تحديد الكلاس الخاص بالدور (أستاذ، طالب، موظف)
            $roleClass = 'staff-bg';
            $dataRole = 'staff';
            if($row['role'] == 'أستاذ') { $roleClass = 'teacher-bg'; $dataRole = 'teacher'; }
            if($row['role'] == 'طالب') { $roleClass = 'student-bg'; $dataRole = 'student'; }

            // 2. تحديد نصوص وحالات الألوان
            $statusText = ($row['status'] == 'active') ? ' نشط' : (($row['status'] == 'suspended') ? ' معلق' : ' مغلق');
            $statusClass = 'status-' . $row['status'];
    ?>
        <tr id="row-<?php echo $row['id']; ?>" data-role="<?php echo $dataRole; ?>">
            <td><?php echo $row['full_name']; ?></td>
            <td><span class="badge <?php echo $roleClass; ?>"><?php echo $row['role']; ?></span></td>
            
            <td class="st-cell" id="status-cell-<?php echo $row['id']; ?>">
                <span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
            </td>

            <td>
                <select onchange="updateRowStatus(this)" class="admin-select" data-id="<?php echo $row['id']; ?>">
                    <option value="active" <?php if($row['status'] == 'active') echo 'selected'; ?>>تنشيط الحساب</option>
                    <option value="suspended" <?php if($row['status'] == 'suspended') echo 'selected'; ?>>تعليق مؤقت</option>
                    <option value="closed" <?php if($row['status'] == 'closed') echo 'selected'; ?>>قفل الحساب</option>
                </select>
            </td>
        </tr>
    <?php 
        } 
    } else {
        echo "<tr><td colspan='4' style='text-align:center;'>لا توجد حسابات مسجلة حالياً</td></tr>";
    }
    ?>
    </tbody>
</table>
        </div>

        <div class="task-header" onclick="toggleTask('absenceSection')">
            <h2>🚨 بوابة الانضباط والغياب</h2>
            <i class="fas fa-user-clock"></i>
        </div>
      <div id="absenceSection" class="task-content">
            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <select id="absRole" onchange="masterFilter('', 'absRole', 'absTableBody')" style="padding: 10px; border-radius: 8px; border: 1px solid var(--gold); width: 100%;">
                    <option value="all">كل المتجاوزين</option>
                    <option value="teacher">الأساتذة</option>
                    <option value="student">الطلاب</option>
                </select>
            </div>
            <table>
                <thead>
                    <tr><th>الاسم</th><th>الفئة</th><th>الغيابات</th><th style="text-align:center;">إجراء سريع</th></tr>
                </thead>
                <tbody id="absTableBody">
                    <tr data-role="student">
                        <td>محمد أحمد</td>
                        <td>طالب</td>
                        <td style="color:var(--danger); font-weight:bold;">5</td>
                        <td class="quick-action-cell">
                            <button class="btn-lux btn-warn"><i class="fas fa-exclamation-triangle"></i> إنذار</button>
                            <button class="btn-lux btn-renew"><i class="fas fa-sync-alt"></i> تجديد</button>
                            <button class="btn-lux btn-evict"><i class="fas fa-user-times"></i> طرد</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="task-header" onclick="toggleTask('chartSection')">
            <h2>📈 إحصائيات النجاح والرسوب (أرشيف الأحزاب)</h2>
            <i class="fas fa-chart-line"></i>
        </div>
        <div id="chartSection" class="task-content">
            <div style="margin-bottom: 20px;">
                <label>السنة:</label> <select id="yearSelect" class="admin-select" style="width:auto; display:inline-block;"><option value="2026" selected>2026</option></select>
                <label style="margin-right:15px;">الشهر:</label> <select id="monthSelect" onchange="updateChart()" class="admin-select" style="width:auto; display:inline-block;"><option value="1">يناير</option><option value="10">أكتوبر</option></select>
            </div>
            <canvas id="successChart" style="max-height: 400px;"></canvas>
        </div>

        <div class="task-header" onclick="toggleTask('countSection')">
            <h2>📊 إحصائيات المنتسبين</h2>
            <i class="fas fa-users"></i>
        </div>
        <div id="countSection" class="task-content">
            <div style="display: flex; gap: 20px;">
                <div style="flex:1; background:#e3f2fd; padding:20px; border-radius:15px; text-align:center; border:1px solid #bbdefb;">
                    <i class="fas fa-user-graduate" style="color:#1976d2; font-size:30px; margin-bottom:10px;"></i>
                    <h3 style="margin:0; font-size:28px;">150</h3><p style="margin:5px 0 0; color:#555;">طلاب</p>
                </div>
                <div style="flex:1; background:#f1f8e9; padding:20px; border-radius:15px; text-align:center; border:1px solid #c5e1a5;">
                    <i class="fas fa-chalkboard-teacher" style="color:#388e3c; font-size:30px; margin-bottom:10px;"></i>
                    <h3 style="margin:0; font-size:28px;">12</h3><p style="margin:5px 0 0; color:#555;">أساتذة</p></div><div style="flex:1; background:#fff3e0; padding:20px; border-radius:15px; text-align:center; border:1px solid #ffe0b2;">
                    <i class="fas fa-user-cog" style="color:#f57c00; font-size:30px; margin-bottom:10px;"></i>
                    <h3 style="margin:0; font-size:28px;">5</h3><p style="margin:5px 0 0; color:#555;">عمال وموظفين</p>
                </div><div style="flex:1; background:#fce4ec; padding:20px; border-radius:15px; text-align:center; border:1px solid#f8bbd0;">
    <i class="fas fa-mosque" style="color:#d81b60; font-size:30px; margin-bottom:10px;"></i>
    <h3 style="margin:0; font-size:28px;">8</h3>
    <p style="margin:5px 0 0; color:#555;">حلقات</p>
</div>
        
        </div></main>

<script> 


        // إخفاء وإظهار الأقسام (الأكورديون)
        function toggleTask(id) {
            const content = document.getElementById(id);
            const isHidden = window.getComputedStyle(content).display === "none";
            document.querySelectorAll('.task-content').forEach(c => c.style.display = "none");
            if (isHidden) {
                content.style.display = "block";
                if(id === 'chartSection') { initChart(); }
            }
        }

        // دالة الفلترة الذكية
        function masterFilter(searchId, roleId, bodyId) {
            let input = searchId ? document.getElementById(searchId).value.toLowerCase() : "";
            let role = document.getElementById(roleId).value;
            let rows = document.getElementById(bodyId).getElementsByTagName("tr");

            for (let row of rows) {
                let name = row.cells[0].textContent.toLowerCase();
                let userRole = row.getAttribute("data-role");
                let matchesSearch = name.includes(input);
                let matchesRole = (role === "all" || userRole === role);
                row.style.display = (matchesSearch && matchesRole) ? "" : "none";
            }
        }

      
       

    const row = select.closest('tr');
    const id = select.getAttribute('data-id'); 
    const status = select.value; 
    const role = row ? row.getAttribute('data-role') : 'غير محدد';

    if (!id) {
        alert("خطأ: لم يتم العثور على معرف المستخدم!");
        return;
    }
function updateRowStatus(selectElement) {
    // جلب البيانات من العنصر الذي تم النقر عليه
    const id = selectElement.getAttribute('data-id');
    const status = selectElement.value;
    const role = selectElement.closest('tr').getAttribute('data-role');

    // إرسال الطلب للسيرفر
    fetch('./update_status.php', { 
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&status=${status}&role=${role}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            const row = document.getElementById('row-' + id);
            
            if (status === 'closed') {
                if(row) row.remove(); // حذف الصف فوراً إذا تم قفل الحساب
            } else {
                // تحديث النص واللون في الخلية
                const statusCell = document.querySelector(`#status-cell-${id} span`);
                if (statusCell) {
                    statusCell.className = 'status-' + status;
                    statusCell.textContent = (status === 'active') ? ' نشط' : ' معلق';
                }
            }
        } else {
            alert("فشل التحديث: " + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
        // الترتيب الأبجدي
        function sortTable(tableId, n) {
            let table = document.getElementById(tableId);
            let rows = Array.from(table.rows).slice(1);
            rows.sort((a, b) => a.cells[n].textContent.localeCompare(b.cells[n].textContent, 'ar'));
            rows.forEach(row => table.tBodies[0].appendChild(row));
        }

        window.onload = () => sortTable('accTable', 0);

        // نظام المنحنيات (60 حزباً)
        let myChart;
        function initChart() {
            const ctx = document.getElementById('successChart').getContext('2d');
            if (myChart) myChart.destroy();
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['10 أحزاب', '20 حزباً', '30 حزباً', '40 حزباً', '50 حزباً', '60 حزباً'],
                    datasets: [
                        { label: 'النجاح %', data: [92, 88, 85, 78, 72, 68], borderColor: '#0d3c1a', backgroundColor: 'rgba(13, 60, 26, 0.1)', fill: true, tension: 0.4 },
                        { label: 'الرسوب %', data: [8, 12, 15, 22, 28, 32], borderColor: '#e74c3c', backgroundColor: 'rgba(231, 76, 60, 0.1)', fill: true, tension: 0.4 }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { labels: { font: { family: 'Cairo', size: 14 } } } },
                    scales: { y: { beginAtZero: true, max: 100 } }
                }
            });
        }function updateChart() {myChart.data.datasets[0].data = myChart.data.datasets[0].data.map(() => Math.floor(Math.random() * 20) + 75);
            myChart.data.datasets[1].data = myChart.data.datasets[1].data.map(() => Math.floor(Math.random() * 15));
            myChart.update();
        }
    </script>
</body>
</html>
