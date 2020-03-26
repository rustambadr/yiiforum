<?php
  use yii\bootstrap4\ActiveForm;
  use vova07\imperavi\Widget;
  use kartik\datetime\DateTimePicker;
  use yii\helpers\ArrayHelper;
  use app\models\Category;
  use app\models\Users;
  use yii\helpers\Html;
  use yii\helpers\Url;

  $this->title = 'Редактирование страницы';
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-model">
  <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->errorSummary($model); ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'date_create')->widget(DateTimePicker::classname(), [
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd H:i'
        ]
    ]) ?>
    <?= $form->field($model, 'content')->widget(Widget::className(), [
        'settings' => [
          'lang' => 'ru',
          'minHeight' => 200,
          'plugins' => [
              'fontcolor',
              'fullscreen',
          ],
        ],
    ]) ?>
    <?= $form->field($model, 'icon')->textInput(['placeholder' => 'arrows-alt'])->hint('https://fontawesome.com/icons?d=gallery&q=arrows-alt') ?>
    <?= $form->field($model, 'alias')->textInput() ?>

    <div class="form-group">
      <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
  <?php ActiveForm::end(); ?>
</div>
