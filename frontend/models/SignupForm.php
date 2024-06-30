<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

class SignupForm extends Model
{
    public $cliente_razon_social;
    public $usuario_nombre;
    public $password;
    public $cliente_cuit;
    public $cliente_telefono;
    public $cliente_mail;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['usuario_nombre', 'trim'],
            ['usuario_nombre', 'required'],
            ['usuario_nombre', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este nombre de usuario ya está registrado.'],
            ['usuario_nombre', 'string', 'min' => 2, 'max' => 255],

            ['cliente_razon_social', 'trim'],
            ['cliente_razon_social', 'required'],
            ['cliente_razon_social', 'string', 'min' => 2, 'max' => 255],

            ['cliente_mail', 'trim'],
            ['cliente_mail', 'required'],
            ['cliente_mail', 'email'],
            ['cliente_mail', 'string', 'max' => 255],
            ['cliente_mail', 'unique', 'targetClass' => '\frontend\models\Clientes', 'message' => 'Este mail ya está registrado.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength'], 'max' => Yii::$app->params['user.passwordMaxLength']],
            ['password', 'match', 'pattern' => '/[A-Z]/', 'message' => 'La contraseña debe contener al menos una letra mayúscula.'],
            ['password', 'match', 'pattern' => '/[a-z]/', 'message' => 'La contraseña debe contener al menos una letra minúscula.'],
            ['password', 'match', 'pattern' => '/\d/', 'message' => 'La contraseña debe contener al menos un número.'],
            ['password', 'match', 'pattern' => '/[\W_]/', 'message' => 'La contraseña debe contener al menos un carácter especial.'],

            [['cliente_cuit', 'cliente_telefono'], 'required'],
            ['cliente_cuit', 'string', 'max' => 20],
            ['cliente_cuit', 'unique', 'targetClass' => '\frontend\models\Clientes', 'message' => 'Cuit/DNI ya registrado.'],
            ['cliente_telefono', 'string', 'max' => 15],
        ];
    }

    /**
     * Signs user up.
     *
     * @return int|null the ID of the created user or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->usuario_nombre = $this->usuario_nombre;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = User::STATUS_INACTIVE;

        return $user->save() ? $user->usuario_id : null;
    }


    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */

public function sendEmail()
    {
        $cliente = Clientes::findOne([
            'cliente_mail' => $this->cliente_mail,
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
            ->setTo($this->cliente_mail)
            ->setSubject('Cuenta registrada en ' . Yii::$app->name)
            ->send();
    }

}








