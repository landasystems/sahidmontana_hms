
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
class LandaTreeMenu extends CWidget {

    public $type;
    public $htmlOptions;
    public $submenuOptions;
    public $product_category_id;
    public $level;
    public $layout;

    //put your code here
    public function run() {
        $this->registerScript() ;   
//        if ($this->type == 'fdw') {
//            $this->registerScript();
//            echo '<div id="fdw"><nav>';
//            $this->widget('zii.widgets.CMenu', array(
//                'items' => $this->menu(),
//                'id' => $this->id,
//                'htmlOptions' => $this->htmlOptions,
//                'encodeLabel' => true));
//            echo '</nav></div>';
//        } else {
        if($this->layout=='blue-black'){
        echo '<div id="landaTreeMenu">';
        $this->widget('zii.widgets.CMenu', array(
            'items' => $this->menu(),
            'htmlOptions' => $this->htmlOptions,
            'encodeLabel' => true));
        echo '</div><br>';
        }else{
           echo '<div id="landaTreeMenu">';
        $this->widget('zii.widgets.CMenu', array(
            'items' => $this->menu(),
            'htmlOptions' => $this->htmlOptions,
            'encodeLabel' => true));
        echo '</div><br>'; 
        }
    }

    public function menu() {
//        trace($this->menu_category_id . 'aaa');
//        if (empty($this->product_category_id)){
//            return $this->buildMenu(ProductCategory::model()->listProductCategory());
//        }else{
//            $result = array();
        if (empty($this->product_category_id)) {
            $listProductCategory = ProductCategory::model()->listProductCategory();
            $result = $listProductCategory;
        } else {
            $listProductCategory = ProductCategory::model()->listProductCategory($this->product_category_id);
//            $this->level = 0;
            foreach ($listProductCategory as $o) {
                if ($this->product_category_id == $o->parent_id) {
                    $o->parent_id = 0;
                }
                
//                if ($o->level > $this->level){
//                    $this->level = $o->level;
//                }
                $result[] = $o;
            }
        }
        return $this->buildMenu($result);
//        }
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
            }
        }

        return $branch;
    }

    public function typeMenu($item) {


        $item_node = array(
            'label' => $item->name,
            'url' => ($this->level == $item->level) ? url('category/'.$item->id.'/'.$item->alias) : '#',
//                'linkOptions' => array('data-description' => ''),
        );
//        }

        return $item_node;
    }

    public function registerScript() {
//        $assetUrl = app()->assetManager->publish(Yii::getPathOfAlias('common.extensions.landa.assets'));
//        cs()->registerScriptFile($assetUrl . '/js/landaMenu.js');
//        cs()->registerCssFile($assetUrl . '/css/landaTreeMenu.css');
        app()->landa->registerAssetCss('landaTreeMenu.css');
        app()->landa->registerAssetScript('landaMenu2.js');
    }

}

?>
