<?php
use yii\helpers\Html;
use yii\widgets\Block;
use backend\widgets\Alert;

$this->title = $model->categoryMsg;
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
    </div>
</div>

<?php $this->beginBlock('note-content', true) ?>
<?= nl2br($model->tRecycleContent) ?>
<?php $this->endBlock() ?>