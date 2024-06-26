<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);


return [
    'language' => 'es', 
    'sourceLanguage' => 'en',

    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            
        ],
        'authManager' => [
        'class' => 'yii\rbac\DbManager',
        ],  
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            
            'rules' => [
               
            ],
        ],
        
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                ],
                'yii2mod.rbac' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                ],

            ],
           
           
        ],
        
      

        
/* 
 * Para importar el modulo
 */
        
    ],
    'modules' => [
        'user' => [
            'class' => 'frontend\modules\user\Module',
            
        ],
        'rbac' => [
        'class' => 'yii2mod\rbac\Module',
        // Otras opciones de configuración del módulo RBAC
    ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['*'],  // Permitir acceso desde cualquier IP (solo para desarrollo)
        ],
    ],
    
    
    
    'as access' => [
    'class' => yii2mod\rbac\filters\AccessControl::class,
    'allowActions' => [
        'site/login',
        'site/logout',
        'site/signup',
        'site/reset-password',
        'site/verify-email',
        'site/resend-verification-email',
        'site/request-password-reset',
        'site/error',
        'site/*',
        'clientes/*',
        'conductor/*',
        'vehiculo/*'
       
    ],  
 ],

    

    
    'params' => $params,
    
   
    
];
