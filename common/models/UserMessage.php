<?php

/**
 * This is the model class for table "{{user_message}}".
 *
 * The followings are the available columns in table '{{user_message}}':
 * @property integer $id
 * @property integer $created_user_id
 * @property string $receiver_user_ids
 * @property string $last_date
 * @property string $last_message
 * @property string $count_messages
 */
class UserMessage extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserMessage the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_message}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, user_id_opp', 'numerical', 'integerOnly' => true),
            array('', 'length', 'max' => 255),
//            array('last_message', 'length', 'max' => 45),
            array('count_messages', 'length', 'max' => 3),
            array('last_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, last_date, last_message, count_messages', 'safe', 'on' => 'search'),
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
            'receiver_user_ids' => 'Receiver User Ids',
            'last_date' => 'Last Date',
            'last_message' => 'Last Title',
            'count_messages' => 'Count Messages',
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
        $criteria->compare('last_date', $this->last_date, true);
        $criteria->compare('last_message', $this->last_message, true);
        $criteria->compare('count_messages', $this->count_messages, true);
        $criteria->compare('count_messages','>0');
        $criteria->compare('user_id', user()->id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function unread() {
        return $this->findAll(array('limit' => 10, "condition" => 'user_id = ' . user()->id . ' AND is_read=0'));
    }

    public function insertMsg($user_id, $user_id_opp) {

        $modelUserMessage = $this->find(array('condition' => 'user_id=' . $user_id . ' AND user_id_opp=' . $user_id_opp));

        if (empty($modelUserMessage)) {
            $modelUserMessage = new UserMessage;
            //$modelUserMessage->receiver_user_ids = json_encode($_POST['receiver_user_ids']);
            $modelUserMessage->count_messages = 1;
        } else {
            $modelUserMessage->count_messages += 1;
        }
        $modelUserMessage->user_id = $user_id;
        $modelUserMessage->user_id_opp = $user_id_opp;
        $modelUserMessage->last_date = date('Y-m-d H:i');
        $modelUserMessage->last_message = substr(strip_tags($_POST['UserMessageDetail']['message']),0,45);
        $modelUserMessage->is_read = ($user_id == user()->id) ? true : false;
        $modelUserMessage->save();

        $model = new UserMessageDetail;
        $model->attributes = $_POST['UserMessageDetail'];
        $model->user_message_id = $modelUserMessage->id;
        $model->save();

        return array('UserMessageId' => $modelUserMessage->id, 'UserMessageDetailId' => $model->id);
    }

    public function insertMsgMass($user_id, $user_id_opps, $type = 'mass', $type_roles_ids = array()) {
        arsort($user_id_opps);
        $opp = json_encode($user_id_opps);
        $modelUserMessage = $this->find(array('condition' => 'user_id=' . $user_id . ' AND type_id_opp=\'' . $opp . '\''));
        if (empty($modelUserMessage)) {
            $modelUserMessage = new UserMessage;
            $modelUserMessage->count_messages = 1;
        } else {
            $modelUserMessage->count_messages += 1;
        }
        $modelUserMessage->user_id = $user_id;
        $modelUserMessage->last_date = date('Y-m-d H:i');
        $modelUserMessage->last_message = substr(strip_tags($_POST['UserMessageDetail']['message']),0,45);
        $modelUserMessage->is_read = ($user_id == user()->id) ? true : false;
        $modelUserMessage->type = $type;
        $modelUserMessage->type_id_opp = json_encode($user_id_opps);

        $modelUserMessage->save();

        foreach ($user_id_opps as $user_id_opp) {
            $this->insertMsg($user_id_opp, $user_id);
        }

        $model = new UserMessageDetail;
        $model->attributes = $_POST['UserMessageDetail'];
        $model->user_message_id = $modelUserMessage->id;
        $model->save();

        return array('UserMessageId' => $modelUserMessage->id, 'UserMessageDetailId' => $model->id);
    }

    public function insertMsgGroup($user_id, $user_id_opps, $type = 'group', $type_roles_ids) {
        arsort($type_roles_ids);
        $opp = json_encode($type_roles_ids);
        $modelUserMessage = $this->find(array('condition' => 'user_id=' . $user_id . ' AND type_roles_ids=\'' . $opp . '\''));
        if (empty($modelUserMessage)) {
            $modelUserMessage = new UserMessage;
            $modelUserMessage->count_messages = 1;
        } else {
            $modelUserMessage->count_messages += 1;
        }
        $modelUserMessage->user_id = $user_id;
        $modelUserMessage->last_date = date('Y-m-d H:i');
        $modelUserMessage->last_message = substr(strip_tags($_POST['UserMessageDetail']['message']),0,45);
        $modelUserMessage->is_read = ($user_id == user()->id) ? true : false;
        $modelUserMessage->type = $type;
        $modelUserMessage->type_id_opp = json_encode($user_id_opps);
        $modelUserMessage->type_roles_ids = json_encode($type_roles_ids);

        $modelUserMessage->save();

        foreach ($user_id_opps as $user_id_opp) {
            $this->insertMsg($user_id_opp, $user_id);
        }

        $model = new UserMessageDetail;
        $model->attributes = $_POST['UserMessageDetail'];
        $model->user_message_id = $modelUserMessage->id;
        $model->save();

        return array('UserMessageId' => $modelUserMessage->id, 'UserMessageDetailId' => $model->id);
    }

    public function getTagImg() {
        $foto = ($this->type == 'group') ? '<a title="Roles" rel="tooltip"><i style="color:#468847" class="icon large wpzoom-shield"></a>' :
                (($this->type == 'mass') ? '<a title="Multiple User" rel="tooltip"><i style="color:#f89406" class="icon  entypo-icon-users"></a>' :
                        '<a title="User" rel="tooltip"><i style="color:currentcolor" class="entypo-icon-user"></a>');
        ;
        return $foto;
    }

    public function getName() {
        $listUser = User::model()->listUser();
        $sResult = '';
        $SingleUser = '';
        $hasil = '';



        if ($this->type == 'group') {
            $count = 1;
            foreach (json_decode($this->type_roles_ids) as $arrReceiver) {
                $sResult .= '-> ' . substr(ucwords($arrReceiver),0,25) . '<br> ';
                $count++;
                if ($count > 4) {
                    $sResult .= '....';
                    break;
                }
            }
        } else if ($this->type == 'mass') {
            $count = 1;
            foreach (json_decode($this->type_id_opp) as $arrReceiver) {
                $sResult .= '-> ' . ucwords($listUser[$arrReceiver]['name']) . '<br> ';
                $count++;
                if ($count > 4) {
                    $sResult .= '....';
                    break;
                }
            }
        } else {
            $sResult = '-> ' . ucwords($listUser[$this->user_id_opp]['name']) . '';
        }
        //$hasil=($this->type =='group') ? $sResult : $SingleUser ;
        return '<div class="row-fluid">
                    <div class="span12" style="text-align:left">
                        ' . $sResult . '
                    </div>
                </div>
                
                ';
    }

    public function getTgl() {
        return date('d F Y', strtotime($this->last_date));
    }

    public function getContent() {
        return'<div class="row-fluid">
                    
                    <div class="span8" style="text-align:left">
                        ' . strip_tags($this->last_message) . '
                    </div>
                </div>';
    }

    public function getMessage() {
        return'<div class="row-fluid">
                    
                    <div class="span8" style="text-align:left">
                        Have <b>' . $this->count_messages . '</b> Message.
                    </div>
                </div>';
    }

}
