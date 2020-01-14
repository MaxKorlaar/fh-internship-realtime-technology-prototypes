<?php
/**
 * Created by Max in 2019
 */
session_start();


if (isset($_SESSION['user'])) {
    session_destroy();
} else {
    $_SESSION['user'] = [
        'username' => 'Max',
        'id' => 1
    ];
}
header('Location: index.php');
