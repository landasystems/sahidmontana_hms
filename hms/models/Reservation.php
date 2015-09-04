<?php

/**
 * This is the model class for table "{{reservation}}".
 *
 * The followings are the available columns in table '{{reservation}}':
 * @property integer $id
 * @property string $code
 * @property integer $guest_user_id
 * @property string $created
 * @property integer $created_user_id
 * @property string $reserved_by
 * @property string $cp_name
 * @property string $cp_telephone_number
 * @property string $cp_note
 * @property string $date_from
 * @property string $date_to
 * @property integer $billing_user_id
 * @property string $billing_note
 */
class Reservation extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{reservation}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('guest_user_id, created_user_id, billing_user_id,market_segment_id', 'numerical', 'integerOnly' => true),
            array('code', 'length', 'max' => 100),
//            array('date_from', 'required'),
            array('reserved_by, cp_name, cp_telephone_number', 'length', 'max' => 45),
            array('cp_note, billing_note', 'length', 'max' => 255),
            array('approval_user_id,type,deposite_id,created,package_room_type_id,remarks,reason_of_cancel, date_from, date_to,status', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, code, guest_user_id, created, created_user_id, reserved_by, cp_name, cp_telephone_number, cp_note, date_from, date_to, billing_user_id, billing_note', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Approval' => array(self::BELONGS_TO, 'User', 'approval_user_id'),
            'Guest' => array(self::BELONGS_TO, 'User', 'guest_user_id'),
            'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'Bill' => array(self::BELONGS_TO, 'User', 'billing_user_id'),
            'Package' => array(self::BELONGS_TO, 'RoomType', 'package_room_type_id'),
            'Deposite' => array(self::BELONGS_TO, 'Deposite', 'deposite_id'),
            'MarketSegment' => array(self::BELONGS_TO, 'MarketSegment', 'market_segment_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'approval_user_id' => 'Approval',
            'code' => 'Code',
            'package_room_type_id' => 'Package',
            'guest_user_id' => 'Guest Name',
            'created' => 'Created',
            'created_user_id' => 'Created',
            'reserved_by' => 'Reserved By',
            'cp_name' => 'Name',
            'cp_telephone_number' => 'Phone',
            'cp_note' => 'Note',
            'date_from' => 'Arrival',
            'date_to' => 'Departure',
            'status' => 'Status',
            'billing_user_id' => 'Bill To',
            'billing_note' => 'Billing Note',
            'dp' => 'Amount',
            'cc_number' => 'Debit/Credit Card',
            'by' => 'DP By'
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
        $criteria->with = array('Guest','Guest.Roles',);
        $criteria->together = true;

        $criteria->compare('id', $this->id);
        $criteria->compare('t.code', $this->code, true);
        $criteria->compare('Guest.name', $this->guest_user_id, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('reserved_by', $this->reserved_by, true);
        $criteria->compare('cp_name', $this->cp_name, true);
        $criteria->compare('cp_telephone_number', $this->cp_telephone_number, true);
        $criteria->compare('cp_note', $this->cp_note, true);
        $criteria->compare('date_from', $this->date_from, true);
        $criteria->compare('date_to', $this->date_to, true);
        $criteria->compare('billing_user_id', $this->billing_user_id);
        $criteria->compare('billing_note', $this->billing_note, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Reservation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getThisPackage() {
        if (empty($this->package_room_type_id) || $this->package_room_type_id == 0) {
            echo '';
        } else {
            echo $this->Package->name;
        }
    }

    public function getTotalRoom() {
        $detail = ReservationDetail::model()->findAll(array('condition' => 'reservation_id=' . $this->id));
        return count($detail);
    }

    public function getTotalNight() {
        $startTimeStamp = strtotime($this->date_from);
        $endTimeStamp = strtotime($this->date_to);
        $timeDiff = abs($endTimeStamp - $startTimeStamp);
        $numberDays = $timeDiff / 86400;
        $numberDays = intval($numberDays);
        return $numberDays;
    }

    public function getRoom() {
        $detail = ReservationDetail::model()->findAll(array('condition' => 'reservation_id=' . $this->id));
        $return = '';
        foreach ($detail as $det) {
            $return .= $det->Room->number . ', ';
        }
        $return = substr($return, 0, strlen($return) - 1);
        return $return;
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

    public function getFullReservation() {
        return $this->code . ' [ ' . strtoupper($this->Guest->name) . ' ]';
    }

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

    public function getRoomNumber() {
        $return = '';
        $roomNumber = ReservationDetail::model()->findAll(array('condition' => 'reservation_id=' . $this->id));
        $nm = array();
        foreach ($roomNumber as $number) {
            if (isset($number->room_id)) {
                if (!in_array($number->room_id, $nm)) {
                    array_push($nm, $number->room_id);
                }
            }
        }
        foreach ($nm as $a) {
            $return .= $a . ' , ';
        }
        return substr($return, 0, strlen($return) - 3);
    }
    public function getRoomNumberDet() {
        $return = '';
        $tot = 0;
        $roomNumber = ReservationDetail::model()->findAll(array('condition' => 'reservation_id=' . $this->id));
        foreach ($roomNumber as $number) {
            $tot += $number->charge;
            $return .= '<tr>
                        <td>'.$number->room_id.'</td>
                        <td>'.$number->pax.'</td>
                        <td>'.$number->extrabed.'</td>
                        <td>'.landa()->rp($number->charge).'</td>
                      </tr>';
        }
        
        $return = '<table class=\'table\'>
                        <thead>
                        <tr>
                          <th>Number</th>
                          <th>Pax</th>
                          <th>EB</th>
                          <th>Room Rate</th>
                        </tr>
                        </thead>
                        <tbody>'.$return.'</tbody>
                        <tfoot>
                        <tr>
                          <td colspan=\'3\'>Total</th>
                          <td>'.landa()->rp($tot).'</td>
                        </tr>
                        </tfoot>
                   </table>';
        return $return;
    }

    public function getRoomCount() {
        $roomNumber = ReservationDetail::model()->findAll(array('condition' => 'reservation_id=' . $this->id));
        return count($roomNumber);
    }

}
