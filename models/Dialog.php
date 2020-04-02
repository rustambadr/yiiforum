<?php

namespace app\models;

use Yii;
use app\models\Users;

/**
 * This is the model class for table "dialog".
 *
 * @property int $id
 * @property string $user_ids
 * @property string $date_update
 * @property int $enable
 */
class Dialog extends \yii\db\ActiveRecord
{
    public $_title = false;
    public $_unread = false;
    public static function tableName()
    {
        return 'dialog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_ids', 'date_update', 'enable'], 'required'],
            [['user_ids'], 'string'],
            [['date_update'], 'safe'],
            [['enable'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_ids' => 'User Ids',
            'date_update' => 'Date Update',
            'enable' => 'Enable',
        ];
    }

    public function getTitle() {
      if( !$this->_title ) {
        $users = json_decode($this->user_ids, true);
        $arr = [];
        foreach ($users as $key => $user) {
          $u = Users::find()->where(['id' => intval($user)])->one();
          if( $u )
            $arr[] = $u->name;
        }
        $this->_title = implode(', ', $arr);
      }

      return $this->_title;
    }
    public function getNewmessage( $id, $ignore ) {
      $users = json_decode($this->user_ids, true);
      foreach ($users as $key => $user) {
        $user = intval($user);
        if( $user == $ignore )
          continue;
        $u = Users::find()->where(['id' => intval($user)])->one();
        if( $u ) {
          $ms = explode(',', $u->unread_message);
          $ms[] = $id;
          $u->unread_message = implode(',', array_filter($ms));
          $u->save( false );
        }
      }
      $this->date_update = date('Y-m-d H:i:s');
      $this->save( false );
    }
    public function getHasadmin() {
      $users = json_decode($this->user_ids, true);
      foreach ($users as $key => $user) {
        $u = Users::find()->where(['id' => intval($user)])->one();
        if( count($users) > 2 && $u && $u->role == Users::ROLE_ADMIN )
          return true;
      }

      return false;
    }
    public function getUserCount() {
      $users = json_decode($this->user_ids, true);
      return count($users);
    }
    public function getUnread() {
      if( Yii::$app->user->isGuest )
        return 0;

      if( $this->_unread === false ) {
        $unread = explode(',',  Yii::$app->user->identity->unread_message);
        $unread = array_filter($unread);

        $this->_unread = Message::find()->where(['id' => $unread])->andWhere(['id_dialog' => $this->id])->count();
      }

      return $this->_unread;
    }
}
