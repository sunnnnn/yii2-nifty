<?php
namespace components\widgets\editor;

use yii\web\AssetBundle;

class Asset extends AssetBundle{
    
    public $sourcePath = __DIR__;
    
    public $css = [
        'style.css'
    ];
    
    public $js = [
        'v5/tinymce.min.js',
        //'https://cloud.tinymce.com/5/tinymce.min.js?apiKey=2ifaa7kz2kucu5ar22yykcvngexr0chq87ma0huzuh0fulo2'
    ];
    
    public $depends = [
    ];
}
