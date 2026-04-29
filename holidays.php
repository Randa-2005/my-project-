<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طلبات العطل - مدرسة أسرتي القرآنية</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Amiri:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #0d3c1a;
            --gold: #d4af37;
            --light-bg: #f4f7f6;
            --cream: #fdfbf7;
        }

        body {
            margin: 0;
            font-family: 'Cairo', sans-serif;
            background-color: var(--light-bg);
            display: flex;
        }

        /* القائمة الجانبية - Sidebar */
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
        .main-content {
            margin-right: 280px;
            width: 100%;
            padding: 40px;
        }

        .ornamental-card {
            background-color: var(--cream);
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            border: 2px solid var(--gold);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .section-title {
            color: var(--primary-green);
            font-family: 'Amiri', serif;
            font-size: 28px;
            border-bottom: 2px solid var(--gold);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        /* تنسيق جدول العطل */
        .holiday-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .holiday-table th {
            background-color: var(--primary-green);
            color: var(--gold);
            padding: 15px;
            text-align: right;
        }

        .holiday-table td {
            background-color: white;
            padding: 15px;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .status-pending {
            color: #d4af37;
            font-weight: bold;
        }

        .btn-approve {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
        }

        .btn-reject {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>
    <main class="main-content">
        <div class="ornamental-card">
            <h2 class="section-title"><i class="fas fa-envelope-open-text"></i> مراجعة طلبات العطل</h2>
            
            <table class="holiday-table">
                <thead>
                    <tr>
                        <th style="border-radius: 0 10px 10px 0;">اسم الأستاذ</th>
                        <th>التاريخ المطلوب</th>
                        <th>سبب العطلة</th>
                        <th>الحالة</th>
                        <th style="border-radius: 10px 0 0 10px;">القرار الإداري</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: bold;">أ/ ياسين الحسين</td>
                        <td>2024-05-25</td>
                        <td>ظرف عائلي طارئ</td>
                        <td><span class="status-pending">قيد الانتظار</span></td>
                        <td>
                            <button class="btn-approve"><i class="fas fa-check"></i> قبول</button>
                            <button class="btn-reject"><i class="fas fa-times"></i> رفض</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>