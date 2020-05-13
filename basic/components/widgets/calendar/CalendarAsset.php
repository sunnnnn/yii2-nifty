<?php
namespace components\widgets\calendar;

use yii\web\AssetBundle;

class CalendarAsset extends AssetBundle{
    
    public $sourcePath = __DIR__;
    
    public $css = [
        'css/fullcalendar.min.css'
    ];
    
    public $js = [
        'js/moment.min.js',
        'js/fullcalendar.min.js',
        'locale/locale-all.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
