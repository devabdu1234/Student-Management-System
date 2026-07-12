<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$parts = explode('/', trim(str_replace('/api/', '', $uri), '/'));
$resource = $parts[0] ?? '';
$id = $parts[1] ?? null;

$data = json_decode(file_get_contents('php://input'), true) ?? [];

function json($data, $code = 200) { http_response_code($code); echo json_encode($data); exit; }
function error($msg, $code = 400) { json(['error' => $msg], $code); }

function requireAuth() {
    session_start();
    if (!isset($_SESSION['user_id'])) json(['error' => 'Unauthorized'], 401);
    return $_SESSION;
}

// --- AUTH ---
if ($resource === 'login' && $method === 'POST') {
    $email = sanitize($data['email'] ?? '');
    $password = $data['password'] ?? '';
    if (!$email || !$password) error('Email and password required');
    
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || !password_verify($password, $user['password'])) error('Invalid credentials', 401);
    
    session_start();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['fullname'] = $user['fullname'];
    
    json(['user' => [
        'id' => $user['user_id'],
        'name' => $user['fullname'],
        'email' => $user['email'],
        'role' => $user['role'],
        'phone' => $user['phone'],
    ]]);
}

if ($resource === 'logout' && $method === 'POST') {
    session_start(); session_destroy();
    json(['ok' => true]);
}

if ($resource === 'me' && $method === 'GET') {
    $sess = requireAuth();
    $stmt = $pdo->prepare('SELECT user_id, fullname, email, role, phone, created_at FROM users WHERE user_id = ?');
    $stmt->execute([$sess['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user ? json($user) : error('User not found', 404);
}

// --- USERS ---
if ($resource === 'users') {
    requireAuth();
    
    if ($method === 'GET') {
        if ($id) {
            $stmt = $pdo->prepare('SELECT user_id, fullname, email, role, phone, created_at FROM users WHERE user_id = ?');
            $stmt->execute([$id]);
            json($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
        }
        $stmt = $pdo->query('SELECT user_id, fullname, email, role, phone, created_at FROM users ORDER BY created_at DESC');
        json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    if ($method === 'POST') {
        $fullname = sanitize($data['fullname'] ?? '');
        $email = filter_var(sanitize($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $data['password'] ?? 'password123';
        $role = sanitize($data['role'] ?? 'student');
        $phone = sanitize($data['phone'] ?? '');
        if (!$fullname || !$email) error('Name and email required');
        
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (fullname, email, password, role, phone) VALUES (?,?,?,?,?)');
        $stmt->execute([$fullname, $email, $hash, $role, $phone]);
        json(['id' => $pdo->lastInsertId()], 201);
    }
    
    if ($method === 'PUT' && $id) {
        $fields = []; $params = [];
        foreach (['fullname','email','role','phone'] as $f) {
            if (isset($data[$f])) { $fields[] = "$f=?"; $params[] = sanitize($data[$f]); }
        }
        if (!$fields) error('No fields to update');
        $params[] = $id;
        $stmt = $pdo->prepare('UPDATE users SET ' . implode(',', $fields) . ' WHERE user_id=?');
        $stmt->execute($params);
        json(['ok' => true]);
    }
    
    if ($method === 'DELETE' && $id) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE user_id=?');
        $stmt->execute([$id]);
        json(['ok' => true]);
    }
}

// --- STUDENTS ---
if ($resource === 'students') {
    requireAuth();
    $table = 'student';
    $pk = 'StudentID';
    
    if ($method === 'GET') {
        if ($id) {
            $stmt = $pdo->prepare("SELECT s.*, u.fullname, u.email FROM $table s JOIN users u ON s.UserID = u.user_id WHERE s.$pk=?");
            $stmt->execute([$id]);
            json($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
        }
        $stmt = $pdo->query("SELECT s.*, u.fullname, u.email, u.phone FROM $table s JOIN users u ON s.UserID = u.user_id ORDER BY s.StudentName");
        json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    if ($method === 'POST') {
        $name = sanitize($data['StudentName'] ?? '');
        $class = sanitize($data['ClassID'] ?? '');
        $user = $data['UserID'] ?? null;
        if (!$name) error('Student name required');
        $stmt = $pdo->prepare("INSERT INTO $table (StudentName, ClassID, UserID) VALUES (?,?,?)");
        $stmt->execute([$name, $class ?: null, $user]);
        json(['id' => $pdo->lastInsertId()], 201);
    }
    
    if ($method === 'PUT' && $id) {
        $name = sanitize($data['StudentName'] ?? '');
        $class = sanitize($data['ClassID'] ?? '');
        $stmt = $pdo->prepare("UPDATE $table SET StudentName=?, ClassID=? WHERE $pk=?");
        $stmt->execute([$name, $class ?: null, $id]);
        json(['ok' => true]);
    }
    
    if ($method === 'DELETE' && $id) {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE $pk=?");
        $stmt->execute([$id]);
        json(['ok' => true]);
    }
}

// --- TEACHERS ---
if ($resource === 'teachers') {
    requireAuth();
    $table = 'teacher';
    $pk = 'TeacherID';
    
    if ($method === 'GET') {
        if ($id) {
            $stmt = $pdo->prepare("SELECT t.*, u.fullname, u.email FROM $table t JOIN users u ON t.UserID = u.user_id WHERE t.$pk=?");
            $stmt->execute([$id]);
            json($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
        }
        $stmt = $pdo->query("SELECT t.*, u.fullname, u.email FROM $table t JOIN users u ON t.UserID = u.user_id ORDER BY t.TeacherName");
        json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    if ($method === 'POST') {
        $name = sanitize($data['TeacherName'] ?? '');
        $phone = sanitize($data['Phone'] ?? '');
        $user = $data['UserID'] ?? null;
        if (!$name) error('Teacher name required');
        $stmt = $pdo->prepare("INSERT INTO $table (TeacherName, Phone, UserID) VALUES (?,?,?)");
        $stmt->execute([$name, $phone, $user]);
        json(['id' => $pdo->lastInsertId()], 201);
    }
    
    if ($method === 'DELETE' && $id) {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE $pk=?");
        $stmt->execute([$id]);
        json(['ok' => true]);
    }
}

// --- SUBJECTS ---
if ($resource === 'subjects') {
    requireAuth();
    $table = 'subject';
    $pk = 'SubjectID';
    
    if ($method === 'GET') {
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE $pk=?");
            $stmt->execute([$id]);
            json($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
        }
        $stmt = $pdo->query("SELECT * FROM $table ORDER BY SubjectName");
        json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    if ($method === 'POST') {
        $name = sanitize($data['SubjectName'] ?? '');
        if (!$name) error('Subject name required');
        $stmt = $pdo->prepare("INSERT INTO $table (SubjectName) VALUES (?)");
        $stmt->execute([$name]);
        json(['id' => $pdo->lastInsertId()], 201);
    }
    
    if ($method === 'DELETE' && $id) {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE $pk=?");
        $stmt->execute([$id]);
        json(['ok' => true]);
    }
}

// --- CLASSES ---
if ($resource === 'classes') {
    requireAuth();
    $table = 'class';
    $pk = 'ClassID';
    
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM $table ORDER BY ClassName");
        json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    if ($method === 'POST') {
        $name = sanitize($data['ClassName'] ?? '');
        if (!$name) error('Class name required');
        $stmt = $pdo->prepare("INSERT INTO $table (ClassName) VALUES (?)");
        $stmt->execute([$name]);
        json(['id' => $pdo->lastInsertId()], 201);
    }
}

// --- ATTENDANCE ---
if ($resource === 'attendance') {
    requireAuth();
    
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT a.*, s.StudentName, sub.SubjectName FROM attendance a JOIN student s ON a.StudentID = s.StudentID JOIN subject sub ON a.SubjectID = sub.SubjectID ORDER BY a.AttendanceDate DESC LIMIT 50");
        json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    if ($method === 'POST') {
        $sid = $data['StudentID'] ?? null;
        $subj = $data['SubjectID'] ?? null;
        $date = $data['AttendanceDate'] ?? date('Y-m-d');
        $status = sanitize($data['Status'] ?? 'present');
        if (!$sid || !$subj) error('StudentID and SubjectID required');
        $stmt = $pdo->prepare('INSERT INTO attendance (StudentID, SubjectID, AttendanceDate, Status) VALUES (?,?,?,?)');
        $stmt->execute([$sid, $subj, $date, $status]);
        json(['id' => $pdo->lastInsertId()], 201);
    }
}

// --- EXAMS ---
if ($resource === 'exams') {
    requireAuth();
    $table = 'exam';
    $pk = 'ExamID';
    
    if ($method === 'GET') {
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE $pk=?");
            $stmt->execute([$id]);
            json($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
        }
        $stmt = $pdo->query("SELECT e.*, s.SubjectName FROM $table e JOIN subject s ON e.SubjectID = s.SubjectID ORDER BY e.ExamName");
        json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    if ($method === 'POST') {
        $name = sanitize($data['ExamName'] ?? '');
        $subj = $data['SubjectID'] ?? null;
        $date = $data['ExamDate'] ?? date('Y-m-d');
        if (!$name) error('Exam name required');
        $stmt = $pdo->prepare("INSERT INTO $table (ExamName, SubjectID, ExamDate) VALUES (?,?,?)");
        $stmt->execute([$name, $subj, $date]);
        json(['id' => $pdo->lastInsertId()], 201);
    }
}

// --- EXAM RESULTS ---
if ($resource === 'examresults') {
    requireAuth();
    $table = 'examresults';
    $pk = 'ResultID';
    
    if ($method === 'GET') {
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE $pk=?");
            $stmt->execute([$id]);
            json($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
        }
        $stmt = $pdo->query("SELECT r.*, e.ExamName, s.StudentName FROM $table r JOIN exam e ON r.ExamID = e.ExamID JOIN student s ON r.StudentID = s.StudentID ORDER BY r.ObtainedMarks DESC");
        json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    if ($method === 'POST') {
        $eid = $data['ExamID'] ?? null;
        $sid = $data['StudentID'] ?? null;
        $marks = $data['ObtainedMarks'] ?? 0;
        if (!$eid || !$sid) error('ExamID and StudentID required');
        $stmt = $pdo->prepare("INSERT INTO $table (ExamID, StudentID, ObtainedMarks) VALUES (?,?,?)");
        $stmt->execute([$eid, $sid, $marks]);
        json(['id' => $pdo->lastInsertId()], 201);
    }
}

// --- SCHEDULE ---
if ($resource === 'schedules') {
    requireAuth();
    
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT sch.*, s.SubjectName, t.TeacherName, c.ClassName FROM schedule sch JOIN subject s ON sch.SubjectID = s.SubjectID LEFT JOIN teacher t ON sch.TeacherID = t.TeacherID LEFT JOIN class c ON sch.ClassID = c.ClassID ORDER BY sch.DayOfWeek, sch.StartTime");
        json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

// --- FEATURES ---
if ($resource === 'features') {
    if ($method === 'GET') {
        $stmt = $pdo->query('SELECT * FROM features ORDER BY Features_name');
        json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

// --- DASHBOARD STATS ---
if ($resource === 'dashboard') {
    requireAuth();
    $stats = [];
    $stats['total_students'] = $pdo->query('SELECT COUNT(*) FROM student')->fetchColumn();
    $stats['total_teachers'] = $pdo->query('SELECT COUNT(*) FROM teacher')->fetchColumn();
    $stats['total_subjects'] = $pdo->query('SELECT COUNT(*) FROM subject')->fetchColumn();
    $stats['total_classes'] = $pdo->query('SELECT COUNT(*) FROM class')->fetchColumn();
    $stmt = $pdo->query('SELECT AVG(ObtainedMarks) FROM examresults');
    $stats['avg_marks'] = round($stmt->fetchColumn() ?: 0, 1);
    $stmt = $pdo->query('SELECT COUNT(*) FROM attendance WHERE Status="present" AND AttendanceDate >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)');
    $total = $pdo->query('SELECT COUNT(*) FROM attendance WHERE AttendanceDate >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)')->fetchColumn();
    $present = $stmt->fetchColumn();
    $stats['attendance_rate'] = $total > 0 ? round($present / $total * 100, 1) : 0;
    $stats['recent_attendance'] = $pdo->query("SELECT a.*, s.StudentName, sub.SubjectName FROM attendance a JOIN student s ON a.StudentID = s.StudentID JOIN subject sub ON a.SubjectID = sub.SubjectID ORDER BY a.AttendanceDate DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    
    json($stats);
}

json(['error' => 'Not found'], 404);
