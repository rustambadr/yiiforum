<?php
  use yii\helpers\Url;
  use yii\bootstrap4\LinkPager;

  $this->title = 'Категории';
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-list">
  <div class="last-thread">
    <?php foreach ($categorys as $key => $category): ?>
      <div class="thread">
        <a href="<?= Url::to(['category/index', 'alias' => $category->alias]) ?>">
          <div class="title">
            <i class="far fa-comment"></i>
            <p><?= $category->title ?></p>
          </div>
          <?php if ($category->getThreadCount() > 0): ?>
            <div class="stats">
              <p>Темы: <?= $category->getThreadCount() ?></p>
            </div>
            <div class="comment">
              <p>Тема: <?= $category->getLastThread()->title ?></p>
              <p><?= Yii::$app->functions->formatDate($category->getLastThread()->date_create) ?></p>
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
