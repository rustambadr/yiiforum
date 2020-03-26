<?php
  use yii\bootstrap4\ActiveForm;
  use vova07\imperavi\Widget;
  use kartik\datetime\DateTimePicker;
  use yii\helpers\ArrayHelper;
  use yii\helpers\Html;
  use yii\helpers\Url;
  use app\models\Users;

  $this->title = $thread->title;
  $this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => Url::to(['category/index', 'alias' => $category->alias])];
  $this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread">
  <?php if ((Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin) || (Yii::$app->user->isGuest == false && Yii::$app->user->id == $thread->id_owner)): ?>
    <div class="form-group">
      <a href="<?= Url::to(['thread/edit', 'id' => $thread->id]) ?>" class="btn btn-primary">Редиктировать тему</a>
      <?php if (Yii::$app->user->identity->isAdmin): ?>
        <a href="<?= Url::to(['thread/delete', 'id' => $thread->id]) ?>" class="btn btn-danger">Удалить тему</a>
      <?php endif; ?>
      <?php if ($thread->isModeration): ?>
        <p class="float-right badge badge-primary text-wrap" style="font-size: 1rem;">На модерации</p>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <div class="thread-content">
    <div class="left">
      <a href="<?= Url::to(['user/index', 'id' => $thread->owner->id]) ?>">
        <div class="image">
          <img src="<?= $thread->owner->getImage() ?>" alt="">
        </div>
      </a>
      <a href="<?= Url::to(['user/index', 'id' => $thread->owner->id]) ?>" class="name" style="color: <?= $thread->owner->getColor() ?>" alt="<?= $thread->owner->name ?>"><?= $thread->owner->name ?></a>
    </div>
    <div class="right">
      <div class="top">
        <p class="title"><b><?= $thread->title ?></b></p>
        <p class="date"><i><?= Yii::$app->functions->formatDate($thread->date_create) ?></i></p>
      </div>
      <div class="bottom p-field">
        <?= $thread->content ?>
      </div>
    </div>
  </div>

  <?php if (count($comments)): ?><hr><?php endif; ?>
  <?php foreach ($comments as $key => $_comment): ?>
    <div class="thread-comment <?= $_comment->getType() ?>">
      <div class="left">
        <a href="<?= Url::to(['user/index', 'id' => $_comment->owner->id]) ?>">
          <div class="image">
            <img src="<?= $_comment->owner->getImage() ?>" alt="">
          </div>
        </a>
        <a href="<?= Url::to(['user/index', 'id' => $_comment->owner->id]) ?>" class="name" style="color: <?= $_comment->owner->getColor() ?>" alt="<?= $_comment->owner->name ?>"><?= $_comment->owner->name ?></a>
      </div>
      <div class="right">
        <div class="top">
          <p class="date"><i><?= Yii::$app->functions->formatDate($_comment->date_create) ?></i></p>
          <?php if ($_comment->type == 2): ?>
            <p class="award"><i class="fas fa-award"></i></p>
          <?php endif; ?>
          <?php if (Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin): ?>
            <a href="<?= Url::to(['comment/edit/'.$_comment->id]) ?>" class="edit"><i class="fas fa-edit"></i></a>
          <?php endif; ?>
        </div>
        <div class="bottom p-field">
          <?= $_comment->comment ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <?php if (Yii::$app->user->isGuest == false && $thread->enable != 2): ?>
    <hr>
    <div class="add-comment">
      <div class="left">
        <div class="image">
          <img src="<?= Yii::$app->user->identity->getImage() ?>" alt="">
        </div>
        <p class="name" style="color: <?= Yii::$app->user->identity->getColor() ?>" alt="<?= Yii::$app->user->identity->name ?>"><?= Yii::$app->user->identity->name ?></p>
      </div>
      <div class="right">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?= $form->field($comment, 'comment')->widget(Widget::className(), [
            'settings' => [
              'lang' => 'ru',
              'minHeight' => 200,
              'imageUpload' => Url::to(['/thread/image-upload']),
              'plugins' => [
                  'fontcolor',
                  'fullscreen',
                  'imagemanager',
              ],
            ],
        ])->label(false) ?>
          <?php if (Yii::$app->user->identity->isAdmin): ?>
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
          <?php else: ?>
            <?php if ($allowComment): ?>
              <?= $form->field($comment, 'type')->dropDownList([
                  '3' => 'Обычное сообщение',
                  '0' => 'Положительный отзыв',
                  '1' => 'Отрицательный отзыв'
              ],[
                  'prompt' => 'Тип сообщения'
              ]) ?>
            <?php endif; ?>
          <?php endif; ?>

          <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
          </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  <?php endif; ?>
</div>
