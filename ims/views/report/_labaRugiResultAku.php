<?php


//trace($balance);
$tanggal = "";
$tanggal = $start . " - " . $end;
?>
<div class='printableArea'>
<table width="100%">
    <tr>
        <td  style="text-align: center" colspan="2"><h2>LAPORAN LABA/RUGI</h2>
            <?php echo $tanggal; ?>
            <hr></td>
    </tr>   
</table>


<table class="table table-bordered table-striped table" border="1">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="55%">Keterangan</th>
            <th width="20%">Saldo</th>
            <th width="20%">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php
            $no = 1;
            $idcoa = array();
            foreach($accCoa as $aa){
                $AccDet = AccCoaDet::model()->sumDet($aa->id, $start, $end);
                foreach($AccDet as $bb){
                    if($bb->sumDebet !=0){
                        echo '<tr><td>'.$no++.'</td>'
                           . '<td>'.$aa->name.'</td>'
                           . '<td>'.$bb->sumDebet.'</td>'
                           . '<td>'.'test'.'</td>'
                           .'</tr>';
                    }
                   
                   
                }
            }
            
                
            ?>
        </tr>
    </tbody>
</table>
</div>
<style type="text/css" media="print">
body {visibility:hidden;}
.printableArea{visibility:visible;position: absolute;top:0;left:0px;width: 100%;font-size:17px}
table{width: 100%}
</style>
<script type="text/javascript">
function printDiv()
{

window.print();

}
</script>
