<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student-management-system";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Helper: fetch all rows using prepared statement
function db_query($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

// Helper: fetch a single row
function db_fetch($sql, $params = []) {
    $stmt = db_query($sql, $params);
    return $stmt->fetch();
}

// Helper: fetch all rows
function db_fetch_all($sql, $params = []) {
    $stmt = db_query($sql, $params);
    return $stmt->fetchAll();
}

// Helper: generate CSRF token
function csrf_token(){
  if(empty($_SESSION['csrf_token'])){$_SESSION['csrf_token']=bin2hex(random_bytes(32));}
  return $_SESSION['csrf_token'];
}
// Helper: verify CSRF token
function verify_csrf($token){return isset($_SESSION['csrf_token'])&&hash_equals($_SESSION['csrf_token'],$token);}
// Helper: sanitize input string
function sanitize($str){return trim(filter_var($str,FILTER_SANITIZE_STRING));}

// Keep $conn for backwards compatibility (but redirect to use pdo)
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
