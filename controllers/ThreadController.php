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

class ThreadController extends Controller
{
    public function actions()
    {
        return [
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => '/images/temp',
                'path' => 'images/temp',
            ],
        ];
    }

    public function beforeAction($action)
    {
      if ( !Yii::$app->user->isGuest && Yii::$app->user->identity->role == Users::ROLE_BANNED )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");
      return parent::beforeAction($action);
    }

    public function actionIndex($alias)
    {
      $thread = Thread::findByAlias($alias);
      if( !$thread )
        throw new \yii\web\NotFoundHttpException("Тема на модерации");

      $category = $thread->getCategory();
      if( !$category )
        throw new \yii\web\NotFoundHttpException("Тема на модерации");

      if( Yii::$app->user->isGuest && $thread->type == Thread::TYPE_PRIVATE )
        throw new \yii\web\NotFoundHttpException("Тема недоступна");

      $allowComment = false;
      if( Yii::$app->user->isGuest == false ) {
        $ids = explode(',', $thread->allow_comment_ids);
        if( Yii::$app->user->identity->isAdmin || array_search(Yii::$app->user->id, $ids) !== false )
          $allowComment = true;
      }
      if( $thread->type == Thread::TYPE_PRIVATE && !$thread->getAllowview() ) {
        // $ids = explode(',', $thread->allow_view_ids);
        // if( !Yii::$app->user->identity->isAdmin && array_search(Yii::$app->user->id, $ids) === false )
          throw new \yii\web\NotFoundHttpException("Тема недоступна");
      }

      $comment = new Comment;
      $comment->date_create = date('Y-m-d H:i:s');
      $comment->type = 3;
      $comment->id_owner = Yii::$app->user->id;
      $comment->id_thread = $thread->id;

      if ($comment->load(Yii::$app->request->post()) && Yii::$app->user->isGuest == false && $comment->saveModel()) {
        $thread->date_update = date('Y-m-d H:i:s');
        $thread->save( false );

        Yii::$app->session->setFlash('success', "Сообщение опубликовано");
        return Yii::$app->response->redirect(Url::to(['thread/index', 'alias' => $thread->alias]));
      }

      // $comments = Comment::find()->where(['id_thread' => $thread->id])->orderBy(['type' => SORT_ASC, 'date_create' => SORT_ASC])->all();
      $comments = Comment::find()->where(['id_thread' => $thread->id])->orderBy(['date_create' => SORT_ASC]);
      $countQuery = clone $comments;
      $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 16]);
      $comments = $comments->offset($pages->offset)->limit($pages->limit)->all();

      return $this->render('thread', [
        'thread' => $thread,
        'comment' => $comment,
        'pages' => $pages,
        'comments' => $comments,
        'category' => $category,
        'allowComment' => $allowComment
      ]);
    }
    public function actionDelete($id)
    {
      if ( (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin) == false )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $thread = Thread::find()->where(['id'=>$id])->one();
      if( !$thread )
          throw new \yii\web\NotFoundHttpException("Тема не найдена");
      $thread->delete();
      Yii::$app->session->setFlash('success', "Тема удалена");
      return Yii::$app->response->redirect(Url::to(['site/thread']));
    }
    public function actionEdit($id)
    {
      $thread = Thread::findByID($id);
      if( !$thread ) {
        if( $id != 0 )
          throw new \yii\web\NotFoundHttpException("Тема не найдена");
      }

      if ( Yii::$app->user->isGuest )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      if( !$thread ) {
        $thread = new Thread;
        $thread->date_create = date('Y-m-d H:i:s');
        $thread->date_update = date('Y-m-d H:i:s');
        $thread->id_category = 0;
        $thread->enable = 1;
        $thread->id_owner = Yii::$app->user->id;
        $thread->role_view = Users::ROLE_MODERATOR;
        $thread->color = "";
        $thread->color_text = "";
      }
      else {
        if ( (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin) == false && (Yii::$app->user->id == $thread->id_owner && $id != 0) == false )
          throw new \yii\web\NotFoundHttpException("Доступ закрыт");
      }
      if ($thread->load(Yii::$app->request->post()) && $thread->saveModel()) {
        Yii::$app->session->setFlash('success', "Тема сохранена");
        return Yii::$app->response->redirect(Url::to(['thread/index', 'alias' => $thread->alias]));
      }
      return $this->render('new', [
        'model' => $thread
      ]);
    }
    public function actionCommentedit($id)
    {
      $comment = Comment::findByID($id);
      if( !$comment )
        throw new \yii\web\NotFoundHttpException("Комментарий не найден");

      if ( (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin) == false )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      if( isset($_POST['delete']) ) {
        Yii::$app->session->setFlash('success', "Комментарий удален");
        $url = Url::to(['thread/index', 'alias' => $comment->getThread()->alias]);
        $comment->delete();

        return Yii::$app->response->redirect($url);
      }

      if ($comment->load(Yii::$app->request->post()) && $comment->saveModel()) {
        Yii::$app->session->setFlash('success', "Комментарий сохранен");
        return Yii::$app->response->redirect(Url::to(['thread/index', 'alias' => $comment->getThread()->alias]));
      }
      return $this->render('edit_comment', [
        'comment' => $comment
      ]);
    }
    public function actionList()
    {
      if ( (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin) == false )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $threads = Thread::find()->orderBy(['role_view' => SORT_DESC, 'date_create' => SORT_DESC]);
      $countQuery = clone $threads;
      $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 16]);
      $threads = $threads->offset($pages->offset)->limit($pages->limit)->all();

      return $this->render('list', [
        'threads' => $threads,
        'pages' => $pages
      ]);
    }
}
