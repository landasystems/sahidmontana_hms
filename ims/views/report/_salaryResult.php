<?php
$a = explode('-', $created);
$start = date('d M Y', strtotime($a[0]));
$end = (isset($a[1])) ? date('d/m/Y', strtotime($a[1])) . " 23:59:59" : date('d/m/Y');
?>
<a class="btn" href="<?php echo Yii::app()->controller->createUrl('report/GenerateExcelSalary?created='.str_replace(" ", "" ,$created)) ?>">
    <i class="entypo-icon-list"></i>Export to Excel</a>
<button onclick="js:printDiv();return false;" class="btn btn-info"><span class="icon-print"></span>Print this report</button>
<hr>
<div class='printableArea'>
<table style="width: 1000px">
    <tr>
        <td  style="text-align: center" colspan="3"><h2>Laporan Gaji Sudah Terbayar</h2>
            <?php echo date('d/m/Y', strtotime($a[0])) . " - " . $end; ?>
            <hr></td>
    </tr>
</table>



<table class="table table-bordered table-condensed" style="width: 1000px">
            <thead>
                <tr>
                    <th colspan="9"><center>Penggajian periode <?php echo (isset($model->created)) ? date('d M Y', strtotime($model->created)) :'' ?></center></th>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th class="span3" style="text-align:center"> Proses</th> 
                    <th class="span2" style="text-align:center">Nopot</th> 
                    <th class="span2" style="text-align:center">Qty</th> 
                    <th class="span3" style="text-align:center">Rp.</th> 
                    <th class="span3" style="text-align:center">Jumlah</th>                                                       
                    <th class="span3" style="text-align:center">Tanggal Selesai</th>                                                          


                    <th class="span1" style="text-align:center">Hilang</th>                                                          
                    <th class="span3" style="text-align:center">Denda</th>                                                          
                    <th class="span3" style="text-align:center">Total</th>                                                            
                </tr>
            </thead>
            <?php
            if (!empty($salary)) {
                $subtotalprint=0;
                $totalfineprint= 0 ;
                $totalsalaryprint= 0;
                $kasbonprint=0;
                $no = 1;
                $group = '';
                $groups = '';
                $detail = SalaryDet::model()->findAll(array());
                //mencari total dulu
                $totalCharge = array();
                foreach ($detail as $value) {
                    if (isset($totalCharge[$value->Workorderprocess->start_user_id]))
                        $totalCharge[$value->Workorderprocess->start_user_id] += $value->Workorderprocess->charge;
                    else
                        $totalCharge[$value->Workorderprocess->start_user_id] = $value->Workorderprocess->charge;
                }
                //for edit form

                foreach ($detail as $value) {
                    if ($value->Workorderprocess->is_payment == 1) {
                        $checked = 'checked="Checked"';
                        $payable = '<span class="label label-info">Yes</span>';
                        $color = 'info';
                        $class = 'ok';
                    } else {
                        $checked = 'checked="Checked"';
                        $payable = '<span class="label label-important">No</span>';
                        $color = 'error';
                        $class = 'ok';
                    }
                    if ($group != $value->Workorderprocess->start_user_id) {

                        echo '<tr>
                                    <td colspan="8"><b>Pengerja :' . $value->Workorderprocess->StartUser->name . '</b></td>
                                    <td colspan=""><b>' . landa()->rp($totalCharge[$value->Workorderprocess->start_user_id]) . '</td>
                                </tr>
                                ';

                        $group = $value->Workorderprocess->start_user_id;
                    }
                    echo '<tr class="' . $color . '">';
                    echo '<input type="hidden" class="charge_' . $class . ' charge" name="detCharge[]" value="' . $value->Workorderprocess->charge . '" />';
                    echo '<input type="hidden" class="loss_charge_' . $class . ' loss_charge" name="detLossCharge[]" value="' . $value->Workorderprocess->loss_charge . '" />';

                    echo '<td>' . ucwords($value->Workorderprocess->Process->name) . '</td>';
                    echo '<td>' . $value->Workorderprocess->NOPOT->code . '</td>';
                    echo '<td>' . $value->Workorderprocess->start_qty . '</td>';
                    echo '<td>' . landa()->rp($value->Workorderprocess->Process->charge) . '</td>';
                    echo '<td style="text-align:center">' . landa()->rp($value->Workorderprocess->Process->charge * ($value->Workorderprocess->start_qty)) . '</td>';
                    echo '<td>' . date('d-M-Y', strtotime($value->Workorderprocess->time_end)) . '</td>';

                    echo '<td style="text-align:center">' . $value->Workorderprocess->loss_qty . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($value->Workorderprocess->loss_charge) . '</td>';
                    echo '<td style="text-align:right">' . landa()->rp($value->Workorderprocess->charge) . '</td>';
                    echo '</tr>';

                    $subtotalprint += $value->Workorderprocess->charge;
                    $totalfineprint += $value->Workorderprocess->loss_charge;
                    $totalsalaryprint += $value->Workorderprocess->charge - $value->Workorderprocess->loss_charge;
                    $kasbonprint = $value->Salary->other;
                    $no++;
                }
            }else{
                echo'<tr><td colspan="9">Data Kosong.</td></tr>';
            }
            ?>
            <tr>
            <td colspan="8" style="text-align: right;padding-right: 15px"><b>Sub Total : </b></td>
            <td style="text-align:right">   
                <span class="subtotal"><?php echo (isset($subtotalprint)) ? landa()->rp($subtotalprint) : ''; ?></span>
                
            </td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: right;padding-right: 15px"><b>Total Loss Charge : </b></td>
            <td style="text-align:right"> 
                <span class="Salary_total_loss_charge"><?php echo (isset($totalfineprint)) ? landa()->rp($totalfineprint) : ''; ?></span>
                
            </td>        
        </tr>
<!--        <tr class="">
            <td colspan="10" style="text-align: right;padding-right: 15px"><b>Kasbon  : </b></td>
            <td style="text-align: right">
                <div class="input-prepend">
                    <span class="add-on">Rp</span>
                    <input name="Salary[other]" style="width:110px;direction: rtl" id="Salary_other" type="text" value="<?php // echo $kasbon; ?>">
                </div>
            </td>
        </tr>-->
        <tr>
            <td colspan="8" style="text-align: right;padding-right: 15px"><b>Total Salary : </b></td>
            <td style="text-align:right">  
                <span class="Salary_total"> <?php echo (isset($totalsalaryprint)) ? landa()->rp($totalsalaryprint) :''; ?> </span>                
               
            </td>
        </tr>
        </table>
       
</div>
<style type="text/css" media="print">
    body {visibility:hidden;}
     .printableArea{visibility:visible;margin: 0px;padding: 0px;margin-left:-224px;padding-right: 120px;position: relative;top: -570px} 
    
</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>