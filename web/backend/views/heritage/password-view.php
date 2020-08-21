<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = '密码详情';
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    </div>
</div>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'sPasswordItem',
        'sUserName',
        'sPassword',
        [
            'attribute' => 'tRemark',
            'format' => 'html',
            'value' => Html::tag('div', nl2br($model->tRemark))
        ],
        'uUpdatedTime'
    ]
]) ?>