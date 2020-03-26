<?php
namespace app\widgets;

use Yii;

class AdminMenu extends \yii\bootstrap4\Widget
{
    public function run()
    {
        return \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/views/parts/adminmenu.php');
    }
}
