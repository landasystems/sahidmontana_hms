<?php

/**
 * This is the model class for table "{{bill_ca}}".
 *
 * The followings are the available columns in table '{{bill_ca}}':
 * @property integer $id
 * @property integer $bill_id
 * @property integer $bill_charge_id
 * @property double $charge
 * @property double $charge_less
 * @property string $description
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class BillCa extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{bill_ca}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
//            array('id', 'required'),
            array('id, bill_id, bill_charge_id, created_user_id', 'numerical', 'integerOnly' => true),
            array('charge, charge_less', 'numerical'),
            array('description', 'length', 'max' => 255),
            array('created, modified,guest_user_id', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, bill_id, bill_charge_id, charge, charge_less, description, created, created_user_id, modified', 'safe', 'on' => 'search'),
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
            'Bill' => array(self::BELONGS_TO, 'Bill', 'bill_id'),
            'BillCharge' => array(self::BELONGS_TO, 'BillCharge', 'bill_charge_id'),
            'Guest' => array(self::BELONGS_TO, 'User', 'guest_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'bill_id' => 'Bill',
            'bill_charge_id' => 'Bill Charge',
            'charge' => 'Charge',
            'charge_less' => 'Charge Less',
            'description' => 'Description',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
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
        $criteria->compare('bill_charge_id', $this->bill_charge_id);
        $criteria->compare('charge', $this->charge);
        $criteria->compare('charge_less', $this->charge_less);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BillCa the static model class
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

}
