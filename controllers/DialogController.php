<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\Dialog;
use app\models\Message;
use app\models\Users;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\db\Query;

class DialogController extends Controller
{
  public function beforeAction($action)
  {
    if ( !Yii::$app->user->isGuest && Yii::$app->user->identity->role == Users::ROLE_BANNED )
      throw new \yii\web\NotFoundHttpException("Доступ закрыт");
    return parent::beforeAction($action);
  }
    public function actionIndex()
    {
      if( Yii::$app->user->isGuest )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $myID = Yii::$app->user->id;
      $dialogs = Dialog::find()->where("`user_ids`->'$.u$myID'")->orderBy(['date_update' => SORT_DESC]);

      $countQuery = clone $dialogs;
      $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 18]);
      $dialogs = $dialogs->offset($pages->offset)->limit($pages->limit)->all();

      return $this->render('index', [
        'dialogs' => $dialogs,
        'pages' => $pages
      ]);
    }
    public function actionStartdialog( $id )
    {
      if( Yii::$app->user->isGuest )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $user = Users::findIdentity( $id );
      if( !$user )
        throw new \yii\web\NotFoundHttpException("Пользователь не найден");

      $myID = Yii::$app->user->id;
      $dialog = Dialog::find()->where("`user_ids`->'$.u$myID'")->andWhere("`user_ids`->'$.u$id'")->andWhere(['enable' => 1])->one();
      if( !$dialog ) {
        $dialog = new Dialog;
        $dialog->user_ids = json_encode(["u$myID" => $myID, "u$id" => $id]);
        $dialog->date_update = date('Y-m-d H:i:s');
        $dialog->enable = 1;
        $dialog->save( false );
      }
      return Yii::$app->response->redirect(Url::to(['dialog/view', 'id' => $dialog->id]));
    }
    public function actionView( $id )
    {
      if( Yii::$app->user->isGuest )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $myID = Yii::$app->user->id;
      $dialog = Dialog::find()->where(['id' => $id]);
      if( Yii::$app->user->identity->role < Users::ROLE_MODERATOR )
        $dialog = $dialog->andWhere("`user_ids`->'$.u$myID'");
      $dialog = $dialog->one();

      if( !$dialog )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $messages = Message::find()->where(['id_dialog' => $dialog->id])->orderBy(['date_create' => SORT_DESC])->limit(11)->all();
      $messages = array_reverse($messages);

      return $this->render('view', [
        'dialog' => $dialog,
        'messages' => $messages
      ]);
    }
    public function actionNewmessage()
    {
      if( Yii::$app->user->isGuest )
        die('err');
      $myID = Yii::$app->user->id;
      $id_dialog = Yii::$app->request->post('id_dialog', false);
      $_message = Yii::$app->request->post('message', '');

      $dialog = Dialog::find()->where(['id' => $id_dialog]);
      if( Yii::$app->user->identity->role < Users::ROLE_MODERATOR )
        $dialog = $dialog->andWhere("`user_ids`->'$.u$myID'");
      $dialog = $dialog->one();

      if( !$dialog || strlen($_message) <= 0 )
        die('err');

      $message = new Message;
      $message->id_dialog = $id_dialog;
      $message->id_user = $myID;
      $message->message = $_message;
      $message->date_create = date('Y-m-d H:i:s');
      $message->save( false );
      $dialog->getNewmessage( $message->id, $myID );

      echo 'ok';
    }
    public function actionViewmessage() {
      if( Yii::$app->user->isGuest )
        die('err');

      $msIds = Yii::$app->request->post('msIds', false);
      if( !$msIds )
        die('err');
      $msIds = json_decode($msIds, true);

      $newArr = [];
      $unread = explode(',', Yii::$app->user->identity->unread_message);
      foreach ($unread as $key => $_unread) {
        if( array_search($_unread, $msIds) === false )
          $newArr[] = $_unread;
      }
      Yii::$app->user->identity->unread_message = implode(',', array_filter($newArr));
      Yii::$app->user->identity->save( false );

      echo (count($unread) - count($newArr));
    }
    public function actionUnreadmessage() {
      if( Yii::$app->user->isGuest )
        die('err');

      return Yii::$app->user->identity->getUnread();
    }
    public function actionUpdatemessage() {
      if( Yii::$app->user->isGuest )
        die('err');

      $lastID = Yii::$app->request->post('lastID', false);
      $id_dialog = Yii::$app->request->post('id_dialog', false);
      if( $lastID === false || $id_dialog === false )
        die('err');

      $response = "";
      $messages = Message::find()->where(['>', 'id', $lastID])->andWhere(['id_dialog' => $id_dialog])->orderBy(['date_create' => SORT_ASC])->all();
      foreach ($messages as $key => $message) {
        $response .= \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/views/parts/message.php', [
          'message' => $message
        ]);
      }

      return $response;
    }
    public function actionLoadmessage() {
      if( Yii::$app->user->isGuest )
        die('err');

      $firstID = Yii::$app->request->post('firstID', false);
      $id_dialog = Yii::$app->request->post('id_dialog', false);
      if( $firstID === false || $id_dialog === false )
        die('err');

      $response = "";
      $messages = Message::find()->where(['<', 'id', $firstID])->andWhere(['id_dialog' => $id_dialog])->orderBy(['date_create' => SORT_DESC])->limit(10)->all();
      $messages = array_reverse($messages);

      foreach ($messages as $key => $message) {
        $response .= \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/views/parts/message.php', [
          'message' => $message
        ]);
      }

      return $response;
    }
    public function actionInviteadmin( $id ) {
      if( Yii::$app->user->isGuest )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $myID = Yii::$app->user->id;
      $dialog = Dialog::find()->where(['id' => $id])->andWhere("`user_ids`->'$.u$myID'")->one();
      if(!$dialog)
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      if($dialog->getHasadmin())
        throw new \yii\web\NotFoundHttpException("Администратор уже приглашен в чат");

      $admins = Users::find()->where(['role' => Users::ROLE_GARANT])->all();
      $user_ids = json_decode($dialog->user_ids, true);
      foreach ($admins as $key => $admin) {
        $id = $admin->id;
        $user_ids["u$id"] = $id;
      }
      $dialog->user_ids = json_encode($user_ids);
      $dialog->save( false );

      $message = new Message;
      $message->id_dialog = $dialog->id;
      $message->id_user = $myID;
      $message->message = "Пользователь пригласил администратора в данный чат!";
      $message->date_create = date('Y-m-d H:i:s');
      $message->save( false );
      $dialog->getNewmessage( $message->id, $myID );

      return Yii::$app->response->redirect(Url::to(['dialog/view', 'id' => $dialog->id]));
    }
    public function actionLeaveadmin( $id ) {
      if( Yii::$app->user->isGuest )
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $myID = Yii::$app->user->id;
      $dialog = Dialog::find()->where(['id' => $id])->andWhere("`user_ids`->'$.u$myID'")->one();
      if(!$dialog)
        throw new \yii\web\NotFoundHttpException("Доступ закрыт");

      $user_ids = json_decode($dialog->user_ids, true);
      foreach ($user_ids as $key => $admin) {
        if( $admin == $myID ) {
          unset($user_ids[$key]);
          break;
        }
      }
      $dialog->user_ids = json_encode($user_ids);
      $dialog->save( false );

      $message = new Message;
      $message->id_dialog = $dialog->id;
      $message->id_user = $myID;
      $message->message = "Администратор покинул данный чат!";
      $message->date_create = date('Y-m-d H:i:s');
      $message->save( false );
      $dialog->getNewmessage( $message->id, $myID );

      return Yii::$app->response->redirect(Url::to(['dialog/index']));
    }
}
