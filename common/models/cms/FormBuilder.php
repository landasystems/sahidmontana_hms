<?php

/**
 * This is the model class for table "{{form_builder}}".
 *
 * The followings are the available columns in table '{{form_builder}}':
 * @property integer $id
 * @property integer $form_category_id
 * @property string $builder_result
 * @property string $created
 * @property string $modified
 * @property integer $created_user_id
 */
class FormBuilder extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{form_builder}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('form_category_id, created_user_id', 'numerical', 'integerOnly' => true),
            array('builder_result, created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, form_category_id, builder_result, created, modified, created_user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'FormCategory' => array(self::BELONGS_TO, 'FormCategory', 'form_category_id'),
            'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'No. Pendaftaran',
            'form_category_id' => 'Form Category',
            'builder_result' => 'Builder Result',
            'created' => 'Tanggal Pendaftaran',
            'modified' => 'Modified',
            'created_user_id' => 'Created User',
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
        $criteria->compare('form_category_id', $this->form_category_id);
        $criteria->compare('builder_result', $this->builder_result, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('created_user_id', $this->created_user_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
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

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FormBuilder the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getCc() {

        return $this->User->name;
    }

    public function getTgl() {
        return date('d F Y', strtotime($this->created));
    }

    public function getForm() {
        return $this->FormCategory->name;
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
