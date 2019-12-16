<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use backend\widgets\Alert;

$this->title = '微信小程序SessionKey';
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
            'tableOptions' => ['class' => 'table table-striped table-bordered table-center'],
            'summaryOptions' => ['tag' => 'p', 'class' => 'text-right text-muted'],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'headerOptions' => ['class' => 'col-md-1'],
                ],
                [
                    'attribute' => 'sOpenID',
                    'headerOptions' => ['class' => 'col-md-2'],
                ],
                [
                    'attribute' => 'sSessionKey',
                    'headerOptions' => ['class' => 'col-md-2'],
                ],
                [
                    'attribute' => 'sWxToken',
                    'headerOptions' => ['class' => 'col-md-2'],
                ],
                [
                    'attribute' => 'sCreatedTime',
                    'headerOptions' => ['class' => 'col-md-2'],
                ],
                [
                    'attribute' => 'uUpdatedTime',
                    'headerOptions' => ['class' => 'col-md-2'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'headerOptions' => ['class' => 'col-md-2'],
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            return Html::a('删除', ['delete-skey', 'id' => $key], ['class' => 'btn btn-danger btn-xs']);
                        },
                    ]
                ]
            ]
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>