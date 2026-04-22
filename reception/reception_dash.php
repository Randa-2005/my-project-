<?php 
  
  $current_page = 'dash'; 
  include 'reception_sidebar.php'; 
  
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة الاستقبال - الرئيسية</title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<main class="main-content">
    <button id="open-sidebar" class="open-sidebar-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <header class="top-bar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="بحث سريع عن تلميذ...">
        </div>
        <div class="user-info">
            <span>مرحباً، موظف الاستقبال</span>
            <i class="fas fa-user-circle"></i>
        </div>
    </header>
    <div class="stats-container" style="display: flex; gap: 20px; padding: 20px; direction: rtl; align-items: stretch;">
    
     <div class="stat-card" onclick="openGeneralPaymentModal()""
         style="flex: 1; background: white; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 10px rgba(0,0,0,0.05); cursor: pointer; border-right: 5px solid #1a472a;">
         <div class="stat-info">
             <h3 style="margin: 0; color: #333; font-size: 1.1rem;">عملية دفع جديدة</h3>
             <p style="margin: 5px 0 0; color: #666; font-size: 0.9rem;">تسجيل مبلغ مستلم عام</p>
         </div>
         <i class="fas fa-cash-register" style="font-size: 2rem; color: #1a472a; opacity: 0.8;"></i>
     </div>

     <div class="stat-card" onclick="openRequestsModal()" 
         style="flex: 1; background: white; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 10px rgba(0,0,0,0.05); cursor: pointer; border-right: 5px solid #d4a017;">
         <div class="stat-info">
             <h3 style="margin: 0; color: #333; font-size: 1.1rem;">طلبات التسجيل الجديدة  </h3>
             <p class="number" style="margin: 5px 0 0; font-size: 1.5rem; font-weight: bold; color: #1a472a;">12</p>
         </div>
          <i class="fas fa-user-plus" style="font-size: 2rem; color: #d4a017; opacity: 0.8;"></i>
     </div>

    </div>
    <div style="padding: 20px; direction: rtl;">
        <h2 style="color: #1a472a; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
           <i class="fas fa-calendar-alt"></i> الحصص الجارية الآن
       </h2>
    
        <div id="currentClassesContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        </div>
    </div>  
   
    <div id="requestsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-clipboard-list"></i> طلبات التسجيل الجديدة</h2>
                <span class="close" onclick="closeRequestsModal()">&times;</span>
            </div>
            <div class="modal-body">
               <table class="requests-table">
                  <thead>
                    <tr>
                       <th>الاسم الكامل</th>
                       <th>المستوى الدراسي</th>
                      <th>رقم الهاتف</th>
                     <th>الإجراء</th>
                 </tr>
               </thead>
                <tbody>
                  <tr>
                     <td>ياسين بلقاسم</td>
                     <td>2 ثانوي - علوم</td>
                     <td>0661223344</td>
                      <td>
                            <button class="btn-pay-activate" onclick="openActivationModal('ياسين بلقاسم')">
                                <i class="fas fa-check-circle"></i> دفع وتفعيل الحساب
                            </button>
                     </td>
                  </tr>
             </tbody>
         </table>
            </div>
        </div>
    </div>

    <div id="activationModal" class="modal" style="display: none;">
        <div class="payment-modal" style="width: 450px; direction: rtl; text-align: right; padding: 20px; background: white; border-radius: 12px; overflow: hidden; margin: auto; ">
            <h3 style="background-color: #1a472a; color: white; padding: 15px; margin: 0; text-align: center;">
                <i class="fas fa-money-bill-wave"></i> تفعيل حساب جديد
            </h3>

            <div class="form-group" style="padding: 15px 20px;">
                <label style="display: block; text-align: right;">اسم التلميذ:</label>
                <input type="text" id="displayStudentName" readonly style="width: 100%; padding: 10px; background: #eee; border-radius: 8px; border: 1px solid #ddd;">
            </div>

            <div class="form-group" style="padding: 10px 20px;">
                <label style="display: block; text-align: right;">نوع الدفع:</label>
                <select id="act_paymentType" onchange="updateActivationAmount()" style="width: 100%; padding: 10px; border-radius: 8px;">
                    <option value="0">اختر نوع الدفع...</option>
                    <option value="2000">دفع شهري (2000 د.ج)</option>
                    <option value="6000">دفع فصلي (6000 د.ج)</option>
                </select>
            </div>

            <div class="form-group" style="padding: 10px 20px;">
                <label style="display: block; text-align: right;">المبلغ المستحق:</label>
                <input type="number" id="act_amount" value="0" readonly style="width: 100%; padding: 10px; text-align: center; font-weight: bold; border-radius: 8px;">
            </div>

            <div class="toggle-container" style="width: 100%; margin: 15px auto; display: flex; align-items: center; gap: 10px; background: #f9f9f9; padding: 10px; border-radius: 8px;">
                <label class="switch">
                    <input type="checkbox" id="hasPaid">
                    <span class="slider round"></span>
                </label>
                <span style="font-size: 0.8rem;">تم استلام المبلغ وتفعيل الحساب</span>
            </div>

            <button class="submit-btn" onclick="finalizeActivation()" style="width: 100%; background: #1a472a; color: white; padding: 12px; border: none; border-radius: 8px; margin-bottom: 20px; cursor: pointer;">
                تأكيد  <i class="fas fa-arrow-left"></i>
            </button>
            <div style="text-align: center; margin-top: 15px; padding-bottom: 10px;">
               <a href="javascript:void(0)" onclick="closeActivationModal()" 
                 style="color: #dc3545; text-decoration: none; font-size: 0.95rem; font-weight: bold; cursor: pointer;">
                  إلغاء 
              </a>
          </div>
        </div>
    </div>
    <div id="receiptModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6);">
    <div style="background: white; width: 380px; margin: 10% auto; padding: 25px; border-radius: 12px; text-align: center; direction: rtl; border: 2px dashed #1a472a;">
        <h2 style="color: #1a472a; margin-top: 0;">وصل استلام دفع</h2>
        <hr style="border: 1px solid #eee;">
        
        <div style="text-align: right; line-height: 2;">
            <p><strong>اسم التلميذ:</strong> <span id="rStudentName"></span></p>
            <p><strong>نوع الاشتراك:</strong> <span id="rType"></span></p>
            <p><strong>المبلغ المدفوع:</strong> <span id="rAmount"></span> د.ج</p>
            <p><strong>التاريخ:</strong> <span id="rDate"></span></p>
        </div>

        <hr style="border: 1px solid #eee;">
        <button onclick="window.print()" style="background: #1a472a; color: white; padding: 10px 25px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">طباعة الوصل</button>
        <button onclick="document.getElementById('receiptModal').style.display='none'" style="background: #eee; color: #333; padding: 10px 15px; border: none; border-radius: 6px; cursor: pointer; margin-top: 10px;">إغلاق</button>
    </div>
</div>
<div id="generalPaymentModel" class="payment-modal" style="display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); align-items: center; justify-content: center; direction: rtl;">
    
    <div style="background-color: white; width: 450px; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 25px rgba(0,0,0,0.3);">
        
        <div style="background-color: #1a472a; color: white; padding: 20px; text-align: center;">
            <h3 style="margin: 0;">تسجيل عملية دفع جديدة</h3>
        </div>

        <div style="padding: 25px;">
            <div style="margin-bottom: 20px; text-align: right;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">اسم التلميذ:</label>
                <input list="studentsList" id="searchStudentName" placeholder=" ابحث عن اسم التلميذ..." 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box;">
                <datalist id="studentsList">
                    <option value="ياسين بلقاسم">
                </datalist>
            </div>

            <div style="margin-bottom: 20px; text-align: right;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">نوع الدفع:</label>
                <select id="gen_paymentType" onchange="updateGenAmount()" 
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box;">
                    <option value="0">اختر نوع الدفع...</option>
                    <option value="2000">دفع شهري (2000 د.ج)</option>
                    <option value="6000">دفع فصلي (6000 د.ج)</option>
                </select>
            </div>

            <div style="margin-bottom: 25px; text-align: right;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">المبلغ المستحق:</label>
                <input type="text" id="gen_amount" value="0.00" readonly 
                       style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; background: #f9f9f9; text-align: center; font-weight: bold; color: #1a472a; box-sizing: border-box;">
            </div>

            <button onclick="processGeneralPayment()" style="width: 100%; background-color: #1a472a; color: white; padding: 15px; border: none; border-radius: 8px; cursor: pointer; font-size: 1.1rem; font-weight: bold; margin-bottom: 10px;">
                تأكيد العملية ←
            </button>

            <div style="text-align: center;">
                <a href="javascript:void(0)" onclick="closeGeneralPayment()" style="color: #cc0000; text-decoration: none; font-weight: bold;">إلغاء</a>
            </div>
        </div>
    </div>
</div>
</main>

<script>
// --- نافذة طلبات التسجيل ---
function openRequestsModal() {
    document.getElementById('requestsModal').style.display = "flex";
}
function closeRequestsModal() {
    document.getElementById('requestsModal').style.display = "none";
}

// --- نافذة تفعيل الحساب (ياسين بلقاسم) ---
function openActivationModal(studentName) {
    document.getElementById('displayStudentName').value = studentName;
    document.getElementById('activationModal').style.display = 'flex';
}

function closeActivationModal() {
    document.getElementById('activationModal').style.display = 'none';
}

// تحديث المبلغ تلقائياً في نافذة التفعيل
function updateActivationAmount() {
    var price = document.getElementById('act_paymentType').value;
    document.getElementById('act_amount').value = price;
}

function finalizeActivation() {
    // التحقق من خانة الدفع
    if(!document.getElementById('hasPaid').checked) {
        alert("يرجى التأشير على خانة استلام المبلغ وتفعيل الحساب أولاً!");
        return;
    }

    try {
        // جلب البيانات من الحقول المفتوحة في صورتك
        var name = document.getElementById('displayStudentName').value;
        var amt = document.getElementById('act_amount').value; // تأكدي من أن ID المبلغ هو act_amount
        var typeSelect = document.getElementById('act_paymentType'); // تأكدي من الـ ID في الـ Select
        var typeText = typeSelect.options[typeSelect.selectedIndex].text;

        // ملء بيانات الوصل بالترتيب
        document.getElementById('rStudentName').innerText = name;
        document.getElementById('rAmount').innerText = amt;
        document.getElementById('rType').innerText = typeText;
        document.getElementById('rDate').innerText = new Date().toLocaleDateString('ar-DZ');

        // إغلاق النافذة القديمة وإظهار الوصل
        closeActivationModal();
        document.getElementById('receiptModal').style.display = 'block';
        
    } catch (e) {
        console.error(e);
        alert("تأكدي من إضافة كود الـ HTML الخاص بالوصل (receiptModal) في نهاية الملف.");
    }
}

// --- نافذة الدفع العامة (الزر الأخضر العلوي) ---
// وظيفة لفتح واجهة تسجيل عملية دفع جديدة
// دالة فتح النافذة
function openGeneralPaymentModal() {
    var modal = document.getElementById('generalPaymentModel');
    if(modal) {
        modal.style.display = 'flex'; // تأكدي أنها flex لتظهر في المنتصف
    } else {
        console.error("لم يتم العثور على النافذة بالـ ID: generalPaymentModel");
    }
}

// دالة إغلاق النافذة
function closeGeneralPayment() {
    document.getElementById('generalPaymentModel').style.display = 'none';
}

// تحديث المبلغ في نافذة الدفع العامة
function fetchStudentPrice() {
    let type = document.getElementById('paymentType').value;
    let amountTag = document.getElementById('requiredAmount');
    
    if(type === 'monthly') amountTag.innerText = "3,000 د.ج";
    else if(type === 'registration') amountTag.innerText = "1,500 د.ج";
    else amountTag.innerText = "0.00 د.ج";
}
// تحديث المبلغ تلقائياً في النافذة الجديدة
function updateGenAmount() {
    var select = document.getElementById('gen_paymentType');
    var amountField = document.getElementById('gen_amount');
    amountField.value = (select.value !== "0") ? select.value + " د.ج" : "0.00 د.ج";
}

// معالجة الدفع وإظهار الوصل
function processGeneralPayment() {
    // 1. جلب البيانات من الحقول المفتوحة
    var student = document.getElementById('searchStudentName').value;
    var amount = document.getElementById('gen_amount').value;
    var typeSelect = document.getElementById('gen_paymentType');
    var typeText = typeSelect.options[typeSelect.selectedIndex].text;

    // 2. التحقق من إدخال البيانات
    if(student === "" || amount === "0.00") {
        alert("يرجى اختيار التلميذ ونوع الدفع أولاً");
        return;
    }

    try {
        // 3. ملء بيانات الوصل (تأكدي أن الـ IDs هذه موجودة في كود الوصل rStudentName)
        document.getElementById('rStudentName').innerText = student;
        document.getElementById('rAmount').innerText = amount;
        document.getElementById('rType').innerText = typeText;
        document.getElementById('rDate').innerText = new Date().toLocaleDateString('ar-DZ');

        // 4. إغلاق نافذة الدفع وإظهار الوصل
        document.getElementById('generalPaymentModel').style.display = 'none';
        document.getElementById('receiptModal').style.display = 'block';
        
    } catch (error) {
        console.error("خطأ في إظهار الوصل:", error);
        alert("تأكدي من وجود كود الوصل (receiptModal) في الصفحة.");
    }
}

function closeGeneralPayment() {
    var modal = document.getElementById('generalPaymentModel');
    
    if (modal) {
        // 1. إخفاء النافذة
        modal.style.display = 'none';
        
        // 2. تصفير الحقول لكي لا تبقى البيانات القديمة عند الفتح مرة أخرى
        document.getElementById('searchStudentName').value = ""; 
        document.getElementById('gen_paymentType').selectedIndex = 0; 
        document.getElementById('gen_amount').value = "0.00";
    }
}

const scheduleData = [
    { 
        teacher: "أ. ياسين بلقاسم", 
        group: "فوج 01 - رياضيات", 
        room: "قاعة 05", 
        startTime: "13:00", 
        duration: 120, 
        isAbsent: false 
    },
    { 
        teacher: "أ. فاطمة الزهراء", 
        group: "فوج 03 - فيزياء", 
        room: "قاعة 02", 
        startTime: "13:00", 
        duration: 120, 
        isAbsent: true 
    }
];

// 3. دالة تحديث الحصص (مكتوبة بحذر لتجنب الأخطاء)
function updateCurrentClasses() {
    try {
        const container = document.getElementById('currentClassesContainer');
        if (!container) return;

        const now = new Date();
        const currentMin = now.getHours() * 60 + now.getMinutes();
        
        container.innerHTML = ""; 
        let found = false;

        scheduleData.forEach(function(session) {
            const timeParts = session.startTime.split(':');
            const start = parseInt(timeParts[0]) * 60 + parseInt(timeParts[1]);
            const end = start + session.duration;

            if (currentMin >= start && currentMin < end) {
                found = true;
                const card = document.createElement('div');
                // تنسيق البطاقة مع مراعاة حالة الغياب
                card.style.cssText = "background:white; border-radius:12px; padding:20px; box-shadow:0 4px 10px rgba(0,0,0,0.05); margin-bottom:15px; border-right:5px solid " + (session.isAbsent ? "#cc0000" : "#1a472a");
                
                card.innerHTML = 
                    "<div style='display:flex; justify-content:space-between;'>" +
                        "<strong>" + session.group + "</strong>" +
                        (session.isAbsent ? "<span style='color:#cc0000; font-weight:bold;'>🚫 غائب</span>" : "<span style='color:#1a472a;'>🕒 جارية</span>") +
                    "</div>" +
                    "<div style='font-size:0.9rem; margin-top:10px;'>الأستاذ: <b>" + session.teacher + "</b></div>" +
                    "<div style='font-size:0.9rem;'>القاعة: <b>" + session.room + "</b></div>";
                
                container.appendChild(card);
            }
        });

        if (!found) {
            container.innerHTML = "<p style='text-align:center; color:#999; padding:20px;'>لا توجد حصص جارية حالياً.</p>";
        }
    } catch (err) {
        console.error("خطأ في سكريبت الحصص: ", err);
    }
}

// 4. التشغيل التلقائي عند فتح الصفحة
window.addEventListener('load', function() {
    updateCurrentClasses();
    setInterval(updateCurrentClasses, 60000);
});
</script>
</body>
</html>