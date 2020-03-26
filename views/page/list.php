<?php
  use yii\helpers\Url;
  use yii\bootstrap4\LinkPager;

  $this->title = "Список страниц";
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-list">
  <div class="last-thread">
    <?php foreach ($_pages as $key => $page): ?>
      <div class="thread">
        <a href="<?= Url::to(['page/index', 'alias' => $page->alias]) ?>">
          <div class="title">
            <i class="far fa-file-word"></i>
            <p><?= $page->title ?></p>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
  </div>
</div>
