<?php
namespace frontend\models;
use frontend\modules\user\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * Clientes model
 *
 * @property int $usuario_id
 * @property string $cliente_razon_social
 * @property string $cliente_cuit
 * @property string $cliente_mail
 * @property string $cliente_telefono
 */
class Clientes extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cliente_razon_social', 'cliente_cuit', 'cliente_mail', 'cliente_telefono'], 'required'],
            [['usuario_id'], 'integer'],
            [['cliente_mail'], 'string', 'max' => 255],
            [['cliente_razon_social'], 'string', 'max' => 255],
            [['cliente_cuit'], 'string', 'max' => 20],
            [['cliente_telefono'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => Yii::t('app', 'Usuario ID'),
            'cliente_razon_social' => Yii::t('app', 'Cliente Razon Social'),
            'cliente_cuit' => Yii::t('app', 'Cliente Cuit'),
            'cliente_mail' => Yii::t('app', 'Cliente Mail'),
            'cliente_telefono' => Yii::t('app', 'Cliente Telefono'),
        ];
    }
     public function getTurnos()
    {
        return $this->hasMany(Turnos::class, ['usuario_id' => 'usuario_id']);
    }

    public function getConductores()
    {
        return $this->hasMany(Conductor::class, ['cliente_cuit' => 'cliente_cuit']);
    }

    public function getVehiculos()
    {
        return $this->hasMany(Vehiculo::class, ['cliente_cuit' => 'cliente_cuit']);
    }

    public function getUsuario()
    {
        return $this->hasOne(User::class, ['usuario_id' => 'usuario_id']);
    }

    /**
     * Override afterSave to update related models
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (isset($changedAttributes['cliente_cuit'])) {
            $this->updateRelatedModelsCuit($changedAttributes['cliente_cuit']);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Update cliente_cuit in related models
     */
    protected function updateRelatedModelsCuit($oldCuit)
    {
        
        Conductor::updateAll(['cliente_cuit' => $this->cliente_cuit], ['cliente_cuit' => $oldCuit]);
        Vehiculo::updateAll(['cliente_cuit' => $this->cliente_cuit], ['cliente_cuit' => $oldCuit]);
    }
}