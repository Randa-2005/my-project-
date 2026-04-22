<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل تلميذ جديد </title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
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
        <div class="registration-form-container">
    <div class="form-header">
        <i class="fas fa-user-plus"></i>
        <h2>تسجيل تلميذ جديد</h2>
    </div>

    <form id="regForm">
        <div class="input-row">
            <div class="input-field full-width">
                <label>الاسم الكامل (Full name):</label>
                <input type="text" name="full_name" required>
            </div>
        </div>

        <div class="input-row">
            <div class="input-field">
                <label>تاريخ الميلاد (Password تلقائياً):</label>
                <input type="date" id="dob" name="dob" required onchange="updatePrice()">
            </div>
            <div class="input-field">
                <label>رقم الهاتف (Phone number):</label>
                <input type="tel" name="phone">
            </div>
        </div>
        <div class="input-row">
    <div class="input-field">
        <label>الجنس:</label>
        <select id="gender" name="gender" onchange="filterGroups()" required>
            <option value="">-- اختر --</option>
            <option value="male">ذكر</option>
            <option value="female">أنثى</option>
        </select>
    </div>

    <div class="input-field">
        <label>المستوى الدراسي:</label>
        <select id="level" name="level" onchange="filterGroups()" required>
            <option value="">-- اختر المستوى --</option>
            <option value="1">المستوى الأول</option>
            <option value="2">المستوى الثاني</option>
            <option value="3">المستوى الثالث</option>
        </select>
    </div>
 
</div>

<div class="input-row">
    <div class="input-field full-width">
        <label>الفوج الدراسي المتاح:</label>
        <select id="student_group" name="student_group" >
            <option value="">-- يرجى اختيار الجنس والمستوى أولاً --</option>
        </select>
    </div>
</div>
<div class="input-row">
            <div class="input-field">
                <label>مدة الاشتراك:</label>
                <select id="period" name="period" onchange="updatePrice()">
                    <option value="0">اختر المدة...</option>
                    <option value="1">شهر واحد (One month)</option>
                    <option value="3">فصل دراسي (3 أشهر)</option>
                    <option value="12">سنة كاملة</option>
                </select>
            </div>
</div>
 
        <div id="priceDisplay" class="price-notice" style="display:none;">
            <i class="fas fa-tag"></i> المبلغ المطلوب سداده: <span id="amountText">0</span> د.ج
        </div>

        <div class="payment-box">
            <div class="toggle-container">
                <label class="switch">
                    <input type="checkbox" id="hasPaid" name="has_paid">
                    <span class="slider round"></span>
                </label>
                <span class="payment-label">تم دفع حقوق التسجيل (تفعيل الحساب)</span>
            </div>
        </div>

        <button type="submit" class="submit-btn" id="submitBtn">
            تأكيد    <i class="fas fa-arrow-left"></i>
        </button>
    </form>
</div>
    </main>
    <div id="receiptModal" class="modal">
    <div class="modal-content receipt-card" id="printableArea">
        <h2 class="receipt-title">شهادة تأكيد التسجيل</h2>
        
        <div class="info-section">
            <p><strong>اسم التلميذ:</strong> <span id="res_name"></span></p>
            <p><strong>رقم الهاتف:</strong> <span id="res_phone"></span></p>
            <p><strong>كلمة السر (تاريخ الميلاد):</strong> <span id="res_pass"></span></p>
            <p><strong>تاريخ التسجيل:</strong> <span id="res_date"></span></p>
            <p><strong>الوقت:</strong> <span id="res_time"></span></p>
            <p><strong>المستوى:</strong> <span id="res_level"></span></p>
            <p><strong>الفوج:</strong> <span id="res_group"></span></p>
        </div>

        <div id="paymentDetails" class="payment-receipt-section" style="display:none;">
            <hr>
            <h3 class="payment-title">وصل الدفع</h3>
            <p><strong>المبلغ المدفوع:</strong> <span id="res_amount"></span> د.ج</p>
            <p><strong>حالة الحساب:</strong> <span style="color: green; font-weight: bold;">مفعّل ✅</span></p>
        </div>

        <div class="no-print actions">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> طباعة الشهادة
            </button>
            <button onclick="closeModal()" class="close-btn">إغلاق</button>
        </div>
    </div>
</div>
    <script>
// دالة تحديث السعر
function updatePrice() {
    const period = document.getElementById('period').value;
    const priceDisplay = document.getElementById('priceDisplay');
    const amountText = document.getElementById('amountText');
    let price = 0;
    
    if(period == "1") price = 3500;
    else if(period == "3") price = 9000;
    else if(period == "12") price = 30000;

    if(price > 0) {
        amountText.innerText = price;
        priceDisplay.style.display = 'block';
    } else {
        priceDisplay.style.display = 'none';
    }
}

// تنفيذ الكود عند الضغط على تأكيد
document.addEventListener('DOMContentLoaded', function() {
    const regForm = document.getElementById('regForm');
    
    if(regForm) {
        regForm.onsubmit = function(e) {
            e.preventDefault();
            console.log("تم الضغط على تأكيد"); // للتأكد في المتصفح

            // جلب القيم من الحقول (تأكدي من وجود هذه الـ IDs في حقول الإدخال)
            const name = document.querySelector('input[name="full_name"]').value;
            const phone = document.querySelector('input[name="phone"]').value;
            const dob = document.getElementById('dob').value;
            const isPaid = document.getElementById('hasPaid').checked;
            const amount = document.getElementById('amountText').innerText;

            // الوقت والتاريخ
            const now = new Date();
            const dateStr = now.toLocaleDateString('ar-DZ');
            const timeStr = now.toLocaleTimeString('ar-DZ', { hour: '2-digit', minute: '2-digit' });

            // تعبئة بيانات الشهادة
            document.getElementById('res_name').innerText = name;
            document.getElementById('res_phone').innerText = phone;
            document.getElementById('res_pass').innerText = dob;
            document.getElementById('res_date').innerText = dateStr;
            document.getElementById('res_time').innerText = timeStr;
            document.getElementById('res_level').innerText = document.getElementById('level').value;
            document.getElementById('res_group').innerText = document.getElementById('student_group').value;

            // التحكم في وصل الدفع
            const paymentDiv = document.getElementById('paymentDetails');
            if(isPaid) {
                document.getElementById('res_amount').innerText = amount;
                paymentDiv.style.display = 'block';
            } else {
                paymentDiv.style.display = 'none';
            }

            // إظهار النافذة (Modal)
            document.getElementById('receiptModal').style.display = 'flex';
        };
    }
});

function closeModal() {
    document.getElementById('receiptModal').style.display = 'none';
}

</script>
<script>
function filterGroups() {
    // جلب العناصر من الصفحة
    const gender = document.getElementById('gender').value;
    const level = document.getElementById('level').value;
    const groupSelect = document.getElementById('student_group');

    // تفريغ القائمة الحالية وإظهار نص مساعد
    groupSelect.innerHTML = '<option value="">-- اختر الفوج الآن --</option>';

    if (gender && level) {
        let groups = [];
        let levelText = (level == "1") ? "الأول" : (level == "2") ? "الثاني" : "الثالث";

        // توزيع الأفواج بناءً على الجنس والمستوى
        if (gender === 'female') {
            groups = [فوج الإناث - المستوى ${levelText} (أ), فوج الإناث - المستوى ${levelText} (ب)];
        } else {
            groups = [فوج الذكور - المستوى ${levelText} (أ), فوج الذكور - المستوى ${levelText} (ب)];
        }

        // ملء القائمة بالخيارات الجديدة
        groups.forEach(function(groupName) {
            let option = document.createElement('option');
            option.value = groupName;
            option.text = groupName;
            groupSelect.add(option);
        });
    }
}
</script>
</body>
</html>