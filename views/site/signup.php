<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'autocomplete' => 'off']) ?>
        <?= $form->field($model, 'name')->textInput(['autocomplete' => 'off']) ?>
        <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'off']) ?>
        <!-- <?= $form->field($model, 'file')->fileInput() ?> -->
        <?= $form->field($model, 'captcha')->widget(Captcha::className()) ?>
        <div class="form-group">
          <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
