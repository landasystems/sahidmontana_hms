<?php

/**
 * This is the model class for table "{{charge_additional}}".
 *
 * The followings are the available columns in table '{{charge_additional}}':
 * @property integer $id
 * @property integer $charge_additional_category_id
 * @property string $name
 * @property integer $charge
 * @property string $description
 */
class ChargeAdditional extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{charge_additional}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('charge_additional_category_id,account_id,name,charge,type_transaction', 'required'),
            array('charge_additional_category_id,account_id, charge,discount,acc_coa_id', 'numerical', 'integerOnly' => true),
            array('name,type_transaction', 'length', 'max' => 45),
            array('description', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, is_publish,charge_additional_category_id, name, charge, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ChargeAdditionalCategory' => array(self::BELONGS_TO, 'ChargeAdditionalCategory', 'charge_additional_category_id'),
            'Account' => array(self::BELONGS_TO, 'Account', 'account_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'charge_additional_category_id' => 'Departement',
            'name' => 'Name',
            'account_id' => 'Account',
            'charge' => 'Charge',
            'is_publish' => 'Publish',
            'description' => 'Note',
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
    public function search($export = '') {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('charge_additional_category_id', $this->charge_additional_category_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('charge', $this->charge);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('account_id', $this->account_id, true);
        $criteria->compare('type_transaction', $this->type_transaction);

        if (empty($export)) {
            $data = new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => array('defaultOrder' => 't.id DESC')
            ));
        } else {
            $data = ChargeAdditional::model()->findAll($criteria);
        }
        
        return $data;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ChargeAdditional the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getPrice() {
        return landa()->rp($this->charge);
    }

    public function getFullInitialCategory() {
        return $this->ChargeAdditionalCategory->code . ' - ' . $this->name;
    }

    public function getCategory() {
        return
                $this->ChargeAdditionalCategory->name

        ;
    }

    public function getFullTransaction() {
        $transaction = SiteConfig::model()->getStandartTransactionMalang();
        $type_transaction = array();
        foreach ($transaction as $key => $value) {
            $type_transaction[$key] = '[ ' . $key . ' ] - ' . ucwords($value);
        }
        return $type_transaction[$this->type_transaction];
    }

}
