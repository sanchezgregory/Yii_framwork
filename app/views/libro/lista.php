<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>

<h1> Libros a la venta </h1>

<div class="row">
    <?php  foreach ($books as $book) : ?>

        <div class="col-xs-4 col-sm-4 col-md-43 col-lg-4">
            <a href="#" class="thumbnail">

                <?php echo Html::img($book->imagen, ['size'=>'80px'])?>
                <?php echo Html::encode("{$book->titulo}")?>

            </a>
        </div>

    <?php endforeach; ?>
</div>

<?php echo LinkPager::widget(['pagination' => $pagination])?>