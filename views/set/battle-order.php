<?php

/*  @var $this yii\web\View 
    @var $model app/components/BattleOrder
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Боевой порядок подразделения';
?>
<div class="battle-order">
    <div class="container">
        <h1><?=Html::encode($this->title) ?></h1>
        <hr>
        <?php
        $form = ActiveForm::begin([
            'id' => 'bof',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
            'template' => '<div class="col-md-5">{label}</div><div class="col-md-3">{input}</div><div class="col-md-4">{error}</div>',
            ],
        ]);
        ?>
        <?= $form->field($model, 'osn') ?>
        <?= $form->field($model, 'operDate')->textInput(['type' => 'datetime-local', 'template' => '<div class="col-md-4">{label}</div><div class="col-md-6">{input}</div><div class="col-md-2">{error}</div>']);?>
        <?= $form->field($model, 'unitName') ?>
        <?= $form->field($model, 'xknp') ?>
        <?= $form->field($model, 'yknp') ?>
        <?= $form->field($model, 'hknp') ?>
        
        <?= $form->field($model, 'xbnp') ?>
        <?= $form->field($model, 'ybnp') ?>
        <?= $form->field($model, 'hbnp') ?>
        <?= $form->field($model, 'nGuns') ?>
        <?= $form->field($model, 'caliber') ?>
        <?= $form->field($model, 'artSystem') ?>
        <?= $form->field($model, 'xop') ?>
        <?= $form->field($model, 'yop') ?>
        <?= $form->field($model, 'hop') ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>                
        
    </div>
</div>