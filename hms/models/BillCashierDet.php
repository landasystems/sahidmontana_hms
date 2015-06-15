<?php

/**
 * This is the model class for table "{{bill_cashier_det}}".
 *
 * The followings are the available columns in table '{{bill_cashier_det}}':
 * @property integer $id
 * @property integer $bill_id
 * @property string $bill_cashier_id
 */
class BillCashierDet extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{bill_cashier_det}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('bill_id', 'numerical', 'integerOnly' => true),
            array('bill_cashier_id', 'length', 'max' => 45),
            array('deposite_id,bill_id,bill_charge_id', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, bill_id, bill_cashier_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Cashier' => array(self::BELONGS_TO, 'BillCashier', 'bill_cashier_id'),
            'Bill' => array(self::BELONGS_TO, 'Bill', 'bill_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'bill_id' => 'Bill',
            'bill_cashier_id' => 'Bill Cashier',
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
        $criteria->compare('bill_id', $this->bill_id);
        $criteria->compare('bill_cashier_id', $this->bill_cashier_id, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BillCashierDet the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
