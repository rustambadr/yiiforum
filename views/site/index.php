<?php
  use app\models\Category;
  use yii\helpers\Url;
  use app\widgets\LastThread;

  $this->title = 'Главная';
?>
<div class="main">
  <div class="top-text">
    <p>Eu enim fugiat in senserit, nulla cupidatat transferrem, ubi in illum quorum
      duis, ex ex magna proident. Fugiat deserunt officia ea illum instituendarum
      proident tempor commodo, ex quo quae litteris, de pariatur a litteris. Ita aut
      summis enim irure.Malis admodum an concursionibus, excepteur de iudicem.
      Ingeniis in noster. Iis veniam quae elit singulis eu ubi fugiat anim nam
      mentitum.</p>
  </div>
  <div class="top-category">
    <div class="row">
      <?php if (($category = Category::findByAlias('belye_uslugi')) != NULL): ?>
        <div class="col-lg-4">
          <a href="<?= Url::to(['category/index', 'alias' => $category->alias]) ?>">
            <div class="category">
              <div class="image" style="background-image: url('<?= Url::to('@web/') ?><?= $category->image ?>');"></div>
              <!-- <div class="info">
                <p><?= $category->title ?></p>
              </div> -->
            </div>
          </a>
        </div>
      <?php endif; ?>
      <?php if (($category = Category::findByAlias('serye_uslugi')) != NULL): ?>
        <div class="col-lg-4">
          <a href="<?= Url::to(['category/index', 'alias' => $category->alias]) ?>">
            <div class="category">
              <div class="image" style="background-image: url('<?= Url::to('@web/') ?><?= $category->image ?>');"></div>
              <!-- <div class="info">
                <p><?= $category->title ?></p>
              </div> -->
            </div>
          </a>
        </div>
      <?php endif; ?>
      <?php if (($category = Category::findByAlias('cernye_uslugi')) != NULL): ?>
        <div class="col-lg-4">
          <a href="<?= Url::to(['category/index', 'alias' => $category->alias]) ?>">
            <div class="category">
              <div class="image" style="background-image: url('<?= Url::to('@web/') ?><?= $category->image ?>');"></div>
              <!-- <div class="info">
                <p><?= $category->title ?></p>
              </div> -->
            </div>
          </a>
        </div>
      <?php endif; ?>
    </div>
    <a href="<?= Url::to(['category/main']) ?>" class="more">Смотреть все</a>
  </div>
  <?= LastThread::widget() ?>
  <div class="bot-category mt-4">
    <div class="row">
      <?php if (($category = Category::findByAlias('belye_uslugi')) != NULL): ?>
        <div class="col-lg-4">
          <a href="<?= Url::to(['category/index', 'alias' => $category->alias]) ?>">
            <div class="category">
              <!-- <div class="info">
                <p><?= $category->title ?></p>
              </div> -->
              <div class="image" style="background-image: url('<?= Url::to('@web/') ?><?= $category->image ?>');"></div>
            </div>
          </a>
        </div>
      <?php endif; ?>
      <?php if (($category = Category::findByAlias('serye_uslugi')) != NULL): ?>
        <div class="col-lg-4">
          <a href="<?= Url::to(['category/index', 'alias' => $category->alias]) ?>">
            <div class="category">
              <!-- <div class="info">
                <p><?= $category->title ?></p>
              </div> -->
              <div class="image" style="background-image: url('<?= Url::to('@web/') ?><?= $category->image ?>');"></div>
            </div>
          </a>
        </div>
      <?php endif; ?>
      <?php if (($category = Category::findByAlias('cernye_uslugi')) != NULL): ?>
        <div class="col-lg-4">
          <a href="<?= Url::to(['category/index', 'alias' => $category->alias]) ?>">
            <div class="category">
              <!-- <div class="info">
                <p><?= $category->title ?></p>
              </div> -->
              <div class="image" style="background-image: url('<?= Url::to('@web/') ?><?= $category->image ?>');"></div>
            </div>
          </a>
        </div>
      <?php endif; ?>
    </div>
    <!-- <a href="<?= Url::to(['site/category']) ?>" class="more">Смотреть все</a> -->
  </div>
</div>
