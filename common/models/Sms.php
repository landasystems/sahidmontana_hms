<?php

/**
 * This is the model class for table "{{sms}}".
 *
 * The followings are the available columns in table '{{sms}}':
 * @property integer $id
 * @property integer $created_user_id
 * @property string $last_date
 * @property string $last_message
 * @property integer $count_message
 * @property integer $is_read
 * @property string $phone
 * @property string $type
 * @property string $type_phones
 * @property string $type_roles_ids
 */
class Sms extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{sms}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_user_id, count_message, is_read', 'numerical', 'integerOnly' => true),
            array('phone', 'length', 'max' => 45),
            array('type', 'length', 'max' => 255),
            array('last_date, last_message, type_phones, type_roles_ids', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, created_user_id, last_date, last_message, count_message, is_read, phone, type, type_phones, type_roles_ids', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'created_user_id' => 'Created User',
            'last_date' => 'Last Date',
            'last_message' => 'Last Message',
            'count_message' => 'Count Message',
            'is_read' => 'Is Read',
            'phone' => 'Phone',
            'type' => 'Type',
            'type_phones' => 'Type Phones',
            'type_roles_ids' => 'Type Roles Ids',
        );
    }

    public function insertMsg($user_id, $user_id_opp, $text, $status = false) {
        //check id send is user id or phone
        if ($status == false) {
            $listUser = User::model()->listUser();
            $phone = $listUser[$user_id_opp]['phone'];
        } else {
            $phone = $user_id_opp;
        }
        $modelUserMessage = $this->find(array('condition' => 'phone=' . $phone));
        if (empty($modelUserMessage)) {
            $modelUserMessage = new Sms;
            $modelUserMessage->count_message = 1;
        } else {
            $modelUserMessage->count_message += 1;
        }
        $modelUserMessage->is_read = (empty($user_id)) ? 0 : 1;
        $modelUserMessage->type = 'user';
        $modelUserMessage->phone = $phone;
        $modelUserMessage->last_date = date('Y-m-d H:i');
        $modelUserMessage->last_message = substr(strip_tags($text), 0, 45);
        $modelUserMessage->save();

        $model = new SmsDetail;
        $model->message = $text;
        $model->sms_id = $modelUserMessage->id;
        $model->status = 'sending';
        $model->save();
        return array('UserMessageDetailId' => $model->id);
    }

    public function insertMsgMass($user_id, $user_id_opps, $text, $type = 'mass', $status = false) {
        //check id send is user id or phone
        if ($status == false) {
            $listUser = User::model()->listUser();
            arsort($user_id_opps);
            foreach ($user_id_opps as $value) {
                $phone[] = $listUser[$value]['phone'];
            }
        } else {
            $phone = json_decode($user_id_opps);
        }
        $opp = json_encode($phone);
        $modelUserMessage = $this->find(array('condition' => 'type_phones=\'' . $opp . '\''));
        if (empty($modelUserMessage)) {
            $modelUserMessage = new Sms;
            $modelUserMessage->count_message = 1;
        } else {
            $modelUserMessage->count_message += 1;
        }
        $modelUserMessage->is_read = (empty($user_id)) ? 0 : 1;
        $modelUserMessage->type = $type;
        $modelUserMessage->last_date = date('Y-m-d H:i');
        $modelUserMessage->last_message = substr(strip_tags($text), 0, 45);
        $modelUserMessage->type_phones = $opp;
        $modelUserMessage->save();

        $model = new SmsDetail;
        $model->message = $text;
        $model->sms_id = $modelUserMessage->id;
        $model->status = 'sending';
        $model->save();
        return array('UserMessageDetailId' => $model->id);
    }

    public function insertMsgGroup($user_id, $user_id_opps, $text, $type = 'group', $type_roles_ids, $status = false) {
        //check id send is user id or phone
        if ($status == false) {
            $listUser = User::model()->listUser();
            foreach ($user_id_opps as $value) {
                if (isset($listUser[$value]['phone']) && !empty($listUser[$value]['phone']))
                    $phone[] = $listUser[$value]['phone'];
            }
        } else {
            $phone = json_decode($user_id_opps);
        }

        arsort($type_roles_ids);
        $opp = json_encode($type_roles_ids);
        $modelUserMessage = $this->find(array('condition' => 'type_roles_ids=\'' . $opp . '\' AND type="' . $type . '"'));
        if (empty($modelUserMessage)) {
            $modelUserMessage = new Sms;
            $modelUserMessage->count_message = 1;
        } else {
            $modelUserMessage->count_message += 1;
        }
        $modelUserMessage->is_read = (empty($user_id)) ? 0 : 1;
        $modelUserMessage->last_date = date('Y-m-d H:i');
        $modelUserMessage->last_message = substr(strip_tags($text), 0, 45);
        $modelUserMessage->type = $type;
        $modelUserMessage->type_phones = json_encode($phone);
        $modelUserMessage->type_roles_ids = json_encode($type_roles_ids);
        $modelUserMessage->save();

        $model = new SmsDetail;
        $model->message = $text;
        $model->sms_id = $modelUserMessage->id;
        $model->status = 'sending';
        $model->save();
        return array('UserMessageDetailId' => $model->id);
    }

    //inbox & sent items, use this function
    public function insertMsgNumber($user_id, $phone, $text = '', $inbox = FALSE, $date = '', $is_autoreply = false) {
        if (empty($date))
            $date = date('Y-m-d H:i');

        $userPhone = User::model()->find(array('condition' => 'phone=\'' . $phone . '\''));
        if (empty($userPhone)) {
            $modelUserMessage = $this->find(array('condition' => 'phone=\'' . $phone . '\''));
            if (empty($modelUserMessage)) {
                $modelUserMessage = new Sms;
                $modelUserMessage->count_message = 1;
                $modelUserMessage->phone = $phone;
                $modelUserMessage->type = 'phone';
            } else {
                $modelUserMessage->count_message += 1;
                $modelUserMessage->phone = $phone;
            }
        } else {
            $modelUserMessage = $this->find(array('condition' => 'phone=\'' . $userPhone->phone . '\''));
            if (empty($modelUserMessage)) {
                $modelUserMessage = new Sms;
                $modelUserMessage->count_message = 1;
                $modelUserMessage->phone = $userPhone->phone;
            } else {
                $modelUserMessage->count_message += 1;
                $modelUserMessage->phone = $userPhone->phone;
            }
            $modelUserMessage->type = 'user';
        }

        $model = new SmsDetail;

        if ($inbox == FALSE) {
            $textMessage = $text;
            $modelUserMessage->last_date = date('Y-m-d H:i');
            $model->status = 'sending';
        } else {
            $textMessage = $text;
            $model->is_process = 1;
            $modelUserMessage->last_date = date('Y-m-d H:i', strtotime($date));
            $model->date_received = date('Y-m-d H:i', strtotime($date));
        }
        $modelUserMessage->is_read = (empty($user_id)) ? 0 : 1;
        $modelUserMessage->last_message = substr(strip_tags($textMessage), 0, 45);
        $modelUserMessage->save();

        $model->message = $text;
        $model->sms_id = $modelUserMessage->id;
        $model->is_autoreply = ($is_autoreply) ? 1 : 0;
        $model->save();
        return array('UserMessageDetailId' => $model->id);
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('last_date', $this->last_date, true);
        $criteria->compare('last_message', $this->last_message, true);
        $criteria->compare('count_message', $this->count_message);
        $criteria->compare('is_read', $this->is_read);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('type_phones', $this->type_phones, true);
        $criteria->compare('type_roles_ids', $this->type_roles_ids, true);
        $criteria->compare('count_message', '>0');
        $criteria->order = "last_date DESC";

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function button() {
        if (app()->name == 'Academic Management Systems') {
            $results = array(
                array('label' => 'Group', 'active' => true, 'htmlOptions' => array(
                        'data-field' => 'Model_left',
                        'data-value' => 'group',
                    )),
                array('label' => 'Kontak', 'htmlOptions' => array(
                        'data-field' => 'Model_middle',
                        'data-value' => 'user',
                    )),
                array('label' => 'Ketik Nomor', 'htmlOptions' => array(
                        'data-field' => 'Model_right',
                        'data-value' => 'phone',
                    )),
                array('label' => 'Kelas', 'htmlOptions' => array(
                        'data-field' => 'Model_right',
                        'data-value' => 'classroom',
                    )),
            );
        } else {
            $results = array(
                array('label' => 'Group', 'active' => true, 'htmlOptions' => array(
                        'data-field' => 'Model_left',
                        'data-value' => 'group',
                    )),
                array('label' => 'Kontak', 'htmlOptions' => array(
                        'data-field' => 'Model_middle',
                        'data-value' => 'user',
                    )),
                array('label' => 'Ketik Nomor', 'htmlOptions' => array(
                        'data-field' => 'Model_right',
                        'data-value' => 'phone',
                    )),
            );
        }

        return $results;
    }

    public function unread() {
        return $this->findAll(array('limit' => 10, "condition" => 'is_read=0', 'order' => 'last_message DESC'));
    }

    public function getTagImg() {
        if (empty($this->created_user_id))
            $color = "currentcolor";
        else
            $color = "currentcolor";

        $foto = ($this->type == 'group') ? '<a title="Roles" rel="tooltip"><i style="color:' . $color . '" class="icon large wpzoom-shield"></a>' :
                (($this->type == 'mass') ? '<a title="Multiple User" rel="tooltip"><i style="color:' . $color . '" class="icon  entypo-icon-users"></a>' :
                        (($this->type == 'phone') ? '<a title="Phone Number" rel="tooltip"><i style="color:' . $color . '" class="icon brocco-icon-address-book"></a>' :
                                '<a title="User" rel="tooltip"><i style="color:' . $color . '" class="entypo-icon-user"></a>'));
        ;
        return $foto;
    }

    public function getName() {

        $listUserPhone = User::model()->listUserPhone();
        $listRoles = Roles::model()->listRoles();
        $sResult = '';
        if ($this->type == 'group') {
            $count = 1;
            foreach (json_decode($this->type_roles_ids) as $arrReceiver) {
                $roles = (isset($listRoles[$arrReceiver])) ? $listRoles[$arrReceiver]->name : '';
                $sResult .= '-> ' . $roles . '<br> ';
                $count++;
                if ($count > 4) {
                    $sResult .= '....';
                    break;
                }
            }
        } else if ($this->type == 'mass') {
            $count = 1;
            foreach (json_decode($this->type_phones) as $arrReceiver) {
                $sResult .= '-> ' . ucwords($listUserPhone[$arrReceiver]['name']) . '<br> ';
                $count++;
                if ($count > 4) {
                    $sResult .= '....';
                    break;
                }
            }
        } else if ($this->type == 'phone') {
            if (array_key_exists($this->phone, $listUserPhone))
                $sResult = '-> ' . ucwords($listUserPhone[$this->phone]['name']) . '';
            else
                $sResult = '-> ' . landa()->hp($this->phone) . '';
        } else if ($this->type == 'student' || $this->type == 'parent') {
            if ($this->type=='parent')
                $sResult = '(Wali Murid)<br/>';
            
            $count = 1;
            $listClassroom = Classroom::model()->findAll(array('index' => 'id'));
            foreach (json_decode($this->type_roles_ids) as $arrReceiver) {
                $classroom = (isset($listClassroom[$arrReceiver])) ? $listClassroom[$arrReceiver]->name : '';
                $sResult .= '-> ' . $classroom . '<br> ';
                $count++;
                if ($count > 4) {
                    $sResult .= '....';
                    break;
                }
            }
            
        } else {
            $sName = (isset($listUserPhone[$this->phone]['name'])) ? ucwords($listUserPhone[$this->phone]['name']) : '-';
            $sResult = '-> ' . $sName . ' (' . landa()->hp($this->phone) . ')';
        }
        return '<div class="row-fluid">
                    <div class="span12" style="text-align:left">
                        ' . $sResult . '
                    </div>
                </div>
                
                ';
    }

    public function getTgl() {
        return date('d/m/Y H:i:s', strtotime($this->last_date));
    }

    public function getTotalMessage() {
        return'<div class="row-fluid">
                    
                    <div class="span8" style="text-align:left">
                         <b>' . $this->count_message . '</b> Messages.
                    </div>
                </div>';
    }

    public function getMessage() {
        return'<div class="row-fluid">
                    
                    <div class="span8" style="text-align:left">
                        ' . strip_tags($this->last_message) . '
                    </div>
                </div>';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    protected function beforeValidate() {
        if (empty($this->created_user_id) && isset(user()->id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

}
