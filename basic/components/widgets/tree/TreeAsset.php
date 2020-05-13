<?php
namespace components\widgets\tree;

use yii\web\AssetBundle;

class TreeAsset extends AssetBundle{
    
    public $sourcePath = __DIR__;
    
    public $css = [
        'css/tree.css'
    ];
    
    public $js = [
        'js/tree.js'
    ];
    
    public $depends = [
        //'yii\web\JqueryAsset',
        //'yii\bootstrap\BootstrapAsset',
        'backend\assets\AdminAsset'
    ];
}
