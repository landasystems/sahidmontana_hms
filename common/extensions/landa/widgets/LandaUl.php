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
class LandaUl extends CWidget {

    public $type;
    public $id;
    public $htmlOptions;
    public $submenuOptions;
    public $itemCssClass;
    public $menu_category_id;
    public $linkOptions;
    public $linkOptionsParent = false; //is link option just for parent
    public $separator;
    public $model;
    public $parent_id;

    //put your code here
    public function run() {

        $this->widget('zii.widgets.CMenu', array(
            'items' => $this->menu(),
            'itemCssClass' => $this->itemCssClass,
            'id' => $this->id,
            'htmlOptions' => $this->htmlOptions,
            'encodeLabel' => false));
    }

    public function menu() {
        return $this->buildMenu($this->model, $this->parent_id);
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
                $branch[] = array_merge(array('submenuOptions' => array('class' => $this->submenuOptions['class'])), $node);
                ;
            }
        }

        return $branch;
    }

    public function typeMenu($item) {
//            $param = json_decode($item->param, true);
        $sparator = (isset($this->separator)) ? ' | ' : '';
        $item_node = array(
            'label' => $item->name . $sparator,
            'url' => $item->link,
//            'linkOptions' => ($this->linkOptionsParent) ? (($item->link == '#' && $item->type == 'url') ? $this->linkOptions : '') : $this->linkOptions,
        );

        return $item_node;
    }

    public function registerScript() {
//        $assetUrl = app()->assetManager->publish(Yii::getPathOfAlias('ext.landa.assets'));
//        cs()->registerScriptFile($assetUrl . '/js/landaMenu.js');
//        cs()->registerCssFile($assetUrl . '/css/landaMenu.css');
//        app()->landa->registerAssetCss('landaMenu.css');
//        app()->landa->registerAssetScript('landaMenu.js');
    }

}

?>
