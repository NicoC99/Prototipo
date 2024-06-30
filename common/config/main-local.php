<?php

return [
    'components' => [
        'name' => 'Turnos San Máximo',
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=prototipo_cea',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\symfonymailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default.
            
            // You have to set
            //
             'useFileTransport' => false,
            //
            // and configure a transport for the mailer to send real emails.
            //
            // SMTP server example:
                'transport' => [
                    'scheme' => 'smtps',
                    'host' => 'c1640603.ferozo.com',
                    'username' => 'no-reply@infinito.ar',
                    'password' => 'bawYtHZd2W',
                    'port' => 465,
                    'dsn' => 'smtps://no-reply%40infinito.ar:bawYtHZd2W@c1640603.ferozo.com:465',
                ],
            //
            // DSN example:
                'transport' => [
                   'dsn' => 'smtps://no-reply%40infinito.ar:bawYtHZd2W@c1640603.ferozo.com:465',
               ],
            //
            // See: https://symfony.com/doc/current/mailer.html#using-built-in-transports
            // Or if you use a 3rd party service, see:
            // https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport
        ],
    ],
];
