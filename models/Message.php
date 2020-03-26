<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $id_dialog
 * @property int $id_user
 * @property string $message
 * @property string $date_create
 */
class Message extends \yii\db\ActiveRecord
{
    public $_user = false;
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dialog', 'id_user', 'message', 'date_create'], 'required'],
            [['id_dialog', 'id_user'], 'integer'],
            [['date_create'], 'safe'],
            [['message'], 'string', 'max' => 8192],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_dialog' => 'Id Dialog',
            'id_user' => 'Id User',
            'message' => 'Message',
            'date_create' => 'Date Create',
        ];
    }

    public function getDialog() {
        return $this->hasOne(Dialog::className(), ['id' => 'id_dialog'])->from(Dialog::tableName());
    }

    public function getOwner() {
      if( $this->_user == false )
        $this->_user = Users::find()->where(['id' => $this->id_user])->one();
      return $this->_user;
    }
}
