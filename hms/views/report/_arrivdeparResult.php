<?php
$date = explode('-', $date_from);
$date_from = date('d M Y', strtotime($date[0]));
$date_to = date('d M Y', strtotime($date[1]));
?>
<table width="100%">
    <tr>
        <td  style="text-align: center" colspan="3"><h2>ARRIVAL / DEPARTURE</h2>
            <?php echo date('d/m/Y', strtotime($date[0])) . " - " . $date_to = date('d/m/Y', strtotime($date[1])); ?>
            <hr></td>
    </tr>
</table>
<div class="row-fluid">
    <div class="span6">
        <table class="responsive table table-bordered" width="100%">
            <thead>
            <tr>
                    <td colspan="6" ><center>ARRIVAL</center></td>
                </tr>
            <tr>
                <th>No</th>
                <th>Room</th>
                <th>Number of Room</th>
                <th>Name / Group</th>
                <th>WI / RSV</th>
                <th>C/I Time</th>
            </tr>
            </thead>
            <?php
              $no=0;
    foreach($reservation as $o){
        
        $reservationdetailReport = ReservationDetail::model()->findAll(array('condition'=>'reservation_id='.$o->id));
        foreach($reservationdetailReport as $s){
         $no++;
           echo' <tr>
                <td>'.$no.'</td>
                <td>'.$s->Room->RoomType->name.'</td>
                <td>'.$s->Room->number.'</td>
                <td>'.$o->Guest->name.'</td>
                <td>Guest</td>
                <td>'.date('d-m-Y', strtotime($o->date_from)).'</td>
            </tr>';
    }
    
        }
                ?>
        </table>
    </div>
    <div class="span6">
        <table class="responsive table table-bordered" width="100%">
            <thead>
                <tr>
                    <td colspan="6"><center>DEPARTURE</center></td>
                </tr>
            <tr>
                <th>No</th>
                <th>Room</th>
                <th>Number of Room</th>
                <th>Name / Group</th>
                <th>WI / RSV</th>
                <th>C/I Time</th>
            </tr>
            </thead>
            <?php
     $no=0;
    foreach($registration as $o){
        
        $registrationdetailReport = RegistrationDetail::model()->findAll(array('condition'=>'registration_id='.$o->id));
        foreach($registrationdetailReport as $s){
           $no++;
            echo'<tr>
                <td>'.$no.'</td>
                <td>'.$s->Room->RoomType->name.'</td>
                <td>'.$s->Room->number.'</td>
                <td>'.$o->Guest->name.'</td>
                <td>Guest</td>
                <td>'.date('d-m-Y', strtotime($o->date_to)).'</td>
            </tr>';
        }
    }
    ?>
        </table>
    </div>
</div>