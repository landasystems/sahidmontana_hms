<?php
$date = explode('-', $date_from);
$date_from = date('d M Y', strtotime($date[0]));
$date_to = date('d M Y', strtotime($date[1]));
?>
<table width="100%">
    <tr>
        <td  style="text-align: center" colspan="3"><h2>EXPECTED GUEST</h2>
            <?php echo date('d/m/Y', strtotime($date[0])) . " - " . $date_to = date('d/m/Y', strtotime($date[1])); ?>
            <hr></td>
    </tr>
</table>
      
   

<table class="responsive table table-bordered" width="100%">
    <thead>
    <tr>
        <th>No</th>
        <th>Guest/Group Name</th>
        <th>Room Blocked</th>
        <th>Address / Company</th>
        <th>Contact Person</th>
        <th>Departure Date</th>
        <th>Remark</th>
    </tr>
    </thead>
    <?php
        $no=0;
    foreach($reservation as $o){
        
        $reservationdetailReport = ReservationDetail::model()->findAll(array('condition'=>'reservation_id='.$o->id));
        foreach($reservationdetailReport as $s){
         $no++;
        
        echo '
            <tr>
            <td>'.$no.'</td>
            <td>'.$o->Guest->name.'</td>
            <td>'.$s->Room->number.'</td>
            <td>'.$o->Guest->address.'</td>
            <td>'.$o->Guest->phone.'</td>
            
            <td>'.date('d M Y', strtotime($o->date_to)).'</td>
            <td></td>
           
            ';
        }
    }
    ?>
</table>