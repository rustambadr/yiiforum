<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $alias
 * @property string $title
 * @property string $content
 * @property string $image
 * @property string $date_create
 * @property int $role_view
 */
class Category extends \yii\db\ActiveRecord
{
    public $file;
    public $_lastThread = false;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'role_view'], 'integer'],
            [['title', 'content', 'date_create', 'role_view'], 'required'],
            [['title', 'content'], 'string'],
            [['date_create'], 'safe'],
            [['alias'], 'string', 'max' => 256],
            [['image'], 'string', 'max' => 256],
            [['file'], 'file', 'skipOnEmpty' => true],
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
            'parent_id' => 'Родительская категория',
            'alias' => 'Алиас',
            'title' => 'Заголовок',
            'content' => 'Контент',
            'image' => 'Изображение',
            'date_create' => 'Дата создания',
            'role_view' => 'Видно всем',
            'file' => 'Изображение',
        ];
    }

    public function saveModel() {
      $this->file = UploadedFile::getInstance($this, 'file');
      if( !$this->validate() ) return false;

      if ($this->file) {
        $name = 'images/upload/' . md5(md5(time()).$this->file->baseName) . '.' . $this->file->extension;
        $this->file->saveAs($name);
        $this->image = $name;
      }
      if(!$this->parent_id) $this->parent_id = 0;
      $this->date_create = date('Y-m-d H:i', strtotime($this->date_create));

      $this->save( false );
      return true;
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
      if( is_array($alias) ) {
        return static::find()->where(['alias' => $alias])->andWhere(['<=', 'role_view', $role])->all();
      }
      else
        return static::find()->where(['alias' => $alias])->andWhere(['<=', 'role_view', $role])->one();
    }
    public static function findByParentId($id=0) {
      $role = Users::ROLE_MEMBER;
      if( Yii::$app->user->isGuest == false )
        $role = Yii::$app->user->identity->role;
      return static::find()->where(['parent_id' => $id])->andWhere(['<=', 'role_view', $role]);
    }
    public function getThreadCount() {
      $role = Users::ROLE_MEMBER;
      if( Yii::$app->user->isGuest == false )
        $role = Yii::$app->user->identity->role;

      return Thread::find()->where(['id_category' => $this->id])->andWhere(['<=', 'role_view', $role])->andWhere(['type' => 0])->count();

    }
    public function getLastThread() {
      if( $this->_lastThread == false ) {
        $thread = Thread::find()->where(['id_category' => $this->id])->andWhere(['type' => 0])->orderBy(['date_create' => SORT_DESC])->one();
        $this->_lastThread = $thread;
      }
      return $this->_lastThread;
    }
}
