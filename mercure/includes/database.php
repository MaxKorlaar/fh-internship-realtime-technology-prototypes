<?php
    /**
     * Created by Max in 2019
     */

    $host     = 'db';
    $username = 'test';
    $password = 'password';
    $database = 'prototype';

    $connection = new mysqli($host, $username, $password, $database);

    if ($connection->connect_error) {
        die('Er ging iets mis tijdens het opzetten van een databaseverbinding :( - Misschien is de database nog aan het starten?');
    }
