<?php
  use yii\helpers\Url;
  use yii\bootstrap4\ActiveForm;
  use app\models\Users;
  use yii\helpers\Html;
  use vova07\imperavi\Widget;

  $this->title =  'Профиль - ' . $user->name;
  $this->params['breadcrumbs'][] = 'Профиль';
  $this->params['breadcrumbs'][] = $user->name;
?>
<div class="user-profile">
  <?php if ($allowEdit): ?>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
      <?= $form->errorSummary($user); ?>
      <?= $form->field($user, 'name')->textInput() ?>

      <?= $form->field($user, 'about')->widget(Widget::className(), [
          'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'plugins' => [
                'fontcolor',
                'fullscreen',
            ],
          ],
      ]) ?>

      <img src="<?= $user->getImage() ?>" class="img-thumbnail mb-2" style="width: 200px;">
      <?= $form->field($user, 'file')->fileInput() ?>

      <?php if (Yii::$app->user->identity->isAdmin): ?>
        <?= $form->field($user, 'role')->dropDownList([
            Users::ROLE_MEMBER => 'Пользователь',
            Users::ROLE_SELLER => 'Продавец',
            Users::ROLE_VIP => 'Vip',
            Users::ROLE_MODERATOR => 'Модератор',
            Users::ROLE_ADMIN => 'Админ'
        ],[
            'prompt' => 'Выберите роль'
        ]) ?>
      <?php endif; ?>

      <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
      </div>
    <?php ActiveForm::end(); ?>
    <hr>
  <?php endif; ?>
  <div class="info">
    <div class="name">
      <label>Имя: </label><p style="color: <?= $user->getColor() ?>"><?= $user->name ?></p>
      <label>Обо мне: </label><?= $user->about ?>
    </div>
    <div class="image">
      <img src="<?= $user->getImage() ?>" class="img-thumbnail mb-2" style="width: 200px;">
    </div>
    <div class="form-group">
      <a href="<?= Url::to(['dialog/startdialog', 'id' => $user->id]) ?>" class="btn btn-primary">Начать диалог</a>
    </div>
  </div>
</div>
