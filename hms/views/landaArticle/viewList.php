<?php
$this->setPageTitle('Article Category');
$this->breadcrumbs = array(
    'Articles' => array('index'),
);
?>
<div class="well">


    <ul class="nav nav-list">
        <?php
//        foreach ($modelCategory as $m) {
//            echo '<li><a href="' . url('article/list/' . $m->id . '/' . $m->alias) . '">' . $m->name . '</a></li>';
//        }
        ?>
        <?php
        foreach ($model as $m) {
            echo '<li><a href="' . url('landaArticle/viewFull/' . $m->id) . '" >' . $m->title . '</a></li>';
        }
        ?>
    </ul>


</div>