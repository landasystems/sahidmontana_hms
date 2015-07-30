<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'hotel-trans-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <style>
        .client li{
            width: 40%; float: left;
        }
    </style>

    <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>

    <div class="row-fluid">

        <div class="span12">

            <div class="box gradient invoice">

                <div class="title clearfix">

                    <h4 class="left">
                        <span><h3>Billing</h3></span>
                    </h4>
                    <div class="print">
                        <a href="#" class="tip" title="Print invoice"><span class="icon24 entypo-icon-printer"></span></a>
                    </div>
                    <div class="invoice-info">
                        <span class="number">Invoice <strong class="red">#123456</strong></span>
                        <span class="data gray"><?php echo date('d F Y') ?></span>
                        <div class="clearfix"></div>
                    </div>

                </div>
                <div class="content">
                    <div class="you span6">
                        <?php
                        $img = Yii::app()->landa->urlImg('site/', Yii::app()->session['site']['client_logo'], param('id'));
                        echo '<img src="' . $img['small'] . '" style="float:left; margin-right: 10px" class="img-polaroid"/>';
                        ?>
                        <h3><?php echo Yii::app()->session['site']['client_name'] ?></h3>
                        <ul class="unstyled">
                            <li><span class="icon12 typ-icon-arrow-right"></span></li>
                            <li><span class="icon12 typ-icon-arrow-right"></span>Website: <strong class="red">www.sahidmontana.com</strong></li>
                            <li><span class="icon12 typ-icon-arrow-right"></span>Phone: <strong class="red"><?php echo Yii::app()->session['site']['phone'] ?></strong></li>
                            <li><span class="icon12 typ-icon-arrow-right"></span>Fax: <strong class="red">+040 / 222-222-222</strong></li>
                        </ul>
                    </div>
                    <div class="client span6">
                        <h3>Guest name</h3>
                        <ul class="unstyled">
                            <li><span class="icon12 typ-icon-arrow-right"></span>guest address</li>
                            <li><span class="icon12 typ-icon-arrow-right"></span>Room Number: <strong class="red">301</strong></li>
                            <li><span class="icon12 typ-icon-arrow-right"></span>Company: <strong class="red">PT Abadi</strong></li>
                            <li><span class="icon12 typ-icon-arrow-right"></span>Arrival: <strong class="red">04 July 2013</strong></li>
                            <li><span class="icon12 typ-icon-arrow-right"></span>Phone: <strong class="red">+040 / 444-244-244</strong></li>
                            <li><span class="icon12 typ-icon-arrow-right"></span>Departure: <strong class="red">06 July 2013</strong></li>
                            
                        </ul>
                    </div>
                    <div class="clearfix"></div>

                    <table class="responsive table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Charge</th>
                                <th>Credit</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>05/06</td>
                                <td>306 : Superior Twin</td>
                                <td>561.000</td>
                                <td>0</td>
                                <td>561.000</td>
                            </tr>
                            <tr>
                                <td>05/06</td>
                                <td>309 : Deluxe Double</td>
                                <td>700.000</td>
                                <td>0</td>
                                <td>700.000</td>
                            </tr>
                            <tr>
                                <td>05/06</td>
                                <td>315 : Deluxe Twin</td>
                                <td>561.000</td>
                                <td>0</td>
                                <td>561.000</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="total">
                        <h4>Total :<span class="red"> 1.250.000</span></h4>
                    </div>


                    <div class="clearfix"></div>

                    <div class="invoice-footer">
                        <p>Thank you for your order, you will receive <strong class="green">5%</strong> discount in next order. 
                            <span class="right marginR10">Closed By : <b><?php echo Yii::app()->user->getState('name')?></b></span>
                        </p> 
                    </div>

                </div>

            </div><!-- End .box -->

        </div>

    </div><!-- End .row-fluid -->


    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => $model->isNewRecord ? 'Create' : 'Save',
        ));
        ?>
    </div>


    <?php $this->endWidget(); ?>

</div>
