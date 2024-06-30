<?php

namespace frontend\models;

use Yii;
use common\models\User;
use yii\base\Model;
use frontend\models\Clientes;

class ResendVerificationEmailForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
        ['email', 'trim'],
        ['email', 'required'],
        ['email', 'email'],
        ['email', 'exist',
            'targetClass' => Clientes::className(),
            'targetAttribute' => ['email' => 'cliente_mail'],
            'filter' => function($query) {
                $query->joinWith('usuario')->andWhere([User::tableName() . '.status' => User::STATUS_INACTIVE]);
            },
            'message' => 'No hay ningún usuario registrado con ese e-mail.'
        ],
    ];
    }

    /**
     * Sends confirmation email to user
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        $cliente = Clientes::findOne([
            'cliente_mail' => $this->email,
        ]);

        if (!$cliente) {
            return false;
        }

        $user = User::findOne([
            'usuario_id' => $cliente->usuario_id,
            'status' => User::STATUS_INACTIVE,
        ]);

        if ($user === null) {
            return false;
        }

        return Yii::$app
            ->mailer->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom('no-reply@infinito.ar')
            ->setTo($this->email)
            ->setSubject('Cuenta registrada en ' . Yii::$app->name)
            ->send();
    }
}

