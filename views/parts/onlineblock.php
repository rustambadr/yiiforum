<?php
  $user_online = random_int($params['user_online_min'], $params['user_online_max']);
  $guest_online = random_int($params['guest_online_min'], $params['guest_online_max']);
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
