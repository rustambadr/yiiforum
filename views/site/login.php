<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'autocomplete' => 'off']) ?>
        <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'off']) ?>
        <div class="form-group">
          <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
