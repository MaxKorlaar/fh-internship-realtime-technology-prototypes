<?php
    /**
     * Created by Max in 2019
     */
    include './vendor/autoload.php';
    require_once 'includes/database.php';

    use Hhxsv5\SSE\SSE;
    use Hhxsv5\SSE\Update;

    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('Connection: keep-alive');
    header('X-Accel-Buffering: no');//Nginx: unbuffered responses suitable for Comet and HTTP streaming applications

    /**
     * @param null $new
     *
     * @return false|string|null
     */
    function getTimestamp($new = null) {
        static $timestamp;

        if ($timestamp === null) {
            $timestamp = date('Y-m-d H:i:s');
        }
        if ($new !== null) {
            $timestamp = $new;
        }

        return $timestamp;
    }

    (new SSE())->start(new Update(static function () use ($connection) {
        /** @var mysqli $connection */
        $result = $connection->query("SELECT * FROM notifications WHERE created_at > '" . getTimestamp() . "' ORDER BY created_at DESC");

        if ($result === false) {
            // Query failed
            return json_encode(['success' => false, 'error' => $connection->error]);
        }

        if ($result->num_rows === 0) {
            $result->close();
            return false;
        }

        $return = [];
        while ($row = $result->fetch_assoc()) {
            getTimestamp($row['created_at']);
            $return[] = $row;
        }
        $result->close();
        return json_encode($return);
    }), 'new-notifications');
