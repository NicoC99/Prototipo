<?php

namespace frontend\models;
use frontend\models\Clientes;
use Yii;

/**
 * This is the model class for table "vehiculo".
 *
 * @property string $vehiculo_patente
 * @property string $vehiculo_marca
 * @property string $vehiculo_vencimiento_rto
 * @property int $vehiculo_carga_maxima
 * @property int $cliente_cuit
 */
class Vehiculo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vehiculo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vehiculo_patente', 'vehiculo_marca', 'vehiculo_vencimiento_rto', 'vehiculo_carga_maxima', 'cliente_cuit'], 'required'],
            [['vehiculo_vencimiento_rto'], 'safe'],
            [['vehiculo_carga_maxima'], 'integer'],
            [['vehiculo_patente'], 'string', 'max' => 10],
            [['cliente_cuit'], 'string', 'max' => 20],
            [['vehiculo_marca'], 'string', 'max' => 50],
            [['vehiculo_patente'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'vehiculo_patente' => Yii::t('app', 'Vehiculo Patente'),
            'vehiculo_marca' => Yii::t('app', 'Vehiculo Marca'),
            'vehiculo_vencimiento_rto' => Yii::t('app', 'Vehiculo Vencimiento Rto'),
            'vehiculo_carga_maxima' => Yii::t('app', 'Vehiculo Carga Maxima'),
            'cliente_cuit' => Yii::t('app', 'Cliente Cuit'),
        ];
    }
    
    public function getClientes()
    {
        return $this->hasOne(Clientes::class, ['cliente_cuit' => 'cliente_cuit']);
    }
}
