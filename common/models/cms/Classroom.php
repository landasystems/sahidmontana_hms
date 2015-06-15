<?php

/**
 * This is the model class for table "{{classroom}}".
 *
 * The followings are the available columns in table '{{classroom}}':
 * @property integer $id
 * @property integer $school_year_id
 * @property string $name
 * @property string $description
 */
class Classroom extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Classroom the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{classroom}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, school_year_id', 'required'),
            array('school_year_id', 'numerical', 'integerOnly' => true),
            array('name, description', 'length', 'max' => 255),
            array('id','safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, school_year_id, name, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'SchoolYear' => array(self::BELONGS_TO, 'SchoolYear', 'school_year_id'),
            'Test' => array(self::HAS_MANY, 'Test', 'id'),
            'UserClassroom'=>array(self::HAS_MANY,'UserClassroom','id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'school_year_id' => 'Tahun Ajaran',
            'name' => 'Kelas',
            'description' => 'Description',
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
        $criteria->compare('school_year_id', $this->school_year_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getHaventClass() {
        $results = cmd()
                ->select('id, name')
                ->from('acca_user')
                ->where('id NOT IN (SELECT user_id FROM acca_user_classroom)')
                ->queryAll();
        return $results;
    }

}