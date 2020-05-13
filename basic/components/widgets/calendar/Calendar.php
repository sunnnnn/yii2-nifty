<?php
namespace components\widgets\calendar;

use yii\helpers\Html; 
use yii\helpers\Json;
use yii\base\Widget;

/**
 * @use: Calendar
 *    <?= \components\widgets\calendar\Calendar::widget([
 *        'options' => [
 *            'id' => 'calendar'
 *        ],
 *        '_defaultView' => 'agendaDay',
 *        '_header' => [
 *           'left' => 'prev',
 *            'center' => 'title',
 *            'right' => 'next'
 *        ],
 *        '_events' => [
 *            [
 *                'title' => 'Event111',
 *                'start' => '2018-10-16 08:30:00',
 *                'end'   => '2018-10-16 10:30:00',
 *            ]
 *        ]
 *   ]); ?>
 *
 * @url: https://fullcalendar.io
 * @date: 2018/10/16 下午1:57
 * @author: sunnnnn [http://www.sunnnnn.com] [mrsunnnnn@qq.com]
 */
class Calendar extends Widget{
    /**
     * 纯净版，为true时，所有参数都需要自定义
     * @var boolean
     */
    public $_pure = false;
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
    /**
     * div id
     * @var string
     */
    public $_id = 'calendar';
    /**
     * div options
     * @var array
     */
    public $options = [];
    /**
     * 语言
     * @var string
     */
    public $_language = 'zh-cn';
    /**
     * @var string
     */
    public $_height = 'auto';
    /**
     * @var string
     */
    public $_contentHeight = 'auto';
    /**
     * 显示形式，month，agendaDay，agendaWeek，listDay，listWeek···
     * @var string
     */
    public $_defaultView = 'month';
    /**
     * @var array
     */
    public $_header = [
        'left' => 'prev,next today',
        'center' => 'title',
        'right' => 'month,agendaWeek,agendaDay'
    ];
    public $_titleFormat = 'YYYY-MM-DD';
    /**
     * 模式显示日期
     * @var string
     */
    public $_defaultDate = '';
    /**
     * 事件
     * @var array
     */
    public $_events = [];

    public function init(){
        parent::init();

        empty($this->options['id']) && $this->options['id'] = $this->_id;

        empty($this->_defaultDate) && $this->_defaultDate = date('Y-m-d');

        if(in_array($this->_language, ['zh', 'zh-cn', 'zh-CN', 'zh-tw', 'zh-TW'])){
            $this->_language = 'zh-cn';
        }
    }

    public function run(){
        parent::run();
        $this->renderWidget();
    }
    
    public function renderWidget(){
        $input = Html::tag('div', '', $this->options);
        $this->renderAsset();
        echo $input;
    }
    
    public function renderAsset(){
        $view = $this->getView();

        CalendarAsset::register($view);
        
        if($this->_pure === true){
            $options = [];
        }else{
            $options = [
                'locale' => $this->_language,
                'height' => $this->_height,
                'contentHeight' => $this->_contentHeight,
                'defaultView' => $this->_defaultView,
                'header' => $this->_header,
                'titleFormat' => $this->_titleFormat,
                'defaultDate' => $this->_defaultDate,
                'events' => $this->_events,
            ];
        }
        
        if(!empty($this->_options)){
            $options = $this->_optionsMerge === true ? array_merge($options, $this->_options) : $this->_options;
        }
        
        $jsonOptions = Json::encode($options);
        
        $js = <<<JS
            $(function(){
                $('#{$this->_id}').fullCalendar({$jsonOptions});
        	});
JS;
        $view->registerJs($js, $view::POS_END);
    }
    
}