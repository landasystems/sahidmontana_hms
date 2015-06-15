<?php
$this->setPageTitle('Online Helper - Table Of Content');
$this->breadcrumbs = array(
    'Online Helper',
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
            echo '<li><a href="' . url('tools/viewTOC/' . $m->id) . '" >' . $m->title . '</a></li>';
        }
        ?>
    </ul>


</div>