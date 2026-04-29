<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title> كشف نقاطي</title>
    <link rel="stylesheet" href="student_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #1a472a;
            --light-green: #f0f4f1;
            --gold: #bfa15f;
            --white: #ffffff;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            display: flex;
        }

        /* تنسيق المحتوى الرئيسي بجانب السايدبار */
        .main-content {
            flex: 1;
            margin-right: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
        }

        /* الهيدر العلوي */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--white);
            padding: 15px 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        .top-bar h2 { color: var(--primary-green); margin: 0; }

        /* إحصائيات سريعة للتلميذ */
        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-box {
            background: var(--white);
            padding: 20px;
            border-radius: 12px;
            flex: 1;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .stat-box i { font-size: 2rem; color: var(--gold); }
        .stat-box .info h4 { margin: 0; color: #666; font-size: 0.9rem; }
        .stat-box .info p { margin: 5px 0 0; font-size: 1.2rem; font-weight: bold; color: var(--primary-green); }

        /* الجدول */
        .table-wrapper {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th {
            background: var(--primary-green);
            color: var(--white);
            padding: 18px;
            text-align: right;
        }

        .history-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #444;
        }

        /* تنسيق العلامات */
        .score-tag {
            background: var(--light-green);
            padding: 5px 12px;
            border-radius: 8px;
            font-weight: bold;
            color: var(--primary-green);
            border: 1px solid #d4edda;
        }

        .final-grade {
            font-weight: bold;
            color: var(--primary-green);
            font-size: 1.1rem;
        }

        /* ملاحظة الأستاذ */
        .teacher-note {
            font-style: italic;
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* السايدبار */
        .sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--primary-green); /* تأكدي من لون سايدبارك المعتاد */
            color: white;
            z-index: 1000;
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
    
            /* هذه الخاصية ضرورية جداً لدمج النقش مع الأخضر */
            background-blend-mode: multiply; 
    
              /* لتكرار النقش بشكل صحيح */
              background-repeat: repeat;
             background-size: 100px;
        }
    </style>
</head>
<body>

         <?php include 'student_sidebar.php'; ?>
        <main class="main-content">
        <header class="top-bar">
            <h2><i class="fas fa-award"></i> سجل نتائج اختباراتي</h2>
            <div class="student-meta">
                <span>المستوى: <strong>متوسط</strong></span>
            </div>
        </header>

        <div class="stats-container">
            <div class="stat-box">
                <i class="fas fa-file-signature"></i>
                <div class="info">
                    <h4>عدد الاختبارات</h4>
                    <p>05 اختبارات</p>
                </div>
            </div>
            <div class="stat-box">
                <i class="fas fa-star"></i>
                <div class="info">
                    <h4>آخر معدل</h4>
                    <p>18 / 20</p>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>حفظ (8)</th>
                        <th>أحكام (8)</th>
                        <th>مخارج (4)</th>
                        <th>المجموع</th>
                        <th>ملاحظة الأستاذ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2026-04-14</td>
                        <td><span class="score-tag">7.5</span></td>
                        <td><span class="score-tag">7</span></td>
                        <td><span class="score-tag">3.5</span></td>
                        <td class="final-grade">18 / 20</td>
                        <td class="teacher-note">ممتاز، واصل مراجعة مخارج الحروف الشجرية.</td>
                    </tr>
                    <tr>
                        <td>2026-03-20</td>
                        <td><span class="score-tag">6</span></td>
                        <td><span class="score-tag">5</span></td>
                        <td><span class="score-tag">3</span></td>
                        <td class="final-grade">14 / 20</td>
                        <td class="teacher-note">تحسن جيد، ركز أكثر على أحكام النون الساكنة.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>