<?php
  use yii\helpers\Url;
  use yii\bootstrap4\LinkPager;

  $this->title = "Главные категории";
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="category">
  <div class="last-thread">
    <?php foreach ($threads as $key => $thread): ?>
      <div class="thread" style="<?= ($thread->color && strlen($thread->color) > 0) ? "background-color: $thread->color;" : '' ?><?= ($thread->color && strlen($thread->color) > 0) ? "color: $thread->color_text;" : '' ?>">
        <a href="<?= Url::to(['thread/index', 'alias' => $thread->alias]) ?>">
          <div class="title">
            <i class="far fa-comments"></i>
            <p><?= $thread->title ?></p>
          </div>
          <?php if ($thread->comment_count > 0): ?>
            <div class="stats">
              <p>Сообщений: <?= $thread->comment_count ?></p>
            </div>
            <div class="comment">
              <p>Последнее от: <?= $thread->getLastComment()->owner->name ?></p>
              <p><?= Yii::$app->functions->formatDate($thread->getLastComment()->date_create) ?></p>
            </div>
          <?php endif; ?>
        </a>
      </div>
    <?php endforeach; ?>
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
  </div>
</div>
