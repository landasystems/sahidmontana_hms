<?php

/**
 * This is the model class for table "{{out}}".
 *
 * The followings are the available columns in table '{{out}}':
 * @property integer $id
 * @property string $code
 * @property string $type
 * @property integer $departement_id
 * @property string $description
 * @property string $created
 * @property string $created_user_id
 * @property string $modified
 */
class Out extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{out}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('departement_id', 'numerical', 'integerOnly' => true),
            array('departement_id,type','required'),
            array('code, created, created_user_id, modified', 'length', 'max' => 45),
            array('type', 'length', 'max' => 8),
            array('description', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, code, description, created, created_user_id, modified', 'safe', 'on' => 'search'),
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
            'Departement' => array(self::BELONGS_TO, 'Departement', 'departement_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'code' => 'Code',
            'type' => 'Type',
            'departement_id' => 'Departement',
            'description' => 'Description',
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
        $criteria->with = array('Departement');
        $criteria->together = true;

        

        $criteria->compare('id', $this->id);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('Departement.name',$this->departement_id,true);

        $criteria->compare('description', $this->description, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id, true);
        $criteria->compare('modified', $this->modified, true);



        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Out the static model class
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
