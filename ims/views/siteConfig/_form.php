<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'site-config-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>

        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#site">Site</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Format Print Out<b class="caret"></b></a>
                <ul class="dropdown-menu">

                    <li><a style="padding: 5px 2px" href="#reportCashIn">Kas Masuk</a></li>
                    <li><a style="padding: 5px 2px" href="#reportCashOut">Kas Keluar</a></li>
                    <li><a style="padding: 5px 2px" href="#reportJurnal">Jurnal</a></li>
                </ul>
            </li>   
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="site">
                <?php echo $form->fileFieldRow($model, 'client_logo', array('class' => 'span5')); ?>
                <div class="control-group ">
                    <label class="control-label">Tgl Mulai Applikasi</label>
                    <div class="controls">
                        <?php
                        if ($model->date_system != "0000-00-00") {
                            $dateSystem = $model->date_system;
                        } else {
                            $dateSystem = date("Y-m-d");
                        }
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name' => 'SiteConfig[date_system]',
                            'value' => $dateSystem,
                            'options' => array(
                                'showAnim' => 'fold',
                                'changeMonth' => 'true',
                                'changeYear' => 'true',
                                'dateFormat' => 'yy-mm-dd'
                            ),
                            'htmlOptions' => array(
                                'style' => 'height:20px;',
                                'id' => 'acccoa',
                                'class' => 'span2',
                            ),
                        ));
                        ?>
                    </div>        
                </div>


            </div>

            <div class="tab-pane" id="reportCashIn">   
                <h3>Layout Report Kas Masuk</h3><hr>
                <?php
                echo $form->ckEditorRow(
                        $model, 'report_cash_in', array(
                    'options' => array(
                        'fullpage' => 'js:true',
                        'resize_maxWidth' => '1007',
                        'resize_minWidth' => '320'
                    ), 'label' => false,
                        )
                );
                ?>  
                <div class="well">
                    <ul>
                        <li>Design Report Cash In. Gunakan format berikut untuk men-generate sebuah field.</li>
                        <li><b>{cash_in}</b>  : Mengembalikan nomor transaksi</li>
                        <li><b>{date}</b> : Mengembalikan tanggal transaksi</li>  
                        <li><b>{no_approval}</b>  : Mengembalikan nomor approve</li>
                        <li><b>{date_approval}</b>  : Mengembalikan tanggal approve</li>
                        <li><b>{account}</b> : Mengembalikan account kas/bank</li> 
                        <li><b>{detail_cash}</b> : Mengembalikan daftar transaksi</li> 
                        <li><b>{managerName}</b> : Mengembalikan nama manager</li>
                        <li><b>{managerApprove}</b> : Mengembalikan tanggal acc oleh manager</li>
                        <li><b>{adminName}</b> : Mengembalikan nama admin</li>
                        <li><b>{adminApprove}</b> : Mengembalikan tanggal acc oleh admin</li>
                        <li><b>{tellerName}</b> : Mengembalikan nama kasir</li>
                        <li><b>{tellerApprove}</b> : Mengembalikan tanggal input oleh kasir</li>
                    </ul>
                </div>                
            </div>
            <div class="tab-pane" id="reportCashOut">   
                <h3>Kas Keluar</h3><hr>
                <?php
                echo $form->ckEditorRow(
                        $model, 'report_cash_out', array(
                    'options' => array(
                        'fullpage' => 'js:true',
                        'resize_maxWidth' => '1007',
                        'resize_minWidth' => '320'
                    ), 'label' => false,
                        )
                );
                ?>  
                <div class="well">
                    <ul>
                        <li>Design Report Cash Out. Gunakan format berikut untuk men-generate sebuah field.</li>
                        <li><b>{cash_out}</b>  : Mengembalikan nomor transaksi</li>
                        <li><b>{date}</b> : Mengembalikan tanggal transaksi</li>  
                        <li><b>{no_approval}</b>  : Mengembalikan nomor approve</li>
                        <li><b>{date_approval}</b>  : Mengembalikan tanggal approve</li>
                        <li><b>{account}</b> : Mengembalikan account kas/bank</li>  
                        <li><b>{detail_cash}</b> : Mengembalikan daftar transaksi</li> 
                        <li><b>{managerName}</b> : Mengembalikan nama manager</li>
                        <li><b>{managerApprove}</b> : Mengembalikan tanggal acc oleh manager</li>
                        <li><b>{adminName}</b> : Mengembalikan nama admin</li>
                        <li><b>{adminApprove}</b> : Mengembalikan tanggal acc oleh admin</li>
                        <li><b>{tellerName}</b> : Mengembalikan nama kasir</li>
                        <li><b>{tellerApprove}</b> : Mengembalikan tanggal input oleh kasir</li>
                    </ul>
                </div>                
            </div>
            <div class="tab-pane" id="reportJurnal">   
                <h3>Jurnal</h3><hr>
                <?php
                echo $form->ckEditorRow(
                        $model, 'report_jurnal', array(
                    'options' => array(
                        'fullpage' => 'js:true',
                        'resize_maxWidth' => '1007',
                        'resize_minWidth' => '320'
                    ), 'label' => false,
                        )
                );
                ?>  
                <div class="well">
                    <ul>
                        <li>Design Report Jurnal. Gunakan format berikut untuk men-generate sebuah field.</li>
                        <li><b>{jurnal}</b>  : Mengembalikan nomor transaksi</li>
                        <li><b>{date}</b> : Mengembalikan tanggal transaksi</li>   
                        <li><b>{no_approval}</b>  : Mengembalikan nomor approve</li>
                        <li><b>{date_approval}</b>  : Mengembalikan tanggal approve</li>
                        <li><b>{detail_cash}</b> : Mengembalikan daftar transaksi</li>
                        <li><b>{managerName}</b> : Mengembalikan nama manager</li>
                        <li><b>{managerApprove}</b> : Mengembalikan tanggal acc oleh manager</li>
                        <li><b>{adminName}</b> : Mengembalikan nama admin</li>
                        <li><b>{adminApprove}</b> : Mengembalikan tanggal acc oleh admin</li>
                        <li><b>{tellerName}</b> : Mengembalikan nama kasir</li>
                        <li><b>{tellerApprove}</b> : Mengembalikan tanggal input oleh kasir</li>
                    </ul>
                </div>                
            </div>

        </div>


        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'label' => $model->isNewRecord ? 'Tambah' : 'Simpan',
            ));
            ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
