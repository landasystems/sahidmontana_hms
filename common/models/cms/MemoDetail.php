<?php

/**
 * This is the model class for table "{{ticket_detail}}".
 *
 * The followings are the available columns in table '{{ticket_detail}}':
 * @property integer $id
 * @property integer $ticket_id
 * @property string $message
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class MemoDetail extends CActiveRecord {

//    public function getDbConnection() {
//        return Yii::app()->db2;
//    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TicketDetail the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{ticket_detail}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ticket_id, created_user_name', 'numerical', 'integerOnly' => true),
            array('message, created, modified', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, ticket_id, message, created, created_user_name, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Ticket' => array(self::BELONGS_TO, 'Memo', 'ticket_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'ticket_id' => 'Ticket',
            'message' => 'Message',
            'created' => 'Created',
            'created_user_name' => 'Created User',
            'modified' => 'Modified',
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
        $criteria->compare('ticket_id', $this->ticket_id);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_name', $this->created_user_name);
        $criteria->compare('modified', $this->modified, true);

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
            )
        );
    }

    protected function beforeValidate() {
        if (empty($this->created_user_name))
            $this->created_user_name = Yii::app()->user->id;
        return parent::beforeValidate();
    }

}