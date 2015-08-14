<?php

/**
 * This is the model class for table "{{account}}".
 *
 * The followings are the available columns in table '{{account}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $created_user_id
 * @property string $created
 * @property string $modified
 */
class Account extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{account}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_user_id,tax,service', 'numerical', 'integerOnly' => true),
            array('name, description', 'length', 'max' => 255),
            array('modified,', 'safe'),
            array('acc_coa_id, name', 'required'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, description, created_user_id, created, modified, acc_coa_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Note',
            'created_user_id' => 'Created User',
            'created' => 'Created',
            'modified' => 'Modified',
            'acc_coa_id' => 'Acc Coa Id',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id ASC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Account the static model class
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

    public function getNet() {
        return (($this->tax + $this->service) / 100) + 1;
    }

}
