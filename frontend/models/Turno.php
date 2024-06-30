<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "turno".
 *
 * @property int $turno_id
 * @property string $turno_hora
 * @property string $turno_fecha
 * @property int|null $turno_estado
 * @property int|null $usuario_id
 * @property string $vehiculo_patente
 * @property string $conductor_dni
 * @property string $turno_producto
 * @property float $turno_cantidad
 * @property string|null $turno_observacion
 */
class Turno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'turno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['turno_hora', 'turno_fecha', 'vehiculo_patente', 'conductor_dni', 'turno_producto', 'turno_cantidad'], 'required'],
            [['turno_fecha'], 'safe'],
            [['turno_estado', 'usuario_id'], 'integer'],
            [['turno_cantidad'], 'number'],
            [['turno_observacion'], 'string'],
            [['turno_hora'], 'string', 'max' => 10],
            [['vehiculo_patente'], 'string', 'max' => 15],
            [['conductor_dni'], 'string', 'max' => 50],
            [['turno_producto'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'turno_id' => Yii::t('app', 'Turno ID'),
            'turno_hora' => Yii::t('app', 'Horario'),
            'turno_fecha' => Yii::t('app', 'Fecha'),
            'turno_estado' => Yii::t('app', 'Estado'),
            'usuario_id' => Yii::t('app', 'Usuario ID'),
            'vehiculo_patente' => Yii::t('app', 'Vehiculo'),
            'conductor_dni' => Yii::t('app', 'Conductor'),
            'turno_producto' => Yii::t('app', 'Producto'),
            'turno_cantidad' => Yii::t('app', 'Cantidad'),
            'turno_observacion' => Yii::t('app', 'Observacion'),
        ];
    }
     public function getClientes()
    {
        return $this->hasOne(Clientes::class, ['usuario_id' => 'usuario_id']);
    }
    public function getConductor()
    {
        return $this->hasOne(Conductor::class, ['conductor_dni' => 'conductor_dni']);
    }
    public function getVehiculo()
    {
        return $this->hasOne(Vehiculo::class, ['vehiculo_patente' => 'vehiculo_patente']);
    }
}
