<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $date_create
 * @property int $role
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $file;

    const ROLE_BANNED = 0;
    const ROLE_MEMBER = 1;
    const ROLE_SELLER = 2;
    const ROLE_VIP = 3;
    const ROLE_MODERATOR = 4;
    const ROLE_ADMIN = 5;
    const ROLE_GARANT = 6;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'date_create', 'role'], 'required'],
            [['date_create', 'about', 'unread_message'], 'safe'],
            [['role'], 'integer'],
            [['name', 'email', 'image'], 'string', 'max' => 256],
            [['password'], 'string', 'max' => 64],
            [['file'], 'file', 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'image' => 'Фотография',
            'date_create' => 'Дата регистрации',
            'role' => 'Роль',
            'about' => 'Обо мне',
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
      if( strlen($this->password) > 0 )
        $this->password = md5($this->password);

      $this->save( false );
      return true;
    }

    public static function findByEmail($email) {
      return static::findOne(['email' => $email]);
    }
    public static function findByLogin($email) {
      return static::findOne(['name' => $email]);
    }
    public function validatePassword($password) {
        return ($this->password === md5($password));
    }
    public static function findIdentity($id) {
        return static::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null) {
        return null;
    }
    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getImage() {
        if( $this->image == NULL )
          return Url::to('@web/') . "images/nophoto.png";
        return Url::to('@web/') . $this->image;
    }
    public function getAuthKey() {
        return null;
    }
    public function validateAuthKey($authKey) {
        return false;
    }
    public function getIsAdmin()
    {
        return ($this->role >= Users::ROLE_ADMIN);
    }
    public function getIsModer()
    {
        return ($this->role >= Users::ROLE_MODERATOR);
    }
    public function getColor() {
        if( $this->role == Users::ROLE_GARANT )
          return "#f44336";
        else if( $this->role == Users::ROLE_ADMIN )
          return "#f44336";
        else if( $this->role == Users::ROLE_ADMIN )
          return "#f44336";
        else if( $this->role == Users::ROLE_MODERATOR )
          return "#ff9900";
        else if( $this->role == Users::ROLE_VIP )
          return "#9900ff";
        else if( $this->role == Users::ROLE_SELLER )
          return "#6aa84f";

        return "#000000";
    }
    public function getUnread()
    {
      $unread = array_filter(explode(',', $this->unread_message));
      return count($unread);
    }
}
