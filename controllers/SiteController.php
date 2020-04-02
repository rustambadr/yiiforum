<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Thread;
use app\models\SignupForm;
use app\models\ContactForm;
use yii\captcha\Captcha;

use app\models\Category;
use yii\data\Pagination;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
      if ( !Yii::$app->user->isGuest && Yii::$app->user->identity->role == Users::ROLE_BANNED )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");
      return parent::beforeAction($action);
    }

    public function actionIndex()
    {
      return $this->render('index');
    }
    public function actionCategory()
    {
      $categorys = Category::findByParentId();
      $countQuery = clone $categorys;
      $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 18]);
      $categorys = $categorys->offset($pages->offset)->limit($pages->limit)->all();

      return $this->render('category', [
        'pages' => $pages,
        'categorys' => $categorys
      ]);
    }
    public function actionSetting()
    {
      if ( Yii::$app->user->isGuest || !Yii::$app->user->identity->isAdmin )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      if( count($_POST) ) {
        $user_online_min = Yii::$app->request->post('user_online_min', false);
        $user_online_max = Yii::$app->request->post('user_online_max', false);

        $guest_online_min = Yii::$app->request->post('guest_online_min', false);
        $guest_online_max = Yii::$app->request->post('guest_online_max', false);

        if( $user_online_min !== false && $user_online_max !== false
        && $guest_online_min !== false && $guest_online_max !== false ) {
          Yii::$app->functions->writeToConfig([
            'user_online_min' => min($user_online_min, $user_online_max),
            'user_online_max' => max($user_online_min, $user_online_max),
            'guest_online_min' => min($guest_online_min, $guest_online_max),
            'guest_online_max' => max($guest_online_min, $guest_online_max),
          ]);
        }
      }

      return $this->render('settings', [
        'params' => Yii::$app->params
      ]);
    }
    public function actionPrivate()
    {
      if ( Yii::$app->user->isGuest )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $myID = Yii::$app->user->id;
      $threads = Thread::find()->where(['type' => Thread::TYPE_PRIVATE])->orWhere("`id_category` = '7' AND `id_owner` = '$myID'")->orderBy(['date_create' => SORT_DESC])->all();

      return $this->render('list', [
        'threads' => $threads
      ]);
    }
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->goHome();
        }

        $model->password = '';
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    // public function actionContact()
    // {
    //     $model = new ContactForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
    //         Yii::$app->session->setFlash('contactFormSubmitted');
    //
    //         return $this->refresh();
    //     }
    //     return $this->render('contact', [
    //         'model' => $model,
    //     ]);
    // }

    // public function actionAbout()
    // {
    //     return $this->render('about');
    // }
}
