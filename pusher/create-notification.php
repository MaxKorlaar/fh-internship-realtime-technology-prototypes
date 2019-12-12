<?php
    /**
     * Created by Max in 2019
     */

    use Pusher\PusherException;

    require_once 'includes/database.php';
    require __DIR__ . '/vendor/autoload.php';

    if (!isset($_POST['title'], $_POST['content'])) {
        die('Missing variables');
    }

    $title   = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

    $statement = $connection->prepare('INSERT INTO notifications (title, content) VALUES (?, ?)');
    $statement->bind_param('ss', $title, $content);

    $result = $statement->execute();

    $options = [
        'cluster' => 'eu',
        'useTLS'  => true
    ];
    try {
        $pusher = new Pusher\Pusher(
            'f7b6150b2c3e9d04fd97',
            '26d385601d3024477380',
            '916289',
            $options
        );
        $pusher->trigger('private-notifications', 'new-notification', [
            'title'      => $title,
            'content'    => $content,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        //todo sessie authenticatie doen voor server sent events
        echo json_encode(['success' => $result]);
    } catch (PusherException $e) {
        echo json_encode(['success' => false, 'exception' => $e]);
    }


