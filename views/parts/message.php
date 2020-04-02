<?php
  use yii\helpers\Url;
?>

<div class="ms-block" data-id="<?= $message->id ?>">
  <?php if ($message->id_user != Yii::$app->user->id): ?>
    <div class="incoming_msg">
      <div class="incoming_msg_img" style="background-image: url('<?= $message->getOwner()->getImage() ?>');"></div>
      <div class="received_msg">
        <div class="received_withd_msg">
  <?php else: ?>
    <div class="outgoing_msg">
      <div class="sent_msg">
  <?php endif; ?>
        <a href="<?= Url::to(['user/index', 'id' => $message->id_user]) ?>" class="name" style="color: <?= $message->getOwner()->getColor() ?>"><?= $message->getOwner()->name ?></a>
        <div class="text p-field">
          <?= $message->message ?>
        </div>
        <span class="time_date"><?= Yii::$app->functions->formatDate($message->date_create) ?></span>
  <?php if ($message->id_user != Yii::$app->user->id): ?>
        </div>
      </div>
    </div>
  <?php else: ?>
      </div>
    </div>
  <?php endif; ?>
</div>
