<?php 
  $current_page = 'dash'; 
  include 'reception_sidebar.php'; 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الإعلانات  </title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
    <style>
        :root { 
            --primary-green: #1a472a; 
            --urgent-red: #d32f2f; 
            --admin-blue: #1976d2; 
            --general-green: #388e3c;
            --sidebar-width: 260px;
        }

        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        
        .main-content { flex: 1; margin-right: var(--sidebar-width); padding: 30px; }

        /* نموذج كتابة الإعلان */
        .announcement-form {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 8px; font-weight: bold; color: var(--primary-green); }
        .input-field, textarea, select { 
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 1rem;
        }

        .btn-post {
            background: var(--primary-green); color: white; border: none; padding: 12px 30px;
            border-radius: 8px; cursor: pointer; font-weight: bold; display: flex; align-items: center; gap: 10px;
        }

        /* تنسيق الإعلانات كما ستظهر للجميع */
        .announcements-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        
        .ann-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: relative;
            transition: transform 0.2s;
        }

        /* الحاشية الملونة حسب النوع */
        .ann-card.urgent { border-right: 6px solid var(--urgent-red); }
        .ann-card.admin { border-right: 6px solid var(--admin-blue); }
        .ann-card.general { border-right: 6px solid var(--general-green); }

        .ann-card h4 { margin: 0 0 10px 0; display: flex; justify-content: space-between; align-items: center; }
        .ann-type-label { font-size: 0.75rem; padding: 3px 8px; border-radius: 15px; color: white; }
        
        .bg-red { background: var(--urgent-red); }
        .bg-blue { background: var(--admin-blue); }
        .bg-green { background: var(--general-green); }

        .ann-date { font-size: 0.8rem; color: #888; margin-top: 15px; display: block; border-top: 1px solid #eee; padding-top: 10px; }
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

    <?php include 'reception_sidebar.php'; ?>

    <main class="main-content">
        <button id="open-sidebar" class="open-sidebar-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
       </button>
        <h2 style="color: var(--primary-green);"><i class="fas fa-bullhorn"></i> لوحة تحكم الإعلانات</h2>

        <div class="announcement-form">
            <div style="display: flex; gap: 20px;">
                <div class="input-group" style="flex: 2;">
                    <label>عنوان الإعلان:</label>
                    <input type="text" id="annTitle" class="input-field" placeholder="مثلاً: موعد الفروض الفصلية">
                </div>
                <div class="input-group" style="flex: 1;">
                    <label>نوع الإعلان:</label>
                    <select id="annType" class="input-field">
                        <option value="general">عام (أخضر)</option>
                        <option value="admin">إداري (أزرق)</option>
                        <option value="urgent">هام جداً (أحمر)</option>
                    </select>
                </div>
            </div>
            <div class="input-group">
                <label>نص الإعلان:</label>
                <textarea id="annContent" rows="3" placeholder="اكتب تفاصيل الإعلان هنا..."></textarea>
            </div>
            <button class="btn-post" onclick="addAnnouncement()">
                <i class="fas fa-paper-plane"></i> نشر الإعلان للجميع
            </button>
        </div>

        <h3 style="color: #666; margin-bottom: 20px;">الإعلانات المنشورة حالياً:</h3>
        <div class="announcements-list" id="annContainer">
            </div>
    </main>

<script>
    // مصفوفة لتخزين الإعلانات مؤقتاً (مستقبلاً يتم جلبها من الـ Database)
    let announcements = [
        { title: "تنبيه بخصوص الدفع", content: "يرجى من جميع التلاميذ تسوية المستحقات قبل نهاية الشهر.", type: "admin", date: "2026-04-18" },
        { title: "مسابقة ثقافية", content: "تنظم المدرسة مسابقة في حفظ القرآن الكريم يوم السبت القادم.", type: "general", date: "2026-04-15" }
    ];

    function renderAnnouncements() {
        const container = document.getElementById('annContainer');
        container.innerHTML = announcements.map((ann, index) => `
            <div class="ann-card ${ann.type}">
                <h4>
                    ${ann.title}
                    <span class="ann-type-label ${ann.type === 'urgent' ? 'bg-red' : ann.type === 'admin' ? 'bg-blue' : 'bg-green'}">
                        ${ann.type === 'urgent' ? 'هام' : ann.type === 'admin' ? 'إداري' : 'عام'}
                    </span>
                </h4>
                <p style="color: #444; line-height: 1.5;">${ann.content}</p>
                <span class="ann-date"><i class="far fa-clock"></i> نشر بتاريخ: ${ann.date}</span>
                <button onclick="deleteAnn(${index})" style="background:none; border:none; color:red; cursor:pointer; float:left; margin-top:-20px;"><i class="fas fa-trash"></i></button>
            </div>
        `).join('');
    }

    function addAnnouncement() {
        const title = document.getElementById('annTitle').value;
        const type = document.getElementById('annType').value;
        const content = document.getElementById('annContent').value;
        const date = new Date().toLocaleDateString('en-CA');

        if(title && content) {
            announcements.unshift({ title, content, type, date });
            renderAnnouncements();
            // مسح الحقول بعد النشر
            document.getElementById('annTitle').value = '';
            document.getElementById('annContent').value = '';
        } else {
            alert("يرجى ملء جميع الحقول!");
        }
    }

    function deleteAnn(index) {
        announcements.splice(index, 1);
        renderAnnouncements();
    }

    window.onload = renderAnnouncements;
</script>
</body>
</html>