<?php
namespace components\widgets\table;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\Widget;

/**
 * https://bootstrap-table.com/docs/api
 * @use: bootstrap table plugin
 * @date: 2020/4/24 11:27 AM
 * @author: sunnnnn
 */
class Table extends Widget{
    /**
     * 纯净版，为true时，所有参数都需要自定义
     * @var boolean
     */
    public $_pure = false;
    /**
     * 表头
     * @var array
     */
    public $_columns = [];
    /**
     * 表头配置
     * 'field' => $key,
     * 'title' => $text,
     * 'halign' => 'center',
     * 'align' => 'center',
     * 'width' => '100',
     * 'widthUnit' => 'px',
     * 'sortable' => true,
     * 'visible' => true,
     * 'switchable' => true,
     * 'searchable' => true
     * @var array
     */
    public $_columns_setting = [];
    /**
     * 讲data中的键值KEY作为一列
     * id_field 默认为此列
     * 此列默认不显示
     * 此列默认无法切换
     * 设为false则不启用
     * @var string
     */
    public $_row_index = false;
    /**
     * 行号列设置
     * @var array
     */
    public $_row_index_setting = [];
    /**
     * 多选框
     * @var bool
     */
    public $_checkbox = false;
    /**
     * checkbox列设置
     * @var array
     */
    public $_checkbox_setting = [];
    /**
     * 单选框
     * @var bool
     */
    public $_radio = false;
    /**
     * radio列设置
     * @var array
     */
    public $_radio_setting = [];
    /**
     * 表格数据
     * @var array
     */
    public $_data = [];
    /**
     * table元素
     * @var array
     */
    public $_table_options = [];
    /**
     * 后台获取数据的url
     * @var string
     */
    public $_url = '';
    /**
     * 请求方式
     * @var string
     */
    public $_method = 'post';
    /**
     * 请求数据类型
     * @var string
     */
    public $_content_type = 'application/x-www-form-urlencoded'; //application/json
    /**
     * 是否缓存
     * @var bool
     */
    public $_cache = false;
    /**
     * 自定义请求参数
     * @var string
     */
    public $_query = '';
    /**
     * The class name of table. 'table', 'table-bordered', 'table-hover', 'table-striped', 'table-dark', 'table-sm' and 'table-borderless' can be used.
     * By default, the table is bordered.
     * @var string
     */
    public $_class = 'table table-bordered table-hover';
    /**
     * The class name of table thead. Bootstrap v4, use the modifier classes .thead-light or .thead-dark to make theads appear light or dark gray.
     * @var string
     */
    public $_thead_class = '';
    /**
     * @var null
     */
    public $_sortable = null;
    /**
     * 分页 设置为true可以在表格底部显示分页工具栏。
     * @var bool
     */
    public $_pagination = null;
    /**
     * 切换是否分页
     * @var bool
     */
    public $_pagination_switch = null;
    /**
     * 每页显示数量
     * @var int
     */
    public $_page_size = 20;
    /**
     * 每页显示选择
     * @var array
     */
    public $_page_list = [10, 20, 50, 100];
    /**
     * 是否显示搜索框
     * @var string
     */
    public $_search = null;
    /**
     * true：精确搜索，false：模糊搜索
     * @var bool
     */
    public $_search_strict = false;
    /**
     * 设置为true,仅在可见列/数据中搜索，如果数据包含未显示的其他值，则在搜索时将忽略它们。
     * @var bool
     */
    public $_search_visible = true;
    /**
     * 搜索框位置：left、right
     * @var string
     */
    public $_search_align = 'right';
    /**
     * 默认搜索文字
     * @var string
     */
    public $_search_text = '';
    /**
     * 按回车键搜索
     * @var null
     */
    public $_search_enter = null;
    /**
     * 显示表头
     * @var bool
     */
    public $_show_header = true;
    /**
     * 显示表尾
     * @var bool
     */
    public $_show_footer = false;
    /**
     * 设置为true以显示列下拉列表。 我们可以将switchable column选项设置为false，以隐藏下拉列表中的column项目。
     * @var bool
     */
    public $_show_columns = true;
    /**
     * 显示刷新按钮
     * @var bool
     */
    public $_show_refresh = true;
    /**
     * checkbox和radio取值字段
     * 指明哪个字段将用作复选框/单选框的值
     * @var bool
     */
    public $_id_field = '';
    /**
     * 为每一行指示唯一的标识符。
     * @var string
     */
    public $_uniqueId = '';
    /**
     * 指示工具栏的jQuery选择器，例如：＃toolbar，.toolbar或DOM节点。
     * @var null
     */
    public $_toolbar = '';
    /**
     * 显示行间隔色
     * @var bool
     */
    public $_striped = true;
    /**
     * 图标
     * @var array
     */
    public $_icons = [
        'paginationSwitchDown' => 'pli-arrow-down',
        'paginationSwitchUp' => 'pli-arrow-up',
        'refresh' =>  'pli-repeat-2',
        'toggle:' => 'pli-layout-grid',
        'columns' => 'pli-check',
        'detailOpen' => 'psi-add',
        'detailClose' => 'psi-remove'
    ];
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

    private $columns = [];
    private $data = [];

    public function init(){
        parent::init();
        //设置table id
        empty($this->_table_options['id']) && $this->_table_options['id'] = Yii::$app->security->generateRandomString(8);
        //设置是否启用checkbox或者radio
        if($this->_checkbox === true){
            $_checkbox = ['checkbox' => true];
            $this->columns[] = empty($this->_checkbox_setting) ? $_checkbox : \yii\helpers\ArrayHelper::merge($_checkbox, $this->_checkbox_setting);
        }elseif($this->_radio === true){
            $_radio = ['radio' => true];
            $this->columns[] = empty($this->_radio_setting) ? $_radio : \yii\helpers\ArrayHelper::merge($_radio, $this->_radio_setting);
        }
        if(!empty($this->_row_index)){
            $_row_index = [
                'field' => $this->_row_index,
                'title' => '行号',
                'halign' => 'center',
                'align' => 'center',
                'sortable' => false,
                'visible' => false,
                'switchable' => true,
                'searchable' => false
            ];
            $this->columns[] = empty($this->_row_index_setting) ? $_row_index : \yii\helpers\ArrayHelper::merge($_row_index, $this->_row_index_setting);

            $this->_id_field = $this->_row_index;
        }
        //设置表头columns
        foreach($this->_columns as $key => $text){
            $_column = [
                'field' => $key,
                'title' => $text,
                'halign' => 'center',
                'align' => 'center',
                'widthUnit' => 'px',
                'sortable' => true,
                'visible' => true,
                'switchable' => true,
                'searchable' => true
            ];
            if(isset($this->_columns_setting[$key])){
                $_column = \yii\helpers\ArrayHelper::merge($_column, $this->_columns_setting[$key]);
            }

            $this->columns[] = $_column;
        }

        //设置data
        foreach($this->_data as $key => $value){
            if(!empty($this->_row_index)){
                $value[$this->_row_index] = $key;
            }
            $this->data[] = $value;
        }
    }

    public function run(){
        parent::run();
        $this->renderWidget();
    }
    
    public function renderWidget(){
        $input = Html::tag('table', '', $this->_table_options);
        
        $this->renderAsset();
        echo $input;
    }
    
    public function renderAsset(){
        $view = $this->getView();
        
        TableAsset::register($view);
        
        if($this->_pure === true){
            $options = [];
        }else{
            $options = [
                'classes' => $this->_class,
                'columns' => $this->columns,
                'data' => $this->data,
                'theadClasses' => $this->_thead_class,
                'pagination' => $this->_pagination ?? false,
                'sortable' => $this->_sortable ?? true,
                'showPaginationSwitch' => $this->_pagination_switch ?? true,
                'pageSize' => $this->_page_size,
                'pageList' => $this->_page_list,
                'search' => $this->_search ?? true,
                'strictSearch' => $this->_search_strict,
                'searchOnEnterKey' => $this->_search_enter ?? false,
                'visibleSearch' => $this->_search_visible,
                'searchAlign' => $this->_search_align,
                'searchText' => $this->_search_text,
                'showHeader' => $this->_show_header,
                'showFooter' => $this->_show_footer,
                'showColumns' => $this->_show_columns,
                'showRefresh' => $this->_show_refresh,
                'idField' => $this->_id_field,
                'icons' => $this->_icons,
                'striped' => $this->_striped,
            ];

            !empty($this->_toolbar) && $options['toolbar'] = $this->_toolbar;
            !empty($this->_uniqueId) && $options['uniqueId'] = $this->_uniqueId;

            if(!empty($this->_url)){
                $_options = [
                    'url' => $this->_url,
                    'method' => $this->_method,
                    'contentType' => $this->_content_type,
                    'cache' => $this->_cache,
                    'search' => $this->_search ?? false,
                    'sortable' => $this->_sortable ?? false,
                    'sidePagination' => 'server',
                    'pagination' => $this->_pagination ?? true,
                    'showPaginationSwitch' => $this->_pagination_switch ?? false,
                    'searchOnEnterKey' => $this->_search_enter ?? true
                ];
                !empty($this->_query) && $options['queryParams'] = $this->_query;

                $options = ArrayHelper::merge($options, $_options);
            }
        }
        
        if(!empty($this->_options)){
            $options = $this->_optionsMerge === true ? array_merge($options, $this->_options) : $this->_options;
        }
        
        $jsonOptions = Json::encode($options);
        
        $js = <<<JS
            function toolbar_query(params){
                var dict = {
                    'offset': params.offset,
                    'limit': params.limit,
                    'sort': params.sort,
                    'order': params.order
                };
                $('.toolbar .toolbar-attr').each(function(){
                    var name = $(this).attr('name');
                    var value = $(this).val();
                    if(name) dict[name] = value;
                });
                return dict;
            }

            $(function(){
                $('#{$this->_table_options['id']}').bootstrapTable({$jsonOptions});
                
                $('body').on('click', '[data-bind="toolbar-search"]', function(){
                    $('.toolbar-table').bootstrapTable('removeAll');
                    $('.toolbar-table').bootstrapTable('refresh');
                });
            
                $('body').on('click', '[data-bind="toolbar-export"]', function(){
                    var that = $(this);
                    var dict = {};
                    var url = that.data('action');
                    
                    $('.toolbar .toolbar-attr').each(function(){
                        var name = $(this).attr('name');
                        var value = $(this).val();
                        if(name) dict[name] = value;
                    });
                    
                    if(dict){
                        var params = Object.keys(dict).map(function (key) {
                            return encodeURIComponent(key) + "=" + encodeURIComponent(dict[key]);
                        }).join("&");
                        
                        location.href = url + '?' + params;
                    }else{
                        location.href = url;
                    }
                    
                });
                
                $('body').on('keydown', '[data-enter="toolbar-search"]', function(event){
                      if(event.keyCode == 13){
                        $('[data-bind="toolbar-search"]').trigger("click");
                      }
                });
                
                $('body').on('change', '[data-change="toolbar-search"]', function(event){
                      $('[data-bind="toolbar-search"]').trigger("click");
                });
        	});
JS;
        $view->registerJs($js, $view::POS_END);
    }
    
}