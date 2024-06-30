<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "horarios".
 *
 * @property int $horario_id
 * @property string $horario_hora
 */
class Horarios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'horarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['horario_hora'], 'required'],
            [['horario_hora'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'horario_id' => Yii::t('app', 'Horario ID'),
            'horario_hora' => Yii::t('app', 'Horario Hora'),
        ];
    }
}
