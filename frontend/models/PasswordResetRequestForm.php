<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\models\Clientes;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
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
                $query->joinWith('usuario')->andWhere([User::tableName() . '.status' => User::STATUS_ACTIVE]);
            },
            'message' => 'No hay ningún usuario registrado con ese e-mail.'
        ],
    ];
}

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
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
            'status' => User::STATUS_ACTIVE,
            'usuario_id' => $cliente->usuario_id,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom('support@infinito.ar')
            ->setTo($this->email)
            ->setSubject('Restablecimiento de contraseña para ' . Yii::$app->name)
            ->send();
    }
}



