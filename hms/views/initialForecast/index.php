<?php
$this->setPageTitle('Initial Forecasts');
$this->breadcrumbs = array(
    'Initial Forecasts',
);
?>

<div class="form">
    <?php
    $siteConfig = SiteConfig::model()->findByPk(1);
    $foodAcc = 2;
    $beverageAcc = 3;
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'forecast-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'vertival',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset>      
        <div class="alert alert-info">
            <b>Description</b><br>
            Initial Forecast adalah sebuah tool pada aplikasi ini untuk membuat start value pada DSR dan juga FNB Analysis.<br> 
            Sehingga DSR dan FNB Analysis diharapkan akan bisa langsung digunakan dan sesuai dengan manualnya.<br><br>
            <b>Cara Kerja</b><br>
            Selain Last Month, semua data Inisial Forecast hanya digunakan untuk sekali NA, karena untuk NA selanjutnya akan mengambil data NA sebelumnya. 
            Sehingga Initial Forecast harus di 0 kan kembali.<br>
            Sedangkan cara kerja Initial Forecast adalah : <span class="label label-warning"> NA today + (NA yesterday or NA last Month) + Initial Forecash.</span><br>
            Dengan cara kerja diatas, maka untuk sekali NA, Initial forecast sudah tidak bisa digunakan lagi. karena akan terjadi
            penambahan sebanyak 2 kali pada NA selanjutnya.<br><br>
            <b>Penggunaan Initial Forecast</b><br>
            1. Bersihkan semua data NA.<br>
            2. Inputkan data Initial forecast, baik MONTH TO DATE, YEAR TO DATE maupun LAST MONTH.<br>
            3. Nilai yang inputkan adalah nilai NET, yakni nilai yang sudah dikurangi tax dan services.<br>
            4. Lakukan NA.<br>
            5. Bersihkan kembali data Initial Forecast kecuali LAST MONTH. Karena initial LAST MONTH akan digunakan selama 1 bulan ini.
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="text-align: center;vertical-align: middle" rowspan="2">NAME</th>            
                    <th style="text-align: center;vertical-align: middle" rowspan="2">MONTH TO DATE</th>
                    <th style="text-align: center;vertical-align: middle" rowspan="2">YEAR TO DATE</th>
                    <th style="text-align: left;vertical-align: middle" colspan="31">LAST MONTH</th>                    
                </tr>
                <tr>
                    <?php
                    for ($i = 1; $i <= 31; $i++) {
                        echo '<th style="text-align: center">' . $i . '</th>';
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $dsr = json_decode($model->dsr, true);
                $statistic = json_decode($model->statistik, true);
                $food_analysis = json_decode($model->food_analysis, true);
                $food_percover = json_decode($model->food_percover, true);
                $beverage_analysis = json_decode($model->beverage_analysis, true);

                //DSR
                $account = Account::model()->findAll();
                echo '<tr><td style="background:khaki;text-align:;" colspan="34"><b>INITIAL FOR DSR</b></td></tr>';
                foreach ($account as $acc) {
                    echo '<tr>';
                    echo '<td>' . $acc->name . '</td>';
                    $monthToDate = (!empty($dsr[$acc->id]['monthToDate'])) ? $dsr[$acc->id]['monthToDate'] : 0;
                    $yearToDate = (!empty($dsr[$acc->id]['yearToDate'])) ? $dsr[$acc->id]['yearToDate'] : 0;
                    echo '<td><input style="direction: rtl;margin:0px" class="angka nol"  value="' . $monthToDate . '" type="text" name="dsr[' . $acc->id . '][monthToDate]" /></td>';
                    echo '<td><input style="direction: rtl;margin:0px" class="angka nol"  value="' . $yearToDate . '" type="text" name="dsr[' . $acc->id . '][yearToDate]" /></td>';
                    for ($i = 1; $i <= 31; $i++) {
                        $lastMonth = (isset($dsr[$acc->id]['lastMonth'][$i])) ? $dsr[$acc->id]['lastMonth'][$i] : 0;
                        echo '<td><input style="direction: rtl;margin:0px" class="angka"  value="' . $lastMonth . '" type="text" name="dsr[' . $acc->id . '][lastMonth][' . $i . ']" /></td>';
                    }
                    echo '</tr>';
                }
                
                //other forecase----------------------------------------------------------------------------------                
                echo '<tr><td style="background:khaki;text-align:;" colspan="34"><b>INITIAL FOR STATISTICT</b></td></tr>';
                $arrOtherForecast = Forecast::model()->otherForcast();
                foreach ($arrOtherForecast as $dep) {
                    if ($dep == 'percentage of double occupancy') {
                        $depName = '% of double Ocupancy';
                    } elseif ($dep == 'percentage of occupancy') {
                        $depName = '% of Ocupancy';
                    } else
                        $depName = $dep;

                    echo '<tr>';
                    echo '<td>' . ucwords($depName) . '</td>';
                    $monthToDate = (!empty($statistic[$dep]['monthToDate'])) ? $statistic[$dep]['monthToDate'] : 0;
                    $yearToDate = (!empty($statistic[$dep]['yearToDate'])) ? $statistic[$dep]['yearToDate'] : 0;
                    echo '<td><input style="direction: rtl;margin:0px"  class="angka nol" value="' . $monthToDate . '" type="text"  name="statistik[' . $dep . '][monthToDate]" /></td>';
                    echo '<td><input style="direction: rtl;margin:0px"  class="angka nol" value="' . $yearToDate . '" type="text"  name="statistik[' . $dep . '][yearToDate]" /></td>';
                    for ($i = 1; $i <= 31; $i++) {
                        $lastMonth = (isset($statistic[$dep]['lastMonth'][$i])) ? $statistic[$dep]['lastMonth'][$i] : 0;
                        echo '<td><input style="direction: rtl;margin:0px" class="angka"  value="' . $lastMonth . '" type="text" name="statistik[' . $dep . '][lastMonth][' . $i . ']" /></td>';
                    }
                    echo '</tr>';
                }


                //food analys----------------------------------------------------------------------------------                           
                echo '<tr><td style="background:khaki;text-align:;" colspan="34"><b>INITIAL FOR FOOD ANALYSIS</b></td></tr>';
                $departement = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $foodAcc, 'group' => 'charge_additional_category_id'));
                foreach ($departement as $dep) {
                    echo '<tr>';
                    echo '<td>' . ucwords($dep->ChargeAdditionalCategory->name) . '</td>';
                    $monthToDate = (!empty($food_analysis[$dep->charge_additional_category_id]['monthToDate'])) ? $food_analysis[$dep->charge_additional_category_id]['monthToDate'] : 0;
                    $yearToDate = (!empty($food_analysis[$dep->charge_additional_category_id]['yearToDate'])) ? $food_analysis[$dep->charge_additional_category_id]['yearToDate'] : 0;
                    echo '<td><input style="direction: rtl;margin:0px"  class="angka nol" value="' . $monthToDate . '" type="text"  name="food_analysis[' . $dep->charge_additional_category_id . '][monthToDate]" /></td>';
                    echo '<td><input style="direction: rtl;margin:0px"  class="angka nol" value="' . $yearToDate . '" type="text"  name="food_analysis[' . $dep->charge_additional_category_id . '][yearToDate]" /></td>';
                    for ($i = 1; $i <= 31; $i++) {
                        $lastMonth = (isset($food_analysis[$dep->charge_additional_category_id]['lastMonth'][$i])) ? $food_analysis[$dep->charge_additional_category_id]['lastMonth'][$i] : 0;
                        echo '<td><input style="direction: rtl;margin:0px" class="angka"  value="' . $lastMonth . '" type="text" name="food_analysis[' . $dep->charge_additional_category_id . '][lastMonth][' . $i . ']" /></td>';
                    }
                    echo '</tr>';
                }
                //cover forecase----------------------------------------------------------------------------------                           
                echo '<tr><td style="background:khaki;text-align:;" colspan="34"><b>INITIAL FOR FOOD COVER</b></td></tr>';
                $departement = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $foodAcc, 'group' => 'charge_additional_category_id'));
                foreach ($departement as $dep) {
                    echo '<tr>';
                    echo '<td>' . ucwords($dep->ChargeAdditionalCategory->name) . '</td>';
                    $monthToDate = (!empty($food_percover[$dep->charge_additional_category_id]['monthToDate'])) ? $food_percover[$dep->charge_additional_category_id]['monthToDate'] : 0;
                    $yearToDate = (!empty($food_percover[$dep->charge_additional_category_id]['yearToDate'])) ? $food_percover[$dep->charge_additional_category_id]['yearToDate'] : 0;
                    echo '<td><input style="direction: rtl;margin:0px"  class="angka nol" value="' . $monthToDate . '" type="text"  name="food_percover[' . $dep->charge_additional_category_id . '][monthToDate]" /></td>';
                    echo '<td><input style="direction: rtl;margin:0px"  class="angka nol" value="' . $yearToDate . '" type="text"  name="food_percover[' . $dep->charge_additional_category_id . '][yearToDate]" /></td>';
                    for ($i = 1; $i <= 31; $i++) {
                        $lastMonth = (isset($food_percover[$dep->charge_additional_category_id]['lastMonth'][$i])) ? $food_percover[$dep->charge_additional_category_id]['lastMonth'][$i] : 0;
                        echo '<td><input style="direction: rtl;margin:0px" class="angka"  value="' . $lastMonth . '" type="text" name="food_percover[' . $dep->charge_additional_category_id . '][lastMonth][' . $i . ']" /></td>';
                    }
                    echo '</tr>';
                }
                //beverages forecase----------------------------------------------------------------------------------                           
                echo '<tr><td style="background:khaki;text-align:;" colspan="34"><b>INITIAL FOR BEVERAGE ANALYSIS</b></td></tr>';
                $departement = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $beverageAcc, 'group' => 'charge_additional_category_id'));
                foreach ($departement as $dep) {
                    echo '<tr>';
                    echo '<td>' . ucwords($dep->ChargeAdditionalCategory->name) . '</td>';
                    $monthToDate = (!empty($beverage_analysis[$dep->charge_additional_category_id]['monthToDate'])) ? $beverage_analysis[$dep->charge_additional_category_id]['monthToDate'] : 0;
                    $yearToDate = (!empty($beverage_analysis[$dep->charge_additional_category_id]['yearToDate'])) ? $beverage_analysis[$dep->charge_additional_category_id]['yearToDate'] : 0;
                    echo '<td><input style="direction: rtl;margin:0px"  class="angka nol" value="' . $monthToDate . '" type="text"  name="beverage_analysis[' . $dep->charge_additional_category_id . '][monthToDate]" /></td>';
                    echo '<td><input style="direction: rtl;margin:0px"  class="angka nol" value="' . $yearToDate . '" type="text"  name="beverage_analysis[' . $dep->charge_additional_category_id . '][yearToDate]" /></td>';
                    for ($i = 1; $i <= 31; $i++) {
                        $lastMonth = (isset($beverage_analysis[$dep->charge_additional_category_id]['lastMonth'][$i])) ? $beverage_analysis[$dep->charge_additional_category_id]['lastMonth'][$i] : 0;
                        echo '<td><input style="direction: rtl;margin:0px" class="angka"  value="' . $lastMonth . '" type="text" name="beverage_analysis[' . $dep->charge_additional_category_id . '][lastMonth][' . $i . ']" /></td>';
                    }
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'label' => 'Save',
            ));
            ?>
            
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'reset',
                'icon' => 'refresh',
            ));
            ?>           
            
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'htmlOptions' => array('class' => 'btn-danger', 'id' => 'clear'),
                'icon' => 'remove white',
                'label' => 'Clear All',
            ));
            ?>
            
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'htmlOptions' => array('class' => 'btn-warning', 'id' => 'clearNol'),
                'icon' => 'check white',
                'label' => 'Clear Except Last Month',
            ));
            ?>
        </div>        
    </fieldset>

    <?php $this->endWidget(); ?>
</div>
<script>
    $("#clear").click(function(event) {                      
        $(".angka").val(0);
    });
</script>
<script>
    $("#clearNol").click(function(event) {                      
        $(".nol").val(0);
    });
</script>
