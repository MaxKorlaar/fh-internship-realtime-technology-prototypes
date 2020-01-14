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
    <title>Server-Sent Events Prototype</title>
    <link rel="stylesheet" href="style/main.css">
</head>
<body>
<div class="content">
    <h1>Server-Sent Events Prototype</h1>
    <div class="status-bar">
        <div class="page-name">Overzicht meldingen</div>
        <div class="user">
            <?php
            if (isset($_SESSION['user'])) {
                echo "Hallo, {$_SESSION['user']['username']}. <a href=\"auth.php\">Log uit</a>";
            } else {
                echo 'Hallo, gast. <a href="auth.php">Log in</a>';
            }
            ?>
        </div>
    </div>
    <form id="new-form" action="create-notification.php" method="POST">
        <h2>Nieuwe notificatie</h2>
        <label>Titel
            <input type="text" name="title" value="" placeholder="Titel">
        </label>
        <label>Inhoud
            <textarea name="content" cols="30" rows="10" placeholder="Inhoud"></textarea>
        </label>
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
    const source = new EventSource("push-notifications.php", {withCredentials: true});
        source.addEventListener("new-notifications", function (event) {

            for (let notification of JSON.parse(event.data)) {
                console.log(notification);
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
            }


        }, false);

        window.addEventListener('beforeunload', function () {
            console.info('Verbinding met EventSource sluiten');
            source.close();
            console.info('Verbinding gesloten');
        });
    let button = document.getElementById('submit-button');
    document.getElementById('new-form').addEventListener('submit', function (event) {
        event.preventDefault();
        button.disabled = true;
        fetch(this.action, {
            method: this.method,
            body: new FormData(this)
        }).then(function () {
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
