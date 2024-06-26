<?php

namespace frontend\modules\user\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property string|null $verification_token
 * @property int $usuario_admin
 */

class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_nombre', 'auth_key', 'password_hash'], 'required'],
            [['status','usuario_admin'], 'integer'],
            [['usuario_nombre', 'password_hash', 'password_reset_token', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['usuario_nombre'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['usuario_admin'],'integer', 'max'=> 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => Yii::t('app', 'ID'),
            'usuario_nombre' => Yii::t('app', 'Nombre de usuario'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'status' => Yii::t('app', 'Status'),
            'verification_token' => Yii::t('app', 'Verification Token'),
            'usuario_admin' => Yii::t('app', 'ADMIN'),
        ];
    }
     public function getCliente()
    {
    return $this->hasOne(Clientes::class, ['cliente_razon_social' => 'cliente_razon_social']);
    }
    
}
