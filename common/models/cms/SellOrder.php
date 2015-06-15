<?php

/**
 * This is the model class for table "{{sell_order}}".
 *
 * The followings are the available columns in table '{{sell_order}}':
 * @property integer $id
 * @property string $code
 * @property integer $departement_id
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 * @property string $description
 * @property double $subtotal
 * @property string $discount
 * @property double $ppn
 * @property double $other
 * @property string $term
 * @property integer $dp
 * @property integer $credit
 * @property integer $payment
 */
class SellOrder extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{sell_order}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('departement_id, created_user_id, dp, credit, payment', 'numerical', 'integerOnly' => true),
           array('departement_id,customer_user_id,term','required'),
            array('subtotal, ppn, other', 'numerical'),
            array('code, description', 'length', 'max' => 255),
            array('discount', 'length', 'max' => 3),
            array('created, modified, term', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, status,code, departement_id, created, created_user_id, modified, description, subtotal, discount, ppn, other, term, dp, credit, payment', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'Customer' => array(self::BELONGS_TO, 'User', 'customer_user_id'),
            'Departement' => array(self::BELONGS_TO, 'Departement', 'departement_id'),
            'ProductSupplier' => array(self::BELONGS_TO, 'ProductSupplier', 'supplier_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'code' => 'Code',
            'departement_id' => 'Departement',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'customer_user_id' => 'Customer',
            'modified' => 'Modified',
            'description' => 'Description',
            'subtotal' => 'Subtotal',
            'discount' => 'Discount',
            'ppn' => 'Ppn',
            'other' => 'other',
            'term' => 'Term',
            'dp' => 'Dp',
            'credit' => 'Credit',
            'payment' => 'Payment',
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
        $criteria->with = array('Departement','User','Customer');
        $criteria->together = true;

        $criteria->compare('id', $this->id);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('Departement.name',$this->departement_id,true);
        $criteria->compare('Customer.username',$this->customer_user_id,true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('subtotal', $this->subtotal);
        $criteria->compare('discount', $this->discount, true);
        $criteria->compare('ppn', $this->ppn);
        $criteria->compare('other', $this->other);
        $criteria->compare('term', $this->term, true);
        $criteria->compare('dp', $this->dp);
        $criteria->compare('credit', $this->credit);
        $criteria->compare('payment', $this->payment);

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

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SellOrder the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
