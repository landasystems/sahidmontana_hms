<?php

/**
 * This is the model class for table "{{bill}}".
 *
 * The followings are the available columns in table '{{bill}}':
 * @property integer $id
 * @property string $code
 * @property string $created
 * @property integer $created_user_id
 * @property integer $cash
 * @property integer $charge
 * @property integer $ca_user_id
 * @property integer $refund
 * @property integer $total
 */
class Bill extends CActiveRecord {

    public $total;
    public $total_dp;

    public function tableName() {
        return '{{bill}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
        return array(
            array('created_user_id, na_id,cash, cc_charge, ca_user_id, refund, total, gl_room_bill_id', 'numerical', 'integerOnly' => true),
            array('code', 'length', 'max' => 45),
            array('created,cc_number,discount,pax_name,is_na,description,is_cashier,ca_charge, guest_room_ids, guest_address, guest_phone, guest_company, bill_charge_id', 'safe'),
            // The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
            array('id, code, created, created_user_id, cash, cc_charge, ca_user_id, refund, total', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
        return array(
            'BillTo' => array(self::BELONGS_TO, 'User', 'ca_user_id'),
            'Guest' => array(self::BELONGS_TO, 'User', 'guest_user_id'),
            'CityLedger' => array(self::BELONGS_TO, 'User', 'ca_user_id'),
            'Room' => array(self::BELONGS_TO, 'Room', 'room_id'),
            'Cashier' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'RoomBill' => array(self::BELONGS_TO, 'RoomBill', 'gl_room_bill_id'),
            'BillDet' => array(self::HAS_MANY, 'BillDet', 'id'),
                //  'BillCharge' => array(self::HAS_MANY, 'BillCharge', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'code' => 'Code',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'cash' => 'Cash',
            'cc_charge' => 'Charge',
            'ca_user_id' => 'Ca User',
            'refund' => 'Refund',
            'total' => 'Total',
            'cc_number' => 'No. C Card'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
// @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('cash', $this->cash);
        $criteria->compare('cc_charge', $this->ca_user_id);
        $criteria->compare('ca_user_id', $this->ca_user_id);
        $criteria->compare('refund', $this->refund);
        $criteria->compare('total', $this->total);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    public function detRoom($room_id, $lead_room_bill_id = array(), $print = false) {
        if (empty($lead_room_bill_id) && empty($room_id)) {
            $model = array();
        } else {
            $modelGl = array();
            $modelRoom = array();
            if (!empty($lead_room_bill_id)) { //untuk mengambil guest ledger bill
                $criteria = new CDbCriteria();
                $criteria->order = 'date_bill, charge';
                $criteria->addInCondition('id', $lead_room_bill_id, 'or');
                $criteria->addInCondition('lead_room_bill_id', $lead_room_bill_id, 'or');
                $criteria->addCondition('is_na = 1');
                $modelGl = RoomBill::model()->findAll($criteria);
            }
            if (!empty($room_id)) {
                $criteria = new CDbCriteria();
                $criteria->order = 'date_bill, charge';
                $criteria->addCondition('is_checkedout = 0 AND is_na = 1');
                $criteria->addInCondition('room_id', $room_id);
                $modelRoom = RoomBill::model()->findAll($criteria);
            }
            $model = array_merge($modelGl, $modelRoom);
        }



//menggabungkan yang mempunyai date bill sama dan charge sama
        $results = '';
        $sDate_bill = '';
        $sCharge = '';
        $temp = array();
        $index = 0;
        foreach ($model as $no => $m) {
            $txtExtrabed = ($m->extrabed == 0) ? '' : '+EB';
            if ($sDate_bill == $m->date_bill && $sCharge == $m->charge) {
                $amount++;
                $room[] = $m->room_number . $txtExtrabed;
                $temp[$index]['amount'] = $amount;
                $temp[$index]['room'] = $room;
                $temp[$index]['sRoomBill'] .= '<input type="hidden" value="' . $m->id . '" name="room_bill_id[]">';
            } else {
                $sDate_bill = $m->date_bill;
                $sCharge = $m->charge;
                $amount = 1;
                $index = $no;
                $room = array();
                $room[] = $m->room_number . $txtExtrabed;
                $temp[$index] = array('sRoomBill' => '<input type="hidden" value="' . $m->id . '" name="room_bill_id[]">', 'date_bill' => date('Y-m-d', strtotime($m->date_bill) + 86400), 'amount' => 1, 'charge' => $m->charge, 'room' => $room);
            }
        }

//merender ulang hasil penggabungan
        foreach ($temp as $m) {
            if ($print) {
                $results .= '<tr>'
                        . '<td  id="bill_print" style="text-align:left">' . date("D, d-M-Y", strtotime($m['date_bill'])) . '</td>'
                        . '<td  id="bill_print" style="text-align:left">Room Rate</td>'
                        . '<td id="bill_print">' . implode(', ', $m['room']) . '</td>'
                        . '<td  id="bill_print" style="text-align:center">' . $m['amount'] . '</td>'
                        . '<td  id="bill_print" style="text-align:right">' . landa()->rp($m['charge'], false) . '</td>'
                        . '<td  id="bill_print" style="text-align:right">' . landa()->rp($m['amount'] * $m['charge'], false) . '</td>'
                        . '</tr>';
            } else {
                $results .= '<tr class="items">
                        <td style="text-align:center">' . $m['sRoomBill'] . '<i class="minia-icon-arrow-right-2"></td>
                        <td>Room Rate' . '</td>
                        <td>' . implode(', ', $m['room']) . '</td>
                        <td>' . date("D, d-M-Y", strtotime($m['date_bill'])) . '</td>                                                        
                        <td style="text-align:center">' . $m['amount'] . '</td>                        
                        <td style="text-align:right">' . landa()->rp($m['charge']) . '</td>                                                        
                        <td style="text-align:right">' . landa()->rp($m['amount'] * $m['charge']) . '</td>                                                        
                        </tr>';
            }
            $this->total += $m['amount'] * $m['charge'];
        }

        return $results;
    }

    public function detAddCharge($leadRoomBill, $print = false) {
        if (!empty($leadRoomBill)) {
            $sWhere = 'gl_room_bill_id IN (' . implode(',', $leadRoomBill) . ')';
            $additionBills = BillCharge::model()->findAll(array('condition' => $sWhere, 'order' => 'id'));
        } else {
            $additionBills = array();
        }
        $departement = ChargeAdditionalCategory::model()->findAll();
        $data = CHtml::listData($departement, 'id', 'name');
        $results = '';
        foreach ($additionBills as $additionBill) {
            if ($print) {
                $results .= '<tr>'
                        . '<td  id="bill_print"  style="text-align:left">' . date("D, d-M-Y H:i", strtotime($additionBill->created)) . '</td>'
                        . '<td  id="bill_print"  style="text-align:left">' . $data[$additionBill->ChargeAdditionalCategory->root] . ' [' . $additionBill->code . ']' . '</td>'
                        . '<td  id="bill_print">' . $additionBill->RoomBill->room_number . '</td>'
                        . '<td  id="bill_print" style="text-align:right"></td>'
                        . '<td  id="bill_print" style="text-align:right">' . landa()->rp($additionBill->gl_charge, false) . '</td>'
                        . '<td id="bill_print"  style="text-align:right">' . landa()->rp($additionBill->gl_charge, false) . '</td>'
                        . '</tr>';
            } else {
                $results .= '<tr class="items">
                                <td style="text-align:center"><i class="minia-icon-arrow-right-2"><input type="hidden" name="bill_charge_id[]" value="' . $additionBill->id . '"></td>
                                <td>' . $data[$additionBill->ChargeAdditionalCategory->root] . ' [' . $additionBill->code . ']' . '</td>
                                <td>' . $additionBill->RoomBill->room_number . '</td>
                                <td>  ' . date("D, d-M-Y H:i", strtotime($additionBill->created)) . '</td>                                                        
                                <td style="text-align:center">' . '-' . '</td>                                                        
                                <td style="text-align:right">' . landa()->rp($additionBill->gl_charge) . '</td>                                                        
                                <td style="text-align:right">' . landa()->rp($additionBill->gl_charge) . '</td>                                                        
                            </tr>';
            }
            $this->total += $additionBill->gl_charge;
        }
        return $results;
    }

    public function detcharge($billCharge, $print = false) {
        $results = '';
        foreach ($billCharge as $valCharge) {
           // $amount = BillChargeDet::model()->find(array('select' => 'sum(amount) as amount', 'condition' => 'bill_charge_id='.$valCharge->bill_charge_id));
           // $price = BillChargeDet::model->find(array())
            if ($print) {
                $results .= '<tr>'
                        . '<td id="bill_print"  style="text-align:left">' . date("D, d-M-Y H:i", strtotime($valCharge->BillCharge->created)) . '</td>'
                        . '<td id="bill_print"  style="text-align:left">' . $valCharge->BillCharge->ChargeAdditionalCategory->name . ' [' . $valCharge->BillCharge->code . ']' . '</td>'
                        . '<td id="bill_print">' . $valCharge->BillCharge->RoomBill->room_number . '</td>'
                        . '<td id="bill_print"  style="text-align:center"></td>'
                        . '<td id="bill_print"  style="text-align:right">' . landa()->rp($valCharge->BillCharge->gl_charge, false) . '</td>'
                        . '<td id="bill_print"  style="text-align:right">' . landa()->rp($valCharge->BillCharge->gl_charge, false) . '</td>'
                        . '</tr>';
            } else {
                $results .= '<tr>'
                        . ' <td style="text-align:center"><i class="minia-icon-arrow-right-2"><input type="hidden" name="bill_charge_id[]" value="' . $valCharge->bill_charge_id . '"></td>'
                        . '<td style="text-align:left">' . $valCharge->BillCharge->ChargeAdditionalCategory->name . ' [' . $valCharge->BillCharge->code . ']' . '</td>'
                        . '<td style="text-align:left">' . $valCharge->BillCharge->RoomBill->room_number . '</td>'
                        . '<td>' . date("D, d-M-Y H:i", strtotime($valCharge->BillCharge->created)) . '</td>'
                        . '<td  style="text-align:center"></td>'
                        . '<td  style="text-align:right">' . landa()->rp($valCharge->BillCharge->gl_charge) . '</td>'
                        . '<td  style="text-align:right">' . landa()->rp($valCharge->BillCharge->gl_charge) . '</td>'
                        . '</tr>';
            }
        }
        return $results;
    }

    public function detDeposite($guest_user_id, $print = false) {
        if (empty($guest_user_id))
            $sWhere = 'id=0';
        else
            $sWhere = 'guest_user_id IN (' . implode(',', $guest_user_id) . ') and is_applied=0';

        $results = '';
        $deposits = Deposite::model()->findAll(array('condition' => $sWhere));
        foreach ($deposits as $deposit) {
            if ($print) {
                
            } else {
                $results .= '<tr class="items trDp">
                        <td style="text-align:center"><i class="minia-icon-arrow-right-2"></td>
                        <td>' . 'Deposit Guest [' . $deposit->code . ']</td>
                        <td>-</td>
                        <td>' . date("D, d-M-Y H:i", strtotime($deposit->created)) . '</td>                                                        
                        <td style="text-align:center">' . '-' . '</td>                                                        
                        <td style="text-align:right">' .
                        '<input type="hidden" id="max" value="' . $deposit->balance_amount . '" />' .
                        '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70%" class="deposite_amount" value="' . $deposit->balance_amount . '" name="Deposite[' . $deposit->id . ']" type="text"></div>'
                        . '</td>                                                        
                        <td style="text-align:right" id="subtotal">' . landa()->rp($deposit->balance_amount * -1) . '</td>                                                        
                    </tr>';
            }
            $this->total_dp += $deposit->balance_amount;
            $this->total-= $deposit->balance_amount;
        }
        return $results;
    }

    public function detDepositeView($ids, $print = false) {
        if (!empty($ids)) {
            $sWhere = 'id IN (' . implode(',', $ids) . ')';
            $billDet = BillDet::model()->findAll(array('condition' => $sWhere));
        } else {
            $billDet = array();
        }


        $results = '';
        foreach ($billDet as $m) {
            if ($print) {
                $results .= '<tr>'
                        . '<td  id="bill_print"  style="text-align:left">' . date("D, d-M-Y H:i", strtotime($m->Deposite->created)) . '</td>'
                        . '<td  id="bill_print"  style="text-align:left">' . 'Deposit Guest [' . $m->Deposite->code . ']</td>'
                        . '<td id="bill_print"></td>'
                        . '<td  id="bill_print" style="text-align:right"></td>'
                        . '<td  id="bill_print" style="text-align:right">' . landa()->rp($m->deposite_amount, false) . '</td>'
                        . '<td id="bill_print"  style="text-align:right">' . landa()->rp($m->deposite_amount * -1, false) . '</td>'
                        . '</tr>';
            } else {
                $results .= '<tr class="items">
                        <td style="text-align:center"><i class="minia-icon-arrow-right-2"></td>
                        <td>' . 'Deposit Guest [' . $m->Deposite->code . ']</td>
                        <td>-</td>
                        <td>' . date("D, d-M-Y H:i", strtotime($m->Deposite->created)) . '</td>                                                        
                        <td style="text-align:center">' . '-' . '</td>                                                        
                        <td style="text-align:right">' .
                        '<input type="hidden" id="max" value="' . $m->deposite_amount . '" />' .
                        '<div class="input-prepend"><span class="add-on">Rp</span><input style="direction:rtl" class="angka deposite_amount" value="' . $m->deposite_amount . '" name="Deposite[' . $m->Deposite->id . ']" type="text"></div>'
                        . '</td>                                                        
                        <td style="text-align:right" id="subtotal">' . landa()->rp($m->deposite_amount * -1) . '</td>                                                        
                    </tr>';
            }
            $this->total_dp += $m->deposite_amount;
            $this->total-= $m->deposite_amount;
        }
        return $results;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Bill the static model class
     */
    public function getRoomNumber() {
        if (empty($this->guest_room_ids)) {
            $return = '';
            $roomNumber = BillDet::model()->findAll(array('condition' => 'bill_id=' . $this->id . ' and room_bill_id is not null'));
            $nm = array();
            foreach ($roomNumber as $number) {
                if (isset($number->RoomBill->room_number)) {
                    if (!in_array($number->RoomBill->room_number, $nm)) {
                        array_push($nm, $number->RoomBill->room_number);
                    }
                }
            }
            foreach ($nm as $a) {
                $return .= $a . ' , ';
            }
            return substr($return, 0, strlen($return) - 3);
        } else {
            return implode(', ', json_decode($this->guest_room_ids));
        }
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function numberToWords($number) {

        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
// overflow
            trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return ucwords($string . ' Rupipahs');
    }

    public function behaviors() {
        return array(
            'timestamps' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

}
