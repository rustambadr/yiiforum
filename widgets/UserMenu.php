<?php
namespace app\widgets;

use Yii;

class UserMenu extends \yii\bootstrap4\Widget
{
    public function run()
    {
        return \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/views/parts/usermenu.php');
    }
}
