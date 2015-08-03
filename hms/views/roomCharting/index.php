<?php
$siteConfig = SiteConfig::model()->findByPk(1);
$this->setPageTitle('Room Charting');

?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'room-view-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>

<div class="well">
    <div class="row-fluid">
        <div class="span12">
            <?php
            $type = CHtml::listData(RoomType::model()->findAll(array('condition' => 'is_package=0')), 'id', 'name');
            $bed = Room::model()->getBedList();
            ?>
            Tahun : <?php echo CHtml::dropDownList('year', (!empty($_POST['year'])) ? $_POST['year'] : date('Y'), landa()->yearly(date('Y') - 1), array('empty' => 'Please Choose')); ?> &nbsp;&nbsp;
            Bulan : <?php echo CHtml::dropDownList('month', (!empty($_POST['month'])) ? $_POST['month'] : date('n'), landa()->monthly(), array('empty' => 'Please Choose')); ?>&nbsp;&nbsp;
            Room Type : <?php echo CHtml::dropDownList('type', (!empty($_POST['type'])) ? $_POST['type'] : '', $type, array('empty' => 'Please Choose')); ?>&nbsp;&nbsp;
            Bed : <?php echo CHtml::dropDownList('bed', (!empty($_POST['bed'])) ? $_POST['bed'] : '', $bed, array('empty' => 'Please Choose')); ?>&nbsp;&nbsp;

            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'type' => 'primary',
                'icon' => 'search white',
                'label' => 'View',
                'id' => 'btnResults'
            ));
            ?>
        </div>         
    </div>
</div>
<hr/>
<div id="results">

</div>
<?php $this->endWidget(); ?>

<div>Status Color Information : </div>
<span class="label label-success"> Available </span>
<span class="label label-reservation" style="background: #4AC3FF"> Reservation (Unconfirmed) </span>
<span class="label label-info"> Reservation (Confirmed) </span>
<span class="label label-important"> Occupied </span>
<span class="label label-warning"> Vacant & Dirty </span>
<span class="label">House Use </span>
<span class="label compliment">Compliment</span>
<span class="label label-inverse"> Out of Order </span>


<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Room Charting Actions</h3>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>        
    </div>
</div>
<style>
    .fixed {
        position: fixed; 
        top: 0; 
        height: 0px; 
    }
</style>
<script>
    function pagination(page, month, year, type, bed) {
        $.ajax({
            url: '<?php echo url('roomCharting/dataResult') ?>',
            data: 'page=' + page + '&month=' + month + '&year=' + year + '&type=' + type + '&bed=' + bed,
            type: 'post',
            success: function (data) {
                $("#results").html(data);
                fixed();
            }
        });
    }
    function fixed() {
        var tableOffset = $("#table-1").offset().top;
        var $header = $("#table-1 > thead").clone();
        var $fixedHeader = $("#header-fixed").append($header);

        $(window).bind("scroll", function () {
            var offset = $(this).scrollTop();

            if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
                $fixedHeader.show();
            }
            else if (offset < tableOffset) {
                $fixedHeader.hide();
            }
        });
    }

    $("body").on("click", ".tombol", function () {
        var status = ($(this).attr("status"));
        if (status != "") {
            var tanggal = ($(this).attr("date"));
            var d = new Date();
            var tgl = d.getMonth() + 1 + "/" + d.getDate() + "/" + d.getFullYear();
            var reservation = ($(this).attr("reservation"));
            var registration = ($(this).attr("registration"));
            var number = ($(this).attr("number"));
            var link = '';
            var tabel = '';

            if (status == 'compliment-past' || status == 'compliment' || status == 'reservation' || status == 'occupied' || status == 'reserved' || status == 'reservation-past' || status == 'occupied-past' || status == 'reserved-past') {
                tabel = '<table class="table">';
                tabel += '<tr><td class="span3">Guest Name</td><td>:</td><td class="span9">' + ($(this).attr("guest")) + '</td></tr>';
                tabel += '<tr><td>Guest Phone</td><td>:</td><td>' + ($(this).attr("guestPhone")) + '</td></tr>';
                tabel += '<tr><td>Guest DP</td><td>:</td><td>' + ($(this).attr("guestDP")) + '</td></tr>';
                tabel += '<tr><td>Check In</td><td>:</td><td>' + ($(this).attr("dateFrom")) + '</td></tr>';
                tabel += '<tr><td>Check Out</td><td>:</td><td>' + ($(this).attr("dateTo")) + '</td></tr>';
                tabel += '<tr><td>Rooms</td><td>:</td><td>' + ($(this).attr("rooms")) + '</td></tr>';
                tabel += '<tr><td>Total Rooms</td><td>:</td><td>' + ($(this).attr("total_rooms")) + '</td></tr>';
                tabel += '<tr><td>Remarks</td><td>:</td><td>' + ($(this).attr("remarks")) + '</td></tr>';
                tabel += '</table>';
            }


            link += '<ul class="nav nav-pills nav-stacked">';
            if (status == "vacant") {
                link += '<li><a target="_blank" href="reservation/create.html?roomNumber=' + number + '&date=' + tanggal + '">Reservation</a></li>';
                if (tgl == tanggal) {
                    link += '<li><a target="_blank" href="registration/create.html?roomNumber=' + number + '&date=' + tanggal + '">Registration</a></li>';
                }
            } else if (status == "vacantinspect") {
                link += '<li><a target="_blank" href="reservation/create.html?roomNumber=' + number + '&date=' + tanggal + '">Reservation</a></li>';
                if (tgl == tanggal) {
                    link += '<li><a target="_blank" href="registration/create.html?roomNumber=' + number + '&date=' + tanggal + '">Registration</a></li>';
                }
            } else if (status == "reservation") {
                link += '<li><a target="_blank" href="registration/create.html?reservationId=' + reservation + '">Registration</a></li>';
                link += '<li><a target="_blank" href="reservation/update/' + reservation + '.html?changeStatus=reserved">Confirm Reservation</a></li>';
                link += '<li><a target="_blank" href="reservation/update/' + reservation + '.html?changeStatus=cancel">Change Status Reservation</a></li>';
            } else if (status == "reserved") {
                link += '<li><a target="_blank" href="registration/create.html?reservationId=' + reservation + '">Registration</a></li>';
                link += '<li><a target="_blank" href="reservation/update/' + reservation + '.html?changeStatus=cancel">Change Status Reservation</a></li>';
            } else if (status == "occupied") {
                link += '<li><a target="_blank" href="roomBill/extend.html?roomNumber=' + number + '">Extend</a></li>';
                link += '<li><a target="_blank" href="roomBill/move.html?roomNumber=' + number + '">Move Room</a></li>';
                link += '<li><a target="_blank" href="bill/create.html?roomNumber=' + number + '">Guest Bill / Checkout</a></li>';
            } else if (status == "dirty") {

            } else if (status == "house use") {
                link += '<li><a target="_blank" href="roomBill/extend.html?roomNumber=' + number + '">Extend</a></li>';

                link += '<li><a target="_blank" href="roomBill/move.html?roomNumber=' + number + '">Move Room</a></li>';
                link += '<li><a target="_blank" href="bill/create.html?roomNumber=' + number + '">Guest Bill / Checkout</a></li>';
            } else if (status == "out of order") {
            }
            link += '</ul>'
            var data = '';
            if (status == 'reservation' || status == 'occupied' || status == 'vacant' || status == 'vacantinspect' || status == 'reserved') {
                data = tabel + link;
            } else {
                data = tabel;
            }
            $(".modal-body").html(data);
            $('#myModal').modal('show');
        }
        return false;
    });

    $('#btnResults').click(function () {
        $.ajax({
            url: '<?php echo url('roomCharting/dataResult') ?>',
            data: $('form').serialize(),
            type: 'post',
            success: function (data) {
                $("#results").html(data);
                fixed();
            }
        });
    });
</script>

