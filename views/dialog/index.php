<?php
  use yii\helpers\Url;
  use yii\bootstrap4\ActiveForm;
  use app\models\Users;
  use yii\helpers\Html;
  use yii\bootstrap4\LinkPager;

  $this->title =  'Диалоги';
  $this->params['breadcrumbs'][] = 'Диалоги';
?>

<div class="dialog-list">
  <div class="last-dialog">
    <?php foreach ($dialogs as $key => $dialog): ?>
      <div class="dialog <?= $dialog->getUnread() > 0 ? 'unread' : '' ?>">
        <a href="<?= Url::to(['dialog/view', 'id' => $dialog->id]) ?>">
          <div class="title">
            <i class="far fa-comments"></i>
            <p><?= $dialog->getTitle() ?></p>
          </div>
          <?php if ($dialog->getUnread() > 0): ?>
            <div class="stats">
              <p>Новых: <?= $dialog->getUnread() ?></p>
            </div>
          <?php endif; ?>
          <div class="comment">
            <p><?= Yii::$app->functions->formatDate($dialog->date_update) ?></p>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
  </div>
</div>
