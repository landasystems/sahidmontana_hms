<?php

/**
 * This is the model class for table "{{reservation_detail}}".
 *
 * The followings are the available columns in table '{{reservation_detail}}':
 * @property integer $id
 * @property integer $reservation_id
 * @property integer $room_id
 */
class ReservationDetail extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{reservation_detail}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('reservation_id, room_id', 'numerical', 'integerOnly' => true),
            array('charge,pax,guest_user_names,others_include,extrabed,room_price,extrabed_price,fnb_price', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, reservation_id, room_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Room' => array(self::BELONGS_TO, 'Room', 'room_id'),
            'Reservation' => array(self::BELONGS_TO, 'Reservation', 'reservation_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'reservation_id' => 'Reservation',
            'room_id' => 'Room',
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
        $criteria->compare('reservation_id', $this->reservation_id);
        $criteria->compare('room_id', $this->room_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ReservationDetail the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
