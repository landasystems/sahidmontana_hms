<?php

/**
 * This is the model class for table "{{payment}}".
 *
 * The followings are the available columns in table '{{payment}}':
 * @property integer $id
 * @property string $trans_number
 * @property string $bank_account
 * @property string $self_name
 * @property string $self_account_number
 * @property string $description
 * @property string $status
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class SaldoDeposit extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{payment}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_user_id', 'numerical', 'integerOnly' => true),
            array('trans_number,self_bank_account, self_name, self_account_number', 'length', 'max' => 45),
            array('bank_account, description', 'length', 'max' => 255),
            array('status', 'length', 'max' => 7),
            array('created, amount,modified', 'safe'),
            array('amount,trans_number','required'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, trans_number, amount,bank_account,self_bank_account , self_name, self_account_number, description, status, created, created_user_id, modified,module', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            //'User' => array(self::HAS_MANY, 'User', 'id'),
            'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'trans_number' => 'Nota',
            'bank_account' => 'Bank Tujuan',
            'self_bank_account' => 'Nama Bank',
            'self_name' => 'Nama Penyetor',
            'User.name' => 'User Name',
            'self_account_number' => 'Nomer Rekening',
            'description' => 'Description',
            'status' => 'Status',
            'created' => 'Date',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
            'amount' => 'Jumlah'
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
        $criteria->compare('trans_number', $this->trans_number, true);
        $criteria->compare('bank_account', $this->bank_account, true);
//        $criteria->compare('self_bank_account', $this->self_bank_account, true);
        $criteria->compare('self_name', $this->self_name, true);
        $criteria->compare('self_account_number', $this->self_account_number, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('module', $this->module,true);
        $criteria->compare('modified', $this->modified, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    public function search_frontend() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->addCondition('created_user_id = ' . user()->id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors() {
        return array(
            'timestamps' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
//                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    protected function beforeValidate() {
        if (empty($this->created_user_id)) {
            $this->created_user_id = Yii::app()->user->id;
        }
        return parent::beforeValidate();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Payment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    
public function getTotalAmount(){
        return landa()->rp($this->amount);
    }
    
    public function module() {
        $result = array();
        $result['roles'] = 'Roles';
        $result['deposit'] = 'Deposit';
        return $result;
    }
    
    public function bank_name() {
        $result = array();
        $result['mandiri'] = 'Mandiri';
        $result['bca'] = 'BCA';
        $result['bni'] = 'BNI';
        $result['muamalat'] = 'Muamalat';
        $result['bri'] = 'BRI';
        return $result;
    }
}


