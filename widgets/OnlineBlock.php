<?php
namespace app\widgets;

use Yii;

class OnlineBlock extends \yii\bootstrap4\Widget
{
    public function run()
    {
      return \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/views/parts/onlineblock.php', [
        'params' => Yii::$app->params
      ]);
    }
}
