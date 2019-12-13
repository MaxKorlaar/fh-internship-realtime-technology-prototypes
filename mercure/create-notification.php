<?php
    /**
     * Created by Max in 2019
     */

    require_once 'includes/database.php';
    require __DIR__ . '/vendor/autoload.php';

    // change these values accordingly to your hub installation
    define('HUB_URL', 'http://mercure/.well-known/mercure');
    define('JWT', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6W10sInB1Ymxpc2giOlsibm90aWZpY2F0aW9ucyJdfX0.YRuVcyKuw7vxSOYVmutyBSiFbCw7Vflt36l7DNtQDlU');

    use Symfony\Component\Mercure\Jwt\StaticJwtProvider;
    use Symfony\Component\Mercure\Publisher;
    use Symfony\Component\Mercure\Update;

    $publisher = new Publisher(HUB_URL, new StaticJwtProvider(JWT));

    if (!isset($_POST['title'], $_POST['content'])) {
        die('Missing variables');
    }

    $title   = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

    $statement = $connection->prepare('INSERT INTO notifications (title, content) VALUES (?, ?)');
    $statement->bind_param('ss', $title, $content);

    $result = $statement->execute();

    try {
        $update = new Update('http://localhost/notifications', json_encode([
            'title'      => $title,
            'content'    => $content,
            'created_at' => date('Y-m-d H:i:s')
        ]), ['notifications']); // Same target name as in auth.php

        // Serialize the update, and dispatch it to the hub, that will broadcast it to the clients
        $id = $publisher($update);

        echo json_encode(['success' => $result]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'exception' => $e->getMessage()]);
    }


