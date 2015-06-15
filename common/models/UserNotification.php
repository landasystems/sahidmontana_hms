<?php

/**
 * This is the model class for table "{{user_notification}}".
 *
 * The followings are the available columns in table '{{user_notification}}':
 * @property integer $id
 * @property string $title
 * @property string $message
 * @property string $created
 * @property string $created_user_id
 * @property string $receiver_user_id
 */
class UserNotification extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserNotification the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_notification}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('id', 'required'),
            array('id', 'numerical', 'integerOnly' => true),
            array('title, created_user_id, receiver_user_id', 'length', 'max' => 45),
            array('message, created', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, message, created, created_user_id, receiver_user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'UserDepartement' => array(self::BELONGS_TO, 'Departement', 'departement_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'message' => 'Message',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'receiver_user_id' => 'Receiver User',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id, true);
        $criteria->compare('receiver_user_id', $this->receiver_user_id, true);
        
        $criteria->addCondition('receiver_user_id='. user()->id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
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

    public function getIntro() {
       return substr(strip_tags($this->message), 0, 75)." ...";
    }
    
    public function getDate(){
        
       return  date('d-m-Y', strtotime($this->created));
    }

}