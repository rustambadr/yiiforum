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
  use kartik\color\ColorInput;

  $this->title = 'Редактирование темы';
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-model">
  <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->errorSummary($model); ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?php if ( Yii::$app->user->identity->isAdmin ): ?>
      <?= $form->field($model, 'date_create')->widget(DateTimePicker::classname(), [
          'pluginOptions' => [
              'autoclose'=>true,
              'format' => 'yyyy-mm-dd H:i'
          ]
      ]) ?>
      <?= $form->field($model, 'date_update')->widget(DateTimePicker::classname(), [
          'pluginOptions' => [
              'autoclose'=>true,
              'format' => 'yyyy-mm-dd H:i'
          ]
      ]) ?>
    <?php endif; ?>
    <?= $form->field($model, 'content')->widget(Widget::className(), [
        'settings' => [
          'lang' => 'ru',
          'minHeight' => 200,
          'imageUpload' => Url::to(['/thread/image-upload']),
          'plugins' => array_merge(Yii::$app->functions->editorPlugins(), [
            'imagemanager',
            ])
        ],
    ]) ?>
    <?php if (Yii::$app->user->identity->isAdmin): ?>
      <?= $form->field($model, 'color')->widget(ColorInput::classname(), [
          'value' => 'rgba(255, 255, 255, 255)',
          'options' => ['placeholder' => 'Select color ...'],
          'pluginOptions' => [
              'showInput' => false,
              'preferredFormat' => 'rgba'
          ]
      ]) ?>
      <?= $form->field($model, 'color_text')->widget(ColorInput::classname(), [
          'value' => 'rgba(0, 0, 0, 255)',
          'options' => ['placeholder' => 'Select color ...'],
          'pluginOptions' => [
              'showInput' => false,
              'preferredFormat' => 'rgba'
          ]
      ]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'id_category')->dropDownList(ArrayHelper::map(Category::find()->all(),'id','title'),[
        'prompt' => 'Выберите категорию'
    ]) ?>
    <?php if (Yii::$app->user->identity->isAdmin): ?>
      <?= $form->field($model, 'id_owner')->dropDownList(ArrayHelper::map(Users::find()->all(),'id','name'),[
          'prompt' => 'Выберите автора публикации'
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
      <?php
        $allow_comment_ids = Users::find()->where(['id'=>explode(',', $model->allow_comment_ids)])->all();
        $data = ArrayHelper::map($allow_comment_ids,'id','name');
        $model->allow_comment_ids = ArrayHelper::map($allow_comment_ids,'id','id');
      ?>
      <?= $form->field($model, 'allow_comment_ids')->widget(Select2::classname(), [
          'data' => $data,
          'options' => ['multiple'=>true, 'placeholder' => 'Введите имя профиля'],
          'pluginOptions' => [
              'allowClear' => false,
              'minimumInputLength' => 2,
              'language' => [
                  'errorLoading' => new JsExpression("function () { return 'Загрузка данных...'; }"),
              ],
              'ajax' => [
                  'url' => Url::to(['user/user']),
                  'dataType' => 'json',
                  'data' => new JsExpression('function(params) { return {q:params.term}; }')
              ],
          ],
      ])->hint('Пользователи, которым разрешено оставить комментарий'); ?>
      <?= $form->field($model, 'type')->dropDownList([
        '0' => 'Видно всем',
        '1' => 'Приватная',
      ],[
          'prompt' => 'Выберите тип...'
      ]) ?>
      <?php
        $allow_view_ids = Users::find()->where(['id'=>explode(',', $model->allow_view_ids)])->all();
        $data = ArrayHelper::map($allow_view_ids,'id','name');
        $model->allow_view_ids = ArrayHelper::map($allow_view_ids,'id','id');
      ?>
      <?= $form->field($model, 'allow_view_ids')->widget(Select2::classname(), [
          'data' => $data,
          'options' => ['multiple'=>true, 'placeholder' => 'Введите имя профиля'],
          'pluginOptions' => [
              'allowClear' => false,
              'minimumInputLength' => 2,
              'language' => [
                  'errorLoading' => new JsExpression("function () { return 'Загрузка данных...'; }"),
              ],
              'ajax' => [
                  'url' => Url::to(['user/user']),
                  'dataType' => 'json',
                  'data' => new JsExpression('function(params) { return {q:params.term}; }')
              ],
          ],
      ])->hint('Пользователи, которым разрешено видеть тему'); ?>
      <?= $form->field($model, 'enable')->dropDownList([
        '1' => 'Активна',
        '2' => 'Закрыта',
      ],[
          'prompt' => 'Выберите статус...'
      ]) ?>
      <?= $form->field($model, 'force')->dropDownList([
        '0' => 'Открепить',
        '1' => 'Закрепить',
      ],[
          'prompt' => 'Выберите статус...'
      ]) ?>
    <?php endif; ?>

    <div class="form-group">
      <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
  <?php ActiveForm::end(); ?>
</div>
