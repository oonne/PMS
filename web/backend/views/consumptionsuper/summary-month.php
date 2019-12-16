<?php
use yii\helpers\Html;
use daixianceng\echarts\ECharts;

/* @var $this yii\web\View */
$this->title = '个人消费';
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    </div>
</div>
<?= $this->render('_nav') ?>

<div class="row">
    <div class="col-lg-12">
        <?= ECharts::widget([
            'responsive' => true,
            'options' => [
                'style' => 'height: 400px;'
            ],
            'pluginOptions' => [
                'option' => [
                    'tooltip' => [
                        'trigger' => 'axis',
                        'axisPointer' => [
                            'type' => 'shadow'
                        ]
                    ],
                    'grid' => [
                        'left' => '2%',
                        'right' => '4%',
                        'bottom' => '2%',
                        'containLabel' => true
                    ],
                    'xAxis' => [
                        'name' => '月份',
                        'type' => 'category',
                        'data' => $month,
                    ],
                    'yAxis' => [
                        'name' => '金额',
                        'type' => 'value'
                    ],
                    'series' => [
                        'name' => '金额',
                        'type' => 'bar',
                        'barWidth' =>  '60%',
                        'label' => [
                            'normal' => [
                                'position' => 'top',
                                'show' => true
                            ]
                        ],
                        'data' => $money
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>