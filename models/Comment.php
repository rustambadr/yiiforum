<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $id_owner
 * @property string $comment
 * @property int $date_create
 * @property int $type
 */
class Comment extends \yii\db\ActiveRecord
{
    public $_user = false;
    public $_thread = false;
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_owner', 'comment', 'date_create', 'type'], 'required'],
            [['id_owner', 'type', 'id_thread'], 'integer'],
            [['comment'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_owner' => 'Автор сообщения',
            'comment' => 'Сообщение',
            'date_create' => 'Дата публикации',
            'type' => 'Тип сообщения',
        ];
    }

    public function saveModel() {
      if( !$this->validate() ) return false;
      $this->save( false );

      $countComment = Comment::find()->where(['id_thread' => $this->id_thread])->count();
      $thread = Thread::find()->where(['id' => $this->id_thread])->one();
      if( $thread ) {
        $thread->comment_count = $countComment;
        $thread->save( false );
      }

      return true;
    }
    public static function findByID($id) {
      return static::find()->where(['id' => $id])->one();
    }
    public function getOwner() {
      if( $this->_user == false )
        $this->_user = Users::find()->where(['id' => $this->id_owner])->one();
      return $this->_user;
    }
    public function getThread() {
      if( $this->_thread == false )
        $this->_thread = Thread::find()->where(['id' => $this->id_thread])->one();
      return $this->_thread;
    }
    public function getType() {
      if( $this->type == 0 )
        return 'good';
      else if( $this->type == 1 )
        return 'bad';
      else if( $this->type == 2 )
        return 'admin';
      else if( $this->type == 3 )
        return 'default';
      return '';
    }
}
