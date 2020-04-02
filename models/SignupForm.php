<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;
use yii\web\UploadedFile;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SignupForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $_user = false;
    public $file;

    public $captcha;

    public function rules()
    {
        return [
            [['email', 'password', 'name', 'captcha'], 'required'],
            ['email', 'validateEmail'],
            ['name', 'validateLogin'],
            ['email', 'email'],
            ['captcha', 'captcha'],
            [['file'], 'file', 'skipOnEmpty' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
            'name' => 'Логин',
            'file' => 'Аватар',
            'captcha' => 'Код'
        ];
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user) {
                $this->addError($attribute, 'Пользователь с данным Email уже существует.');
            }
        }
    }
    public function validateLogin($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Users::findByLogin($this->name);
            if ($user) {
                $this->addError($attribute, 'Пользователь с данным Логином уже существует.');
            }
        }
    }

    public function signup()
    {
        $this->file = UploadedFile::getInstance($this, 'file');
        if ($this->validate()) {
          $user = new Users;

          if ($this->file) {
            $name = 'images/upload/' . md5(md5(time()).$this->file->baseName) . '.' . $this->file->extension;
            $this->file->saveAs($name);
            $user->image = $name;
          }
          else {
            $user->image = '';
          }

          $user->name = $this->name;
          $user->email = $this->email;
          $user->about = '';
          $user->unread_message = '';
          $user->password = md5($this->password);
          $user->date_create = date('Y-m-d H:i:s');
          $user->role = Users::ROLE_MEMBER;
          $user->save( false );

          return Yii::$app->user->login($user, 3600*24*30);
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Users::findByEmail($this->email);
        }

        return $this->_user;
    }
}
