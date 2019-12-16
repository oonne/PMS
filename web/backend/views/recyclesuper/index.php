<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use backend\widgets\Alert;
use common\models\Recycle;

$this->title = '回收站';
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
                    'attribute' => 'Category',
                    'filter' => Recycle::getCategoryList(),
                    'filterInputOptions' => ['class' => 'form-control input-sm'],
                    'headerOptions' => ['class' => 'col-md-1'],
                    'value' => function ($model, $key, $index, $column) {
                        return $model->categoryMsg;
                    }

                ],
                [
                    'attribute' => 'tRecycleContent',
                    'headerOptions' => ['class' => 'col-md-4'],
                    'format' => 'html',
                    'filterInputOptions' => ['class' => 'form-control input-sm'],
                    'value' => function ($model, $key, $index, $column) {
                        $preview = strip_tags($model->tRecycleContent);
                        if(strlen($preview)>200){
                            $preview = mb_substr($preview, 0, 100).'...';    
                        }
                        return $preview;
                    }
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
                        return $model->sCreatedTime;
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'headerOptions' => ['class' => 'col-md-2'],
                    'template' => '{view} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a('查看', ['view-recycle', 'id' => $key], ['class' => 'btn btn-info btn-xs']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('删除', ['delete-recycle', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data-confirm' => Yii::t('yii', '确定彻底删除吗？')]);
                        },
                    ]
                ]
            ]
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>