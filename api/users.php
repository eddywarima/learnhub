<?php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method == 'GET') {
    $role = $_GET['role'] ?? '';
    $id = $_GET['id'] ?? '';
    
    if ($id) {
        $stmt = $pdo->prepare("SELECT id, name, email, role, points, badges FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($user);
    } else {
        $sql = "SELECT id, name, email, role, points, badges FROM users";
        $params = [];
        
        if ($role) {
            $sql .= " WHERE role = ?";
            $params[] = $role;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    }
} elseif ($method == 'PUT') {
    $id = $input['id'] ?? '';
    $points = $input['points'] ?? null;
    $badges = $input['badges'] ?? null;
    
    if ($points !== null) {
        $stmt = $pdo->prepare("UPDATE users SET points = ? WHERE id = ?");
        $stmt->execute([$points, $id]);
    }
    
    if ($badges !== null) {
        $stmt = $pdo->prepare("UPDATE users SET badges = ? WHERE id = ?");
        $stmt->execute([$badges, $id]);
    }
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
}
?>