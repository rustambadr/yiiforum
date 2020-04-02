<?php
  use yii\bootstrap4\ActiveForm;
  use vova07\imperavi\Widget;
  use kartik\datetime\DateTimePicker;
  use yii\helpers\ArrayHelper;
  use app\models\Category;
  use app\models\Users;
  use yii\helpers\Html;
  use yii\helpers\Url;
  use kartik\select2\Select2;
  use yii\web\JsExpression;

  $this->title = 'Редактирование комментария';
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-model">
  <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->field($comment, 'comment')->widget(Widget::className(), [
        'settings' => [
          'lang' => 'ru',
          'minHeight' => 200,
          'imageUpload' => Url::to(['/thread/image-upload']),
          'plugins' => array_merge(Yii::$app->functions->editorPlugins(), [
            'imagemanager',
            ])
        ],
    ])->label(false) ?>
    <?= $form->field($comment, 'date_create')->widget(DateTimePicker::classname(), [
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd H:i'
        ]
    ]) ?>
    <?= $form->field($comment, 'id_owner')->dropDownList(ArrayHelper::map(Users::find()->all(),'id','name'),[
        'prompt' => 'Выберите автора комменатрия'
    ]) ?>
    <?= $form->field($comment, 'type')->dropDownList([
        '3' => 'Обычное сообщение',
        '0' => 'Положительный отзыв',
        '1' => 'Отрицательный отзыв',
        '2' => 'Рецензия',
    ],[
        'prompt' => 'Тип сообщения'
    ]) ?>

    <div class="form-group">
      <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
      <?= Html::submitButton('Удалить', ['class' => 'btn btn-danger', 'name' => 'delete']) ?>
    </div>
  <?php ActiveForm::end(); ?>
</div>
