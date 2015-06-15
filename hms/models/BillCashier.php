<?php

/**
 * This is the model class for table "{{bill_cashier}}".
 *
 * The followings are the available columns in table '{{bill_cashier}}':
 * @property integer $id
 * @property string $created
 * @property integer $created_user_id
 * @property integer $approved_user_id
 */
class BillCashier extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{bill_cashier}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_user_id, approved_user_id', 'numerical', 'integerOnly' => true),
            array('created', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, created, created_user_id, approved_user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(            
            'Approved' => array(self::BELONGS_TO, 'User', 'approved_user_id'),
            'Cashier' => array(self::BELONGS_TO, 'User', 'created_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'approved_user_id' => 'Approved User',
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
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);        
        $criteria->compare('approved_user_id', $this->approved_user_id);
        $criteria->compare('created_user_id', Yii::app()->user->id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BillCashier the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

//    public function behaviors() {
//        return array(
//            'timestamps' => array(
//                'class' => 'zii.behaviors.CTimestampBehavior',
//                'createAttribute' => 'created',
//                'updateAttribute' => 'modified',
//                'setUpdateOnCreate' => true,
//            ),
//        );
//    }

//    protected function beforeValidate() {
//        if (empty($this->created_user_id))
//            $this->created_user_id = Yii::app()->user->id;
//        return parent::beforeValidate();
//    }

}
