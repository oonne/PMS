<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use backend\widgets\Alert;

$this->title = '日记';
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    </div>
</div>
<p>
    <?= Html::a('添加记录', ['diarysuper/add-diary'], ['class' => 'btn btn-success']) ?>
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
                    'attribute' => 'sDate',
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'sDate',
                        'options' => ['class' => 'form-control input-sm'],
                        'clientOptions' => ['firstDay' => 0],
                        'dateFormat' => 'yyyy-MM-dd'
                    ]),
                    'headerOptions' => ['class' => 'col-md-2'],
                ],
                [
                    'attribute' => 'tDiaryContent',
                    'headerOptions' => ['class' => 'col-md-6'],
                    'filterInputOptions' => ['class' => 'form-control input-sm'],
                    'value' => function ($model, $key, $index, $column) {
                        $preview = strip_tags($model->tDiaryContent);
                        if(strlen($preview)>400){
                            $preview = mb_substr($preview, 0, 200).'...';    
                        }
                        return $preview;
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'headerOptions' => ['class' => 'col-md-2'],
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a('查看', ['view-diary', 'id' => $key], ['class' => 'btn btn-info btn-xs']);
                        },
                        'update' => function ($url, $model, $key) {
                            return Html::a('修改', ['update-diary', 'id' => $key], ['class' => 'btn btn-warning btn-xs']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('删除', ['delete-diary', 'id' => $key], ['class' => 'btn btn-danger btn-xs', 'data-confirm' => Yii::t('yii', '确定删除“'.$model->sDate.'”吗？')]);
                        },
                    ]
                ]
            ]
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>