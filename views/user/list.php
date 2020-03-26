<?php
  use yii\helpers\Url;
  use yii\bootstrap4\LinkPager;

  $this->title = "Список пользователей";
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-list">
  <div class="last-thread">
    <?php foreach ($users as $key => $user): ?>
      <div class="thread">
        <a href="<?= Url::to(['user/index', 'id' => $user->id]) ?>">
          <div class="title">
            <i class="far fa-user"></i>
            <p><?= $user->name ?></p>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
  </div>
</div>
