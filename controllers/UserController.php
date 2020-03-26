<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\Category;
use app\models\Thread;
use app\models\Comment;
use app\models\Users;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\db\Query;

class UserController extends Controller
{
    public function actionIndex($id)
    {
      if( Yii::$app->user->isGuest )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $user = Users::findIdentity($id);
      if( !$user )
        throw new \yii\web\NotFoundHttpException("Пользователь не найден");

      $allowEdit = false;
      if( $id == Yii::$app->user->id || Yii::$app->user->identity->isAdmin )
        $allowEdit = true;

      if ($user->load(Yii::$app->request->post()) && $allowEdit && $user->saveModel()) {
        Yii::$app->session->setFlash('success', "Профиль обновлен");
        return Yii::$app->response->redirect(Url::to(['user/index', 'id' => $user->id]));
      }

      return $this->render('index', [
        'user' => $user,
        'allowEdit' => $allowEdit
      ]);
    }
    public function actionList()
    {
      if ( (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin) == false )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $users = Users::find()->orderBy(['date_create' => SORT_DESC]);
      $countQuery = clone $users;
      $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 16]);
      $users = $users->offset($pages->offset)->limit($pages->limit)->all();

      return $this->render('list', [
        'users' => $users,
        'pages' => $pages
      ]);
    }
    public function actionUser($q = null, $id = null) {
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $out = ['results' => ['id' => '', 'text' => '']];
      if (!is_null($q)) {
          $query = new Query;
          $query->select('id, name AS text')
              ->from('users')
              ->where(['like', 'name', $q])
              ->limit(20);
          $command = $query->createCommand();
          $data = $command->queryAll();
          $out['results'] = array_values($data);
      }
      elseif ($id > 0) {
          $out['results'] = ['id' => $id, 'text' => Users::find($id)->name];
      }
      return $out;
  }
}
