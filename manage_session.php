<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الحلقة | مدرسة أسرتي القرآنية</title>
    <link rel="stylesheet" href="teacher_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* التنسيقات العامة للمحتوى */
        .main-content { margin-right: 0 !important; width: 100% !important; padding: 30px !important; }
        
        /* تنسيق الجداول (العام والاختبار) ليكون بحواف دائرية */
        .table-wrapper {
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid #ddd;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            background: white;
            margin-top: 20px;
        }

        .session-table, .evaluation-table { width: 100%; border-collapse: collapse; border: none; }
        .session-table th, .evaluation-table th { background: #4a5d4e; color: white; padding: 15px; border: none; }
        .session-table td, .evaluation-table td { padding: 12px; border-bottom: 1px solid #eee; text-align: center; }

        /* تنسيق النجوم المطور */
        .stars i {
    color: #e0e0e0 !important; /* لون رمادي باهت جداً للنجوم غير المفعلة */
}

.stars i.active {
    color: #ffc107 !important; /* لون ذهبي ساطع للمفعلة */
}

        /* تنسيق الأزرار */
        .btn-save-eval { 
            background-color: #28a745; color: white; border: none; padding: 10px 25px; 
            border-radius: 25px; font-weight: bold; cursor: pointer; transition: 0.3s; 
        }
        .btn-save-eval:hover { background-color: #218838; transform: translateY(-2px); }

        .btn-cancel-eval { 
            background-color: #6c757d; color: white; border: none; padding: 10px 25px; 
            border-radius: 25px; font-weight: bold; cursor: pointer; margin-right: 10px; 
        }

        /* تنسيق زر الحالة (حاضر/غائب) */
        .status-toggle { padding: 8px 20px; border-radius: 20px; border: 1px solid #2e7d32; cursor: pointer; font-weight: bold; min-width: 80px; }
        
        .exam-btn-trigger { background: #c62828; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.4s; }
        .exam-btn-trigger:hover { background-color: #a51f1f !important; transform: translateY(-2px); }

        .student-total { font-weight: bold; color: #1a472a; font-size: 1.2rem; background: #f0f4f1; padding: 5px 10px; border-radius: 8px; }
    </style>
</head>
<body class="dashboard-body">
    <main class="main-content">
        <div class="card-box">
            <header class="top-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>إدارة حلقة: فوج النور</h2>
                <div class="actions">
                    <button type="button" class="exam-btn-trigger" onclick="showExamTable()">
                        <i class="fas fa-file-signature"></i> اختبار جديد
                    </button>
                    <a href="teacher_groups.php" class="btn-home" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #fdfbf7; color: #4a5d4e; border: 2px solid #bfa15f; border-radius: 12px; text-decoration: none; font-weight: bold;">
                        <i class="fas fa-reply"></i> العودة للرئيسية
                    </a>
                </div>
            </header>

            <div id="main-table-container" class="table-wrapper">
                <table class="session-table">
                    <thead>
                        <tr>
                            <th>اسم الطالب</th>
                            <th>الحالة</th>
                            <th>السورة</th>
                            <th>من آية</th>
                            <th>إلى آية</th>
                            <th>الدرجة</th>
                            <th>حفظ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="row-1">
                            <td><b>محمد الجزائري</b></td>
                            <td><button type="button" class="status-toggle" style="background: #e8f5e9; color: #2e7d32;" onclick="toggleStatus(this, 'row-1')">حاضر</button></td>
                            <td><select class="surah-input"><option>اختر السورة...</option><option>البقرة</option></select></td>
                            <td><input type="number" class="from-aya" style="width: 50px;"></td>
                            <td><input type="number" class="to-aya" style="width: 50px;"></td>
                            <td><input type="text" class="score-input" style="width: 60px;"></td>
                            <td><button style="color: #bfa15f; border: none; background: none; cursor: pointer;"><i class="fas fa-save fa-lg"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="exam-section" style="display: none; margin-top: 20px;">
                <h3 style="text-align: center; color: #8b0000; margin-bottom: 15px;"><i class="fas fa-users"></i> رصد علامات الفوج - اختبار جديد</h3>
                <div class="table-wrapper">
                    <table class="evaluation-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>الاسم واللقب</th>
                                <th>الحفظ (8)</th>
                                <th>الأحكام (8)</th>
                                <th>المخارج (4)</th>
                                <th>العلامة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="student-row" data-student-id="101">
                                <td>101</td>
                                <td>أحمد محمد</td>
                                <td><div class="stars hifz-stars"><?php for($i=1;$i<=8;$i++) echo "<i class='fas fa-star ' data-value='$i'></i>"; ?></div></td>
                                <td><div class="stars ahkam-stars"><?php for($i=1;$i<=8;$i++) echo "<i class='fas fa-star ' data-value='$i'></i>"; ?></div></td>
                                <td><div class="stars makharij-stars"><?php for($i=1;$i<=4;$i++) echo "<i class='fas fa-star ' data-value='$i'></i>"; ?></div></td>
                                <td><span class="student-total" style="font-weight: bold; color: #1a472a; font-size: 1.2rem;">0 / 20</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <button class="btn-save-eval" onclick="saveEvaluation()">حفظ تقييم الفوج</button>
                    <button class="btn-cancel-eval" onclick="hideExamTable()">إلغاء</button>
                </div>
            </div>
        </div>
    </main>

    <script>
    // وظيفة تبديل الحالة
    function toggleStatus(btn, rowId) {
        const row = document.getElementById(rowId);
        const inputs = row.querySelectorAll('input, select');
        const isPresent = btn.innerText.trim() === "حاضر";

        btn.innerText = isPresent ? "غائب" : "حاضر";
        btn.style.background = isPresent ? "#ffebee" : "#e8f5e9";
        btn.style.color = isPresent ? "#c62828" : "#2e7d32";
        btn.style.borderColor = isPresent ? "#c62828" : "#2e7d32";

        inputs.forEach(input => {
            input.disabled = isPresent;
            input.style.opacity = isPresent ? "0.5" : "1";
            if(isPresent) input.value = '';
        });
    }

    // إظهار وإخفاء الجداول
    
function showExamTable() {
    // إخفاء الجدول الرئيسي والهيدر
    document.getElementById("main-table-container").style.display = "none";
    document.querySelector(".top-bar").style.display = "none"; // إخفاء الهيدر تماماً
    
    // إظهار قسم الاختبار
    const examSection = document.getElementById("exam-section");
    examSection.style.display = "block";
    
    resetStars();
}

function hideExamTable() {
    // إخفاء قسم الاختبار
    document.getElementById("exam-section").style.display = "none";
    
    // إعادة إظهار الجدول الرئيسي والهيدر
    document.getElementById("main-table-container").style.display = "block";
    document.querySelector(".top-bar").style.display = "flex"; // إعادة الهيدر كما كان (flex)
}
    // نظام النجوم التفاعلي
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('fa-star')) {
    const star = e.target;
    const val = parseInt(star.dataset.value);
    const parent = star.parentElement;
    const row = star.closest('.student-row');

    // المنطق الجديد: التحقق إذا كانت النجمة هي الأخيرة المفعّلة
    const isActive = star.classList.contains('active');
    const isLastActive = !star.nextElementSibling || !star.nextElementSibling.classList.contains('active');

    let targetValue = val;
    
    // إذا ضغطتِ على نجمة صفراء وهي الأخيرة، قومي بإلغائها (تراجع للوراء)
    if (isActive && isLastActive) {
        targetValue = val - 1;
    }

    // تلوين النجوم بناءً على القيمة المستهدفة
    parent.querySelectorAll('i').forEach(s => {
        if (parseInt(s.dataset.value) <= targetValue) {
            s.classList.add('active');
        } else {
            s.classList.remove('active');
        }
    });

    // تحديث العلامة فوراً في السطر
    const activeStars = row.querySelectorAll('.fa-star.active').length;
    row.querySelector('.student-total').innerText = activeStars + " / 20";
}
    });

   function resetStars() {
    // 1. إزالة كلاس active من كل النجمات لتصبح رمادية
    document.querySelectorAll('.stars i').forEach(s => {
        s.classList.remove('active');
    });

    // 2. تصفير نص العلامة لكل سطر (طالب)
    document.querySelectorAll('.student-total').forEach(total => {
        total.innerText = "0 / 20";
    });
}
    </script>
</body>
</html>