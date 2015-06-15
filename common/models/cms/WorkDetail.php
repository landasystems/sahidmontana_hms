<?php

/**
 * This is the model class for table "{{work_detail}}".
 *
 * The followings are the available columns in table '{{work_detail}}':
 * @property integer $id
 * @property integer $work_id
 * @property string $time_start
 * @property string $time_end
 * @property double $time_total
 */
class WorkDetail extends CActiveRecord {

    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return WorkDetail the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{work_detail}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('work_id', 'numerical', 'integerOnly' => true),
            array('time_total', 'numerical'),
            array('time_start, time_end', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, work_id, time_start, time_end, time_total', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Work' => array(self::BELONGS_TO, 'Work', 'work_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'work_id' => 'Work',
            'time_start' => 'Time Start',
            'time_end' => 'Time End',
            'time_total' => 'Time Total',
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
        $criteria->compare('work_id', $this->work_id);
        $criteria->compare('time_start', $this->time_start, true);
        $criteria->compare('time_end', $this->time_end, true);
        $criteria->compare('time_total', $this->time_total);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    

}