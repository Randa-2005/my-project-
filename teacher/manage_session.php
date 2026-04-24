<?php
session_start();
$host = 'localhost';
$dbname = 'smart_quran_schooli';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}

$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;
$today_date = date('Y-m-d');

if ($group_id > 0) {
    $stmt = $conn->prepare("SELECT group_name FROM groups WHERE id = :id");
    $stmt->execute([':id' => $group_id]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);
    $group_name = $group['group_name'] ?? 'غير معروف';
    
    $stmt = $conn->prepare("SELECT id, full_name FROM users WHERE group_id = :group_id AND (role = 'student' OR role = 'طالب') ORDER BY full_name");
    $stmt->execute([':group_id' => $group_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $group_name = 'غير معروف';
    $students = [];
}

// حفظ الكل (الحضور + التقييم) معاً
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_all'])) {
    $students_data = json_decode($_POST['students_data'], true);
    
    foreach ($students_data as $data) {
        // حفظ الحضور
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, group_id, date, status) 
                                VALUES (:student_id, :group_id, :date, :status)
                                ON DUPLICATE KEY UPDATE status = :status");
        $stmt->execute([
            ':student_id' => $data['student_id'],
            ':group_id' => $group_id,
            ':date' => $today_date,
            ':status' => $data['status']
        ]);
        
        // إذا كان حاضر، حفظ التقييم
        if ($data['status'] == 'حاضر') {
            $stmt = $conn->prepare("INSERT INTO daily_evaluation (student_id, group_id, teacher_id, evaluation_date, session_time, surah_name, from_ayah, to_ayah, memorization_score, status) 
                                    VALUES (:student_id, :group_id, :teacher_id, CURDATE(), CURTIME(), :surah, :from_ayah, :to_ayah, :score, 'حاضر')");
            $stmt->execute([
                ':student_id' => $data['student_id'],
                ':group_id' => $group_id,
                ':teacher_id' => 1,
                ':surah' => $data['surah'],
                ':from_ayah' => $data['from_ayah'],
                ':to_ayah' => $data['to_ayah'],
                ':score' => $data['score']
            ]);
        }
    }
    
    echo "<script>alert('✅ تم حفظ البيانات بنجاح!'); window.location.href='manage_session.php?group_id=$group_id';</script>";
}

// حفظ نتائج الاختبار
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_exam'])) {
    $exam_title = $_POST['exam_title'];
    $exam_date = $_POST['exam_date'];
    $exam_type = $_POST['exam_type'];
    $scores = json_decode($_POST['scores_data'], true);
    
    $stmt = $conn->prepare("INSERT INTO exams (exam_title, group_id, teacher_id, exam_date, exam_type) VALUES (:title, :group_id, :teacher_id, :date, :type)");
    $stmt->execute([':title' => $exam_title, ':group_id' => $group_id, ':teacher_id' => 1, ':date' => $exam_date, ':type' => $exam_type]);
    $exam_id = $conn->lastInsertId();
    
    $insert = $conn->prepare("INSERT INTO exam_results (exam_id, student_id, hifz_score, ahkam_score, makharij_score, total_score) VALUES (:exam_id, :student_id, :hifz, :ahkam, :makharij, :total)");
    foreach ($scores as $s) {
        $total = $s['hifz'] + $s['ahkam'] + $s['makharij'];
        $insert->execute([
            ':exam_id' => $exam_id,
            ':student_id' => $s['student_id'],
            ':hifz' => $s['hifz'],
            ':ahkam' => $s['ahkam'],
            ':makharij' => $s['makharij'],
            ':total' => $total
        ]);
    }
    
    echo "<script>alert('✅ تم حفظ نتائج الاختبار بنجاح!'); window.location.href='manage_session.php?group_id=$group_id';</script>";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الحلقة - <?php echo htmlspecialchars($group_name); ?></title>
    <link rel="stylesheet" href="teacher_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .main-content { margin-right: 0 !important; width: 100% !important; padding: 30px !important; }
        .table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .table th { background: #1a472a; color: white; padding: 12px; }
        .table td { padding: 12px; border-bottom: 1px solid #eee; text-align: center; }
        
        .status-btn { padding: 8px 20px; border-radius: 25px; cursor: pointer; font-weight: bold; background: #e8f5e9; color: #1a472a; border: 2px solid #1a472a; }
        .status-btn.absent { background: #ffebee; color: #c62828; border: 2px solid #c62828; }
        
        .score-input { width: 70px; padding: 6px; text-align: center; border: 1px solid #ddd; border-radius: 5px; }
        .select-input { width: 130px; padding: 6px; border: 1px solid #ddd; border-radius: 5px; }
        
        .disabled-input { background: #e0e0e0; opacity: 0.6; cursor: not-allowed; }
        
        .save-all-btn { background: #28a745; color: white; border: none; padding: 10px 30px; border-radius: 8px; cursor: pointer; font-size: 1rem; margin-top: 10px; }
        .cancel-btn { background: #6c757d; color: white; border: none; padding: 10px 30px; border-radius: 8px; cursor: pointer; font-size: 1rem; margin: 0 10px; }
        
        .btn-home, .exam-btn { display: inline-block; padding: 8px 20px; background: #bfa15f; color: white; text-decoration: none; border-radius: 8px; margin-right: 10px; border: none; cursor: pointer; }
        .exam-btn { background: #c62828; }
        
        .stars i { color: #ddd; cursor: pointer; font-size: 1.2rem; margin: 0 2px; }
        .stars i.active { color: #ffc107; }
        .total { font-weight: bold; font-size: 1rem; color: #1a472a; }
        
        .exam-section { display: none; margin-top: 30px; }
        .exam-section.show { display: block; }
        
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
        
        .section-title { 
            background: #f5f5f5; 
            padding: 10px 15px; 
            border-radius: 8px; 
            margin: 20px 0 15px 0;
            border-right: 5px solid #1a472a;
            font-weight: bold;
        }
        .button-group { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <main class="main-content">
        <div class="top-bar">
            <h2>إدارة حلقة: <?php echo htmlspecialchars($group_name); ?></h2>
            <div>
                <button class="exam-btn" onclick="toggleExam()"><i class="fas fa-file-signature"></i> اختبار جديد</button>
                <a href="teacher_groups.php" class="btn-home"><i class="fas fa-reply"></i> العودة</a>
            </div>
        </div>

        <!-- ========== جدول التقييم والحضور ========== -->
        <div id="dailySection">
            <div class="section-title">
                <i class="fas fa-clipboard-list"></i> تسجيل الحضور والتقييم اليومي
            </div>
            
            <form id="mainForm" onsubmit="saveAll(event)">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الطالب</th>
                            <th>الحالة</th>
                            <th>السورة</th>
                            <th>من آية</th>
                            <th>إلى آية</th>
                            <th>الدرجة (0-20)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $counter = 1;
                        foreach ($students as $student): 
                            $stmt = $conn->prepare("SELECT status FROM attendance WHERE student_id = :student_id AND date = :date");
                            $stmt->execute([':student_id' => $student['id'], ':date' => $today_date]);
                            $att = $stmt->fetch(PDO::FETCH_ASSOC);
                            $current_status = $att['status'] ?? 'حاضر';
                            $isAbsent = ($current_status == 'غائب');
                        ?>
                        <tr>
                            <input type="hidden" name="student_id[]" value="<?php echo $student['id']; ?>">
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                            <td>
                                <button type="button" class="status-btn <?php echo ($current_status == 'غائب') ? 'absent' : ''; ?>" onclick="toggleStatus(this, <?php echo $student['id']; ?>)">
                                    <?php echo $current_status; ?>
                                </button>
                                <input type="hidden" id="status_<?php echo $student['id']; ?>" name="status_<?php echo $student['id']; ?>" value="<?php echo $current_status; ?>">
                            </td>
                            <td>
                                <select name="surah_<?php echo $student['id']; ?>" id="surah_<?php echo $student['id']; ?>" class="select-input" <?php echo $isAbsent ? 'disabled class="disabled-input"' : ''; ?>>
                                    <option>سورة الفاتحة</option><option>سورة البقرة</option><option>سورة آل عمران</option>
                                    <option>سورة النساء</option><option>سورة المائدة</option>
                                </select>
                            </td>
                            <td><input type="number" name="from_ayah_<?php echo $student['id']; ?>" id="from_<?php echo $student['id']; ?>" class="score-input" value="1" min="1" <?php echo $isAbsent ? 'disabled class="disabled-input"' : ''; ?>></td>
                            <td><input type="number" name="to_ayah_<?php echo $student['id']; ?>" id="to_<?php echo $student['id']; ?>" class="score-input" value="1" min="1" <?php echo $isAbsent ? 'disabled class="disabled-input"' : ''; ?>></td>
                            <td><input type="number" name="score_<?php echo $student['id']; ?>" id="score_<?php echo $student['id']; ?>" class="score-input" min="0" max="20" value="0" <?php echo $isAbsent ? 'disabled class="disabled-input"' : ''; ?>></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="button-group">
                    <button type="submit" class="save-all-btn"><i class="fas fa-save"></i> حفظ الحضور والتقييمات</button>
                </div>
            </form>
        </div>

        <!-- ========== قسم الاختبار ========== -->
        <div id="examSection" class="exam-section">
            <div class="section-title" style="margin-top: 0;">
                <i class="fas fa-pen"></i> اختبار جديد
            </div>
            <div style="background:#f5f5f5; padding:15px; border-radius:10px; margin-bottom:15px;">
                <div style="display:flex; gap:15px; flex-wrap:wrap;">
                    <div><label>تاريخ الاختبار:</label><input type="date" id="exam_date" style="width:150px; padding:6px;" value="<?php echo date('Y-m-d'); ?>"></div>
                    <div><label>نوع الاختبار:</label><select id="exam_type" style="width:150px; padding:6px;"><option>أسبوعي</option><option>شهري</option></select></div>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الحفظ (8)</th>
                        <th>الأحكام (8)</th>
                        <th>المخارج (4)</th>
                        <th>العلامة /20</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): 
                        $stmt = $conn->prepare("SELECT status FROM attendance WHERE student_id = :student_id AND date = :date");
                        $stmt->execute([':student_id' => $student['id'], ':date' => $today_date]);
                        $att = $stmt->fetch(PDO::FETCH_ASSOC);
                        $isAbsent = ($att['status'] ?? 'حاضر') == 'غائب';
                    ?>
                    <tr class="exam-row" data-id="<?php echo $student['id']; ?>" data-absent="<?php echo $isAbsent ? '1' : '0'; ?>">
                        <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                        <?php if ($isAbsent): ?>
                            <td colspan="4" style="color:#c62828;">🚫 غائب</td>
                        <?php else: ?>
                            <td class="stars hifz"><?php for($i=1;$i<=8;$i++) echo "<i class='fas fa-star' data-val='$i'></i>"; ?></td>
                            <td class="stars ahkam"><?php for($i=1;$i<=8;$i++) echo "<i class='fas fa-star' data-val='$i'></i>"; ?></td>
                            <td class="stars makh"><?php for($i=1;$i<=4;$i++) echo "<i class='fas fa-star' data-val='$i'></i>"; ?></td>
                            <td class="total">0 / 20</td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="button-group">
                <button class="save-all-btn" onclick="saveExam()" style="background:#1a472a;">حفظ نتائج الاختبار</button>
                <button class="cancel-btn" onclick="toggleExam()">إلغاء</button>
            </div>
        </div>
    </main>

    <script>
        // تبديل حالة الحضور/الغياب
        function toggleStatus(btn, id) {
            let isPresent = btn.innerText === "حاضر";
            let newStatus = isPresent ? "غائب" : "حاضر";
            btn.innerText = newStatus;
            btn.classList.toggle('absent');
            
            document.getElementById('status_' + id).value = newStatus;
            
            let isAbsent = (newStatus === "غائب");
            let surah = document.getElementById('surah_' + id);
            let fromAyah = document.getElementById('from_' + id);
            let toAyah = document.getElementById('to_' + id);
            let score = document.getElementById('score_' + id);
            
            if (isAbsent) {
                surah.disabled = true;
                fromAyah.disabled = true;
                toAyah.disabled = true;
                score.disabled = true;
                surah.classList.add('disabled-input');
                fromAyah.classList.add('disabled-input');
                toAyah.classList.add('disabled-input');
                score.classList.add('disabled-input');
                score.value = 0;
            } else {
                surah.disabled = false;
                fromAyah.disabled = false;
                toAyah.disabled = false;
                score.disabled = false;
                surah.classList.remove('disabled-input');
                fromAyah.classList.remove('disabled-input');
                toAyah.classList.remove('disabled-input');
                score.classList.remove('disabled-input');
            }
            
            // تحديث حالة الطالب في جدول الاختبار
            updateExamRowStatus(id, newStatus);
        }
        
        // تحديث حالة الطالب في جدول الاختبار
        function updateExamRowStatus(studentId, status) {
            let examRow = document.querySelector(`.exam-row[data-id="${studentId}"]`);
            if (examRow) {
                let isAbsent = (status === "غائب");
                let studentName = examRow.querySelector('td:first-child').innerText;
                
                if (isAbsent) {
                    examRow.innerHTML = `
                        <td>${studentName}</td>
                        <td colspan="4" style="color:#c62828;">🚫 غائب</td>
                    `;
                    examRow.setAttribute('data-absent', '1');
                } else {
                    examRow.innerHTML = `
                        <td>${studentName}</td>
                        <td class="stars hifz"><?php for($i=1;$i<=8;$i++) echo "<i class='fas fa-star' data-val='$i'></i>"; ?></td>
                        <td class="stars ahkam"><?php for($i=1;$i<=8;$i++) echo "<i class='fas fa-star' data-val='$i'></i>"; ?></td>
                        <td class="stars makh"><?php for($i=1;$i<=4;$i++) echo "<i class='fas fa-star' data-val='$i'></i>"; ?></td>
                        <td class="total">0 / 20</td>
                    `;
                    examRow.setAttribute('data-absent', '0');
                    attachStarEvents(examRow);
                }
                examRow.setAttribute('data-id', studentId);
            }
        }
        
        // ربط حدث النجوم
        function attachStarEvents(row) {
            row.querySelectorAll('.stars i').forEach(star => {
                star.onclick = function() {
                    let r = this.closest('.exam-row');
                    if (r.dataset.absent === '1') return;
                    let val = parseInt(this.dataset.val);
                    let parent = this.parentElement;
                    let isActive = this.classList.contains('active');
                    
                    if (isActive) {
                        parent.querySelectorAll('i').forEach(s => {
                            if (parseInt(s.dataset.val) >= val) s.classList.remove('active');
                        });
                    } else {
                        parent.querySelectorAll('i').forEach(s => {
                            if (parseInt(s.dataset.val) <= val) s.classList.add('active');
                            else s.classList.remove('active');
                        });
                    }
                    updateTotal(r);
                };
            });
        }
        
        // حساب المجموع
        function updateTotal(row) {
            let hifz = row.querySelectorAll('.hifz i.active').length;
            let ahkam = row.querySelectorAll('.ahkam i.active').length;
            let makh = row.querySelectorAll('.makh i.active').length;
            row.querySelector('.total').innerHTML = (hifz + ahkam + makh) + " / 20";
        }
        
        // حفظ الكل (الحضور + التقييم)
        function saveAll(event) {
            event.preventDefault();
            let students = [];
            <?php foreach ($students as $student): ?>
            students.push({
                student_id: <?php echo $student['id']; ?>,
                status: document.getElementById('status_<?php echo $student['id']; ?>').value,
                surah: document.getElementById('surah_<?php echo $student['id']; ?>').value,
                from_ayah: document.getElementById('from_<?php echo $student['id']; ?>').value,
                to_ayah: document.getElementById('to_<?php echo $student['id']; ?>').value,
                score: document.getElementById('score_<?php echo $student['id']; ?>').value
            });
            <?php endforeach; ?>
            let form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = '<input type="hidden" name="save_all" value="1"><input type="hidden" name="students_data" value=\'' + JSON.stringify(students) + '\'>';
            document.body.appendChild(form);
            form.submit();
        }

        // إظهار/إخفاء الاختبار
        function toggleExam() {
            let examDiv = document.getElementById('examSection');
            let dailyDiv = document.getElementById('dailySection');
            
            if (examDiv.classList.contains('show')) {
                examDiv.classList.remove('show');
                dailyDiv.style.display = "block";
            } else {
                examDiv.classList.add('show');
                dailyDiv.style.display = "none";
                
                document.querySelectorAll('#examSection .stars i').forEach(s => s.classList.remove('active'));
                document.querySelectorAll('#examSection .total').forEach(t => t.innerHTML = "0 / 20");
                
                document.querySelectorAll('#examSection .exam-row').forEach(row => {
                    if (row.dataset.absent === '0') {
                        attachStarEvents(row);
                    }
                });
            }
        }

        // حفظ نتائج الاختبار
        function saveExam() {
            let date = document.getElementById('exam_date').value;
            let type = document.getElementById('exam_type').value;
            let title = "اختبار " + type + " - " + date;
            let scores = [];
            document.querySelectorAll('.exam-row').forEach(row => {
                if (row.dataset.absent === '0') {
                    scores.push({
                        student_id: row.dataset.id,
                        hifz: row.querySelectorAll('.hifz i.active').length,
                        ahkam: row.querySelectorAll('.ahkam i.active').length,
                        makharij: row.querySelectorAll('.makh i.active').length
                    });
                }
            });
            if (scores.length === 0) { alert("لا يوجد طلاب حاضرون لتسجيل العلامات"); return; }
            let form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="save_exam" value="1"><input type="hidden" name="exam_title" value="${title}"><input type="hidden" name="exam_date" value="${date}"><input type="hidden" name="exam_type" value="${type}"><input type="hidden" name="scores_data" value='${JSON.stringify(scores)}'>`;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>