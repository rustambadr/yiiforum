<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "thread".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $alias
 * @property int $id_owner
 * @property string $date_create
 * @property string $date_update
 * @property int $role_view
 */
class Thread extends \yii\db\ActiveRecord
{
    const TYPE_DEFAULT = 0;
    const TYPE_PRIVATE = 1;

    public $_user = false;
    public $_lastComment = false;
    public $_category = false;
    public static function tableName()
    {
        return 'thread';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'id_owner', 'id_category'], 'required'],
            [['content', 'color', 'color_text'], 'string'],
            [['id_owner', 'role_view', 'id_category', 'comment_count', 'enable', 'type', 'force'], 'integer'],
            [['date_create', 'date_update', 'allow_comment_ids', 'allow_view_ids'], 'safe'],
            [['title', 'alias'], 'string', 'max' => 256],
            [['alias'], 'unique']
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => 'Zelenin\yii\behaviors\Slug',
                'attribute' => 'title',
                'slugAttribute' => 'alias',
                'ensureUnique' => true,
                'replacement' => '_',
                'lowercase' => true,
                'immutable' => true,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'content' => 'Контент',
            'alias' => 'Алиас',
            'id_owner' => 'Автор публикации',
            'id_category' => 'Раздел публикации',
            'date_create' => 'Дата публикации',
            'date_update' => 'Дата обновления',
            'role_view' => 'Видно всем',
            'allow_comment_ids' => 'Имена пользователей',
            'color' => 'Цвет фона',
            'color_text' => 'Цвет текста',
            'type' => 'Тип темы',
            'allow_view_ids' => 'Имена пользователей',
            'enable' => 'Статус темы',
            'force' => 'Закрепить'
        ];
    }

    public function saveModel() {
      if( !$this->validate() ) return false;

      $this->date_create = date('Y-m-d H:i', strtotime($this->date_create));
      $this->date_update = date('Y-m-d H:i', strtotime($this->date_create));
      if( !$this->comment_count ) $this->comment_count = 0;
      if( !$this->type ) $this->type = Thread::TYPE_DEFAULT;
      if( !$this->force ) $this->force = 0;
      if( !$this->allow_comment_ids ) $this->allow_comment_ids = "";
      if( is_array($this->allow_comment_ids) ) $this->allow_comment_ids = implode(',', $this->allow_comment_ids);
      if( !$this->allow_view_ids ) $this->allow_view_ids = "";
      if( is_array($this->allow_view_ids) ) $this->allow_view_ids = implode(',', $this->allow_view_ids);

      $this->save( false );
      return true;
    }

    public function getIsModeration() {
      return ($this->role_view >= Users::ROLE_MODERATOR);
    }
    public function getOwner() {
      if( $this->_user == false )
        $this->_user = Users::find()->where(['id' => $this->id_owner])->one();
      return $this->_user;
    }
    public function getCategory_t() {
        return $this->hasOne(Category::className(), ['id' => 'id_category'])->from(Category::tableName());
    }
    public function getAllowview() {
      $ids = explode(',', $this->allow_view_ids);
      if( Yii::$app->user->identity->isAdmin || array_search(Yii::$app->user->id, $ids) !== false )
        return true;

      if( Yii::$app->user->id == $this->id_owner )
        return true;

      return false;
    }
    public function getCategory() {
      if( $this->_category == false )
        $this->_category = Category::findByID($this->id_category);
      return $this->_category;
    }
    public function getLastComment() {
      if( $this->_lastComment == false ) {
        $comment = Comment::find()->where(['id_thread' => $this->id])->orderBy(['date_create' => SORT_DESC])->one();
        $this->_lastComment = $comment;
      }
      return $this->_lastComment;
    }
    public static function findByID($id) {
      $role = Users::ROLE_MEMBER;
      if( Yii::$app->user->isGuest == false )
        $role = Yii::$app->user->identity->role;
      return static::find()->where(['id' => $id])->andWhere(['<=', 'role_view', $role])->one();
    }
    public static function findByAlias($alias) {
      $role = Users::ROLE_MEMBER;
      if( Yii::$app->user->isGuest == false )
        $role = Yii::$app->user->identity->role;
      return static::find()->where(['alias' => $alias])->andWhere(['<=', 'role_view', $role])->one();
    }
    public static function findByCategoryId($id=0) {
      $role = Users::ROLE_MEMBER;
      if( Yii::$app->user->isGuest == false )
        $role = Yii::$app->user->identity->role;

      if( is_array($id) )
        return static::find()->where(['id_category' => $id])->andWhere(['<=', 'role_view', $role])->andWhere(['type' => 0])->orderBy(['force' => SORT_DESC, 'date_update' => SORT_DESC]);
      else
        return static::find()->where(['id_category' => $id])->andWhere(['<=', 'role_view', $role])->andWhere(['type' => 0])->orderBy(['force' => SORT_DESC, 'date_update' => SORT_DESC]);
    }
    public static function getModerationCount() {
      return static::find()->where(['role_view' => Users::ROLE_MODERATOR])->count();
    }
}
