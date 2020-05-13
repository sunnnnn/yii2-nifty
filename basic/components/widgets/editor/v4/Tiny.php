<?php
namespace components\widgets\editor;

use yii\helpers\Html; 
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * @use: https://www.tiny.cloud/
 * @date: 2018/12/13 10:12 PM
 * @author: sunnnnn [http://www.sunnnnn.com] [mrsunnnnn@qq.com]
 */
class Tiny extends InputWidget{
    /**
     * 纯净版，为true时，所有参数都需要自定义
     * @var boolean
     */
    public $_pure = false;
    /**
     * 语言
     * @var string
     */
    public $_language = 'zh_CN';
    /**
     * 主题
     * @var string
     */
    public $_theme = 'modern';
    /**
     * 移动端配置
     * @var null
     */
    public $_mobile = null;
    /**高度
     * @var int
     */
    public $_height = 400;
    /**
     * 底部状态栏
     * @var bool
     */
    public $_bottom = false;
    /**
     * 顶部菜单栏
     * @var string
     */
    public $_menu = 'file edit format insert view';
    /**
     * 插件
     * @var string
     */
    public $_plugins = 'hr table textcolor colorpicker link code fullscreen image media imagetools preview searchreplace print lists advlist';
    /**
     * 工具栏
     * @var string
     */
    public $_toolbar = 'fontsizeselect bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image media | code preview fullscreen';
    /**
     * @var bool
     */
    public $_automatic_uploads = true;
    /**
     * 上传图片路由
     * @var string
     */
    public $_upload = 'upload';
    /**
     * 原生配置
     * @var array
     */
    public $_options = [];
    /**
     * 当前配置和原生配置合并
     * @var boolean
     */
    public $_optionsMerge = true;

    public function init(){
        parent::init();

        if(in_array($this->_language, ['zh', 'zh-cn', 'zh-CN', 'zh-tw', 'zh-TW'])){
            $$this->_language = 'zh_CN';
        }

        if($this->_mobile === true){
            $this->_mobile = [
                'theme' => 'mobile',
                'plugins' => 'textcolor colorpicker link image imagetools lists',
                'toolbar' => 'styleselect fontsizeselect bold italic underline forecolor bullist numlist link image'
            ];
        }
    }

    public function run(){
        parent::run();
        $this->renderWidget();
    }
    
    public function renderWidget(){
        
        if($this->hasModel()){
            $input = Html::activeTextarea($this->model, $this->attribute, $this->options);
        }else{
            $input = Html::textarea($this->name, null, $this->options);
        }
        
        $this->renderAsset();
        echo $input;
    }
    
    public function renderAsset(){
        $view = $this->getView();
        
        Asset::register($view);
        
        if($this->_pure === true){
            $options = [
                'selector' => '#'.$this->options['id']
            ];
        }else{
            $options = [
                'selector' => '#'.$this->options['id'],
                'language' => $this->_language,
                'theme'    => $this->_theme,
                'mobile'   => $this->_mobile,
                'height'   => $this->_height,
                'statusbar'   => $this->_bottom,
                'menubar'   => $this->_menu,
                'plugins'   => $this->_plugins,
                'toolbar'   => $this->_toolbar,
                'automatic_uploads'   => $this->_automatic_uploads,
                'images_upload_url'   => $this->_upload,
            ];

            if($this->_mobile === null) unset($options['mobile']);
        }
        
        if(!empty($this->_options)){
            $options = $this->_optionsMerge === true ? array_merge($options, $this->_options) : $this->_options;
        }
        
        $jsonOptions = Json::encode($options);
        
        $js = <<<JS
            $(function(){
                tinymce.init({$jsonOptions});
            })
JS;
        $view->registerJs($js, $view::POS_END);
    }
    
}