<?php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method == 'GET') {
    $user_id = $_GET['user_id'] ?? '';
    
    if ($user_id) {
        $stmt = $pdo->prepare("SELECT r.*, q.title as quiz_title 
                              FROM results r 
                              LEFT JOIN quizzes q ON r.quiz_id = q.id 
                              WHERE r.user_id = ? 
                              ORDER BY r.created_at DESC");
        $stmt->execute([$user_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } else {
        echo json_encode([]);
    }
} elseif ($method == 'POST') {
    $user_id = $input['user_id'] ?? '';
    $quiz_id = $input['quiz_id'] ?? '';
    $score = $input['score'] ?? 0;
    $total = $input['total'] ?? 0;
    $details = $input['details'] ?? '';
    
    $stmt = $pdo->prepare("INSERT INTO results (user_id, quiz_id, score, total, details) 
                          VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$user_id, $quiz_id, $score, $total, $details])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
}
?>