<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Url;

class Functions extends Component {
    public function formatDate( $date_str ) {
      $time = time();
      $date_str = strtotime($date_str);
      $tm = date('H:i', $date_str);
      $d = date('d', $date_str);
      $m = date('m', $date_str);
      $y = date('Y', $date_str);
      $last = round(($time - $date_str)/60);
      if( $last < 55 ) {
        if( $last == 0 ) return "Только что";
        else return "$last минут назад";
      }
      elseif($d.$m.$y == date('dmY',$time)) return "Сегодня в $tm";
      elseif($d.$m.$y == date('dmY', strtotime('-1 day'))) return "Вчера в $tm";
      elseif($y == date('Y',$time)) return "$tm $d/$m";
      else return "$tm $d/$m/$y";
    }

    public function sendEmail( $arr = [] ) {
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

      $message = "<h1>Новая заявка</h1>";
      foreach ($arr as $key => $value) {
        $message .= "<p>$key - $value</p>";
      }
      mail(Yii::$app->params['adminEmail'], "Заявка на сайте", $message, $headers);
    }

    public function writeToConfig( $array = [] ) {
      $config = require $_SERVER['DOCUMENT_ROOT'] . '/config/params.php';
      foreach ($array as $key => $value) {
        $config[$key] = $value;
        Yii::$app->params[$key] = $value;
      }

      file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/config/params.php', '<?php return ' . var_export($config, true) . ';');
    }
    public function editorPlugins() {
      // if( Yii::$app->user->isGuest == false && Yii::$app->user->identity->isAdmin )
        return [
          'fontcolor',
          'fullscreen',
        ];
      // return [];
    }
}
