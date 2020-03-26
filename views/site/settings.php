<?php
  use yii\helpers\Url;
  use yii\bootstrap4\ActiveForm;

  $this->title = 'Настройки';
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings">
  <?php ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
  ]); ?>
    <h3>Настройки онлайна</h3>
    <div class="form-group">
      <label for="user_online_min">Минимально посетителей онлайн</label>
      <input type="text" class="form-control" id="user_online_min" name="user_online_min" value="<?= $params['user_online_min'] ?>">
    </div>
    <div class="form-group">
      <label for="user_online_max">Максимально посетителей онлайн</label>
      <input type="text" class="form-control" id="user_online_max" name="user_online_max" value="<?= $params['user_online_max'] ?>">
    </div>
    <div class="form-group">
      <label for="guest_online_min">Минимально гостей онлайн</label>
      <input type="text" class="form-control" id="guest_online_min" name="guest_online_min" value="<?= $params['guest_online_min'] ?>">
    </div>
    <div class="form-group">
      <label for="guest_online_max">Максимально гостей онлайн</label>
      <input type="text" class="form-control" id="guest_online_max" name="guest_online_max" value="<?= $params['guest_online_max'] ?>">
    </div>
    <button type="submit" class="btn btn-primary">Сохранить</button>
  <?php ActiveForm::end(); ?>
</div>
