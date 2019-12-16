<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use backend\widgets\Alert;

$this->title = $model->isNewRecord ? '添加' : $model->sDate;
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
        <?= $form->field($model, 'sDate')->widget(DatePicker::className(), [
            'options' => ['class' => 'form-control'],
            'clientOptions' => ['firstDay' => 0],
            'dateFormat' => 'yyyy-MM-dd'
        ]) ?>
        <?= $form->field($model, 'tDiaryContent')->textarea(['rows' => '20', 'style' => 'resize: vertical;']) ?>
        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>