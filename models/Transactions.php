<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $timestamp
 * @property int $user Покупатель
 * @property int $amount Сумма (с учетом знака)
 * @property string $reason Причина транзакции
 *
 * @property User $user0
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['timestamp', 'user', 'amount', 'reason'], 'required'],
            [['timestamp', 'user', 'amount'], 'default', 'value' => null],
            [['timestamp', 'user', 'amount'], 'integer'],
            [['reason'], 'string'],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'user' => Yii::t('app', 'Покупатель'),
            'amount' => Yii::t('app', 'Сумма (с учетом знака)'),
            'reason' => Yii::t('app', 'Причина транзакции'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
}
