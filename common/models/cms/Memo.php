<?php

/**
 * This is the model class for table "{{ticket}}".
 *
 * The followings are the available columns in table '{{ticket}}':
 * @property integer $id
 * @property integer $ticket_category_id
 * @property string $subject
 * @property string $status
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class Memo extends CActiveRecord {

    public $message;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Ticket the static model class
     */
//    public function getDbConnection() {
//        return Yii::app()->db2;
//    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{ticket}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('subject, client', 'required'),
            array('ticket_category_id, created_user_name', 'numerical', 'integerOnly' => true),
            array('subject', 'length', 'max' => 45),
            array('status', 'length', 'max' => 8),
            array('created, modified, message', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, ticket_category_id, subject, status, created, created_user_name, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'TicketCategory' => array(self::BELONGS_TO, 'MemoCategory', 'ticket_category_id'),
            'TicketDetail' => array(self::HAS_MANY, 'MemoDetail', 'id'),
            'User'=> array(self::BELONGS_TO,'User', 'created_user_name'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'ticket_category_id' => 'Ticket Category',
            'subject' => 'Subject',
            'status' => 'Status',
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
        $criteria->compare('ticket_category_id', $this->ticket_category_id);
        $criteria->compare('subject', $this->subject, true);
        $criteria->compare('status', $this->status, true);
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

    public function getNestedName() {
        return ($this->level == 1) ? $this->name : str_repeat("|—", $this->level - 1) . $this->name;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function getName(){
        $name = (isset($this->User->name)) ? $this->User->name : '-';
        return $name;
    }
    
    public function getTanggal(){
        $tanggal = (isset($this->created)) ? date('d F Y',strtotime($this->created)) : '-';
        return $tanggal;
    }

}
