<?php
// 1. إعدادات قاعدة البيانات الخاصة بجهازك
$host = 'localhost';
$dbname = 'smart_quran_schooli';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("فشل الاتصال بالقاعدة: " . $e->getMessage());
}
session_start();
$current_page = basename($_SERVER['PHP_SELF']);

// ========== البحث عن الطلاب (AJAX) - فقط اللي عندهم اشتراك نشط أو منتهي ==========
if (isset($_GET['search_students']) && !empty($_GET['term'])) {
    $term = '%' . $_GET['term'] . '%';
    $stmt = $conn->prepare("SELECT id, full_name, phone, subscription_end, status 
                            FROM users 
                            WHERE (role = 'student' OR role = 'طالب') 
                            AND status = 'active'
                            AND subscription_end IS NOT NULL
                            AND (full_name LIKE :term OR phone LIKE :term) 
                            LIMIT 10");
    $stmt->execute([':term' => $term]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($students);
    exit;
}
// عرض الوصل بعد نجاح العملية

// ========== جلب بيانات الطالب عند الاختيار ==========
// جلب بيانات الطالب عند الاختيار عبر AJAX
if (isset($_GET['get_student'])) {
    header('Content-Type: application/json');
    
    $student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($student_id > 0) {
        try {
            $stmt = $conn->prepare("SELECT id, full_name, phone, subscription_end, status FROM users WHERE id = :id");
            $stmt->execute([':id' => $student_id]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($student) {
                echo json_encode($student);
            } else {
                echo json_encode(['error' => 'الطالب غير موجود', 'id' => $student_id]);
            }
        } catch(PDOException $e) {
            echo json_encode(['error' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'معرف الطالب غير صالح']);
    }
    exit;
}

// ========== معالجة عملية الدفع الجديدة ==========
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['process_payment'])) {
    $user_id = intval($_POST['user_id']);
    $full_name = $_POST['full_name'];
    $period = intval($_POST['period']);
    $amount = 0;
    $payment_type = '';
    
    if($period == 1) {
        $amount = 3500;
        $payment_type = 'شهري';
    } elseif($period == 3) {
        $amount = 9000;
        $payment_type = 'فصلي';
    } elseif($period == 12) {
        $amount = 30000;
        $payment_type = 'سنوي';
    }
    
    if ($user_id > 0 && $period > 0) {
        try {
            $conn->beginTransaction();
            
            $stmt = $conn->prepare("SELECT subscription_end, status FROM users WHERE id = :id");
            $stmt->execute([':id' => $user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $today = date('Y-m-d');
            $old_end = $user['subscription_end'];
            $new_end = null;
            
            if ($old_end && $old_end > $today) {
                $new_end = date('Y-m-d', strtotime("+$period months", strtotime($old_end)));
            } else {
                $new_end = date('Y-m-d', strtotime("+$period months"));
            }
            
            $update = $conn->prepare("UPDATE users SET subscription_end = :new_end, status = 'active' WHERE id = :id");
            $update->execute([':new_end' => $new_end, ':id' => $user_id]);
            
            $insert = $conn->prepare("INSERT INTO payments (user_id, full_name, amount, period_months, payment_type, payment_date, subscription_end_old, subscription_end_new) 
                                       VALUES (:user_id, :full_name, :amount, :period_months, :payment_type, :payment_date, :old_end, :new_end)");
            $insert->execute([
                ':user_id' => $user_id,
                ':full_name' => $full_name,
                ':amount' => $amount,
                ':period_months' => $period,
                ':payment_type' => $payment_type,
                ':payment_date' => $today,
                ':old_end' => $old_end,
                ':new_end' => $new_end
            ]);
            
            $conn->commit();
            
            session_start();
            $_SESSION['receipt_data_payment'] = [
                'full_name' => $full_name,
                'phone' => $user['phone'] ?? 'غير مدخل',
                'amount' => $amount,
                'payment_type' => $payment_type,
                'subscription_end_new' => $new_end
            ];
            
            echo "<script>alert('✅ تم تسجيل عملية الدفع بنجاح!'); window.location.href = '?payment_success=1';</script>";
            
        } catch(PDOException $e) {
            $conn->rollBack();
            echo "<script>alert('❌ خطأ: " . addslashes($e->getMessage()) . "');</script>";
        }
    } else {
        echo "<script>alert('⚠ يرجى اختيار طالب ومدة صالحة');</script>";
    }
}

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
        <div class="stat-card" onclick="openGeneralPaymentModal()"
             style="flex: 1; background: white; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 10px rgba(0,0,0,0.05); cursor: pointer; border-right: 5px solid #1a472a;">
             <div class="stat-info">
                 <h3 style="margin: 0; color: #333; font-size: 1.1rem;">عملية دفع جديدة</h3>
                 <p style="margin: 5px 0 0; color: #666; font-size: 0.9rem;">تجديد اشتراك تلميذ</p>
             </div>
             <i class="fas fa-cash-register" style="font-size: 2rem; color: #1a472a; opacity: 0.8;"></i>
        </div>

        <div class="stat-card" onclick="openRequestsModal()" 
             style="flex: 1; background: white; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 10px rgba(0,0,0,0.05); cursor: pointer; border-right: 5px solid #d4a017;">
             <div class="stat-info">
                 <h3 style="margin: 0; color: #333; font-size: 1.1rem;">طلبات التسجيل الجديدة</h3>
                 <p class="number" style="margin: 5px 0 0; font-size: 1.5rem; font-weight: bold; color: #1a472a;">12</p>
             </div>
             <i class="fas fa-user-plus" style="font-size: 2rem; color: #d4a017; opacity: 0.8;"></i>
        </div>
    </div>
    
    <div style="padding: 20px; direction: rtl;">
        <h2 style="color: #1a472a; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-calendar-alt"></i> الحصص الجارية الآن
        </h2>
        <div id="currentClassesContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;"></div>
    </div>  
   
    <!-- باقي النوافذ (requestsModal, activationModal, receiptModal) بنفس ما عندك -->
    
    <!-- نافذة تسجيل عملية دفع جديدة -->
    <div id="generalPaymentModel" class="payment-modal" style="display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); align-items: center; justify-content: center; direction: rtl;">
        <div style="background-color: white; width: 500px; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 25px rgba(0,0,0,0.3);">
            <div style="background-color: #1a472a; color: white; padding: 20px; text-align: center;">
                <h3 style="margin: 0;"><i class="fas fa-credit-card"></i> تجديد اشتراك تلميذ</h3>
            </div>
            <div style="padding: 25px;">
                <div style="margin-bottom: 20px; text-align: right;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">اسم التلميذ:</label>
                    <input type="text" id="searchStudentName" placeholder="اكتب اسم التلميذ..." 
                           onkeyup="searchStudents()" autocomplete="off"
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                    <div id="studentsSearchResults" style="display: none; background: white; border: 1px solid #ccc; border-radius: 8px; max-height: 200px; overflow-y: auto; margin-top: 5px;"></div>
                    <input type="hidden" id="selectedUserId">
                </div>

                <div id="selectedStudentInfo" style="display: none; background: #e8f5e9; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <p><strong>👤 الاسم:</strong> <span id="selectedStudentName"></span></p>
                    <p><strong>📞 الهاتف:</strong> <span id="selectedStudentPhone"></span></p>
                    <p><strong>📅 الاشتراك الحالي حتى:</strong> <span id="selectedStudentEnd"></span></p>
                </div>

                <div style="margin-bottom: 20px; text-align: right;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">مدة الاشتراك الجديدة:</label>
                    <select id="gen_paymentType" onchange="updateGenAmount()" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="0">اختر المدة...</option>
                        <option value="1">شهر واحد (3500 د.ج)</option>
                        <option value="3">فصل دراسي (9000 د.ج)</option>
                        <option value="12">سنة كاملة (30000 د.ج)</option>
                    </select>
                </div>

                <div style="margin-bottom: 25px; text-align: right;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">المبلغ المستحق:</label>
                    <input type="text" id="gen_amount" value="0" readonly 
                           style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; background: #f9f9f9; text-align: center; font-weight: bold; color: #1a472a; font-size: 18px;">
                </div>

                <button onclick="processGeneralPayment()" style="width: 100%; background-color: #1a472a; color: white; padding: 15px; border: none; border-radius: 8px; cursor: pointer; font-size: 1.1rem; font-weight: bold; margin-bottom: 10px;">
                    <i class="fas fa-check-circle"></i> تأكيد الدفع والتجديد
                </button>

                <div style="text-align: center;">
                    <a href="javascript:void(0)" onclick="closeGeneralPayment()" style="color: #cc0000; text-decoration: none; font-weight: bold;">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- نافذة وصل الدفع -->
<div id="receiptModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); justify-content: center; align-items: center;">
    <div style="background: white; width: 420px; margin: 0 auto; padding: 25px; border-radius: 15px; text-align: center; direction: rtl; border: 2px solid #1a472a; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <h2 style="color: #1a472a; margin-top: 0; border-bottom: 2px solid #d4a373; padding-bottom: 10px;">
            <i class="fas fa-receipt"></i> وصل استلام دفع
        </h2>
        
        <div style="text-align: right; line-height: 2; margin: 20px 0;">
            <p><strong>👤 اسم التلميذ:</strong> <span id="rStudentName"></span></p>
            <p><strong>📞 رقم الهاتف:</strong> <span id="rStudentPhone"></span></p>
            <p><strong>📚 نوع الاشتراك:</strong> <span id="rType"></span></p>
            <p><strong>💰 المبلغ المدفوع:</strong> <span id="rAmount"></span> د.ج</p>
            <p><strong>📅 تاريخ الدفع:</strong> <span id="rDate"></span></p>
            <p><strong>📆 تاريخ انتهاء الاشتراك:</strong> <span id="rEndDate"></span></p>
        </div>

        <hr style="border: 1px solid #eee;">
        
        <div style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
            <button onclick="printReceipt()" style="background: #1a472a; color: white; padding: 10px 25px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                <i class="fas fa-print"></i> طباعة الوصل
            </button>
            <button onclick="closeReceiptModal()" style="background: #c62828; color: white; padding: 10px 25px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                <i class="fas fa-times"></i> إغلاق
            </button>
        </div>
    </div>
</div>
</main>
<?php
// ========== هنا تحطي الكود ==========
$showReceipt = false;
$receiptData = null;
if(isset($_GET['payment_success']) && isset($_SESSION['receipt_data_payment'])) {
    $showReceipt = true;
    $receiptData = $_SESSION['receipt_data_payment'];
    unset($_SESSION['receipt_data_payment']);
}
?>
<script>
// ========== وظائف البحث عن الطلاب ==========
let searchTimeout;

function searchStudents() {
    var term = document.getElementById('searchStudentName').value;
    var resultsDiv = document.getElementById('studentsSearchResults');
    
    if(term.length < 2) {
        resultsDiv.style.display = 'none';
        return;
    }
    
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '?search_students=1&term=' + encodeURIComponent(term), true);
    xhr.onload = function() {
        if(xhr.status == 200) {
            try {
                var students = JSON.parse(xhr.responseText);
                console.log("نتائج البحث:", students); // للتأكد
                
                if(students.length > 0) {
                    var html = '';
                    for(var i = 0; i < students.length; i++) {
                        html += '<div onclick="selectStudent(' + students[i].id + ', \'' + students[i].full_name.replace(/'/g, "\\'") + '\')" style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer;">';
                        html += '<strong>' + students[i].full_name + '</strong><br>';
                        html += '<small>📞 ' + (students[i].phone || 'رقم غير مدخل') + '</small>';
                        html += '</div>';
                    }
                    resultsDiv.innerHTML = html;
                    resultsDiv.style.display = 'block';
                } else {
                    resultsDiv.innerHTML = '<div style="padding: 10px;">❌ لا توجد نتائج</div>';
                    resultsDiv.style.display = 'block';
                }
            } catch(e) {
                console.error("خطأ في تحليل JSON:", e);
            }
        }
    };
    xhr.onerror = function() {
        console.error("خطأ في الطلب");
    };
    xhr.send();
}
function selectStudent(id, name) {
    document.getElementById('searchStudentName').value = name;
    document.getElementById('selectedUserId').value = id;
    document.getElementById('studentsSearchResults').style.display = 'none';
    
    // جيب باقي المعلومات باستخدام fetch
    fetch('?get_student=1&id=' + id)
        .then(response => response.json())
        .then(data => {
            console.log("البيانات المستلمة:", data); // للتأكد في console
            
            if(data && data.id) {
                document.getElementById('selectedStudentName').innerText = data.full_name || name;
                document.getElementById('selectedStudentPhone').innerText = data.phone || 'غير مدخل';
                document.getElementById('selectedStudentEnd').innerText = data.subscription_end || 'لا يوجد';
                document.getElementById('selectedStudentInfo').style.display = 'block';
            } else {
                alert("لم يتم العثور على بيانات الطالب");
            }
        })
        .catch(error => {
            console.error("خطأ في جلب البيانات:", error);
            alert("حدث خطأ في جلب بيانات الطالب");
        });
}

function updateGenAmount() {
    var select = document.getElementById('gen_paymentType');
    var amountField = document.getElementById('gen_amount');
    var period = select.value;
    if(period == "1") amountField.value = "3500";
    else if(period == "3") amountField.value = "9000";
    else if(period == "12") amountField.value = "30000";
    else amountField.value = "0";
}

function processGeneralPayment() {
    const userId = document.getElementById('selectedUserId').value;
    const fullName = document.getElementById('searchStudentName').value;
    const period = document.getElementById('gen_paymentType').value;
    const amount = document.getElementById('gen_amount').value;
    const typeSelect = document.getElementById('gen_paymentType');
    const typeText = typeSelect.options[typeSelect.selectedIndex].text;

    if (!userId || userId == "") {
        alert("⚠ يرجى اختيار اسم التلميذ من القائمة");
        return;
    }

    if (period == "0") {
        alert("⚠ يرجى اختيار مدة الاشتراك");
        return;
    }

    // جلب رقم الهاتف وتاريخ الانتهاء القديم
    const studentPhone = document.getElementById('selectedStudentPhone').innerText;
    const oldEndDate = document.getElementById('selectedStudentEnd').innerText;
    
    // حساب تاريخ الانتهاء الجديد
    let newEndDate = new Date();
    if(period == "1") newEndDate.setMonth(newEndDate.getMonth() + 1);
    else if(period == "3") newEndDate.setMonth(newEndDate.getMonth() + 3);
    else if(period == "12") newEndDate.setMonth(newEndDate.getMonth() + 12);
    
    const newEndDateStr = newEndDate.toLocaleDateString('ar-DZ');
    
    // تعبئة بيانات الوصل
    document.getElementById('rStudentName').innerText = fullName;
    document.getElementById('rStudentPhone').innerText = studentPhone;
    document.getElementById('rType').innerText = typeText;
    document.getElementById('rAmount').innerText = amount;
    document.getElementById('rDate').innerText = new Date().toLocaleDateString('ar-DZ');
    document.getElementById('rEndDate').innerText = newEndDateStr;
    
    // إرسال البيانات إلى الخادم
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '';
    form.innerHTML = `
        <input type="hidden" name="process_payment" value="1">
        <input type="hidden" name="user_id" value="${userId}">
        <input type="hidden" name="full_name" value="${fullName}">
        <input type="hidden" name="period" value="${period}">
    `;
    document.body.appendChild(form);
    form.submit();
}

function openGeneralPaymentModal() {
    const modal = document.getElementById('generalPaymentModel');
    if(modal) {
        modal.style.display = 'flex';
        // تصفير الحقول
        document.getElementById('searchStudentName').value = '';
        document.getElementById('selectedUserId').value = '';
        document.getElementById('selectedStudentInfo').style.display = 'none';
        document.getElementById('gen_paymentType').value = '0';
        document.getElementById('gen_amount').value = '0';
        
        // تصفير النتائج
        const resultsDiv = document.getElementById('studentsSearchResults');
        if(resultsDiv) resultsDiv.style.display = 'none';
    }
}

function closeGeneralPayment() {
    document.getElementById('generalPaymentModel').style.display = 'none';
}

document.addEventListener('click', function(e) {
    const searchInput = document.getElementById('searchStudentName');
    const resultsDiv = document.getElementById('studentsSearchResults');
    if (searchInput && !searchInput.contains(e.target)) {
        if (resultsDiv) resultsDiv.style.display = 'none';
    }
});

// وظائف الحصص (نفس ما عندك)
const scheduleData = [
    { teacher: "أ. ياسين بلقاسم", group: "فوج 01 - رياضيات", room: "قاعة 05", startTime: "13:00", duration: 120, isAbsent: false },
    { teacher: "أ. فاطمة الزهراء", group: "فوج 03 - فيزياء", room: "قاعة 02", startTime: "13:00", duration: 120, isAbsent: true }
];

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
                card.style.cssText = "background:white; border-radius:12px; padding:20px; box-shadow:0 4px 10px rgba(0,0,0,0.05); margin-bottom:15px; border-right:5px solid " + (session.isAbsent ? "#cc0000" : "#1a472a");
                card.innerHTML = "<div style='display:flex; justify-content:space-between;'><strong>" + session.group + "</strong>" + (session.isAbsent ? "<span style='color:#cc0000; font-weight:bold;'>🚫 غائب</span>" : "<span style='color:#1a472a;'>🕒 جارية</span>") + "</div><div style='font-size:0.9rem; margin-top:10px;'>الأستاذ: <b>" + session.teacher + "</b></div><div style='font-size:0.9rem;'>القاعة: <b>" + session.room + "</b></div>";
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

window.addEventListener('load', function() {
    updateCurrentClasses();
    setInterval(updateCurrentClasses, 60000);
});


// وظائف النوافذ الأخرى (requestsModal, activationModal, receiptModal)
function openRequestsModal() {
    document.getElementById('requestsModal').style.display = "flex";
}
function closeRequestsModal() {
    document.getElementById('requestsModal').style.display = "none";
}
function openActivationModal(studentName) {
    document.getElementById('displayStudentName').value = studentName;
    document.getElementById('activationModal').style.display = 'flex';
}
function closeActivationModal() {
    document.getElementById('activationModal').style.display = 'none';
}
function updateActivationAmount() {
    var price = document.getElementById('act_paymentType').value;
    document.getElementById('act_amount').value = price;
}
function finalizeActivation() {
    if(!document.getElementById('hasPaid').checked) {
        alert("يرجى التأشير على خانة استلام المبلغ وتفعيل الحساب أولاً!");
        return;
    }
    var name = document.getElementById('displayStudentName').value;
    var amt = document.getElementById('act_amount').value;
    var typeSelect = document.getElementById('act_paymentType');
    var typeText = typeSelect.options[typeSelect.selectedIndex].text;
    document.getElementById('rStudentName').innerText = name;
    document.getElementById('rAmount').innerText = amt;
    document.getElementById('rType').innerText = typeText;
    document.getElementById('rDate').innerText = new Date().toLocaleDateString('ar-DZ');
    closeActivationModal();
    document.getElementById('receiptModal').style.display = 'block';
}
// عرض وصل الدفع (نسخة واحدة فقط)
<?php if($showReceipt): ?>
document.addEventListener('DOMContentLoaded', function() {
    const data = <?php echo json_encode($receiptData); ?>;
    console.log("بيانات الوصل:", data);
    
    const rStudentName = document.getElementById('rStudentName');
    const rStudentPhone = document.getElementById('rStudentPhone');
    const rType = document.getElementById('rType');
    const rAmount = document.getElementById('rAmount');
    const rDate = document.getElementById('rDate');
    const rEndDate = document.getElementById('rEndDate');
    const modal = document.getElementById('receiptModal');
    
    if(rStudentName) rStudentName.innerText = data.full_name || '';
    if(rStudentPhone) rStudentPhone.innerText = data.phone || 'غير مدخل';
    if(rType) rType.innerText = data.payment_type || '';
    if(rAmount) rAmount.innerText = data.amount || '0';
    if(rDate) rDate.innerText = new Date().toLocaleDateString('ar-DZ');
    if(rEndDate) rEndDate.innerText = data.subscription_end_new || '';
    if(modal) modal.style.display = 'flex';
});
<?php endif; ?>
// ========== دوال نافذة الوصل ==========
function closeReceiptModal() {
    document.getElementById('receiptModal').style.display = 'none';
    // تنظيف الحقول
    document.getElementById('searchStudentName').value = '';
    document.getElementById('selectedUserId').value = '';
    document.getElementById('selectedStudentInfo').style.display = 'none';
    document.getElementById('gen_paymentType').value = '0';
    document.getElementById('gen_amount').value = '0';
}

function printReceipt() {
    var printContent = document.getElementById('receiptModal').innerHTML;
    var printWindow = window.open('', '_blank');
    printWindow.document.write('<html dir="rtl"><head><title>وصل دفع</title>');
    printWindow.document.write('<style>body{font-family:Arial;padding:20px;direction:rtl;} .receipt{border:2px solid #1a472a;padding:20px;border-radius:10px;}</style>');
    printWindow.document.write('</head><body><div class="receipt">');
    printWindow.document.write(printContent);
    printWindow.document.write('</div></body></html>');
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
}
</script>

</body>
</html>