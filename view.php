<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->execute([$id]);
    $question = $stmt->fetch();

    if ($question) {
        $filePath = $question['file_path'];
        $fileType = $question['file_type'];
        $fileName = basename($filePath);

        header("Content-Type: application/$fileType");
        header("Content-Disposition: attachment; filename=$fileName");
        readfile($filePath);
        exit;
    }
}
?>
