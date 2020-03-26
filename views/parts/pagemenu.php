<?php
  use yii\helpers\Url;
?>

<?php foreach ($pages as $key => $page): ?>
  <a href="<?= Url::to(['page/index', 'alias' => $page->alias]) ?>" class="btn btn-default"><?= ($page->icon && strlen($page->icon) > 0) ? '<i class="fas fa-'.$page->icon.'"></i>' : '' ?><?= $page->title ?></a>
<?php endforeach; ?>
