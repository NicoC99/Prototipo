<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "turno".
 *
 * @property int $turno_id
 * @property string|null $turno_hora
 * @property string|null $turno_fecha
 * @property int|null $turno_estado
 * @property int $usuario_id
 * @property string $vehiculo_patente
 * @property string $conductor_dni
 * @property string|null $turno_producto
 * @property float|null $turno_cantidad
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
            [['turno_hora', 'turno_fecha'], 'safe'],
            [['turno_estado', 'usuario_id'], 'integer'],
            [['vehiculo_patente', 'conductor_dni'], 'required'],
            [['turno_cantidad'], 'number'],
            [['turno_observacion'], 'string'],
            [['vehiculo_patente'], 'string', 'max' => 15],
            [['turno_hora'], 'string', 'max' => 10],
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
            'turno_hora' => Yii::t('app', 'Turno Hora'),
            'turno_fecha' => Yii::t('app', 'Turno Fecha'),
            'turno_estado' => Yii::t('app', 'Turno Estado'),
            'usuario_id' => Yii::t('app', 'Usuario ID'),
            'vehiculo_patente' => Yii::t('app', 'Vehiculo Patente'),
            'conductor_dni' => Yii::t('app', 'Conductor Dni'),
            'turno_producto' => Yii::t('app', 'Turno Producto'),
            'turno_cantidad' => Yii::t('app', 'Turno Cantidad'),
            'turno_observacion' => Yii::t('app', 'Turno Observacion'),
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
