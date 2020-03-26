<?php
namespace app\widgets;

use Yii;
use app\models\Page;

class PageMenu extends \yii\bootstrap4\Widget
{
    public function run()
    {
      $pages = Page::find()->orderBy(['date_create' => SORT_DESC])->all();
      return \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/views/parts/pagemenu.php', [
        'pages' => $pages
      ]);
    }
}
