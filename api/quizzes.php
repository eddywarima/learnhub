<?php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method == 'GET') {
    $id = $_GET['id'] ?? '';
    $material_id = $_GET['material_id'] ?? '';
    $teacher_id = $_GET['teacher_id'] ?? '';
    
    if ($id) {
        // Get quiz with questions
        $stmt = $pdo->prepare("SELECT q.*, m.title as material_title, s.name as subject_name 
                              FROM quizzes q 
                              LEFT JOIN materials m ON q.material_id = m.id 
                              LEFT JOIN subjects s ON m.subject_id = s.id 
                              WHERE q.id = ?");
        $stmt->execute([$id]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($quiz) {
            $stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
            $stmt->execute([$id]);
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $quiz['questions'] = $questions;
        }
        
        echo json_encode($quiz);
    } else {
        $sql = "SELECT q.*, m.title as material_title, s.name as subject_name 
                FROM quizzes q 
                LEFT JOIN materials m ON q.material_id = m.id 
                LEFT JOIN subjects s ON m.subject_id = s.id";
        $params = [];
        
        if ($material_id) {
            $sql .= " WHERE q.material_id = ?";
            $params[] = $material_id;
        } elseif ($teacher_id) {
            $sql .= " WHERE m.uploaded_by = ?";
            $params[] = $teacher_id;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($quizzes);
    }
} elseif ($method == 'POST') {
    $action = $input['action'] ?? '';
    
    if ($action == 'create_manual_quiz') {
        $material_id = $input['material_id'] ?? '';
        $questions = $input['questions'] ?? [];
        
        // Get material title for quiz title
        $stmt = $pdo->prepare("SELECT title FROM materials WHERE id = ?");
        $stmt->execute([$material_id]);
        $material = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Create quiz
        $stmt = $pdo->prepare("INSERT INTO quizzes (material_id, title) VALUES (?, ?)");
        $stmt->execute([$material_id, $material['title'] . ' Quiz']);
        $quiz_id = $pdo->lastInsertId();
        
        // Add questions
        foreach ($questions as $q) {
            $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question, option_a, option_b, option_c, option_d, correct_answer) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $quiz_id, 
                $q['question'], 
                $q['options'][0], 
                $q['options'][1], 
                $q['options'][2], 
                $q['options'][3], 
                $q['correct_answer']
            ]);
        }
        
        echo json_encode(['success' => true, 'quiz_id' => $quiz_id]);
    } elseif ($action == 'generate_ai_quiz') {
        // Simulate AI quiz generation (in a real app, this would call an AI service)
        $material_id = $input['material_id'] ?? '';
        
        // Get material info
        $stmt = $pdo->prepare("SELECT * FROM materials WHERE id = ?");
        $stmt->execute([$material_id]);
        $material = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Create quiz with some generated questions based on material title
        $stmt = $pdo->prepare("INSERT INTO quizzes (material_id, title) VALUES (?, ?)");
        $stmt->execute([$material_id, $material['title'] . ' AI Quiz']);
        $quiz_id = $pdo->lastInsertId();
        
        // Add some sample questions
        $sample_questions = [
            [
                'question' => 'What is the main topic of ' . $material['title'] . '?',
                'options' => ['Advanced concepts', 'Basic principles', 'Historical context', 'Future predictions'],
                'correct_answer' => 2
            ],
            [
                'question' => 'Which of these is most related to ' . $material['title'] . '?',
                'options' => ['Mathematics', 'Literature', 'Science', 'Arts'],
                'correct_answer' => 3
            ]
        ];
        
        foreach ($sample_questions as $q) {
            $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question, option_a, option_b, option_c, option_d, correct_answer) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $quiz_id, 
                $q['question'], 
                $q['options'][0], 
                $q['options'][1], 
                $q['options'][2], 
                $q['options'][3], 
                $q['correct_answer']
            ]);
        }
        
        echo json_encode(['success' => true, 'quiz_id' => $quiz_id]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
}
?>