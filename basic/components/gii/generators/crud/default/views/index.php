<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title"><?= '<?= '; ?>Yii::t('app/view', 'view_list'); ?></h3>
    </div>

    <div class="panel-body">
        <div class="pad-btm form-inline">
            <div class="row">
                <div class="col-sm-9 table-toolbar-left">
        			<?= '<?php ' ?>$form = ActiveForm::begin([
        				'action' => Url::to(['/<?= Inflector::camel2words(StringHelper::basename($generator->modelClass), false) ?>/index']),
        				'method' => 'get',
        				'options'=>['class' => 'form-inline search-inline'],
        				'enableClientValidation' => false,
        			]); ?>
        				<div class="form-group">
        					<?= '<?= ' ?>Html::activeTextInput($searchModel, 'keywords', ['class' => 'form-control', 'placeholder' => Yii::t('app/view', 'type_for_search')]); ?>
        				</div>
    					<?= '<?= ' ?>Html::submitButton('<i class="pli-magnifi-glass"></i> '.Yii::t('app/view', 'search'), ['class' => 'btn btn-default']); ?>
        		    <?= '<?php ' ?>ActiveForm::end(); ?>
				</div>
                
                <div class="col-sm-3 table-toolbar-right">
                	<?= '<?= ' ?>Html::a('<i class="pli-add""></i> '.Yii::t('app/view', 'add'), ['/<?= Inflector::camel2words(StringHelper::basename($generator->modelClass), false) ?>/add'], ['class' => 'btn btn-purple']) ?>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
			<?= '<?= ' ?>GridView::widget([
				'layout' => "{items}\n{summary}\n{pager}",
				'tableOptions' => ['class' => 'table table-hover'],
				'dataProvider' => $dataProvider,
				'columns' => [ 
//					[
//						'attribute' => '',
//						'headerOptions' => ['width' => '20%'],
//						'contentOptions' => [],
//						'format' => 'raw',
//						'value' => function($model) {
//							return '';
//						},
//					],
			<?php
				$count = 0;
				if (($tableSchema = $generator->getTableSchema()) === false) {
				    foreach ($generator->getColumnNames() as $name) {
				        if (++$count < 6) {
				            echo "            '" . $name . "',\n";
				        } else {
				            echo "            // '" . $name . "',\n";
				        }
				    }
				} else {
				    foreach ($tableSchema->columns as $column) {
				        $format = $generator->generateColumnFormat($column);
				        if (++$count < 6) {
				            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
				        } else {
				            echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
				        }
				    }
				}
				?>
    				[
						'class' => 'yii\grid\ActionColumn',
						"headerOptions" => ["style" => "text-align:center"],
					    "contentOptions" => ["style" => "text-align:right"],
						"template" => "{update} {remove}",
						"header" => Yii::t('app/view', 'actions'),
						"buttons" => [
							"update" => function ($url, $model, $key){
							    return Html::a('<i class="pli-pen"></i> '.Yii::t('app/view', 'edit'), ['/<?= Inflector::camel2words(StringHelper::basename($generator->modelClass), false) ?>/edit', 'id' => $model->id], [
									"class" => "btn btn-sm btn-default"
								]);
							},
							"remove" => function($url, $model, $key){
							    return Html::a('<i class="pli-remove"></i> '.Yii::t('app/view', 'remove'), 'javascript:;', [
									'class' => 'btn btn-sm btn-default ajax-table-delete',
								    'data-action' => Url::to(['/<?= Inflector::camel2words(StringHelper::basename($generator->modelClass), false) ?>/delete'])
								]);
							}
						]
					],
				],
			]); ?>
		</div>
    </div>
</div>