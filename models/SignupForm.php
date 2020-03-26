<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;

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

    public function rules()
    {
        return [
            [['email', 'password', 'name'], 'required'],
            ['email', 'validateEmail'],
            ['email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
            'name' => 'Имя'
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

    public function signup()
    {
        if ($this->validate()) {
          $user = new Users;
          $user->name = $this->name;
          $user->email = $this->email;
          $user->image = '';
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
