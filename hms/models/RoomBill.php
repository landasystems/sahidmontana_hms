<?php

/**
 * This is the model class for table "{{room_bill}}".
 *
 * The followings are the available columns in table '{{room_bill}}':
 * @property integer $id
 * @property integer $room_id
 * @property string $room_number
 * @property string $date_bill
 * @property integer $charge
 * @property integer $processed
 * @property integer $created_user_id
 * @property string $modified
 * @property string $created
 */
class RoomBill extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{room_bill}}';
    }

    public $min_id;
    public $maxDateBill;
    public $totalRoomCharge;
    public $amount;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id,registration_id,charge,na_id, processed, created_user_id, lead_room_bill_id', 'numerical', 'integerOnly' => true),
            array('room_number', 'length', 'max' => 3),
            array('moved_room_bill_id, date_bill,is_na, others_include, package_room_type_id,modified, created,extrabed,pax,extrabed_price,room_price,fnb_price, is_checkedout, room_id', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, room_id, room_number, date_bill, charge, processed, created_user_id, modified, created', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Room' => array(self::BELONGS_TO, 'Room', 'room_id'),
            'Registration' => array(self::BELONGS_TO, 'Registration', 'registration_id'),
            'Package' => array(self::BELONGS_TO, 'RoomType', 'package_room_type_id'),
            'Cashier' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'Na' => array(self::BELONGS_TO, 'Na', 'na_id'),
            'BillDet' => array(self::HAS_ONE, 'BillDet', 'room_bill_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'room_id' => 'Room',
            'room_number' => 'Room Number',
            'date_bill' => 'Date Bill',
            'charge' => 'Charge',
            'processed' => 'Processed',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
            'created' => 'Created',
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
        $criteria->compare('room_id', $this->room_id);
        $criteria->compare('room_number', $this->room_number, true);
        $criteria->compare('date_bill', $this->date_bill, true);
        $criteria->compare('charge', $this->charge);
        $criteria->compare('processed', $this->processed);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('created', $this->created, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RoomBill the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getFullRoom() {
        return '[' . $this->room_number . '] ' . ucwords($this->Registration->Guest->guestName);
    }

    public function getRegistrationBy() {
        return ucwords($this->Registration->Guest->guestName);
    }

    public function getAdditional($id = '') {
        if (!empty($id)) {
            $category = ChargeAdditionalCategory::model()->findAll(array('condition' => 'root=' . $id, 'order' => 'root, lft'));
        } else {
            $category = ChargeAdditionalCategory::model()->findAll(array('order' => 'root, lft'));
        }

        foreach ($category as $value) {
            $return[($value->nestedname)] = '<b>' . $value->nestedname . '</b>';
            $items = ChargeAdditional::model()->findAll(array('condition' => 'charge_additional_category_id=' . $value->id));
            foreach ($items as $item) {
                $data = ($value->level == 1) ? "&nbsp;&nbsp;- " . $item->name : '&nbsp;&nbsp;&nbsp;&nbsp;' . str_repeat("&nbsp;&nbsp;", $value->level - 1) . '- ' . $item->name;
                $return[$item->id] = html_entity_decode($data);
            }
        }

//        foreach ($category as $value) {
//            $items = ChargeAdditional::model()->findAll(array('condition' => 'charge_additional_category_id=' . $value->id));                        
//            foreach ($items as $item) {
//                $return[$value->name][$item->id] = $item->name;
//            }
//            if (count($items) <1) {
//                $return[$value->name][] = 'No Product Found';
//            }
//        }

        return $return;
    }

    public function getMaxDate() {
        $max = RoomBill::model()->find(array('order'=>'date_bill DESC','condition' => 'room_id=' . $this->room_id . ' and registration_id=' . $this->registration_id));
        if (!empty($max))
            return date("Y-m-d", strtotime('+1 day', strtotime($max->date_bill)));
        else
            return '';
    }

    public function getRoomNumber() {
        $return = '';
        $roomNumber = RoomBill::model()->findAll(array('condition' => 'registration_id=' . $this->registration_id));

        $nm = array();
        foreach ($roomNumber as $number) {
            if (!in_array($number->room_number, $nm)) {
                array_push($nm, $number->room_number);
            }
        }
        foreach ($nm as $a) {
            $return .= $a . ' , ';
        }
        return substr($return, 0, strlen($return) - 3);
    }

    public function getRoomNumberNotCheckedout() {
        $return = '';
        $roomNumber = RoomBill::model()->findAll(array('condition' => 'registration_id=' . $this->registration_id . ' and is_checkedout=0'));
        $nm = array();
        foreach ($roomNumber as $number) {
            if (!in_array($number->room_number, $nm)) {
                array_push($nm, $number->room_number);
            }
        }
        foreach ($nm as $a) {
            $return .= $a . ' , ';
        }
        return substr($return, 0, strlen($return) - 3);
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
