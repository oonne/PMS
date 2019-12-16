<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
?>

<nav class="navbar navbar-default">
    <?php
        $menuItems = [
            [
                'label' => '账单明细',
                'url' => ['consumptionsuper/index'],
            ],
            [
                'label' => '月度统计',
                'url' => ['consumptionsuper/summary-month'],
            ],
            [
                'label' => '消费比例',
                'url' => ['consumptionsuper/summary-rate'],
            ] 
        ];

        echo Nav::widget([
            'options' => ['class' => 'nav navbar-nav'],
            'items' => $menuItems,
        ]);
    ?>
</nav>