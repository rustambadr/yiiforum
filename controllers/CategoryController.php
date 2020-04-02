<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\Category;
use app\models\Thread;
use app\models\Users;
use yii\helpers\Url;
use yii\data\Pagination;

class CategoryController extends Controller
{
  public function beforeAction($action)
  {
    if ( !Yii::$app->user->isGuest && Yii::$app->user->identity->role == Users::ROLE_BANNED )
      throw new \yii\web\NotFoundHttpException("Доступ закрыт");
    return parent::beforeAction($action);
  }
    public function actionMain()
    {
      $categorys = Category::findByAlias(['belye_uslugi', 'serye_uslugi', 'cernye_uslugi']);
      if( !count($categorys) )
        throw new \yii\web\NotFoundHttpException("Категории не найдена");

      $ids = [];
      foreach ($categorys as $key => $category) {
        $ids[] = $category->id;
      }
      $threads = Thread::findByCategoryId($ids);
      $countQuery = clone $threads;
      $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 8]);
      $threads = $threads->offset($pages->offset)->limit($pages->limit)->all();

      return $this->render('category_main', [
        'categorys' => $categorys,
        'threads' => $threads,
        'pages' => $pages
      ]);
    }
    public function actionIndex($alias)
    {
      $category = Category::findByAlias($alias);
      if( !$category )
        throw new \yii\web\NotFoundHttpException("Категория не найдена");

      $threads = Thread::findByCategoryId($category->id);
      $countQuery = clone $threads;
      $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 8]);
      $threads = $threads->offset($pages->offset)->limit($pages->limit)->all();

      return $this->render('category', [
        'category' => $category,
        'threads' => $threads,
        'pages' => $pages
      ]);
    }
    public function actionDelete($id)
    {
      if ( (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin) == false )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $category = Category::find()->where(['id'=>$id])->one();
      if( !$category )
          throw new \yii\web\NotFoundHttpException("Категория не найдена");
      $category->delete();
      Yii::$app->session->setFlash('success', "Категория удалена");
      return Yii::$app->response->redirect(Url::to(['site/category']));
    }
    public function actionEdit($id)
    {
      if ( (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin) == false )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $category = Category::find()->where(['id'=>$id])->one();
      if( !$category ) {
        if( $id != 0 )
          throw new \yii\web\NotFoundHttpException("Категория не найдена");
      }
      if( !$category ) {
        $category = new Category;
        $category->date_create = date('Y-m-d H:i:s');
        $category->parent_id = 0;
        $category->role_view = Users::ROLE_MODERATOR;
      }
      if ($category->load(Yii::$app->request->post()) && $category->saveModel()) {
        Yii::$app->session->setFlash('success', "Категория сохранена");
        return Yii::$app->response->redirect(Url::to(['category/index', 'alias' => $category->alias]));
      }
      return $this->render('new', [
        'model' => $category
      ]);
    }
}
