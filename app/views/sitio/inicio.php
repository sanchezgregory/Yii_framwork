<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php if ($respuesta) {
    echo Html::tag('div', Html::encode($respuesta), ['class' => 'alert alert-danger']);
}?>

<?php $formulario = ActiveForm::begin(); ?>

<?php echo $formulario->field($model, 'valora'); ?>
<?php echo $formulario->field($model, 'valorb'); ?>

<div class="form-group">
    <?= Html::submitButton('Enviar', ['class'=>'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>