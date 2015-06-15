<?php

/**
 * This is the model class for table "{{user_message_detail}}".
 *
 * The followings are the available columns in table '{{user_message_detail}}':
 * @property integer $id
 * @property integer $user_message_id
 * @property string $title
 * @property string $message
 * @property string $is_sender
 * @property string $created
 */
class UserMessageDetail extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserMessageDetail the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_message_detail}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_message_id, created_user_id', 'numerical', 'integerOnly' => true),
            array('', 'length', 'max' => 45),
//			array('is_sender', 'length', 'max'=>1),
            array('message, created', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, user_message_id, message, created', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'UserMessage' => array(self::BELONGS_TO, 'UserMessage', 'user_message_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_message_id' => 'User Message',
            'title' => 'Title',
            'message' => 'Message',
            'created' => 'Created',
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
        $criteria->compare('user_message_id', $this->user_message_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('created', $this->created, true);

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

}