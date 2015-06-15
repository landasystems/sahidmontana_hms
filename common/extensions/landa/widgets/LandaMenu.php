<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticleAccordion
 *
 * @author landa
 */
class LandaMenu extends CWidget {

    public $type;
    public $id;
    public $htmlOptions;
    public $submenuOptions;
    public $itemCssClass;
    public $menu_category_id;
    public $linkOptions;
    public $linkOptionsParent = false; //is link option just for parent
    public $separator;

    //put your code here
    public function run() {
        if ($this->type == 'fdw') {
            $this->registerScript();
            echo '<div id="fdw"><nav>';
            $this->widget('zii.widgets.CMenu', array(
                'items' => $this->menu(),
                'id' => $this->id,
                'htmlOptions' => $this->htmlOptions,
                'encodeLabel' => true));
            echo '</nav></div>';
        } else {
            $this->widget('zii.widgets.CMenu', array(
                'items' => $this->menu(),
                'itemCssClass' => $this->itemCssClass,
                'id' => $this->id,
                'htmlOptions' => $this->htmlOptions,
                'encodeLabel' => true));
        }
    }

    public function menu() {
//        trace($this->menu_category_id . 'aaa');
        if (empty($this->menu_category_id)) {
            return $this->buildMenu(Menu::model()->listMenu());
        } else {
            $result = array();
            $listMenu = Menu::model()->listMenu();
            foreach ($listMenu as $o) {
                if ($this->menu_category_id == $o->menu_category_id) {
                    $result[] = $o;
                }
            }
            return $this->buildMenu($result);
        }
    }

    function buildMenu(array $items, $parentId = 0) {
        //trace($items);
        $branch = array();

        foreach ($items as $item) {
            $node = $this->typeMenu($item);

            if ($item->parent_id == $parentId) {
                $children = $this->buildMenu($items, $item->id);
                if ($children) {
                    $node['items'] = $children;
                }
                
                if ($node['url']==$_SERVER['REQUEST_URI']){
                    $active = array('class'=>'active');
                }else{
                    $active = array();
                }
                $branch[] = array_merge(array('itemOptions' => $active,'submenuOptions' => array('class' => $this->submenuOptions['class'])), $node);
            }
        }

        return $branch;
    }

    public function typeMenu($item) {
        if ($item->type == 'url') {
            $param = json_decode($item->param, true);
            $sparator = (isset($this->separator)) ? ' | ' : '';
            $item_node = array(
                'label' => $item->name . $sparator,
                'url' => $item->link,
                'linkOptions' => ($this->linkOptionsParent) ? (($item->link == '#' && $item->type == 'url') ? $this->linkOptions : '') : $this->linkOptions,
            );

            if (isset($param['target '])) {
                if ($param['target '] == '_blank') {
                    $item_node = array_merge($item_node, array('linkOptions ' => array('target' => '_blank')));
                }
            }
        } elseif (($item->type == 'home')) {
            $sparator = (isset($this->separator)) ? ' | ' : '';
            $item_node = array(
                'label' => $item->name .$sparator,
                'url' => (isset(user()->id)) ? url('dashboard') : url('index'),
//                'linkOptions' => $this->linkOptions,
            );                   
        } else {
            $sparator = (isset($this->separator)) ? ' | ' : '';
            $item_node = array(
                'label' => $item->name . $sparator,
                'url' => url($item->alias),
//              array(
                                    'class' => 'active',
//                                )
                'linkOptions' => ($this->linkOptionsParent) ? (($item->link == '#' && $item->type == 'url') ? $this->linkOptions : '') : $this->linkOptions,
            );
        }

        return $item_node;
    }

    public function registerScript() {
//        $assetUrl = app()->assetManager->publish(Yii::getPathOfAlias('ext.landa.assets'));
//        cs()->registerScriptFile($assetUrl . '/js/landaMenu.js');
//        cs()->registerCssFile($assetUrl . '/css/landaMenu.css');
        app()->landa->registerAssetCss('landaMenu.css');
        app()->landa->registerAssetScript('landaMenu.js');
    }

}

?>
