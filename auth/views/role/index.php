<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = Yii::t('app/menu', 'role');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Yii::t('app/view', 'view_list'); ?></h3>
    </div>

    <div class="panel-body">
        <div class="pad-btm form-inline">
            <div class="row">
                <div class="col-sm-9 table-toolbar-left">
                    <?php $form = ActiveForm::begin([
        				'action' => Url::to(['/auth/role/index']),
        				'method' => 'get',
        				'options'=>['class' => 'form-inline search-inline'],
        				'enableClientValidation' => false,
        			]); ?>
            			<div class="form-group">
            				<?= Html::activeTextInput($searchModel, 'keywords', ['class' => 'form-control', 'placeholder' => Yii::t('app/view', 'type_for_search')]); ?>
            			</div>
            			<?= Html::submitButton('<i class="pli-magnifi-glass"></i> '.Yii::t('app/view', 'search'), ['class' => 'btn btn-default']); ?>
        		    <?php ActiveForm::end(); ?>
                </div>
                
                <div class="col-sm-3 table-toolbar-right">
                	<?= Html::a('<i class="pli-add""></i> '.Yii::t('app/view', 'add'), ['/auth/role/add'], ['class' => 'btn btn-purple']) ?>
                </div>
            </div>
        </div>
        <div class="table-responsive">
			<?= GridView::widget([
                'layout' => "{items}\n{summary}\n{pager}",
				'tableOptions' => ['class' => 'table table-hover'],
				'dataProvider' => $dataProvider,
				'columns' => [
					[
						"attribute" => "id",
						"headerOptions" => ["width" => "10%"],
						"contentOptions" => [],
					],
					[
						"attribute" => "name",
						"headerOptions" => ["width" => "30%"],
					    'value' => function($model){
                            return Yii::t('app/menu', $model->name);
                        }
					],
					[
						"attribute" => "remark",
						"headerOptions" => ["width" => "40%"],
					],
					[
						'class' => 'yii\grid\ActionColumn',
					    "headerOptions" => ["style" => "text-align:center"],
					    "contentOptions" => ["style" => "text-align:right"],
						"template" => "{update} {delete}",
						"header" => Yii::t('app/view', 'actions'),
						"buttons" => [
							"update" => function ($url, $model, $key){
							    return Html::a('<i class="pli-pen"></i> '.Yii::t('app/view', 'edit'), ['/auth/role/edit', 'id' => $model->id], [
									"class" => "btn btn-sm btn-default"
								]);
							},
							"delete" => function($url, $model, $key){
							    return Html::a('<i class="pli-remove"></i> '.Yii::t('app/view', 'remove'), 'javascript:;', [
									'class' => 'btn btn-sm btn-default ajax-table-delete',
								    'data-action' => Url::to(['/auth/role/delete'])
								]);
							}
						]
					],
				],
			]); ?>
        </div>
    </div>
</div>