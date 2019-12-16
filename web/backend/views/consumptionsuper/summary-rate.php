<?php
use yii\helpers\Html;
use daixianceng\echarts\ECharts;

$this->title = '个人消费';
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    </div>
</div>
<?= $this->render('_nav') ?>

<div class="row">
    <div class="col-lg-6">
        <?= ECharts::widget([
            'responsive' => true,
            'options' => [
                'style' => 'height: 400px;'
            ],
            'pluginOptions' => [
                'option' => [
                    'tooltip' => [
                        'trigger' => 'item',
                        'formatter' => '{a} <br/>{b}: {c} ({d}%)'
                    ],
                    'series' => [
                        'name' => '金额',
                        'type' => 'pie',
                        'radius' => ['66%', '80%'],
                        'data' => $data
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>