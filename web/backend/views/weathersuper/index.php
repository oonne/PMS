<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use backend\widgets\Alert;
use common\models\Weather;

$this->title = '气象站';
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    </div>
</div>
<p>
    <?= Html::a('添加记录', ['weathersuper/add-weather'], ['class' => 'btn btn-success']) ?>
</p>
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
                    'attribute' => 'Station',
                    'filter' => Weather::getStationList(),
                    'headerOptions' => ['class' => 'col-md-1'],
                    'filterInputOptions' => ['class' => 'form-control input-sm'],
                    'value' => function ($model, $key, $index, $column) {
                        return $model->stationMsg;
                    }
                ],
                [
                    'attribute' => 'dTemperature',
                    'headerOptions' => ['class' => 'col-md-2'],
                    'filterInputOptions' => ['class' => 'form-control input-sm'],
                ],
                [
                    'attribute' => 'dHumidity',
                    'headerOptions' => ['class' => 'col-md-2'],
                    'filterInputOptions' => ['class' => 'form-control input-sm'],
                ],
                [
                    'attribute' => 'dateRange',
                    'filter' => '<div class="drp-container">' . DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'dateRange',
                        'presetDropdown' => true,
                        'hideInput' => true,
                        'containerOptions' => ['class' => 'drp-container input-group date-range-container'],
                        'convertFormat' => true,
                        'initRangeExpr' => true,
                        'pluginOptions' => [
                            'locale' => [
                                'format' => 'Y-m-d',
                                'separator' => '~',
                            ],
                        ],
                    ]) . '</div>',
                    'headerOptions' => ['class' => 'col-md-2'],
                    'value' => function ($model, $key, $index, $column) {
                        return $model->sTime;
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'headerOptions' => ['class' => 'col-md-2'],
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('修改', ['update-weather', 'id' => $key], ['class' => 'btn btn-warning btn-xs']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('删除', ['delete-weather', 'id' => $key], ['class' => 'btn btn-danger btn-xs']);
                        },
                    ]
                ]
            ]
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>