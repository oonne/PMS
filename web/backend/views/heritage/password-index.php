<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use backend\widgets\Alert;

$this->title = '密码';
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
        <?php Pjax::begin() ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-bordered table-center'],
            'summaryOptions' => ['tag' => 'p', 'class' => 'text-right text-muted'],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'headerOptions' => ['class' => 'col-md-1'],
                ],
                [
                    'attribute' => 'sPasswordItem',
                    'headerOptions' => ['class' => 'col-md-2'],
                    'filterInputOptions' => ['class' => 'form-control input-sm'],
                ],
                [
                    'attribute' => 'sUserName',
                    'headerOptions' => ['class' => 'col-md-2'],
                    'filterInputOptions' => ['class' => 'form-control input-sm'],
                ],
                [
                    'attribute' => 'tRemark',
                    'headerOptions' => ['class' => 'col-md-4'],
                    'filterInputOptions' => ['class' => 'form-control input-sm'],
                    'value' => function ($model, $key, $index, $column) {
                        $preview = strip_tags($model->tRemark);
                        if(strlen($preview)>100){
                            $preview = mb_substr($preview, 0, 50).'...';    
                        }
                        return $preview;
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'headerOptions' => ['class' => 'col-md-2'],
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a('查看', ['view-password', 'id' => $key], ['class' => 'btn btn-info btn-xs']);
                        },
                    ]
                ]
            ]
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>