<?php
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

$current_page = 'payments';
include 'reception_sidebar.php';

// جلب عمليات الدفع (البحث سيتم عبر AJAX)
$payments = [];
$stmt = $conn->query("SELECT id, full_name, amount, payment_type, payment_date, subscription_end_new 
                      FROM payments 
                      ORDER BY payment_date DESC 
                      LIMIT 50");
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سجل مداخيل التلاميذ</title>
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

        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
        }

        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #bfa15f;
        }

        .requests-table {
            width: 100%;
            border-collapse: collapse;
        }

        .requests-table th, .requests-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .requests-table th {
            background: #1a472a;
            color: white;
        }

        .requests-table tr:hover {
            background: #f9f9f9;
        }

        .print-btn {
            background: #1a472a;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 20px;
        }

        .print-btn:hover {
            background: #2e7d32;
        }

        .no-results {
            text-align: center;
            padding: 30px;
            color: #999;
        }

        @media print {
            .open-sidebar-btn, .search-box, .no-print, button, .top-bar {
                display: none !important;
            }
            .table-section {
                padding: 0 !important;
            }
            .requests-table th, .requests-table td {
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>

<main class="main-content">
    <button id="open-sidebar" class="open-sidebar-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="search-container">
        <h2 style="font-family: 'Cairo'; color: #1a472a; margin: 0;">
            <i class="fas fa-chart-line"></i> سجل مداخيل التلاميذ
        </h2>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="ابحث عن اسم تلميذ..." autocomplete="off">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <div class="table-section" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <table class="requests-table" id="paymentsTable">
            <thead>
                <tr>
                    <th>رقم الوصل</th>
                    <th>اسم التلميذ</th>
                    <th>البيان (نوع الدفعة)</th>
                    <th>المبلغ المدفوع</th>
                    <th>التاريخ والوقت</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (count($payments) > 0): ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td>#REC-<?php echo str_pad($payment['id'], 6, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo htmlspecialchars($payment['full_name']); ?></td>
                            <td>دفع <?php echo $payment['payment_type']; ?> / ينتهي في <?php echo date('d/m/Y', strtotime($payment['subscription_end_new'])); ?></td>
                            <td style="font-weight: bold; color: #28a745;"><?php echo number_format($payment['amount'], 2); ?> د.ج</td>
                            <td><?php echo date('d/m/Y | H:i', strtotime($payment['payment_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="noResultsRow">
                        <td colspan="5" style="text-align: center;">لا توجد مداخيل مسجلة</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- زر الطباعة تحت الجدول -->
        <div style="text-align: center; margin-top: 25px;" class="no-print" id="printButtonContainer">
            <button onclick="printTable()" class="print-btn">
                <i class="fas fa-print"></i> طباعة السجل
            </button>
        </div>
    </div>
</main>

<script>
// إظهار/إخفاء القائمة الجانبية
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    if(sidebar) {
        sidebar.classList.toggle('active');
    }
}

// طباعة الجدول
function printTable() {
    var printContent = document.getElementById('paymentsTable').outerHTML;
    var tableTitle = '<h2 style="text-align:center; color:#1a472a;">سجل مداخيل التلاميذ</h2>';
    var dateInfo = '<p style="text-align:center;">تاريخ الطباعة: ' + new Date().toLocaleDateString('ar-DZ') + '</p>';
    var printWindow = window.open('', '_blank');
    printWindow.document.write('<html dir="rtl"><head><title>سجل المداخيل</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body{font-family:"Cairo",Arial;padding:20px;direction:rtl;}');
    printWindow.document.write('table{width:100%;border-collapse:collapse;}');
    printWindow.document.write('th,td{border:1px solid #ddd;padding:10px;text-align:center;}');
    printWindow.document.write('th{background:#1a472a;color:white;}');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(tableTitle);
    printWindow.document.write(dateInfo);
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
}

// ========== البحث الحي (Live Search) ==========
const searchInput = document.getElementById('searchInput');
const tableBody = document.getElementById('tableBody');

// جلب البيانات الأصلية من PHP
const originalData = <?php echo json_encode($payments); ?>;

function renderTable(data) {
    if(data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">❌ لا توجد نتائج مطابقة للبحث</td></tr>';
        return;
    }
    
    let html = '';
    for(let i = 0; i < data.length; i++) {
        const payment = data[i];
        const receiptNum = '#REC-' + String(payment.id).padStart(6, '0');
        const endDate = new Date(payment.subscription_end_new).toLocaleDateString('ar-DZ');
        
        html += `
            <tr>
                <td>${receiptNum}</td>
                <td>${payment.full_name}</td>
                <td>دفع ${payment.payment_type} / ينتهي في ${endDate}</td>
                <td style="font-weight: bold; color: #28a745;">${Number(payment.amount).toLocaleString()} د.ج</td>
                <td>${new Date(payment.payment_date).toLocaleDateString('ar-DZ')} | ${new Date(payment.payment_date).toLocaleTimeString('ar-DZ')}</td>
            </tr>
        `;
    }
    tableBody.innerHTML = html;
}

// فلترة البيانات حسب النص المدخل
function filterData(searchTerm) {
    if(!searchTerm) {
        renderTable(originalData);
        return;
    }
    
    const filtered = originalData.filter(payment => 
        payment.full_name.toLowerCase().includes(searchTerm.toLowerCase())
    );
    renderTable(filtered);
}

// الاستماع للكتابة في مربع البحث
searchInput.addEventListener('keyup', function() {
    const searchTerm = this.value.trim();
    filterData(searchTerm);
});
</script>

</body>
</html>