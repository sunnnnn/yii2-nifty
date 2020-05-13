<?php
namespace components\widgets\gallery;

use yii\web\AssetBundle;

class GalleryAsset extends AssetBundle{
    
    public $sourcePath = __DIR__;
    
    public $css = [
        'css/unite-gallery.css',
        'themes/default/ug-theme-default.css',
    ];
    
    public $js = [
        'js/unitegallery.js',
        'themes/default/ug-theme-default.js',
        'themes/slider/ug-theme-slider.js',
        'themes/tiles/ug-theme-tiles.js',
    ];
    
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
