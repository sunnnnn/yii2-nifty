<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('app/menu', 'change_password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel">
	<div class="panel-heading">
        <h3 class="panel-title"><?= Html::a('<i class="pli-left-4"></i> '.Yii::t('app/view', 'back'), 'javascript:history.back();', ['class' => 'btn btn-default']); ?></h3>
    </div>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<?php $form = ActiveForm::begin([
		        'action' => null,
        		'options' => [
        		    'class' => 'panel-body form-horizontal form-padding ajax-form', 
        		    'data-action' => Url::to(['/user/edit-password'])
        		],
        		'fieldConfig' => [
        			'template' => '{label}{input}',
        		    'labelOptions' => ['class' => 'control-label'],
        			'inputOptions' => ['class' => 'form-control'],
        		],
		    ]); ?>
				
				<div class="panel-body">
                    <?= $form->field($model, 'oldPassword')->passwordInput(['maxlength' => true]) ?>
                
                    <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true]) ?>
                
                    <?= $form->field($model, 'confirmPassword')->passwordInput(['maxlength' => true]) ?>
				</div>

				<div class="panel-footer">
					<?= Html::button(Yii::t('app/view', 'submit'), ['class' => 'btn btn-mint ajax-form-submit']); ?>
					<?= Html::a('<i class="pli-left-4"></i> '.Yii::t('app/view', 'back'), 'javascript:history.back();', ['class' => 'btn btn-default']); ?>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
    </div>
</div>