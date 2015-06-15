<?php

/**
 * This is the model class for table "{{forecast}}".
 *
 * The followings are the available columns in table '{{forecast}}':
 * @property integer $id
 * @property integer $tahun
 * @property string $forecast
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class Forecast extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{forecast}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tahun, created_user_id', 'numerical', 'integerOnly' => true),
            array('tahun', 'unique', 'message' => 'This year already exists.'),
            array('tahun', 'required'),
            array('modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, tahun, forecast,cover_forecast,other_forecast, created, created_user_id, modified', 'safe', 'on' => 'search'),
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
            'tahun' => 'Year',
            'forecast' => 'Forecast',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('tahun', $this->tahun);
        $criteria->compare('forecast', $this->forecast, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Forecast the static model class
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

    public function otherForcast() {
        $otherForcast = array('room occupied', 'house use', 'compliment', 'vacant room', 'out of order', 'room available', 
            'avg room rate rupiah', 'avg room rate dolar', 'percentage of occupancy', 'percentage of double occupancy', 'number of guest', 'sales coeficient');
        return $otherForcast;
    }

}
