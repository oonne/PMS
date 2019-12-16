<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use backend\widgets\Alert;

$this->title = '捐款';
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    </div>
</div>
<div class="pms_summary col-md-12">
    <div class="pms_summary_item col-md-2 summary_donation_income">
        <div class="pms_summary_attr">总计捐款</div>
        <div class="pms_summary_num"><?= $incomeTotal ?></div>
    </div>
    <div class="pms_summary_item col-md-2 summary_donation_total">
        <div class="pms_summary_attr">已捐金额</div>
        <div class="pms_summary_num"><?= $donationTotal ?></div>
    </div>
    <div class="pms_summary_item col-md-2 summary_donation_rest">
        <div class="pms_summary_attr">未捐金额</div>
        <div class="pms_summary_num"><?= ($incomeTotal-$donationTotal) ?></div>
    </div>
</div>
<p>
    <?= Html::a('添加记录', ['donationsuper/add-donation'], ['class' => 'btn btn-success']) ?>
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
            'summary' => Html::tag('p', '<b>{totalCount}</b>条数据，共计<b>' .$summary. '</b>元.', ['class' => 'text-right text-muted']),
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => ['class' => 'col-md-1'],
                    ],
                    [
                        'attribute' => 'sDonationItem',
                        'headerOptions' => ['class' => 'col-md-2'],
                        'filterInputOptions' => ['class' => 'form-control input-sm'],
                    ],
                    [
                        'attribute' => 'dMoney',
                        'headerOptions' => ['class' => 'col-md-2'],
                        'filterInputOptions' => ['class' => 'form-control input-sm'],
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
                        'attribute' => 'sRemark',
                        'headerOptions' => ['class' => 'col-md-2'],
                        'filterInputOptions' => ['class' => 'form-control input-sm'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'headerOptions' => ['class' => 'col-md-2'],
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('修改', ['update-donation', 'id' => $key], ['class' => 'btn btn-warning btn-xs']);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('删除', ['delete-donation', 'id' => $key], ['class' => 'btn btn-danger btn-xs']);
                            },
                        ]
                    ]
                ]
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>