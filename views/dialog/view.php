<?php
  use yii\helpers\Url;
  use yii\bootstrap4\ActiveForm;
  use app\models\Users;
  use yii\helpers\Html;
  use vova07\imperavi\Widget;

  $this->title = $dialog->getTitle();
  $this->params['breadcrumbs'][] = 'Диалог - ' . $this->title;
?>

<div class="dialog-box">
  <div class="messaging">
    <div class="inbox_msg">
      <div class="head">
        <div class="name">
          <p><?= $dialog->getTitle() ?></p>
        </div>
        <div class="controls">
          <?php if (!Yii::$app->user->identity->isAdmin && $dialog->getHasadmin() == false): ?>
            <a href="<?= Url::to(['dialog/inviteadmin', 'id' => $dialog->id]) ?>" class="btn btn-success">Пригласить администратора</a>
          <?php endif; ?>
          <?php if (Yii::$app->user->identity->isAdmin && $dialog->getUserCount() > 2): ?>
            <a href="<?= Url::to(['dialog/leaveadmin', 'id' => $dialog->id]) ?>" class="btn btn-danger">Выйти из чата</a>
          <?php endif; ?>
        </div>
      </div>
    	<div class="mesgs">
    	  <div class="msg_history">
          <div class="load-more" style="<?= count($messages) < 11 ? 'display: none;' : '' ?>">
            <a href="#" class="btn btn-primary">Загрузить еще</a>
          </div>
          <?php foreach ($messages as $key => $message): ?>
            <?php if ($key == 0 && count($messages) > 10): continue; endif; ?>
            <?= \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/views/parts/message.php', [
              'message' => $message
            ]); ?>
          <?php endforeach; ?>
    	  </div>
    	  <div class="type_msg">
    		<div class="input_msg_write">
          <?php ActiveForm::begin([
            'id' => 'dialog',
            'options' => ['enctype' => 'multipart/form-data']
          ]); ?>
            <input type="hidden" name="id_dialog" value="<?= $dialog->id ?>">

            <?php echo \vova07\imperavi\Widget::widget([
              'name' => 'message',
              'settings' => [
                  'lang' => 'ru',
                  'minHeight' => 200,
                  'imageUpload' => Url::to(['/thread/image-upload']),
                  'plugins' => array_merge(Yii::$app->functions->editorPlugins(), [
                    'imagemanager',
                    ])
              ],
          ]); ?>

            <button class="btn btn-primary" type="submit">Отправить <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
          <?php ActiveForm::end(); ?>
    		</div>
    	  </div>
    	</div>
    </div>
  </div>
</div>
