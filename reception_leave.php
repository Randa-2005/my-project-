<?php 
  include 'db_connect.php'; 
  $current_page = 'dash'; 
  include 'reception_sidebar.php'; 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقديم طلب عطلة </title>
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

        /* توحيد حجم الحقول لضمان التناسق */
        .input-field { 
            padding: 12px 15px; 
            border: 2px solid #e1e1e1; 
            border-radius: 10px; 
            width: 100%;
            box-sizing: border-box; /* لضمان عدم خروج الحقل عن الإطار */
        }

        /* توسيع مساحة السبب */
        textarea.input-field { height: 120px; resize: none; }

        .upload-zone {
            border: 2px dashed #ccc;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            background: #fafafa;
            cursor: pointer;
        }

        /* مجموعة الأزرار */
        /* مجموعة الأزرار */
.btn-group {
    display: flex;
    gap: 15px;
    margin-top: 20px;
    width: 100%; /* لضمان أخذ العرض الكامل للحاوية */
    padding: 0 20px; /* لإضافة مساحة جانبية متناسقة مع الحقول */
    box-sizing: border-box;
}

/* الحاوية الأساسية */
.btn-group {
    display: flex !important;
    gap: 15px !important;
    width: 100% !important;
    margin-top: 25px !important;
    box-sizing: border-box !important;

}

/* توحيد كل شيء بين الزرين */
.submit-btn, .cancel-btn {
    height: 50px !important;      /* ارتفاع ثابت وموحد */
    display: flex !important;
    align-items: center !important;    /* توسيط النص عمودياً */
    justify-content: center !important; /* توسيط النص أفقياً */
    border-radius: 10px !important;
    font-weight: bold !important;
    font-size: 1rem !important;
    cursor: pointer !important;
    box-sizing: border-box !important; /* لضمان أن الـ border لا يغير الحجم */
    padding: 0 !important;
    margin: 0 !important;
}

/* توزيع العرض (2 للتاكيد و1 للالغاء) */
.submit-btn {
    flex: 2 !important;
    background-color: var(--primary) !important; /* تأكدي أن اسم المتغير صحيح أو ضعي اللون مباشرة */
    color: white !important;
    border: none !important; /* بدون حواف */
}

.cancel-btn {
    flex: 1 !important;
    background-color: #f4f4f4 !important;
    color: #666 !important;
    /* السر هنا: جعل الحواف شفافة أو بنفس لون الخلفية لكي لا تزيد الطول */
    border: 1px solid #ddd !important; 
}
        /* تنسيق زر القائمة */
.open-sidebar-btn {
    display: none; /* مخفي افتراضياً في الشاشات الكبيرة */
    position: fixed;
    top: 15px;
    right: 15px; /* يظهر من جهة اليمين لأن اللغة عربية */
    background: #1a472a;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.2rem;
    z-index: 1001; /* ليكون فوق كل العناصر */
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

/* إظهار الزر عند تصغير الشاشة (أقل من 768px) */
@media (max-width: 768px) {
    .open-sidebar-btn {
        display: block;
    }
    
    .main-content {
        margin-right: 0 !important; /* إلغاء الهامش في الشاشات الصغيرة */
        padding-top: 60px; /* ترك مساحة للزر في الأعلى */
    }
}
    </style>
</head>
<body>

    <main class="main-content">
        <button id="open-sidebar" class="open-sidebar-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
       </button>
        <div class="header">
            <h2><i class="fas fa-calendar-alt"></i> طلب غياب رسمي</h2>
        </div>

        <div class="content-wrapper">
            <div class="leave-card">
                <form action="save_request.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-grid">
                        <div class="input-group full-width">
                            <label><i class="fas fa-user"></i>  الاسم واللقب:</label>
                            <input type="text" class="input-field" style="background:#f9f9f9;" value="الأستاذ المتعاقد" readonly>
                        </div>

                        <div class="input-group full-width">
                            <label><i class="fas fa-hourglass-half"></i> مدة العطلة (بالأيام):</label>
                            <input type="number" name="days" class="input-field" placeholder="أدخل عدد الأيام">
                        </div>

                        <div class="input-group">
                            <label><i class="fas fa-calendar-day"></i> تاريخ البداية:</label>
                            <input type="date" name="start" class="input-field">
                        </div>
                        <div class="input-group">
                            <label><i class="fas fa-calendar-check"></i> تاريخ النهاية:</label>
                            <input type="date" name="end" class="input-field">
                        </div>
                        <div class="input-group full-width">
                            <label><i class="fas fa-pen"></i> سبب طلب العطلة بالتفصيل:</label>
                            <textarea name="reason" class="input-field" placeholder="اكتب تبريرك الوافي هنا..."></textarea>
                        </div>

                        <div class="input-group full-width">
                            <label><i class="fas fa-paperclip"></i> إرفاق الوثيقة (اختياري):</label>
                            <div class="upload-zone" onclick="document.getElementById('fileInp').click()">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--primary);"></i>
                                <p id="fileMsg">اضغط لتحميل الشهادة الطبية أو المبرر</p>
                                <input type="file" id="fileInp" name="doc" hidden onchange="checkFile()">
                            </div>
                        </div>

                        <div class="full-width btn-group">
                            <button type="submit" class="submit-btn">تأكيد وإرسال الطلب</button>
                            <button type="button" class="cancel-btn" onclick="history.back()">إلغاء العملية</button>
                      </div>

                </form>
            </div>
        </div>
    </main>

    <script>
        function checkFile() {
            const inp = document.getElementById('fileInp');
            const msg = document.getElementById('fileMsg');
            if(inp.files.length > 0) {
                msg.innerHTML = "<b>تم اختيار:</b> " + inp.files[0].name;
            }
        }
    </script>
</body>
</html>