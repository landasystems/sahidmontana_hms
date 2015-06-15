<?php

/**
 * This is the model class for table "{{room}}".
 *
 * The followings are the available columns in table '{{room}}':
 * @property integer $id
 * @property string $number
 * @property integer $room_type_id
 * @property string $floor
 * @property string $bed
 * @property integer $linked_room_id
 */
class Room extends CActiveRecord {

    public $statusList;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{room}}';
    }

    public $count;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('registration_id, room_type_id, linked_room_id', 'numerical', 'integerOnly' => true),
            array('number', 'length', 'max' => 3),
            array('floor', 'length', 'max' => 2),
            array('bed', 'length', 'max' => 6),
            array('status,count,description,extrabed,pax,status_housekeeping,modified,modified_user_id', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, number, room_type_id, floor, bed, linked_room_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'number' => 'Number',
            'room_type_id' => 'Room Type',
            'floor' => 'Floor',
            'bed' => 'Bed',
            'linked_room_id' => 'Linked Room',
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'RoomType' => array(self::BELONGS_TO, 'RoomType', 'room_type_id'),
            'Registration' => array(self::BELONGS_TO, 'Registration', 'registration_id'),
            'RoomSchedule' => array(self::HAS_MANY, 'RoomSchedule', 'id'),
            'HouseKeeping' => array(self::BELONGS_TO, 'User', 'modified_user_id'),
        );
    }

    public function search() {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('room_type_id', $this->room_type_id);
        $criteria->compare('floor', $this->floor, true);
        $criteria->compare('bed', $this->bed, true);
        $criteria->compare('linked_room_id', $this->linked_room_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'number',),
//            'sort' => array ('defaultOrder' => 'linked_room_id',),
        ));
    }

    public function getStatusList() {
        return array('vacant', 'occupied', 'house use', 'compliment', 'dirty', 'occupied no luggage', 'do not disturb', 'out of order', 'sleep out', 'vacant inspect');
    }

    public function todayStatus() {

        $result = array('vacant'=>0, 'occupied'=>0, 'house use'=>0, 'compliment'=>0, 'dirty'=>0, 'occupied no luggage'=>0, 'do not disturb'=>0, 'out of order'=>0, 'sleep out'=>0, 'vacant inspect'=>0);
        $model = $this->findAll();
        foreach ($model as $m) {
            $result[$m->status] ++;
        }
        return $result;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Room the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getFloorList() {
        return array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10');
    }

    public function getBedList() {
        return array('king' => 'King', 'twin' => 'Twin');
    }

    public function getFullRoom() {
        if (!empty($this->registration_id)) {
            return '[' . $this->number . '] ' . ucwords($this->Registration->Guest->guestName);
        }
    }

    public function getGuestRegistered() {
        if (!empty($this->registration_id)) {
            return '[' . date('Y-m-d', strtotime($this->Registration->created)) . '] ' . $this->Registration->Guest->guestName;
        }
    }

    public function getRoomType() {
        return $this->RoomType->id;
    }

    public function getGuestName() {
        $guestName = '';
        if (!empty($this->registration_id)) {
            $detail = RegistrationDetail::model()->findByAttributes(array('registration_id' => $this->registration_id, 'room_id' => $this->id));
            if (!empty($detail)) {
                $guestName = nl2br($detail->guest_user_names);
            }
        }
        return $guestName;
    }

    public function getNestedName() {
        if (!empty($this->registration_id))
            return (empty($this->linked_room_id)) ? '[' . $this->number . '] ' . $this->Registration->Guest->name : "|— " . '[' . $this->number . '] ' . $this->Registration->Guest->name;
    }

}
