<?php
$this->setPageTitle('Online Help :: '.$model->title);
//trace($_GET['alias']);
$this->breadcrumbs = array(
//    'Articles' => array('index'),
    $model->title,
);
?>

<div class="row-fluid">
    <div class="span9">
        <?php echo $model->content; ?>
    </div>
    <div class="span3">
        <div class="page-header">
            <h4>Table of Contents</h4>
        </div>
        <?php
        $this->renderPartial('viewListTOC', array(
            'model' => LandaArticle::model()->findAll(array('condition' => 'article_category_id=50', 'order' => 'title')),
        ));
        ?>
    </div>
</div>


