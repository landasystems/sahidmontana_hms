<?php

/**
 * This is the model class for table "{{sms_detail}}".
 *
 * The followings are the available columns in table '{{sms_detail}}':
 * @property integer $id
 * @property integer $sms_id
 * @property string $message
 * @property integer $created_user_id
 * @property string $created
 * @property string $modified
 * @property integer $is_process
 */
class SmsDetail extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{sms_detail}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sms_id, created_user_id, is_process, is_autoreply', 'numerical', 'integerOnly' => true),
            array('message, created, modified, status', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sms_id, message, created_user_id, created, modified, is_process', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Sms' => array(self::BELONGS_TO, 'Sms', 'sms_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'sms_id' => 'Sms',
            'message' => 'Message',
            'created_user_id' => 'Created User',
            'created' => 'Created',
            'modified' => 'Modified',
            'is_process' => 'Is Process',
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
        $criteria->compare('sms_id', $this->sms_id);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('is_process', $this->is_process);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function labelStatus($status="") {
        $result = '';
        if ($status == 'sending') {
            $result = '<label class="label">Sending...</label>';
        } elseif ($status == 'SendingError') {
            $result = '<label class="label label-important">Not Send</label>';
        } elseif ($status == 'SendingOK') {
            $result = '<label class="label label-info">Sent</label>';
        }
        return $result;
    }

    public function outbox() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('is_process', 0);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function inbox() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('date_received', '<>NULL');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getName() {
        if (strlen($this->Sms->phone) < 6)
            $prefix = "";
        else
            $prefix = "+62";
        $listUserPhone = User::model()->listUserPhone();
        $sResult = '';
        if ($this->Sms->type == 'group') {
            $count = 1;
            foreach (json_decode($this->Sms->type_roles_ids) as $arrReceiver) {
                $sResult .= '-> ' . substr(ucwords($arrReceiver), 0, 25) . '<br> ';
                $count++;
                if ($count > 4) {
                    $sResult .= '....';
                    break;
                }
            }
        } else if ($this->Sms->type == 'mass') {
            $count = 1;
            foreach (json_decode($this->Sms->type_phones) as $arrReceiver) {
                $sResult .= '-> ' . ucwords($listUserPhone[$arrReceiver]['name']) . '<br> ';
                $count++;
                if ($count > 4) {
                    $sResult .= '....';
                    break;
                }
            }
        } else if ($this->Sms->type == 'phone') {
            if (array_key_exists($this->Sms->phone, $listUserPhone))
                $sResult = '-> ' . ucwords($listUserPhone[$this->Sms->phone]['name']) . '';
            else
                $sResult = '-> ' . $prefix . $this->Sms->phone . '';
        } else {
            $sResult = '-> ' . ucwords($listUserPhone[$this->Sms->phone]['name']) . '';
        }
        return '<div class="row-fluid">
                    <div class="span12" style="text-align:left">
                        ' . $sResult . '
                    </div>
                </div>
                
                ';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SmsDetail the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
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
        if (empty($this->created_user_id) && isset(user()->id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

}
