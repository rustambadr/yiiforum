<?php
  use yii\bootstrap4\ActiveForm;
  use vova07\imperavi\Widget;
  use kartik\datetime\DateTimePicker;
  use yii\helpers\ArrayHelper;
  use app\models\Category;
  use yii\helpers\Html;
  use yii\helpers\Url;

  $this->title = 'Редактирование раздела';
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
              'clips',
              'fullscreen',
          ],
        ],
    ]) ?>

    <?php if ( strlen($model->image) ): ?>
      <img src="<?= Url::to('@web/') ?><?= $model->image ?>" class="img-thumbnail mb-2" style="width: 200px;">
    <?php endif; ?>
    <?= $form->field($model, 'file')->fileInput() ?>
    <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map(Category::find()->all(),'id','title'),[
        'prompt' => 'Выберите категорию'
    ]) ?>

    <?= $form->field($model, 'role_view')->dropDownList([
        '1' => 'Видно всем',
        '2' => 'Продавец',
        '3' => 'Випам',
        '4' => 'Модераторам',
        '5' => 'Админу',
    ],[
        'prompt' => 'Выберите статус...'
    ]) ?>
    <?= $form->field($model, 'alias')->textInput() ?>

    <div class="form-group">
      <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
  <?php ActiveForm::end(); ?>
</div>
