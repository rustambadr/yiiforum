<?php
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\UserMenu;
use app\widgets\AdminMenu;
use app\widgets\OnlineBlock;
use app\widgets\PageMenu;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<header style="background-image: url(https://placehold.it/1440x500);" onclick="window.location.href = '/';">
  <div class="container">
    <div class="logo d-none">
      <a href="<?= Url::to(['site/index']) ?>"><img src="https://placehold.it/400x100" alt=""></a>
    </div>
  </div>
</header>
<section id="app">
    <div class="container main-container">
      <div class="left-menu">
        <div class="menu">
          <?= UserMenu::widget() ?>
          <hr>
          <?= AdminMenu::widget() ?>
          <a href="<?= Url::to(['site/index']) ?>" class="btn btn-default"><i class="fas fa-home"></i>Главная</a>
          <a href="<?= Url::to(['site/category']) ?>" class="btn btn-default"><i class="fas fa-list"></i>Список разделов</a>
          <?= PageMenu::widget() ?>
          <hr>
          <?= OnlineBlock::widget() ?>
          <hr>
          <a href="#"><img src="https://placehold.it/200x300" alt=""></a>
        </div>
      </div>
      <div class="content">
        <?= Breadcrumbs::widget([
            'links' => (isset($this->params['breadcrumbs']) && count($this->params['breadcrumbs']) > 0) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
      </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <p>&copy; My Company <?= date('Y') ?></p>
        <p><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
