<?php
// Set up database for this prototype

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
    <style>
        body {
            box-sizing: content-box;
            font-family: sans-serif;
        }

        #data {
            background: lightgray;
            border: solid 1px black;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            grid-gap: 15px;
        }

        input, textarea {
            display: block;
            max-width: 500px;
            padding: 5px;
            margin: 5px;
            font-size: 16px;
            border: 1px solid gray;
            border-radius: 10px;
            transition: all .2s ease;
            width: 100%;
        }

        .green {
            background: forestgreen;
            color: white;
        }

        #submit-button {
            cursor: pointer;
        }

        #data div {
            border: 1px solid cornflowerblue;
            background: antiquewhite;
            padding: 10px;
        }

        h4 {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Server-Sent Events Prototype</h1>
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
