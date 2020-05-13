<?php
namespace components\widgets\tree;

use yii\helpers\Html;
use yii\base\Widget;

class Tree extends Widget{

    /**
     * @var array
     *  id
     *  name
     *  icon
     *  active
     *  extra
     *  items
     *      id
     *      name
     *      icon
     *      active
     *      extra
     *      items
     */
    public $items = [];

    public function init(){
        parent::init();

    }

    public function run(){
        parent::run();

        $this->renderWidget();
    }

    public function renderWidget(){
        $input = Html::tag('div',
            Html::tag('ul', $this->renderTree($this->items), [])
            , ['class' => 'tree']);

        $view = $this->getView();
        TreeAsset::register($view);

        echo $input;
    }

    private function renderTree($items, $result = ''){
        if(!empty($items)){
            foreach($items as $key => $item){
                if(!empty($item['items'])){
                    $tmp = '<li><span><i class="{icon}"></i> {label}</span> {extra} <ul style="{style}">{items}</ul></li>';
                    $replace = [
                        '{label}' => $item['name'],
                        '{icon}'  => isset($item['active']) && $item['active'] === true ? 'pli-remove' : 'pli-add',
                        '{style}' => isset($item['active']) && $item['active'] === true ? '' : 'display:none;',
                        '{extra}' => $item['extra'] ?? '',
                        '{items}' => $this->renderTree($item['items']),
                    ];
                    $result .= strtr($tmp, $replace);
                }else{
                    $tmp = '<li><span><i class="{icon}"></i> {label}</span> {extra}</li>';
                    $replace = [
                        '{label}' => $item['name'],
                        '{icon}'  => $item['icon'] ?? '',
                        '{extra}' => $item['extra'] ?? '',
                    ];
                    $result .= strtr($tmp, $replace);
                }
            }
        }

        return $result;
    }

}