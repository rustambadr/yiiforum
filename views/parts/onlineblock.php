<?php
  $user_online = Yii::$app->session->get('user_online', false);
  $guest_online = Yii::$app->session->get('guest_online', false);
  $online_last = Yii::$app->session->get('online_last', false);

  if( $user_online == false || $guest_online == false || $online_last == false || (time() - $online_last > 3600) ) {
    $user_online = random_int($params['user_online_min'], $params['user_online_max']);
    $guest_online = random_int($params['guest_online_min'], $params['guest_online_max']);

    Yii::$app->session->set('user_online', $user_online);
    Yii::$app->session->set('guest_online', $guest_online);
    Yii::$app->session->set('online_last', time());
  }

  $user_online += random_int(-2, 2);
  if( $user_online <= 0 ) $user_online = 0;
  $guest_online += random_int(-2, 2);
  if( $guest_online <= 0 ) $guest_online = 0;
?>

<div class="onlineblock">
  <p class="title">Онлайн статистика</p>
  <div class="stats">
    <p class="text">Пользователей онлайн:</p>
    <p class="num"><?= $user_online ?></p>
  </div>
  <div class="stats">
    <p class="text">Гостей онлайн:</p>
    <p class="num"><?= $guest_online ?></p>
  </div>
  <div class="stats">
    <p class="text">Всего онлайн:</p>
    <p class="num"><?= ($guest_online + $user_online) ?></p>
  </div>
</div>
