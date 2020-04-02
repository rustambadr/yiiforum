<?php
namespace app\widgets;

use Yii;
use app\models\Thread;
use app\models\Users;

class LastThread extends \yii\bootstrap4\Widget
{
    public $countThread = 8;
    public function run()
    {
      $role = Users::ROLE_MEMBER;
      if( Yii::$app->user->isGuest == false )
        $role = Yii::$app->user->identity->role;

      $threads = Thread::find()->joinWith('category_t')->where(['<=', '`thread`.role_view', $role])->andWhere(['type' => 0])->andWhere(['<=', '`category`.role_view', $role])->andWhere(['>', '`thread`.comment_count', 0])->orderBy([`thread`.'force' => SORT_DESC, '`thread`.date_update' => SORT_DESC])->limit($this->countThread)->all();
      return \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/views/parts/lastthread.php', [
        'threads' => $threads
      ]);
    }
}
