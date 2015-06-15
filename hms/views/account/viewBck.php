<?php
$this->setPageTitle('Berhasil');
$this->breadcrumbs=array(
	'Accounts'=>array('index'),
);
?>

<div class="label label-info">
    Database berhasil terbackup! 
</div>
<br/>
<a href="<?php echo url('account/bck')?>">Backup Lagi</a>