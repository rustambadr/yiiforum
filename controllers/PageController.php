<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\Page;
use yii\helpers\Url;
use yii\data\Pagination;

class PageController extends Controller
{
    public function actionIndex($alias)
    {
      $page = Page::findByAlias($alias);
      if( !$page )
        throw new \yii\web\NotFoundHttpException("Страница не найдена");

      return $this->render('page', [
        'page' => $page
      ]);
    }
    public function actionDelete($id)
    {
      if ( (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin) == false )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $page = Page::find()->where(['id'=>$id])->one();
      if( !$page )
          throw new \yii\web\NotFoundHttpException("Страница не найдена");
      $page->delete();
      Yii::$app->session->setFlash('success', "Страница удалена");
      return Yii::$app->response->redirect(Url::to(['page/list']));
    }
    public function actionEdit($id)
    {
      if ( (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin) == false )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $page = Page::find()->where(['id'=>$id])->one();
      if( !$page ) {
        if( $id != 0 )
          throw new \yii\web\NotFoundHttpException("Страница не найдена");
      }
      if( !$page ) {
        $page = new Page;
        $page->date_create = date('Y-m-d H:i:s');
      }
      if ($page->load(Yii::$app->request->post()) && $page->saveModel()) {
        Yii::$app->session->setFlash('success', "Страница сохранена");
        return Yii::$app->response->redirect(Url::to(['page/index', 'alias' => $page->alias]));
      }
      return $this->render('new', [
        'model' => $page
      ]);
    }
    public function actionList()
    {
      if ( (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin) == false )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $_pages = Page::find()->orderBy(['date_create' => SORT_DESC]);
      $countQuery = clone $_pages;
      $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 16]);
      $_pages = $_pages->offset($pages->offset)->limit($pages->limit)->all();

      return $this->render('list', [
        '_pages' => $_pages,
        'pages' => $pages
      ]);
    }
}
