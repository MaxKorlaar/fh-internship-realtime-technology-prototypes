<?php
    /**
     * Created by Max in 2019
     */
    session_start();

    header('Refresh: 1.5; url=index.php');

    if (isset($_SESSION['user'])) {
        session_destroy();
        echo '<h1>Je bent nu uitgelogd</h1>';
    } else {
        $_SESSION['user'] = [
            'username' => 'Max',
            'id'       => 1
        ];
        echo '<h1>Je bent nu ingelogd</h1>';
    }
