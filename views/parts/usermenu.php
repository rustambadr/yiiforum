<?php
  use yii\helpers\Url;
?>

<?php if (Yii::$app->user->isGuest): ?>
  <a href="<?= Url::to(['site/login']) ?>" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i>Авторизация</a>
  <a href="<?= Url::to(['site/signup']) ?>" class="btn btn-success"><i class="fas fa-user-plus"></i>Регистрация</a>
<?php else: ?>
  <a href="<?= Url::to(['user/index', 'id' => Yii::$app->user->id]) ?>" class="btn btn-primary profile"><i class="fas fa-address-card"></i><div><span>Профиль</span><span><?= Yii::$app->user->identity->name ?></span></div></a>
  <a href="<?= Url::to(['dialog/index']) ?>" class="btn btn-default message"><i class="fas fa-sms"></i><div><span>Личные сообщения</span><span><?= Yii::$app->user->identity->getUnread() > 0 ? '+'.Yii::$app->user->identity->getUnread() : '' ?></span></div></a>
  <a href="<?= Url::to(['site/private']) ?>" class="btn btn-default"><i class="fas fa-lock-open"></i>Пользовательские темы</a>
  <a href="<?= Url::to(['site/logout']) ?>" class="btn btn-default"><i class="fas fa-sign-out-alt"></i>Выход</a>
<?php endif; ?>
