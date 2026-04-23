<?php
$host = 'localhost';
$dbname = 'smart_quran_schooli';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("خطأ في الاتصال: " . $e->getMessage());
}

// حفظ البيانات (AJAX)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax_save'])) {
    header('Content-Type: application/json');
    
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $level = $_POST['level'] ?? '';
    $student_group = $_POST['student_group'] ?? '';
    $period = intval($_POST['period'] ?? 0);
    $has_paid = isset($_POST['has_paid']) ? true : false;
    
    if (empty($full_name) || empty($dob) || empty($gender) || empty($level)) {
        echo json_encode(['success' => false, 'message' => 'الرجاء ملء جميع الحقول']);
        exit;
    }
    
    $amount = 0;
    if($period == 1) $amount = 3500;
    elseif($period == 3) $amount = 9000;
    elseif($period == 12) $amount = 30000;
    
    $status = $has_paid ? 'active' : 'pending';
    $is_verified = $has_paid ? 1 : 0;
    $pass = password_hash($dob, PASSWORD_DEFAULT);
    $role = 'طالب';
    
    $subscription_end = null;
    if($period > 0 && $has_paid) {
        $subscription_end = date('Y-m-d', strtotime("+$period months"));
    }
    
    try {
        $sql = "INSERT INTO users (full_name, phone, birth_date, password, role, status, gender, academic_level, subscription, duration, subscription_end, is_verified) 
                VALUES (:full_name, :phone, :birth_date, :password, :role, :status, :gender, :academic_level, :subscription, :duration, :subscription_end, :is_verified)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':full_name' => $full_name,
            ':phone' => $phone,
            ':birth_date' => $dob,
            ':password' => $pass,
            ':role' => $role,
            ':status' => $status,
            ':gender' => $gender,
            ':academic_level' => $level,
            ':subscription' => $student_group,
            ':duration' => $period . ' أشهر',
            ':subscription_end' => $subscription_end,
            ':is_verified' => $is_verified
        ]);
        
        $user_id = $conn->lastInsertId();
        
        if($has_paid && $period > 0) {
            $payment_type = ($period == 1) ? 'شهري' : (($period == 3) ? 'فصلي' : 'سنوي');
            $insert_payment = $conn->prepare("INSERT INTO payments (user_id, full_name, amount, period_months, payment_type, payment_date, subscription_end_new) 
                                               VALUES (:user_id, :full_name, :amount, :period_months, :payment_type, :payment_date, :new_end)");
            $insert_payment->execute([
                ':user_id' => $user_id,
                ':full_name' => $full_name,
                ':amount' => $amount,
                ':period_months' => $period,
                ':payment_type' => $payment_type,
                ':payment_date' => date('Y-m-d'),
                ':new_end' => $subscription_end
            ]);
        }
        
        echo json_encode(['success' => true, 'message' => 'تم التسجيل بنجاح']);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل تلميذ جديد</title>
    <link rel="stylesheet" href="reception_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .open-sidebar-btn {
            display: none;
            position: fixed;
            top: 15px;
            right: 15px;
            background: #1a472a;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2rem;
            z-index: 1001;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            .open-sidebar-btn {
                display: block;
            }
            .main-content {
                margin-right: 0 !important;
                padding-top: 60px;
            }
        }

        /* تنسيق الشهادة */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            border: 1px solid #d4a373;
        }

        .receipt-title {
            color: #1a472a;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #d4a373;
            padding-bottom: 10px;
        }

        .info-section {
            background: #fef9e6;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .info-section p {
            margin: 10px 0;
            padding: 8px;
            background: white;
            border-radius: 8px;
            border-right: 4px solid #1a472a;
        }

        .payment-receipt-section {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 15px;
            margin: 15px 0;
            text-align: center;
        }

        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }

        .print-btn, .close-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .print-btn {
            background: #1a472a;
            color: white;
        }

        .close-btn {
            background: #c62828;
            color: white;
        }

        @media print {
            .no-print {
                display: none;
            }
            .modal {
                display: block;
                position: relative;
                background: white;
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
                    <label>الاسم الكامل:</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>
            </div>

            <div class="input-row">
                <div class="input-field">
                    <label>تاريخ الميلاد:</label>
                    <input type="date" id="dob" name="dob" required onchange="updatePrice()">
                </div>
                <div class="input-field">
                    <label>رقم الهاتف:</label>
                    <input type="tel" id="phone" name="phone">
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
                        <option value="">-- اختر --</option>
                        <option value="إبتدائي">إبتدائي</option>
                        <option value="متوسط">متوسط</option>
                        <option value="ثانوي">ثانوي</option>
                    </select>
                </div>
            </div>

            <div class="input-row">
                <div class="input-field full-width">
                    <label>الفوج الدراسي:</label>
                    <select id="student_group" name="student_group">
                        <option value="">-- اختر الفوج --</option>
                    </select>
                </div>
            </div>
            
            <div class="input-row">
                <div class="input-field">
                    <label>مدة الاشتراك:</label>
                    <select id="period" name="period" onchange="updatePrice()">
                        <option value="0">اختر المدة...</option>
                        <option value="1">شهر واحد (3500 دج)</option>
                        <option value="3">فصل دراسي (9000 دج)</option>
                        <option value="12">سنة كاملة (30000 دج)</option>
                    </select>
                </div>
            </div>

            <div id="priceDisplay" class="price-notice" style="display:none; background:#e8f5e9; padding:10px; border-radius:8px; margin:10px 0; text-align:center;">
                <i class="fas fa-tag"></i> المبلغ المطلوب: <span id="amountText">0</span> د.ج
            </div>

            <div class="payment-box">
                <div class="toggle-container">
                    <label class="switch">
                        <input type="checkbox" id="hasPaid" name="has_paid">
                        <span class="slider round"></span>
                    </label>
                    <span>تم دفع حقوق التسجيل (تفعيل الحساب)</span>
                </div>
            </div>

            <button type="button" id="submitBtn" class="submit-btn">
                تأكيد <i class="fas fa-arrow-left"></i>
            </button>
        </form>
    </div>
</main>

<!-- نافذة الشهادة -->
<div id="receiptModal" class="modal">
    <div class="modal-content" id="printableArea">
        <h2 class="receipt-title">📜 شهادة تأكيد التسجيل</h2>
        
        <div class="info-section">
            <p><strong>👤 اسم التلميذ:</strong> <span id="res_name"></span></p>
            <p><strong>📞 رقم الهاتف:</strong> <span id="res_phone"></span></p>
            <p><strong>🔑 كلمة السر:</strong> <span id="res_pass"></span></p>
            <p><strong>📅 تاريخ التسجيل:</strong> <span id="res_date"></span></p>
            <p><strong>⏰ الوقت:</strong> <span id="res_time"></span></p>
            <p><strong>📚 المستوى:</strong> <span id="res_level"></span></p>
            <p><strong>👥 الفوج:</strong> <span id="res_group"></span></p>
            <p><strong>📆 تاريخ انتهاء الاشتراك:</strong> <span id="res_sub_end"></span></p>
        </div>

        <div id="paymentDetails" class="payment-receipt-section" style="display:none;">
            <h3>🧾 وصل الدفع</h3>
            <p><strong>💰 المبلغ المدفوع:</strong> <span id="res_amount"></span> د.ج</p>
            <p><strong>✅ حالة الحساب:</strong> <span style="color: green;">مفعّل</span></p>
        </div>

        <div class="no-print actions">
            <button onclick="window.print()" class="print-btn">🖨️ طباعة</button>
            <button onclick="closeModal()" class="close-btn">✖️ إغلاق</button>
        </div>
    </div>
</div>

<script>
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

function filterGroups() {
    const gender = document.getElementById('gender').value;
    const level = document.getElementById('level').value;
    const groupSelect = document.getElementById('student_group');
    groupSelect.innerHTML = '<option value="">-- اختر الفوج --</option>';
    if (gender && level) {
        let groups = [];
        if (gender === 'female') {
            groups = ['فوج الإناث - ' + level + ' (أ)', 'فوج الإناث - ' + level + ' (ب)'];
        } else {
            groups = ['فوج الذكور - ' + level + ' (أ)', 'فوج الذكور - ' + level + ' (ب)'];
        }
        groups.forEach(function(groupName) {
            let option = document.createElement('option');
            option.value = groupName;
            option.text = groupName;
            groupSelect.add(option);
        });
    }
}

function calculateSubscriptionEnd(periodMonths) {
    if(periodMonths == 0) return 'لا يوجد';
    const today = new Date();
    let endDate = new Date(today);
    endDate.setMonth(today.getMonth() + parseInt(periodMonths));
    return endDate.toLocaleDateString('ar-DZ');
}

document.getElementById('submitBtn').onclick = function() {
    const name = document.getElementById('full_name').value;
    const phone = document.getElementById('phone').value;
    const dob = document.getElementById('dob').value;
    const level = document.getElementById('level').value;
    const gender = document.getElementById('gender').value;
    const student_group = document.getElementById('student_group').value;
    const period = document.getElementById('period').value;
    const isPaid = document.getElementById('hasPaid').checked;
    
    let price = 0;
    if(period == "1") price = 3500;
    else if(period == "3") price = 9000;
    else if(period == "12") price = 30000;
    
    if(!name || !dob || !level || !gender) {
        alert('⚠ الرجاء ملء جميع الحقول المطلوبة');
        return;
    }
    
    const now = new Date();
    const dateStr = now.toLocaleDateString('ar-DZ');
    const timeStr = now.toLocaleTimeString('ar-DZ', { hour: '2-digit', minute: '2-digit' });
    
    document.getElementById('res_name').innerText = name;
    document.getElementById('res_phone').innerText = phone || 'غير مدخل';
    document.getElementById('res_pass').innerText = dob;
    document.getElementById('res_date').innerText = dateStr;
    document.getElementById('res_time').innerText = timeStr;
    document.getElementById('res_level').innerText = level;
    document.getElementById('res_group').innerText = student_group || 'غير محدد';
    document.getElementById('res_sub_end').innerText = calculateSubscriptionEnd(period);
    
    const paymentDiv = document.getElementById('paymentDetails');
    if(isPaid) {
        document.getElementById('res_amount').innerText = price;
        paymentDiv.style.display = 'block';
    } else {
        paymentDiv.style.display = 'none';
    }
    
    document.getElementById('receiptModal').style.display = 'flex';
    
    // حفظ البيانات بعد 3 ثواني
    setTimeout(function() {
        const formData = new FormData();
        formData.append('ajax_save', '1');
        formData.append('full_name', name);
        formData.append('phone', phone);
        formData.append('dob', dob);
        formData.append('gender', gender);
        formData.append('level', level);
        formData.append('student_group', student_group);
        formData.append('period', period);
        formData.append('has_paid', isPaid ? '1' : '0');
        
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                console.log('✅ تم الحفظ');
            } else {
                console.log('❌ خطأ: ' + data.message);
            }
        });
    }, 3000);
};

function closeModal() {
    document.getElementById('receiptModal').style.display = 'none';
    document.getElementById('regForm').reset();
    document.getElementById('priceDisplay').style.display = 'none';
}
</script>

</body>
</html>