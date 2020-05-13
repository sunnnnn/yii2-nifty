<?php
namespace components\widgets\gallery;

use Yii;
use yii\helpers\Html;
use yii\base\Widget;
use yii\helpers\Json;

/**
 * @use: https://unitegallery.net
 * @date: 2019/9/6 2:05 PM
 * @author: sunnnnn [http://www.sunnnnn.com] [mrsunnnnn@qq.com]
 */

class Gallery extends Widget{

    public $pure = false;
    /**
     * @var array
     *
     * [
     *  [
     *      'src' => '图片路径',
     *      'alt' => '图片标题',
     *      'data-type' => 'Youtube, Vimeo, Html5 Video, Wistia, SoundCloud',
     *      'data-image' => '原（大）图地址',
     *      'data-description' => '图片描述',
     *  ]
     * ]
     */
    public $items = [];

    public $theme = 'default';

    public $id = '';

    public $width = '100%';

    public $minWidth = 400;

    public $height = 500;

    public $minHeight = 300;

    public $controlPanel = false;

    public $textPanel = false;

    public $play = true;

    public $playTime = 3000;

    public $pause = true;

    public $zoom = false;

    public $swipe = true;

    public $arrows = true;
    /**
     * @var string
     * [bar, pie, pie2]
     */
    public $progress = 'bar';
    /**
     * 原生配置
     * @var array
     */
    public $options = [];
    /**
     * 当前配置和原生配置合并
     * @var boolean
     */
    public $optionsMerge = true;

    public function init(){
        parent::init();
    }

    public function run(){
        parent::run();

        $this->id = empty($this->id) ? 'gallery-'.Yii::$app->security->generateRandomString(8) : $this->id;

        $this->renderWidget();
    }

    public function renderWidget(){

        $input = Html::tag('div',
            $this->renderItems($this->items),
            ['id' => $this->id, 'style' => 'display:none;']
        );

        $this->renderAsset();
        echo $input;
    }

    private function renderItems($items){
        $result = '';

        foreach($items as $item){
            if(isset($item['data-href'])){
                $result .= Html::a(Html::tag('img', '', $item), $item['data-href'], ['target' => '_blank']);
                unset($item['data-href']);
            }else{
                $result .= Html::tag('img', '', $item);
            }
        }

        return $result;
    }

    public function renderAsset(){
        $view = $this->getView();

        GalleryAsset::register($view);

        if($this->pure === true){
            $options = [];
        }else{
            $options = [
                'gallery_theme' => $this->theme,
                'gallery_width' => $this->width,
                'gallery_min_width' => $this->minWidth,
                'gallery_height' => $this->height,
                'gallery_min_height' => $this->minHeight,
                'theme_enable_fullscreen_button' => $this->controlPanel,
                'theme_enable_play_button' => $this->controlPanel,
                'theme_enable_hidepanel_button' => $this->controlPanel,
                'theme_enable_text_panel' => $this->textPanel,
                'gallery_autoplay' => $this->play,
                'gallery_play_interval' => $this->playTime,
                'gallery_pause_on_mouseover' => $this->pause,
                'slider_control_zoom' => $this->zoom,
                'slider_enable_zoom_panel' => $this->zoom,
                'slider_control_swipe' => $this->swipe,
                'slider_progress_indicator_type' => $this->progress,
                'slider_enable_arrows' => $this->arrows
            ];
        }

        if(!empty($this->options)){
            $options = $this->optionsMerge === true ? array_merge($options, $this->options) : $this->options;
        }

        $jsonOptions = Json::encode($options);

        $js = <<<JS
            $(function(){
                $('#{$this->id}').unitegallery({$jsonOptions});
        	});
JS;
        $view->registerJs($js, $view::POS_END);
    }

}