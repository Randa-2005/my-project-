<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نظام الإدارة المالية الاحترافي - مدرسة أسرتي</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Amiri:wght@700&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-green: #0d3c1a;
            --staff-green: #2e7d32;
            --gold-op: #d4af37;
            --prize-blue: #1976d2;
            --bg-light: #f4f7f6;
        }

        body { margin: 0; font-family: 'Cairo', sans-serif; background-color: var(--bg-light); display: flex; }

        /* القائمة الجانبية المزيّنة */
          :root {
            --primary-green: #0d3c1a;
            --gold: #d4af37;
            --light-bg: #f4f7f6;
            --cream: #fdfbf7;
        }

        body { margin: 0; font-family: 'Cairo', sans-serif; background-color: var(--light-bg); display: flex; }

        /* القائمة الجانبية الثابتة */
          .sidebar {
            width: 280px;
            background-color: var(--primary-green);
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            color: white;
            height: 100vh;
            position: fixed;
            border-left: 5px solid var(--gold);
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px;
            text-align: center;
            background: rgba(13, 60, 26, 0.6);
        }

        .admin-logo-icon {
            font-size: 70px;
            color: var(--gold);
            margin-bottom: 15px;
            display: block;
        }

        .nav-links {
            list-style: none;
            padding: 0;
        }

        .nav-links li a {
            display: block;
            padding: 15px 25px;
            color: #eee;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links li a.active {
            background-color: rgba(212, 175, 55, 0.2);
            color: var(--gold);
        }

        /* المحتوى الرئيسي */
        .main-content { margin-right: 280px; width: 100%; padding: 40px; box-sizing: border-box; }

        /* البطاقات المالية البارزة */
        .stat-cards { display: flex; gap: 20px; margin-bottom: 30px; }
        .card { flex: 1; background: white; padding: 25px; border-radius: 15px; text-align: center; border-right: 8px solid var(--gold-op); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }

        /* حاوية الرسم البياني والمفتاح */
        .chart-box { background: white; padding: 30px; border-radius: 15px; flex: 1; text-align: center; }
        #pie-chart { width: 220px; height: 220px; border-radius: 50%; margin: 20px auto; border: 5px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        
        /* مفتاح الدائرة البيانية 🗝️ */
        .chart-legend { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px; text-align: right; font-size: 14px; }
        .legend-item { display: flex; align-items: center; gap: 10px; }
        .dot { width: 12px; height: 12px; border-radius: 3px; }

        /* جدول تفاصيل المصاريف 📜 */
        .details-box { background: white; padding: 30px; border-radius: 15px; flex: 2; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; text-align: right; border-bottom: 1px solid #eee; }
        th { color: var(--primary-green); background: #fdfbf7; }
    </style>
</head>
<body>

     <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: white; padding: 20px; border-radius: 15px;">
            <h1 style="font-family: 'Amiri'; color: var(--primary-green); margin: 0;">📉 سيناريو الإدارة المالية الشامل</h1>
            <select id="month-select" onchange="runFinanceEngine()" style="padding: 12px; border-radius: 8px; border: 1px solid var(--gold-op); font-family: 'Cairo';">
                <option value="أفريل">أفريل 2026</option>
                <option value="مارس">مارس 2026</option>
            </select>
        </div>

        <div class="stat-cards">
            <div class="card"> <p>إجمالي المداخيل 📥</p> <h2 id="txt-income" style="color: var(--staff-green);">0</h2> </div>
            <div class="card"> <p>إجمالي المصاريف 📤</p> <h2 id="txt-expenses" style="color: #c62828;">0</h2> </div>
            <div class="card"> <p>الباقي
                ✨</p> <h2 id="txt-net" style="color: var(--primary-green);">0</h2> </div>
        </div>

        <div style="display: flex; gap: 30px;">
            <div class="chart-box">
                <h3 style="font-family: 'Amiri';">📊 تحليل بنود المصاريف</h3>
                <div id="pie-chart"></div><div class="chart-legend">
                    <div class="legend-item"><span class="dot" style="background:var(--primary-green)"></span> رواتب أساتذة</div>
                    <div class="legend-item"><span class="dot" style="background:var(--staff-green)"></span> رواتب موظفين</div>
                    <div class="legend-item"><span class="dot" style="background:var(--gold-op)"></span> تشغيل وفواتير</div>
                    <div class="legend-item"><span class="dot" style="background:var(--prize-blue)"></span> جوائز وأنشطة</div>
                </div>
            </div>

            <div class="details-box">
                <h3 style="font-family: 'Amiri'; border-right: 5px solid var(--gold-op); padding-right: 15px;">📜 كشف تفاصيل المصاريف التشغيلية</h3>
                <table>
                    <thead>
                        <tr> <th>البند المالي</th> <th>الفئة</th> <th>المبلغ (د.ج)</th> <th>الحالة</th> </tr>
                    </thead>
                    <tbody id="finance-table"></tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // سيناريو الشؤون المالية (المبالغ الثابتة)
        const schoolFees = { teacher: 45000, staff: 30000, op: 15000, prize: 5000 };
        
        const monthlyRecords = {
            "أفريل": { income: 680000, teachers: 6, staff: 3 },
            "مارس": { income: 550000, teachers: 5, staff: 2 }
        };

        function runFinanceEngine() {
            const m = document.getElementById('month-select').value;
            const current = monthlyRecords[m];

            // العمليات الحسابية الشاملة
            const sumT = current.teachers * schoolFees.teacher;
            const sumS = current.staff * schoolFees.staff;
            const totalExp = sumT + sumS + schoolFees.op + schoolFees.prize;
            const net = current.income - totalExp;

            // تحديث البطاقات
            document.getElementById('txt-income').innerText = current.income.toLocaleString() + " د.ج";
            document.getElementById('txt-expenses').innerText = totalExp.toLocaleString() + " د.ج";
            document.getElementById('txt-net').innerText = net.toLocaleString() + " د.ج";

            // ملء جدول التفاصيل
            document.getElementById('finance-table').innerHTML = 
                "<tr><td>رواتب الطاقم التعليمي</td><td>أساتذة</td><td>" + sumT.toLocaleString() + "</td><td>✅ تم</td></tr>" +
                "<tr><td>رواتب الإداريين والعمال</td><td>موظفين</td><td>" + sumS.toLocaleString() + "</td><td>✅ تم</td></tr>" +
                "<tr><td>فواتير (ماء، كهرباء، إنترنت)</td><td>تشغيل</td><td>" + schoolFees.op.toLocaleString() + "</td><td>⏳ معلق</td></tr>" +
                "<tr><td>ميزانية الجوائز الطلابية</td><td>أنشطة</td><td>" + schoolFees.prize.toLocaleString() + "</td><td>🏆 مبرمج</td></tr>";

            // حساب زوايا الدائرة البيانية بدقة 📊
            const p1 = (sumT / totalExp) * 100;
            const p2 = p1 + (sumS / totalExp) * 100;
            const p3 = p2 + (schoolFees.op / totalExp) * 100;

            const chart = document.getElementById('pie-chart');
            chart.style.background = "conic-gradient(" +
                "#0d3c1a 0% " + p1 + "%, " +
                "#2e7d32 " + p1 + "% " + p2 + "%, " +
                "#d4af37 " + p2 + "% " + p3 + "%, " +
                "#1976d2 " + p3 + "% 100%)";
        }

        window.onload = runFinanceEngine;
    </script>
</body>
</html>