<?php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method == 'GET') {
    $id = $_GET['id'] ?? '';
    $subject_id = $_GET['subject_id'] ?? '';
    $uploaded_by = $_GET['uploaded_by'] ?? '';
    $status = $_GET['status'] ?? 'approved';
    $limit = $_GET['limit'] ?? 0;
    
    $sql = "SELECT m.*, s.name as subject_name, u.name as uploaded_by_name 
            FROM materials m 
            LEFT JOIN subjects s ON m.subject_id = s.id 
            LEFT JOIN users u ON m.uploaded_by = u.id 
            WHERE m.status = ?";
    $params = [$status];
    
    if ($id) {
        $sql .= " AND m.id = ?";
        $params[] = $id;
    }
    
    if ($subject_id) {
        $sql .= " AND m.subject_id = ?";
        $params[] = $subject_id;
    }
    
    if ($uploaded_by) {
        $sql .= " AND m.uploaded_by = ?";
        $params[] = $uploaded_by;
    }
    
    if ($limit) {
        $sql .= " LIMIT ?";
        $params[] = $limit;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($materials);
} elseif ($method == 'POST') {
    $action = $input['action'] ?? '';
    
    if ($action == 'upload') {
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $type = $input['type'] ?? '';
        $subject_id = $input['subject_id'] ?? '';
        $uploaded_by = $input['uploaded_by'] ?? '';
        $file = $input['file'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO materials (title, description, type, subject_id, uploaded_by, file, upload_date) 
                              VALUES (?, ?, ?, ?, ?, ?, CURDATE())");
        
        if ($stmt->execute([$title, $description, $type, $subject_id, $uploaded_by, $file])) {
            echo json_encode(['success' => true, 'message' => 'Material uploaded successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Upload failed']);
        }
    } elseif ($action == 'download') {
        $material_id = $input['material_id'] ?? '';
        $user_id = $input['user_id'] ?? '';
        
        // Increment download count
        $stmt = $pdo->prepare("UPDATE materials SET downloads = downloads + 1 WHERE id = ?");
        $stmt->execute([$material_id]);
        
        // Get material info
        $stmt = $pdo->prepare("SELECT * FROM materials WHERE id = ?");
        $stmt->execute([$material_id]);
        $material = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'material' => $material]);
    }
} elseif ($method == 'PUT') {
    $id = $input['id'] ?? '';
    $status = $input['status'] ?? '';
    
    $stmt = $pdo->prepare("UPDATE materials SET status = ? WHERE id = ?");
    
    if ($stmt->execute([$status, $id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} elseif ($method == 'DELETE') {
    $id = $input['id'] ?? '';
    
    $stmt = $pdo->prepare("DELETE FROM materials WHERE id = ?");
    
    if ($stmt->execute([$id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
}
?>