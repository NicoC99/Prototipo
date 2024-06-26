<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

class SignupForm extends Model
{
    public $cliente_razon_social;
    public $usuario_nombre;
    public $usuario_clave;
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

            ['usuario_clave', 'required'],
            ['usuario_clave', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

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
        $user->setPassword($this->usuario_clave);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = User::STATUS_ACTIVE;

        return $user->save() ? $user->id : null;
    }


    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
//protected function sendEmail($user)
//{
//    $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);

       // return Yii::$app->mailer->compose(
          //  ['html' => 'emailVerifyhtml', 'text' => 'emailVerifytext'],
        //    ['user' => $user, 'verifyLink' => $verifyLink]
      //  )
       //           ->setFrom('support@infinito.ar')
      //  ->setTo($this->email)
    //     ->setSubject($this->subject)
  //       ->send();

       // ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
        //->setTo($this->email)
       // ->setSubject('Account registration at ' . Yii::$app->name)
       // ->send();
//}

}







