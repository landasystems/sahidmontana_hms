<?php

/**
 * This is the model class for table "{{registration}}".
 *
 * The followings are the available columns in table '{{registration}}':
 * @property integer $id
 * @property integer $reservation_id
 * @property integer $guest_user_id
 * @property string $note
 * @property string $created
 * @property integer $created_user_id
 * @property string $billing
 * @property string $date_to
 */
class Registration extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Registration the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{registration}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('reservation_id, guest_user_id, created_user_id', 'numerical', 'integerOnly' => true),
            array('note', 'length', 'max' => 255),
            array('date_from', 'required'),
//            array('billing', 'length', 'max' => 45),
            array('created,deposite_id,package_room_type_id,type,remarks, cc_number,by,date_to, dp,billing_user_id, billing_note,market_segment_id,approval_user_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, code, reservation_id, guest_user_id, note, created, created_user_id,  date_to', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Guest' => array(self::BELONGS_TO, 'User', 'guest_user_id'),
            'Approval' => array(self::BELONGS_TO, 'User', 'approval_user_id'),
            'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'Bill' => array(self::BELONGS_TO, 'User', 'billing_user_id'),
            'Reservation' => array(self::BELONGS_TO, 'Reservation', 'reservation_id'),
            'Package' => array(self::BELONGS_TO, 'RoomType', 'package_room_type_id'),
            'MarketSegment' => array(self::BELONGS_TO, 'MarketSegment', 'market_segment_id'),
            'Deposite' => array(self::BELONGS_TO, 'Deposite', 'deposite_id'),
            'RoomBIll' => array(self::HAS_MANY, 'RoomBill', 'registration_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'reservation_id' => 'Reservation',
            'guest_user_id' => 'Guest Name',
            'note' => 'Note',
            'code' => 'Code',
            'created' => 'Created',
            'market_segment_id' => 'Mar. Segment',
            'created_user_id' => 'Created User',
            'date_to' => 'Date Range',
            'date_from' => 'Date Range',
            'dp' => 'Amount',
            'cc_number' => 'Debit/Credit Card',
            'by' => 'DP By',
            'package_room_type_id' => 'Package'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
         $criteria->with = array('Guest');
        $criteria->together = true;

        $criteria->compare('id', $this->id);
        $criteria->compare('t.code', $this->code, true);
        $criteria->compare('reservation_id', $this->reservation_id, true);
        $criteria->compare('Guest.name', $this->guest_user_id, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
//        $criteria->compare('billing', $this->billing, true);
        $criteria->compare('date_to', $this->date_to, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    public function getThisPackage() {
        if (empty($this->package_room_type_id) || $this->package_room_type_id == 0) {
            echo '';
        } else {
            echo $this->Package->name;
        }
    }

    public function getIs_checkedout() {

        $check = RoomBill::model()->findAll(array('condition'=>'registration_id=' .$this->id. ' and is_checkedout=0'));
        if (!empty($check)) {
            return 0;
        } else {
            return 1;
        }
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

    public function getRoomNumber() {
        $return = '';
        $roomNumber = RegistrationDetail::model()->findAll(array('condition' => 'registration_id=' . $this->id . ' and is_moved=0'));
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
        $roomNumber = RegistrationDetail::model()->findAll(array('condition' => 'registration_id=' . $this->id . ' and is_moved=0'));
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
        $roomNumber = RegistrationDetail::model()->findAll(array('condition' => 'registration_id=' . $this->id . ' and is_moved=0'));
        return count($roomNumber);
    }

}
