<?php

/**
 * This is the model class for table "{{registration_dp}}".
 *
 * The followings are the available columns in table '{{registration_dp}}':
 * @property integer $id
 * @property integer $registration_id
 * @property double $amount
 * @property string $cc_number
 * @property integer $is_cashier
 * @property string $by
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class RegistrationDp extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{registration_dp}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(            
            array('registration_id, is_cashier, created_user_id', 'numerical', 'integerOnly' => true),
            array('amount', 'numerical'),
            array('cc_number', 'length', 'max' => 45),
            array('by', 'length', 'max' => 5),
            array('created, modified,is_na', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, registration_id, amount, cc_number, is_cashier, by, created, created_user_id, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Registration' => array(self::BELONGS_TO, 'Registration', 'registration_id'),
            'Cashier' => array(self::BELONGS_TO, 'User', 'created_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'registration_id' => 'Registration',
            'amount' => 'Amount',
            'cc_number' => 'Debit/Credit Card',
            'is_cashier' => 'Is Cashier',
            'by' => 'DP By',
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
        $criteria->compare('registration_id', $this->registration_id);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('cc_number', $this->cc_number, true);
        $criteria->compare('is_cashier', $this->is_cashier);
        $criteria->compare('by', $this->by, true);
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
     * @return RegistrationDp the static model class
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
