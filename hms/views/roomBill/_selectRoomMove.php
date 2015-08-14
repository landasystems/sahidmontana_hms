
<table class="selectRoom  table-hover table  table-condensed">
    <thead>
        <tr>
            <th class="span1" style="text-align:center">Number</th>
            <th class="span1">Type</th>
            <th class="span1" style="text-align:center">Floor</th>
            <th class="span1">Bed</th>            
            <th class="span2">FB Price</th>
            <th class="span2">EB Price</th>
            <th class="span2">Other Includes</th>   
            <th class="span2">Room Rate</th>
            <th style="text-align:center;width: 10px"></th>
        </tr>
    </thead>
    <tbody>                           

        <?php
        $siteConfig = SiteConfig::model()->findByPk(1);
        $settings = json_decode($siteConfig->settings, true);
        $exbed = (!empty($settings['extrabed_charge'])) ? $settings['extrabed_charge'] : 0;
        $checkbox_others_include = "";
        if ($siteConfig->others_include != "") {
            $others_include = json_decode($siteConfig->others_include);
            foreach ($others_include as $other) {
                $tuyul = ChargeAdditional::model()->findByPk($other);
                if (count($tuyul) > 0) {                    
                    $checkbox_others_include.='<label><input class="others_include ' . $tuyul->id . '" kode="' . $tuyul->id . '" style="margin:0px 5px 0px 0px" type="checkbox" name="others_include[' . $tuyul->id . ']"  value="' . $tuyul->charge . '">' . $tuyul->name . '</label>';
                }
            }
        }
        ?>

        <?php
        $filter = 't.id not in (select acca_room_schedule.room_id from acca_room_schedule where status<>"vacant" and date_schedule between "' . $start . '" and "' . $end . '")';
        if (!empty($type))
            $filter .= ' and t.room_type_id=' . $type . '';
        if (!empty($floor))
            $filter .= ' and t.floor=' . $floor . '';
        if (!empty($bed))
            $filter .= ' and t.bed="' . strtolower($bed) . '"';


        $data = Room::model()->with('RoomSchedule')->
                findAll(array('condition' => $filter));
        $return = "";
        $y = 0;

        if (!empty($data)) {
            foreach ($data as $value) {
                $rate = json_decode($value->RoomType->rate, true);
                $price = 'Min :' . landa()->rp(($rate[$roles]['min'] - 85000)) . '<br> Default : ' .
                        landa()->rp(($rate[$roles]['default'] - 85000)) . '<br> Max :' .
                        landa()->rp(($rate[$roles]['max'] - 85000));
                $y = 1;
                $room_price = $rate[$roles]['roomRate'];
                echo ' <tr class="item" id="' . $value->id . '">
                                <td class="span1" style="text-align:center">' . $value->number . '</td>
                                <td class="span1">' . ucwords($value->RoomType->name) . '</td>
                                <td class="span1" style="text-align:center">' . $value->floor . '</td>
                               <td class="span1">' . ucwords($value->bed) . '</td>
                               <td>                    
                                    <div class="input-prepend"><span class="add-on">Rp</span>
                                            <input value="' . $value->RoomType->fnb_charge . '" style="width:100px" class="fb" name="fb" disabled type="text">                                            
                                    </div>
                                </td>
                                <td>                    
                                    <div class="input-prepend"><span class="add-on">Rp</span>
                                            <input value="' . $exbed . '" style="width:100px" class="eb" name="eb" disabled type="text">                                            
                                    </div>
                                </td>
                                <td>                    
                                    ' . $checkbox_others_include . '
                                </td>
                               <td>                    
                                    <div class="input-prepend"><span class="add-on">Rp</span>
                                            <input value="' . $room_price . '" style="width:100px" class="price" name="price" disabled type="text">
                                            <input type="hidden" value="' . $value->id . '" class="room_selected" name ="room_selected" disabled />
                                    </div>
                                </td>
                         <td style="width:30px">
                         <button class="chooseRoom btn btn-small" title="" rel="tooltip" data-original-title="Choose Room"><i class="blue icon-check"></i></button>
                         </td>
                      </tr>';
            }
        } else {
            echo '<tr id="roomAddRow"><td colspan="9"> No record available</td></tr>';
        }
        ?>

        <tr id="roomAddRow" style="display:none">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
<script>
    $(".chooseRoom").on("click", function() {
        $(".info").attr('class', 'ok');
        $(".others_include").prop('disabled', true);
        $(".others_include").prop('checked', false);
        $(".eb").prop('disabled', true);
        $(".fb").prop('disabled', true);
        $(".price").prop('disabled', true);
        $(".room_selected").prop('disabled', true);
        $(this).parent().parent().attr('class', 'info');
        $(this).parent().parent().find('td:eq(4)').find('.fb').prop('disabled', false);
        $(this).parent().parent().find('td:eq(5)').find('.eb').prop('disabled', false);
        $(this).parent().parent().find('td:eq(6)').find('.others_include').prop('disabled', false);
        $(this).parent().parent().find('td:eq(7)').find('.price').prop('disabled', false);
        $(this).parent().parent().find('td:eq(7)').find('.room_selected').prop('disabled', false);
        var nomer = $(this).parent().parent().attr('id');
        $.toaster({priority : 'success',title : "Information", message : "Room " + nomer + " Selected"});
        return false;
    });
</script>