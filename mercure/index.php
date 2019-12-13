<?php
// Set up database for this prototype

session_start();

require_once 'includes/database.php';

$connection->query('CREATE TABLE IF NOT EXISTS notifications (
    id INT UNSIGNED primary key AUTO_INCREMENT,
    title VARCHAR(255) DEFAULT NULL,
    content VARCHAR(1000) DEFAULT NULL,
    created_at DATETIME ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)');

$existingNotifications = [];

$results = $connection->query('SELECT * from notifications ORDER BY created_at DESC');
if (!$results) {
    die('Something went wrong while fetching notifications');
}

while ($row = $results->fetch_assoc()) {
    $existingNotifications[] = $row;
}

?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mercure Prototype</title>
    <link rel="stylesheet" href="style/main.css">
    <script src="https://js.pusher.com/5.0/pusher.min.js"></script>
</head>
<body>
    <div class="content">
        <h1>Mercure Prototype</h1>
        <div>
            <?php
                if (isset($_SESSION['user'])) {
                    echo 'Je bent ingelogd. <a href="auth.php">Log uit</a>';
                } else {
                    echo 'Je bent niet ingelogd. <a href="auth.php">Log in</a>';
                }
            ?>
        </div>
        <p>Demo-applicatie</p>
        <form id="new-form" action="create-notification.php" method="POST">
            <input type="text" name="title" value="Titel" placeholder="Titel">
            <textarea name="content" cols="30" rows="10">Inhoud</textarea>
            <input id="submit-button" type="submit" value="Maak nieuwe melding">
        </form>

        <div id="data">
            <?php foreach ($existingNotifications as $notification) { ?>
                <div>
                    <h4><?= $notification['title'] ?></h4>
                    <p><?= $notification['content'] ?></p>
                    <small><?= $notification['created_at'] ?></small>
                </div>
            <?php } ?>
        </div>
    </div>
    <script>

        const eventSource     = new EventSource('http://localhost:8080/.well-known/mercure?topic=' + encodeURIComponent('http://localhost/notifications'), {withCredentials: true}); // { withCredentials: true } was missing from the Symfony docs
        eventSource.onmessage = event => {
            // Will be called every time an update is published by the server
            let notification = JSON.parse(event.data);

            let div      = document.createElement('div');
            let h4       = document.createElement('h4');
            h4.innerText = notification.title;

            let p       = document.createElement('p');
            p.innerText = notification.content;

            let date       = document.createElement('small');
            date.innerText = notification.created_at;

            div.appendChild(h4);
            div.appendChild(p);
            div.appendChild(date);
            div.classList.add('new');

            document.getElementById('data').prepend(div);
        };

        let button = document.getElementById('submit-button');
        document.getElementById('new-form').addEventListener('submit', function (event) {
            event.preventDefault();
            button.disabled = true;
            fetch(this.action, {
                method: this.method,
                body:   new FormData(this)
            }).then(function (response) {
                button.classList.add('green');
                button.disabled = false;
                setTimeout(() => {
                    button.classList.remove('green');
                }, 500);
            });
        })
    </script>
</body>
</html>
