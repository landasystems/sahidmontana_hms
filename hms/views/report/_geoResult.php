<div id="printableArea">
    <table width="100%">
        <tr>
            <td  style="text-align: center" colspan="2"><h2>GEOGRAPHICAL ORIGIN REPORT</h2>
                <h4><?php echo date('d F Y', strtotime($date)); ?></h4>
                <hr></td>
        </tr>   
    </table>

    <table class="table  table">
        <thead>
            <tr> 
                <th rowspan="2" width="10%">City</th>
                <th colspan="3" width="30%">TODAY</th>
                <th colspan="3" width="30%">MTD</th>
                <th colspan="3" width="30%">YTD</th>
            </tr>

            <tr>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
<style type="text/css" media="print">
    body {visibility:hidden;}
    .printableArea{visibility:visible;position: absolute;top:0;left:0px;width: 100%;font-size:19px}
    table{width: 100%}
</style>
<script type="text/javascript">
    function printDiv()
    {
        window.print();
    }
</script>
