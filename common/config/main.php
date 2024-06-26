<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',

    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        
        
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
            
        ],
        'reCaptcha' => [
        'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
        
        ],
    ],
    'modules' => [
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
            
        ],
        ],
     
    ];
   


    



   





