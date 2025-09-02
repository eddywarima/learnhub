<?php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    $id = $_GET['id'] ?? '';
    
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
        $stmt->execute([$id]);
        $subject = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($subject);
    } else {
        $stmt = $pdo->query("SELECT * FROM subjects");
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($subjects);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
}
?>