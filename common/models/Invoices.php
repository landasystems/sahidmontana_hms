<?php

/**
 * This is the model class for table "{{invoices}}".
 *
 * The followings are the available columns in table '{{invoices}}':
 * @property integer $id
 * @property integer $payment
 * @property string $due_date
 * @property string $client
 * @property string $created
 * @property string $modified
 * @property integer $created_user_id
 */
class Invoices extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Invoices the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return CDbConnection database connection
     */
//    public function getDbConnection() {
//        return Yii::app()->db2;
//    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{invoices}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('payment,client_user_id, created_user_id', 'numerical', 'integerOnly' => true),
//            array( 'length', 'max' => 255),
            array('due_date, created ,payment', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, payment, due_date, client_user_id, created, modified, created_user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'InvoiceDetail'=>array(self::BELONGS_TO, 'InvoiceDetail', 'invoice_id'),
            'User'=>array(self::BELONGS_TO, 'User', 'client_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'payment' => 'Payment',
            'due_date' => 'Due Date',
            'client_user_id' => 'Client Name',
            'created' => 'Created',
            'modified' => 'Modified',
            'created_user_id' => 'Created User',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('payment', $this->payment);
        $criteria->compare('due_date', $this->due_date, true);
        $criteria->compare('client_user_id', $this->client_user_id, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('created_user_id', $this->created_user_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
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
public function getTagBiodata() {
        return $this->InvoicesDetail->description;
                    
}
}
