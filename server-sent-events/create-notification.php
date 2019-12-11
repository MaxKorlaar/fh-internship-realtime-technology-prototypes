<?php
    /**
     * Created by Max in 2019
     */

    require_once 'includes/database.php';

    $timestamp = time();

    if (!isset($_POST['title'], $_POST['content'])) {
        die('Missing variables');
    }

    $title   = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

    $statement = $connection->prepare('INSERT INTO notifications (title, content) VALUES (?, ?)');
    $statement->bind_param('ss', $title, $content);

    $result = $statement->execute();

    echo json_encode(['success' => $result]);
