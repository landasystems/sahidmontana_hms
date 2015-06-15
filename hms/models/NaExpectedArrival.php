<?php

/**
 * This is the model class for table "{{na_expected_arrival}}".
 *
 * The followings are the available columns in table '{{na_expected_arrival}}':
 * @property integer $id
 * @property integer $na_id
 * @property integer $reservation_id
 */
class NaExpectedArrival extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{na_expected_arrival}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('na_id, reservation_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, na_id, reservation_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Reservation' => array(self::BELONGS_TO, 'Reservation', 'reservation_id'),
            'Na' => array(self::BELONGS_TO, 'Na', 'na_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'na_id' => 'Na',
            'reservation_id' => 'Reservation',
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
        $criteria->compare('na_id', $this->na_id);
        $criteria->compare('reservation_id', $this->reservation_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return NaExpectedArrival the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
