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
<header>
    <div class="size1">
      <a href="<?= Url::to(['site/index']) ?>"><img class="lazy" data-original="https://placehold.it/1900x250" alt="" style="min-height: 200px;"></a>
    </div>
    <div class="size2">
      <a href="<?= Url::to(['site/index']) ?>"><img class="lazy" data-original="https://placehold.it/1440x250" alt="" style="min-height: 200px;"></a>
    </div>
    <div class="size3">
      <a href="<?= Url::to(['site/index']) ?>"><img class="lazy" data-original="https://placehold.it/768x250" alt="" style="min-height: 200px;"></a>
    </div>
    <div class="size4">
      <a href="<?= Url::to(['site/index']) ?>"><img class="lazy" data-original="https://placehold.it/425x250" alt="" style="min-height: 200px;"></a>
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
          <a href="#" class="d-table"><img src="https://placehold.it/200x300" alt=""></a>
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
    <div class="container p-4">
      <div class="row">
        <div class="col">
          <p>&copy; Forum <?= date('Y') ?></p>
        </div>
        <div class="col">
          <a href="#">Политика конфиденциальности</a>
        </div>
        <div class="col">
          <a href="#">Помощь</a>
        </div>
      </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
