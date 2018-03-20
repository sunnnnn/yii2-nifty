<?php
use yii\helpers\Html;
$this->registerJs('$(function(){$(".default-page-head").remove();$(".default-page-content").attr("id", null);})');
$this->title = $name;
?>
<div id="page-head">
    <div class="text-center cls-content">
        <h1 class="error-code text-info" style="font-size: 60px;"><?= Yii::t('app', 'error_page'); ?></h1>
    </div>
</div>

<div id="page-content">

	<div class="text-center cls-content" style="padding-top: 50px;">
	    <p class="h4 text-uppercase text-bold"><?= Html::encode($this->title) ?></p>
	    <div class="pad-btm">
	        <?= nl2br(Html::encode($message)) ?>
	    </div>
	</div>

</div>
