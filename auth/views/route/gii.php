<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '生成路由';
$this->params['breadcrumbs'][] = ['label' => '路由列表', 'url' => Url::to(['/auth/route/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel">
    <div class="panel-body">
        <div class="form-inline search-inline toolbar">
        <?= Html::button('生成', [
            'class' => 'btn btn-mint',
            'id' => 'generate-permission',
            'disabled' => true,
            'data-action' => Url::to(['/auth/route/gii-generate'])
        ]); ?>
            <div class="input-group">
                <span class="input-group-addon">类名/路径</span>
                <?= Html::textInput('docEntry', null, [
                    'id' => 'keywords',
                    'name' => 'keywords',
                    'class' => 'form-control',
                    'style' => 'width:500px;',
                    'placeholder' => '如 \backend\controllers\SiteController 或 @backend/controllers/'
                ]); ?>
                <div class="input-group-btn">
                    <?= Html::Button('预览', [
                        'id' => 'preview-permission',
                        'data-action' => Url::to(['/auth/route/gii-preview']),
                        'class' => 'btn btn-mint'
                    ]); ?>
                </div>
            </div>
        </div>

        <div class="table-responsive" style="padding-top:15px;">
            <?= \components\widgets\table\Table::widget([
                '_table_options' => [
                    'id' => 'permission-table'
                ],
                '_columns' => $columns,
                '_data' => $data,
                '_checkbox' => true,
                '_toolbar' => '.toolbar',
                '_options' => [
                ]
            ]); ?>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">用法</h3>
    </div>
    <div class="panel-body">
        <p class="text-muted">使用注释，在注释中添加@permission-route和@permission-desc两个标签</p>
        <p class="text-primary"></p>
        <p class="text-danger">/**</p>
        <p class="text-danger">* @permission-route /site/index (路由)</p>
        <p class="text-danger">* @permission-desc 首页 (描述)</p>
        <p class="text-danger">*/</p>
    </div>
</div>

<?php $this->beginBlock('gii');  ?>
$(function(){

    var $table = $('#permission-table'), $buttons = $('#generate-permission');

    $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
        $buttons.prop('disabled', !$table.bootstrapTable('getSelections').length);
    });

    $('#generate-permission').click(function(){
        var that = $(this);
        var selections = $table.bootstrapTable('getSelections');

        if(!selections.length){
            showError('请先选择');
            return false;
        }

        showLoading(_message.loading);
        $.ajax({
            type: 'post',
            url: that.data('action'),
            data: {'selections':selections, '_csrf':_csrf},
            dataType: 'json',
            error: function(xhr) {
                hideLoading();
                if(xhr.status == '403'){
                    showError(_message.errorHttp403);
                }else if(xhr.status == '404'){
                    showError(_message.errorHttp404);
                }else{
                    showError(_message.errorHttp500);
                }
            },
            success: function(result) {
                hideLoading();
                showError(result.message);
            }
        });
    });

    $('#preview-permission').click(function(){
        var that = $(this);
        var keywords = $('#keywords').val();

        if(!keywords){
            showError('请输入类名或路径');
            return false;
        }
        $buttons.prop('disabled', true);
        showLoading(_message.loading);
        $.ajax({
            type: 'post',
            url: that.data('action'),
            data: {'keywords':keywords, '_csrf':_csrf},
            dataType: 'json',
            error: function(xhr) {
                hideLoading();
                if(xhr.status == '403'){
                    showError(_message.errorHttp403);
                }else if(xhr.status == '404'){
                    showError(_message.errorHttp404);
                }else{
                    showError(_message.errorHttp500);
                }
            },
            success: function(result) {
                hideLoading();
                if(result.result){
                    $table.bootstrapTable('load', result.data);
                }else{
                    console.log(result);
                    showError(result.message);
                }
            }
        });

    });

});

<?php $this->endBlock(); ?>
<?php $this->registerJs($this->blocks['gii'], \yii\web\View::POS_END); ?>
