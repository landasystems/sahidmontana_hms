<?php

/**
 * This is the model class for table "{{play_result}}".
 *
 * The followings are the available columns in table '{{play_result}}':
 * @property integer $id
 * @property string $date_from
 * @property string $date_to
 * @property string $date_number
 * @property string $number
 * @property string $output
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class PlayResult extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{play_result}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_user_id', 'numerical', 'integerOnly' => true),
            array('number', 'length', 'max' => 4),
            array('output', 'length', 'max' => 3),
            array('date_from, date_to, date_number, created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, date_from, date_to, date_number, number, output, created, created_user_id, modified', 'safe', 'on' => 'search'),
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
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'date_number' => 'Date Number',
            'number' => 'Number',
            'output' => 'Output',
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
        $criteria->compare('date_from', $this->date_from, true);
        $criteria->compare('date_to', $this->date_to, true);
        $criteria->compare('date_number', $this->date_number, true);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('output', $this->output, true);
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
     * @return PlayResult the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function type() {
        $result = array();
        $result['s'] = 'Singapore';
        $result['h'] = 'Hongkong';
        return $result;
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
