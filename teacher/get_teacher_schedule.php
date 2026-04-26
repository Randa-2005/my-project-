<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'smart_quran_schooli';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['error' => 'فشل الاتصال بقاعدة البيانات']);
    exit;
}

$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 'all';
$week_days = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'];

$time_slots = [
    '08:00 - 10:00' => ['08:00:00', '10:00:00'],
    '10:00 - 12:00' => ['10:00:00', '12:00:00'],
    '13:00 - 15:00' => ['13:00:00', '15:00:00'],
    '15:00 - 17:00' => ['15:00:00', '17:00:00']
];

$response = [];

if ($group_id === 'all') {
    // ====== العرض العام: كل الأفواج ======
    $response['week_days'] = 'السبت - الخميس';
    $response['schedule'] = [];
    
    foreach ($time_slots as $slot_name => $times) {
        $response['schedule'][$slot_name] = [];
        
        $stmt = $conn->prepare("
            SELECT s.*, g.group_name, r.room_number 
            FROM schedules s
            JOIN groups g ON s.group_id = g.id
            JOIN rooms r ON s.room_id = r.id
            WHERE s.start_time >= :start AND s.end_time <= :end
            AND s.status = 'active'
            ORDER BY s.day, s.start_time
        ");
        
        foreach ($week_days as $day) {
            $stmt->execute([':start' => $times[0], ':end' => $times[1]]);
            $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $day_sessions = [];
            foreach ($sessions as $session) {
                if ($session['day'] === $day) {
                    $day_sessions[] = [
                        'group_name' => $session['group_name'],
                        'room_number' => $session['room_number'],
                        'teacher_name' => $session['teacher_name']
                    ];
                }
            }
            $response['schedule'][$slot_name][$day] = $day_sessions;
        }
    }
    
} else {
    // ====== عرض خاص بفوج واحد ======
    $group_id = intval($group_id);
    
    $stmt = $conn->prepare("SELECT group_name, teacher_name FROM groups WHERE id = :id");
    $stmt->execute([':id' => $group_id]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$group) {
        echo json_encode(['error' => 'الفوج غير موجود']);
        exit;
    }
    
    $response['group_name'] = $group['group_name'];
    $response['teacher_name'] = $group['teacher_name'];
    $response['schedule'] = [];
    
    foreach ($time_slots as $slot_name => $times) {
        $response['schedule'][$slot_name] = [];
        
        $stmt = $conn->prepare("
            SELECT s.*, r.room_number 
            FROM schedules s
            JOIN rooms r ON s.room_id = r.id
            WHERE s.group_id = :group_id
            AND s.start_time >= :start AND s.end_time <= :end
            AND s.status = 'active'
            ORDER BY s.day, s.start_time
        ");
        $stmt->execute([':group_id' => $group_id, ':start' => $times[0], ':end' => $times[1]]);
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($week_days as $day) {
            $day_sessions = [];
            foreach ($sessions as $session) {
                if ($session['day'] === $day) {
                    $day_sessions[] = [
                        'teacher_name' => $session['teacher_name'],
                        'room_number' => $session['room_number']
                    ];
                }
            }
            $response['schedule'][$slot_name][$day] = $day_sessions;
        }
    }
}

echo json_encode($response);
?>