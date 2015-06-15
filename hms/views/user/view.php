<?php
$this->setPageTitle('View Users | ID : ' . $model->id);
$this->breadcrumbs = array(
    'Users' => array('index'),
    $model->name,
);
?>

<?php
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create', array('type' => $type)), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl($type), 'linkOptions' => array()),
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id,'type'=>$type,)), 'linkOptions' => array()),
        //array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
)));
$this->endWidget();
?>
<div class='printableArea'>

<?php
//$this->widget('bootstrap.widgets.TbDetailView', array(
//    'data' => $model,
//    'attributes' => array(
//        'id',
//        'username',
//        'email',
//        'password',
//        'user_position_id',
//        'code',
//        'name',
//        'city_id',
//        'address',
//        'phone',
//        'created',
//        'created_user_id',
//        'modified',
//    ),
//));
?>
</div>
<style type="text/css" media="print">
    body {visibility:hidden;}
    .printableArea{visibility:visible;} 
</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>
<!-- Button to trigger modal -->
<?php
$restrict=in_array('mms', param('menu'));
if($restrict){
    echo'<a href="#myModal" role="button" class="btn" data-toggle="modal">Launch demo modal</a>';
}
?>
<!--<a href="#myModal" role="button" class="btn" data-toggle="modal">Unlock My Profile</a>-->
 
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Masukan Password Anda.</h3>
  </div>
  <div class="modal-body">
   <form class="form-inline">

<label class="control-label" for="inputPassword">Password</label>
    <div class="controls">
      <input type="password" id="inputPassword" placeholder="Password">
    </div>

</form>
      
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
			
//                        'icon'=>'icon-plus',  
			'label'=>'masuk',
                        'url'=>Yii::app()->controller->createUrl('update', array('id' => $model->id,'type'=>$type,))
		)); ?>
  </div>
</div>


<div class="tab-pane active" id="personal">

    <table>
        <tr>
            <td width="30%" style="vertical-align: top">

                <?php
                echo $model->mediumImage;
                ?>

            </td>
            <td style="vertical-align: top;" width="70%">
                <table class="table table-striped" style="width:100%">

                    <tr>
                        <td style="text-align: left" class="span1">
                            <b>Name</b>
                        </td>
                        <td style="text-align: left;width:1px">
                            :
                        </td>
                        <td style="text-align: left" class="span4">
                           <?php  echo $model->name; ?>
                        </td>
                        <td style="text-align: left" class="span1">
                            <span class="inventory"><b>Position</b></span>
                        </td>
                        <td style="text-align: left;width:1px" class="">
                            <span class="inventory">:</span>
                        </td>                        
                        <td style="text-align: left" class="span4">
                            <span class="inventory">
                                 <?php  echo (isset($model->Roles->name)) ? $model->Roles->name : 'Super User'; ?>
                            </span>
                        </td>

                    </tr>
                    <tr>
                        <td style="text-align: left" class="span1">
                            <b>Province</b>
                        </td>
                        <td style="text-align: left;width:1px">
                            :
                        </td>
                        <td style="text-align: left" class="span4">
                             <?php  echo $model->City->Province->name; ?>
                        </td>
                        <td style="text-align: left" class="span1">
                            <span class="inventory"><b>Phone</b></span>
                        </td>
                        <td style="text-align: left;width:1px" class="">
                            <span class="inventory">:</span>
                        </td>                        
                        <td style="text-align: left" class="span4">
                            <span class="inventory">
                                      <?php echo landa()->hp($model->phone); ?>            
                            </span>
                        </td>

                    </tr>
                    <tr>
                        <td style="text-align: left" class="span1">
                            <b>City</b>
                        </td>
                        <td style="text-align: left;width:1px">
                            :
                        </td>
                        <td style="text-align: left" class="span4">
                             <?php  echo $model->City->name; ?>
                        </td>
                        <td style="text-align: left" class="span1">
                            <span class="inventory"><b>Email</b></span>&nbsp;
                        </td>
                        <td style="text-align: left;width:1px" class="">
                            <span class="inventory">:</span>&nbsp;
                        </td>                        
                        <td style="text-align: left" class="span4">
                            <span class="inventory">
                                 <?php 
                                 $model->email;
                                 
                                 ?>
                                
                            </span>&nbsp;
                        </td>

                    </tr>                     
                                          
                    <tr class="inventory">
                        <td style="text-align: left" class="span2">
                            <b>Address</b>
                        </td>
                        <td style="text-align: left;width:1px">
                            :
                        </td>
                        <td style="text-align: left" class="span4">
                             <?php echo $model->address; ?>
                        </td>
                        <td style="text-align: left" class="span2 inventory">
                          
                        </td>
                        <td style="text-align: left;width:1px" class="inventory">
                            
                        </td>                        
                        <td style="text-align: left" class="span4 inventory">
                            
                        </td>

                    </tr>  
                    
                    
                    <tr class="inventory">
                        <td style="text-align: left" class="span2" colspan="6">
                            <?php
                            
                            echo '<i>"'.$model->description.'"</i>'; ?>
                        </td>
                        

                    </tr>                     
                </table>                                           
            </td>

        </tr>
                     
       
    </table>

</div> 
