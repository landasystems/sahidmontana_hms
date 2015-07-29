<?php

/**
 * This is the model class for table "{{invoice_det}}".
 *
 * The followings are the available columns in table '{{invoice_det}}':
 * @property integer $id
 * @property string $description
 * @property integer $user_id
 * @property double $payment
 * @property double $charge
 * @property string $type
 */
class InvoiceDet extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{invoice_det}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, id', 'numerical', 'integerOnly' => true),
            array('payment, charge', 'numerical'),
            array('description, code', 'length', 'max' => 255),
            array('type', 'length', 'max' => 15),
//            array('code', 'unique'),
            array('term_date, charge, is_new_invoice, code, description', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, term_date, is_new_invoice, description, code, user_id, payment, charge, type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'User' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'description' => 'Description',
            'user_id' => 'User',
            'payment' => 'Payment',
            'charge' => 'Charge',
            'type' => 'Type',
            'code' => 'Code Invoice',
            'term_date' => 'Tanggal Jatuh Tempo',
            'is_new_invoice' => 'Invoice Baru'
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
        $criteria->compare('description', $this->description, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('payment', $this->payment);
        $criteria->compare('charge', $this->charge);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('code', $this->code);
        $criteria->compare('term_date', $this->term_date);
        $criteria->compare('is_new_invoice', $this->is_new_invoice);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return InvoiceDet the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
