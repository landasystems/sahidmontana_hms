<?php

/**
 * This is the model class for table "{{bill_charge}}".
 *
 * The followings are the available columns in table '{{bill_charge}}':
 * @property integer $id
 * @property string $code
 * @property integer $room_bill_id
 * @property string $description
 * @property integer $cash
 * @property string $cc_number
 * @property integer $charge
 * @property integer $ca_user_id
 * @property integer $refund
 * @property integer $total
 * @property integer $is_temp
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class BillCharge extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{bill_charge}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('gl_room_bill_id,na_id, charge_additional_category_id, cash, ca_user_id, refund, total, is_temp, created_user_id', 'numerical', 'integerOnly' => true),
            array('code, cc_number', 'length', 'max' => 45),
            array('description', 'length', 'max' => 255),
            array('charge_additional_category_id', 'required'),
            array('cover,created, is_na, approval_user_id,is_cashier, modified, cc_charge,ca_charge,ca_user_id,gl_charge,gl_room_bill_id', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, code, gl_room_bill_id, description, cash, cc_number, ca_user_id, refund, total, is_temp, created, created_user_id, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Cashier' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'Approval' => array(self::BELONGS_TO, 'User', 'approval_user_id'),
            'CityLedger' => array(self::BELONGS_TO, 'User', 'ca_user_id'),
            'RoomBill' => array(self::BELONGS_TO, 'RoomBill', 'gl_room_bill_id'),
            'BillChargeDet' => array(self::HAS_MANY, 'BillChargeDet', 'id'),
            'ChargeAdditionalCategory' => array(self::BELONGS_TO, 'ChargeAdditionalCategory', 'charge_additional_category_id'),
        );
    }

    public function getAdditionalName() {
        $return = '';
        $roomNumber = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $this->id . ' and deposite_id=0'));
        $nm = array();
        foreach ($roomNumber as $number) {
            array_push($nm, '<br> - ' . $number->Additional->name);
        }
        foreach ($nm as $a) {
            $return .= $a . ' , ';
        }
        return substr($return, 0, strlen($return) - 3);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'code' => 'Code',
            'gl_room_bill_id' => 'Guest Ledger',
            'approval_user_id' => 'Discount Approval',
            'description' => 'Note',
            'cash' => 'Cash',
            'cc_number' => 'C Card Number',
            'ca_user_id' => 'City Ledger',
            'refund' => 'Refund',
            'total' => 'Total',
            'is_temp' => 'Is Temp',
            'created' => 'Date',
            'created_user_id' => 'Cashier',
            'modified' => 'Modified',
            'charge_additional_category_id' => 'Departement',
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
        $criteria->with = array('Cashier');
        $criteria->together = true;

        $criteria->compare('id', $this->id);
        $criteria->compare('t.code', $this->code, true);
        $criteria->compare('gl_room_bill_id', $this->gl_room_bill_id);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('cash', $this->cash);
        $criteria->compare('cc_number', $this->cc_number, true);
        $criteria->compare('ca_user_id', $this->ca_user_id);
        $criteria->compare('refund', $this->refund);
        $criteria->compare('total', $this->total);
        $criteria->compare('is_temp', $this->is_temp);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('Cashier.name', $this->created_user_id, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('charge_additional_category_id', $this->charge_additional_category_id);
        $criteria->order = 't.id desc';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BillCharge the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
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

    public function getDepartement() {

        if (isset($this->ChargeAdditionalCategory->name))
            return $this->ChargeAdditionalCategory->name;
        else
            return '';
    }

}
