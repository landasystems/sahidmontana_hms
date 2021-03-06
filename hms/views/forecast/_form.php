<div class="form">
    <?php
    $siteConfig = SiteConfig::model()->findByPk(1);
    $foodAcc = 2;
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'forecast-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'vertival',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    if ($model->isNewRecord == FALSE) {
        $forecast = json_decode($model->forecast, true);
        $otherForecast = json_decode($model->other_forecast, true);
        $coverForecast = json_decode($model->cover_forecast, true);
    }
    ?>
    <fieldset>
        <legend>
            <p class="note">Fields with <span class="required">*</span> is Required.</p>
        </legend>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>
        <?php echo $form->dropDownListRow($model, 'tahun', landa()->yearly(), array('class' => 'span3')); ?>

        <table class="table  table-striped table-hover">
            <thead>
            <th>Account</th>
            <th>Departement</th>
            <?php
            for ($i = 1; $i < 13; $i++) {
                echo '<th>' . $monthName = date("F", mktime(0, 0, 0, $i, 10)) . '</th>';
                $forcast[$i] = 0;
            }
            ?>
            <th class="span2" style="text-align: right">Total</th>
            </thead>
            <tbody>
                <?php
                if ($model->isNewRecord == FALSE) {
                    $forecast = json_decode($model->forecast, true);
                    $otherForecast = json_decode($model->other_forecast, true);
                }
                $account = Account::model()->findAll();
                foreach ($account as $acc) {
                    $departement = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $acc->id, 'group' => 'charge_additional_category_id'));
                    if (empty($departement)) {
                        $class = str_replace(' ', '', str_replace('&', '', $acc->name));
                        echo '<tr>';
                        echo '<td colspan="2" style="background:antiquewhite">' . $acc->name . '</td>';
                        $total = 0;
                        for ($i = 1; $i < 13; $i++) {
                            $classMonth = $class . $i;
                            $value = (!empty($forecast[$acc->id][$i])) ? $forecast[$acc->id][$i] : '';
                            echo '<td style="background:antiquewhite"><input style="direction: rtl" class="angka forecast forecast' . $i . ' ' . $class . ' ' . $classMonth . ' span1"  value="' . $value . '" type="text" name="forecast[' . $acc->id . '][' . $i . ']" /></td>';
                            $total +=$value;
                            $forcast[$i] += $value;
                        }
                        echo '<td  class="total' . $class . '"  style="text-align:right">' . landa()->rp($total, false) . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td colspan="2">Total</td>';
                        for ($i = 1; $i < 13; $i++) {
                            $classMonth = $class . $i;
                            $value = (!empty($forecast[$acc->id][$i])) ? $forecast[$acc->id][$i] : '';
                            echo '<td style="text-align:right" class="total' . $classMonth . '" >' . landa()->rp($value, false) . '</td>';
                            echo '<script>
                                 $(".' . $classMonth . '").on("input", function() {
                                    var value=0;
                                    $(".' . $classMonth . '").each(function() {
                                        var a =  parseInt(this.value);
                                        a = a? a: 0;
                                        value += a;
                                    });
                                    $(".total' . $classMonth . '").html(rp(value));
                                });
                                </script>
                                ';
                        }
                        echo '<td style="text-align:right" class="totalAll' . $class . '" >' . landa()->rp($total, false) . '</td>';
                        echo '</tr>';
                        echo '<tr><td colspan="15">&nbsp;</td></tr>';
                        echo '<script>
                            $(".' . $class . '").on("input", function() {
                                var value=0;
                                $(".' . $class . '").each(function() {
                                    var a =  parseInt(this.value);
                                    a = a? a: 0;
                                    value += a;
                                });
                                $(".total' . $class . '").html(rp(value));
                                $(".totalAll' . $class . '").html(rp(value));
                                forecast();
                            });                                                                                   
                            </script>';
                    } else {
                        $totalAccount = 0;
                        $classAcc = str_replace(' ', '', str_replace('&', '', $acc->name));
                        for ($i = 1; $i < 13; $i++) {
                            $totalBulan[$i] = 0;
                        }
                        echo '<tr><td style="background:antiquewhite" colspan="15">' . $acc->name . '</td></tr>';
                        foreach ($departement as $dep) {
                            $class = str_replace(' ', '', str_replace('&', '', $acc->name)) . str_replace(' ', '', str_replace('&', '', $dep->ChargeAdditionalCategory->name));
                            echo '<tr>';
                            echo '<td></td>';
                            echo '<td>' . $dep->ChargeAdditionalCategory->name . '</td>';
                            $total = 0;
                            for ($i = 1; $i < 13; $i++) {
                                $classMonth = $classAcc . $i;
                                $value = (!empty($forecast[$acc->id][$dep->charge_additional_category_id][$i])) ? $forecast[$acc->id][$dep->charge_additional_category_id][$i] : '';
                                echo '<td><input style="direction: rtl"  class="angka forecast forecast' . $i . ' ' . $classAcc . ' ' . $class . ' ' . $classMonth . ' span1" value="' . $value . '" type="text"  name="forecast[' . $acc->id . '][' . $dep->charge_additional_category_id . '][' . $i . ']" /></td>';
                                $total +=$value;
                                $totalAccount +=$value;
                                $totalBulan[$i] +=$value;
                                $forcast[$i] += $value;
                            }
                            echo '<td class="total' . $class . '" style="text-align:right">' . landa()->rp($total, false) . '</td>';
                            echo '<script>
                            $(".' . $class . '").on("input", function() {
                                var value=0;
                                $(".' . $class . '").each(function() {
                                    var a =  parseInt(this.value);
                                    a = a? a: 0;
                                    value += a;
                                });
                                $(".total' . $class . '").html(rp(value));
                                $(".totalAll' . $class . '").html(rp(value));                                
                            });                                                                                   
                            </script>';
                            echo '</tr>';
                        }
                        echo '<tr>';
                        echo '<td colspan="2">Total</td>';
                        for ($i = 1; $i < 13; $i++) {
                            $classMonth = $classAcc . $i;
                            echo '<td style="text-align:right" class="total' . $classMonth . '" >' . landa()->rp($totalBulan[$i], false) . '</td>';
                            echo '<script>
                                 $(".' . $classMonth . '").on("input", function() {
                                    var value=0;
                                    $(".' . $classMonth . '").each(function() {
                                        var a =  parseInt(this.value);
                                        a = a? a: 0;
                                        value += a;
                                    });
                                    $(".total' . $classMonth . '").html(rp(value));
                                    All' . $classAcc . '();
                                    forecast();
                                });
                                </script>
                                ';
                        }
                        echo '<td style="text-align:right" class="totalAll' . $classAcc . '">' . landa()->rp($totalAccount, false) . '</td>';
                        echo '</tr>';
                        echo '<tr><td colspan="15">&nbsp;</td></tr>';
                        echo '<script>
                                function All' . $classAcc . '(){
                                    var value=0;
                                    $(".' . $classAcc . '").each(function() {
                                        var a =  parseInt(this.value);
                                        a = a? a: 0;
                                        value += a;
                                    });
                                    $(".totalAll' . $classAcc . '").html(rp(value));
                                };
                                </script>
                                ';
                    }
                }


                echo '<tr>';
                echo '<td colspan="2"><b>Total Forecast<b></td>';
                $totalForcast = 0;
                for ($i = 1; $i < 13; $i++) {
                    $totalForcast += $forcast[$i];
                    echo '<td style="text-align:right" class="totalForecast' . $i . '" >' . landa()->rp($forcast[$i], false) . '</td>';
                }
                echo '<td style="text-align:right" class="totalAllForcast">' . landa()->rp($totalForcast, false) . '</td>';
                echo '</tr>';

                //other forecase----------------------------------------------------------------------------------

                $totalOtherForecast = 0;
                $classAcc = 'otherForecast';
                for ($i = 1; $i < 13; $i++) {
                    $totalOtherBulan[$i] = 0;
                }
                echo '<tr><td colspan="15">&nbsp;</td></tr>';
                echo '<tr><td style="background:khaki;text-align:center;" colspan="15"><b>STATISTIC FORECAST</b></td></tr>';
                $arrOtherForecast = Forecast::model()->otherForcast();
                foreach ($arrOtherForecast as $dep) {
                    $class = str_replace(' ', '', $dep);
                    echo '<tr>';
                    echo '<td colspan="2">' . ucwords($dep) . '</td>';
                    $total = 0;
                    for ($i = 1; $i < 13; $i++) {
                        $classMonth = $classAcc . $i;
                        $value = (!empty($otherForecast[$dep][$i])) ? $otherForecast[$dep][$i] : '';
                        echo '<td><input style="direction: rtl"  class="otherForecast otherForecast' . $i . ' ' . $classAcc . ' ' . $class . ' ' . $classMonth . ' span1" value="' . $value . '" type="text"  name="other_forecast[' . $dep . '][' . $i . ']" /></td>';
                        $total +=$value;
                        $totalOtherForecast +=$value;
                        $totalOtherBulan[$i] +=$value;
                    }
                    echo '<td class="total' . $class . '" style="text-align:right">' . landa()->rp($total, false) . '</td>';
                    echo '<script>
                            $(".' . $class . '").on("input", function() {
                                var value=0;
                                $(".' . $class . '").each(function() {
                                    var a =  parseInt(this.value);
                                    a = a? a: 0;
                                    value += a;
                                });
                                $(".total' . $class . '").html(rp(value));                                                              
                            });                                                                                   
                            </script>';
                    echo '</tr>';
                }

                echo '<script>
                                function All' . $classAcc . '(){
                                    var value=0;
                                    $(".' . $classAcc . '").each(function() {
                                        var a =  parseInt(this.value);
                                        a = a? a: 0;
                                        value += a;
                                    });
                                    $(".totalAll' . $classAcc . '").html(rp(value));
                                };
                                </script>
                                ';

                //other forecase----------------------------------------------------------------------------------

                $totalCoverForecast = 0;
                $classAcc = 'coverForecast';
                for ($i = 1; $i < 13; $i++) {
                    $totalCoverBulan[$i] = 0;
                }
                echo '<tr><td colspan="15">&nbsp;</td></tr>';
                echo '<tr><td style="background:khaki;text-align:center;" colspan="15"><b>FOOD COVER FORECAST</b></td></tr>';
                $departement = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $foodAcc, 'group' => 'charge_additional_category_id'));
                foreach ($departement as $dep) {
                    $class = str_replace(' ', '', str_replace('&', '', $dep->ChargeAdditionalCategory->name));
                    echo '<tr>';
                    echo '<td colspan="2">' . ucwords($dep->ChargeAdditionalCategory->name) . '</td>';
                    $total = 0;
                    for ($i = 1; $i < 13; $i++) {
                        $classMonth = $classAcc . $i;
                        $value = (!empty($coverForecast[$dep->charge_additional_category_id][$i])) ? $coverForecast[$dep->charge_additional_category_id][$i] : '';
                        echo '<td><input style="direction: rtl"  class="coverForecast coverForecast' . $i . ' ' . $classAcc . ' ' . $class . ' ' . $classMonth . ' span1" value="' . $value . '" type="text"  name="cover_forecast[' . $dep->charge_additional_category_id . '][' . $i . ']" /></td>';
                        $total +=$value;
                        $totalCoverForecast +=$value;
                        $totalCoverBulan[$i] +=$value;
                    }
                    echo '<td class="total' . $class . '" style="text-align:right">' . landa()->rp($total, false) . '</td>';
                    echo '<script>
                            $(".' . $class . '").on("input", function() {
                                var value=0;
                                $(".' . $class . '").each(function() {
                                    var a =  parseInt(this.value);
                                    a = a? a: 0;
                                    value += a;
                                });
                                $(".total' . $class . '").html(rp(value));                                      
                                All' . $classAcc . '();
                                cover_forecast();
                            });                                                                                   
                            </script>';
                    echo '</tr>';
                }
                echo '<tr>';
                echo '<td colspan="2">Total</td>';
                for ($i = 1; $i < 13; $i++) {
                    $classMonth = $classAcc . $i;
                    echo '<td style="text-align:right" class="total' . $classMonth . '" >' . landa()->rp($totalCoverBulan[$i], false) . '</td>';
                    echo '<script>
                                 $(".' . $classMonth . '").on("input", function() {
                                    var value=0;
                                    $(".' . $classMonth . '").each(function() {
                                        var a =  parseInt(this.value);
                                        a = a? a: 0;
                                        value += a;
                                    });
                                    $(".total' . $classMonth . '").html(rp(value));
                                    All' . $classAcc . '();
                                    forecast();
                                });
                                </script>
                                ';
                }
                echo '<td style="text-align:right" class="totalAll' . $classAcc . '">' . landa()->rp($totalCoverForecast, false) . '</td>';
                echo '</tr>';
                echo '<tr><td colspan="15">&nbsp;</td></tr>';
                echo '<script>
                                function All' . $classAcc . '(){
                                    var value=0;
                                    $(".' . $classAcc . '").each(function() {
                                        var a =  parseInt(this.value);
                                        a = a? a: 0;
                                        value += a;
                                    });
                                    $(".totalAll' . $classAcc . '").html(rp(value));
                                };
                                </script>
                                ';


                echo '<script>
                        function forecast(){                            
                            var total =0;
                            for (var i=1;i<13;i++){
                                var value=0;                            
                                $(".forecast"+i).each(function() {
                                    var a =  parseInt(this.value);
                                    a = a? a: 0;
                                    value += a;
                                });
                                $(".totalForecast"+i).html(rp(value));
                                total += value;
                            }      
                            $(".totalAllForcast").html(rp(total));
                        };
                        function cover_forecast(){                            
                            var total =0;
                            for (var i=1;i<13;i++){
                                var value=0;                            
                                $(".coverForecast"+i).each(function() {
                                    var a =  parseInt(this.value);
                                    a = a? a: 0;
                                    value += a;
                                });
                                $(".coverForecast"+i).html(rp(value));
                                total += value;
                            }      
                            $(".totalAllCoverForcast").html(rp(total));
                        };
                        </script>
                        ';
                ?>




            </tbody>
        </table>

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
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
<script>
    function rp(angka) {
        var rupiah = "";
        var angkarev = angka.toString().split("").reverse().join("");
        for (var i = 0; i < angkarev.length; i++)
            if (i % 3 == 0)
                rupiah += angkarev.substr(i, 3) + ".";
        return rupiah.split("", rupiah.length - 1).reverse().join("");
    }
    ;
</script>
