<?php
  use yii\helpers\Url;
  use app\models\Thread;
?>

<?php if (Yii::$app->user->isGuest == false): ?>
  <?php if (Yii::$app->user->identity->isAdmin): ?>
    <a href="<?= Url::to(['category/edit']) ?>" class="btn btn-success"><i class="fas fa-folder-plus"></i>Добавить раздел</a>

    <a href="<?= Url::to(['thread/edit']) ?>" class="btn btn-info"><i class="fas fa-comments"></i>Добавить тему</a>
    <a href="<?= Url::to(['thread/list']) ?>" class="btn btn-info message"><i class="fas fa-stream"></i><div><span>Список тем</span><?php if (($threadCount = Thread::getModerationCount()) > 0): ?><span>+<?= $threadCount ?></span><?php endif; ?></div></a>

    <a href="<?= Url::to(['user/list']) ?>" class="btn btn-primary"><i class="fas fa-users"></i>Список пользователей</a>

    <a href="<?= Url::to(['page/edit']) ?>" class="btn btn-warning"><i class="fas fa-file"></i>Добавить страницу</a>
    <a href="<?= Url::to(['page/list']) ?>" class="btn btn-warning"><i class="fas fa-file"></i>Список страниц</a>
    <a href="<?= Url::to(['site/setting']) ?>" class="btn btn-secondary"><i class="fas fa-cogs"></i>Настройки</a>
    <hr>
  <?php else: ?>
    <a href="<?= Url::to(['thread/edit']) ?>" class="btn btn-info"><i class="fas fa-comments"></i>Добавить тему</a>
  <?php endif; ?>
<?php endif; ?>
