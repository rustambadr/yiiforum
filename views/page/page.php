<?php
  use yii\bootstrap4\ActiveForm;
  use vova07\imperavi\Widget;
  use kartik\datetime\DateTimePicker;
  use yii\helpers\ArrayHelper;
  use yii\helpers\Html;
  use yii\helpers\Url;
  use app\models\Users;

  $this->title = $page->title;
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="page">
  <?php if (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin): ?>
    <div class="form-group">
      <a href="<?= Url::to(['page/edit', 'id' => $page->id]) ?>" class="btn btn-primary">Редактировать страницу</a>
      <a href="<?= Url::to(['page/delete', 'id' => $page->id]) ?>" class="btn btn-danger">Удалить страницу</a>
    </div>
  <?php endif; ?>
  <div class="page-content">
    <h1><?= $page->title ?></h1>
    <?= $page->content ?>
  </div>
</div>
