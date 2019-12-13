<?php
    /**
     * Created by Max in 2019
     */
    session_start();

    header('Refresh: 1.5; url=index.php');

    use Lcobucci\JWT\Builder;
    use Lcobucci\JWT\Signer\Hmac\Sha256;
    use Lcobucci\JWT\Signer\Key;

    require __DIR__ . '/vendor/autoload.php';

    $mercureSecretKey = 'secretkey';

    if (isset($_SESSION['user'])) {
        session_destroy();
        setcookie('mercureAuthorization', '', [
            'expires' => -1,
            'path'    => '/.well-known/mercure',
        ]);
        echo '<h1>Je bent nu uitgelogd</h1>';
    } else {
        $token = (new Builder())
            // set other appropriate JWT claims, such as an expiration date
            ->withClaim('mercure', ['subscribe' => ['notifications']]) // could also include the security roles, or anything else
            ->getToken(new Sha256(), new Key($mercureSecretKey)); // don't forget to set this parameter! Test value: aVerySecretKey

        //function setcookie ($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false) {}
        setcookie('mercureAuthorization', $token, [
            'expires'  => 0,
            'path'     => '/.well-known/mercure',
            'httponly' => true,
            'samesite' => 'strict'
        ]);
        //            sprintf('mercureAuthorization=%s; path=/.well-known/mercure; secure; httponly; SameSite=strict', $token)

        $_SESSION['user'] = [
            'username' => 'Max',
            'id'       => 1
        ];
        echo '<h1>Je bent nu ingelogd</h1>';
    }


    // https://symfony.com/doc/current/mercure.html
