<?php
namespace components\widgets\table;

use yii\web\AssetBundle;

class TableAsset extends AssetBundle{
    
    public $sourcePath = '@components/assets/plugins/bootstrap-table';
    
    public $css = [
        'bootstrap-table.min.css',
        'extensions/x-editable/css/bootstrap-editable.css'
    ];
    
    public $js = [
        'bootstrap-table.min.js',
        'locale/bootstrap-table-zh-CN.js',
        'extensions/x-editable/js/bootstrap-editable.min.js',
        'extensions/editable/bootstrap-table-editable.js'
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
