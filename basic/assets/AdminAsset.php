<?php
namespace app\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle{
	
	public $sourcePath = '@components/assets';
    public $css = [
        'css/nifty.min.css',
        'plugins/pace/pace.min.css',
        'plugins/popup/css/popup.css',
        'css/open-sans.css', //https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700
        'css/admin.css',
    ];
    public $js = [
    	'js/nifty.min.js',
    	'js/admin.js',
        'plugins/pace/pace.min.js',
        'plugins/popup/js/popup.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
        'components\assets\IconsAsset',
    ];
    
    public static function addJs($view, $jsfile) {
    	if(is_array($jsfile)){
    		foreach($jsfile as $js){
    			$view->registerJsFile($js, [AdminAsset::className(), 'depends' => AdminAsset::className()]);
    		}
    	}else{
    		$view->registerJsFile($jsfile, [AdminAsset::className(), 'depends' => AdminAsset::className()]);
    	}
    }
    
    public static function addCss($view, $cssfile) {
    	if(is_array($cssfile)){
    		foreach($cssfile as $css){
    			$view->registerCssFile($css, [AdminAsset::className(), 'depends' => AdminAsset::className()]);
    		}
    	}else{
    		$view->registerCssFile($cssfile, [AdminAsset::className(), 'depends' => AdminAsset::className()]);
    	}
    }
}
