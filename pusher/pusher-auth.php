<?php
    /**
     * Created by Max in 2019
     */

    require __DIR__ . '/vendor/autoload.php';

    session_start();

    if (isset($_SESSION['user'])) {
        // User is logged in

        $options = [
            'cluster' => 'eu',
            'useTLS'  => true
        ];
        $pusher  = new Pusher\Pusher(
            'f7b6150b2c3e9d04fd97',
            '26d385601d3024477380',
            '916289',
            $options
        );
        echo $pusher->socket_auth($_POST['channel_name'], $_POST['socket_id']);

    } else {
        header('Status', true, 403);
    }
