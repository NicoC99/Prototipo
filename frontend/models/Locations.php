<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "locations".
 *
 * @property int $id
 * @property string $tracking_id
 * @property float $latitude
 * @property float $longitude
 * @property string $timestamp
 */
class Locations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'locations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tracking_id', 'latitude', 'longitude'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['timestamp'], 'safe'],
            [['tracking_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tracking_id' => Yii::t('app', 'Tracking ID'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }
}
