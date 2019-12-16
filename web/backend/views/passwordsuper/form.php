<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\widgets\Alert;

$this->title = $model->isNewRecord ? '添加' : $model->sPasswordItem;
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="alert-wrapper">
            <?= Alert::widget() ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'sPasswordItem') ?>
        <?= $form->field($model, 'sUserName') ?>
        <?= $form->field($model, 'sPassword') ?>
        <?= $form->field($model, 'tRemark')->textarea(['rows' => '20', 'style' => 'resize: vertical;']) ?>
        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>