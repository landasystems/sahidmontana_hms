<?php

/**
 * This is the model class for table "{{saldo_withdrawal}}".
 *
 * The followings are the available columns in table '{{saldo_withdrawal}}':
 * @property integer $id
 * @property integer $amount
 * @property string $status
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class SaldoWithdrawal extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{saldo_withdrawal}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('amount, created_user_id', 'numerical', 'integerOnly' => true),
            array('status', 'length', 'max' => 7),
            array('created, modified', 'safe'),
            array('status,amount', 'required'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, amount, status, created, created_user_id, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
             'User'=>array(self::BELONGS_TO,'User','created_user_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'amount' => 'Amount',
            'status' => 'Status',
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
        $criteria->compare('amount', $this->amount);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SaldoWithdrawal the static model class
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
    
    public function getAmountDep(){
        return landa()->rp($this->amount);
    }

        public function getStatusInfo(){
        $status = ($this->status == 'pending') ? '<span class="label label-warning">Pending</span>' :(($this->status == 'reject') ? '<span class="label label-important">Reject</span>' : '<span class="label label-info">Confirm</span>');
        return $status;
    }
    public function getNameUser(){
        return $this->User->name;
    }
    public function getCreatedWithdrawal(){
        return date('d F Y H:i:s', strtotime($this->created));
    }

}
