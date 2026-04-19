<?php
include 'db1.php'; // استدعاء الاتصال بقاعدة البيانات

// هنا نضع الاستعلام الخاص بالطلاب
$sql = "SELECT * FROM users WHERE role = 'طالب'";
$result = mysqli_query($conn, $sql);
?><!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>شؤون الطلاب - مدرسة أسرتي القرآنية</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Amiri:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-green: #0d3c1a;
            --gold: #d4af37;
            --light-bg: #f4f7f6;
            --cream: #fdfbf7;
        }

        body { margin: 0; font-family: 'Cairo', sans-serif; background-color: var(--light-bg); display: flex; }

        /* 🟢 تنسيق المستطيل الأخضر (Sidebar) - نسخة مطابقة لصفحة الأساتذة */
        .sidebar {
            width: 280px;
            background-color: var(--primary-green);
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png"); /* نقش الأرابيسك */
            color: white; 
            height: 100vh; 
            position: fixed; 
            border-left: 5px solid var(--gold); /* الخط الذهبي الجانبي */
            right: 0;
            top: 0;
            overflow-y: auto; /* 📜 تفعيل التمرير لضمان ظهور كل الخيارات */
            z-index: 1000;
        }

        .sidebar-header { padding: 30px; text-align: center; background: rgba(13, 60, 26, 0.6); }
        .admin-logo-icon { font-size: 70px; color: var(--gold); margin-bottom: 15px; display: block; }
        .nav-links { list-style: none; padding: 0; }
        .nav-links li a { display: block; padding: 15px 25px; color: #eee; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .nav-links li a:hover, .nav-links li a.active { background-color: rgba(212, 175, 55, 0.2); color: var(--gold); }

        /* ⚪ المحتوى الرئيسي */
        .main-content { margin-right: 280px; width: calc(100% - 280px); padding: 40px; }
        
        .ornamental-card {
            background-color: var(--cream);
            background-image: url("https://www.transparenttextures.com/patterns/arabesque.png");
            border: 2px solid var(--gold); border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }.section-title {
            color: var(--primary-green); font-family: 'Amiri', serif; font-size: 28px;
            border-bottom: 2px solid var(--gold); padding-bottom: 15px; margin-bottom: 25px;
        }

        .student-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .student-table th { background-color: var(--primary-green); color: var(--gold); padding: 15px; text-align: right; }
        .student-table td { background-color: white; padding: 15px; border-top: 1px solid #eee; border-bottom: 1px solid #eee; }
        
        .badge-level { background: var(--gold); color: var(--primary-green); padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="ornamental-card">
            <h2 class="section-title"><i class="fas fa-user-graduate"></i> سجل الطلاب المسجلين</h2>
            
            <table class="student-table">
                <thead>
                    <tr>
                        <th style="border-radius: 0 10px 10px 0;">اسم الطالب</th>
                        <th>الحلقة / المستوى</th>
                        <th>تاريخ الالتحاق</th>
                        <th style="border-radius: 10px 0 0 10px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: bold;">أحمد محمد علي</td>
                        <td><span class="badge-level">حفظ جزء عم</span></td>
                        <td>2024-01-10</td>
                        <td>
                            <button title="عرض" style="color: var(--primary-green); border: none; background: none; cursor: pointer; font-size: 18px;"><i class="fas fa-eye"></i></button>
                           
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
    </main>

</body>
</html>