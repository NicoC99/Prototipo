<?php

namespace frontend\models;
use frontend\modules\user\models\User;
use Yii;

/**
 * This is the model class for table "conductor".
 *
 * @property string $nombre
 * @property string $dni
 * @property string $vigenciaLicencia
 * @property string $cliente
 * @property string $telefono
 */
class Conductor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conductor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['conductor_nombre', 'conductor_dni', 'cliente_cuit', 'conductor_telefono'], 'required'],
            [['conductor_vigencia_licencia'], 'safe'],
            [['conductor_nombre'], 'string', 'max' => 50],
            [['conductor_dni'], 'string', 'max' => 20],
            [['cliente_cuit'], 'string', 'max' => 30],
            [['conductor_telefono'], 'string', 'max' => 15],
            [['conductor_dni'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'conductor_nombre' => Yii::t('app', 'Nombre y apellido'),
            'conductor_dni' => Yii::t('app', 'Dni'),
            'conductor_vigencia_licencia' => Yii::t('app', 'Vigencia Licencia'),
            'conductor_telefono' => Yii::t('app', 'Teléfono'),
            'cliente_cuit' => Yii::t('app', 'Cliente'),
        ];
    }
     public function getClientes()
{
    return $this->hasOne(Clientes::class, ['cliente_cuit' => 'cliente_cuit']);
}
}
