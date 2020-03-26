<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string $content
 * @property string $date_create
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'date_create'], 'required'],
            [['content', 'icon'], 'string'],
            [['date_create', 'icon'], 'safe'],
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
            'alias' => 'Алиас',
            'content' => 'Контент',
            'date_create' => 'Дата публикации',
            'icon' => 'Иконка'
        ];
    }

    public function saveModel() {
      if( !$this->validate() ) return false;

      $this->date_create = date('Y-m-d H:i', strtotime($this->date_create));

      $this->save( false );
      return true;
    }

    public static function findByAlias($alias) {
      return static::find()->where(['alias' => $alias])->one();
    }
}
