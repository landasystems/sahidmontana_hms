<?php

/**
 * This is the model class for table "{{donation}}".
 *
 * The followings are the available columns in table '{{donation}}':
 * @property integer $id
 * @property string $description
 * @property integer $amount
 * @property integer $amount_less
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class Donation extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{donation}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('amount, amount_less, created_user_id', 'numerical', 'integerOnly' => true),
            array('description', 'length', 'max' => 255),
            array('created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id,type, description, amount, amount_less, created, created_user_id, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'description' => 'Description',
            'amount' => 'Amount',
            'amount_less' => 'Amount Less',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
            'type' => 'type',
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
//        $criteria->with = array('User');

        $criteria->compare('id', $this->id);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('amount_less', $this->amount_less);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);
        $criteria->addCondition('amount_less is not null AND amount_less != ""');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Donation the static model class
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
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

    public function getContributorName() {
        return $this->User->name;
    }

    public function getAmountAll() {
        return landa()->rp($this->amount);
    }

    public function getAmountLess() {
        return (isset($this->amount_less)) ? landa()->rp($this->amount_less) : '-';
    }

    public function gettanggal() {
        return date('d F Y', strtotime($this->created));
    }

    public function getCoin() {
        $coin = ($this->amount_less) / 50000;
        return '<img src="' . param('urlImg') . 'file/gold.png" style="width:20px; position: static;"/>' . $coin;
    }

    public function getUrlRequest() {
        $user = User::model()->findByPk(Yii::app()->user->id);
        $silver = json_decode($user->others, true);
        $btn_danger = "btn-danger";
        $btn_warning = "btn-warning";
        $btn_succes = "btn-success";

        if (user()->id == $this->created_user_id) {
            echo'<td></td>';
        } else {
            if ($silver['mlm_action'] == 'pasif') {
                echo'<td>
                    <button href="" onClick="js:bootbox.alert(\'hanya untuk memberi sumbangan saja.\')" value="' . $this->id . '" role="button" data-toggle="modal" class="btn ' . $btn_danger . '">
                        <i class="icon-ok"></i>
                    </button></td>
                ';
            } else {
                echo'<td>
                    <button href="#myModal" onClick="$(\'#id\').val($(this).val());$(\'#type\').val(\'request\');$(\'#created_user_id\').val(' . $this->created_user_id . ');$(\'#date_request\').val(\'' . $this->created . '\');$(\'#name_request\').val(\'' . $this->User->name . '\');" value="' . $this->id . '" role="button" data-toggle="modal" class="btn btn-success">
                        <i class="icon-ok"></i>
                    </button>
                </td>';
            }
        }
    }

    public function getUrlOffer() {
        $user = User::model()->findByPk(Yii::app()->user->id);
        $silver = json_decode($user->others, true);
        $btn_danger = "btn-danger";
        $btn_warning = "btn-warning";
        $btn_succes = "btn-success";
        if (user()->id == $this->created_user_id) {
            echo'';
        } else {
            if ($silver['mlm_action'] == 'active') {
                echo'
            <button href="" onClick="js:bootbox.alert(\'Hanya untuk memberi sumbangan saja.\')" value="' . $this->id . '" role="button" data-toggle="modal" class="btn ' . $btn_danger . '">
                <i class="icon-ok"></i>
            </button>';
            } else {
                echo'
            <button href="#myModaloffer" onClick="$(\'#id_offer\').val($(this).val());$(\'#type_offer\').val(\'offer\');$(\'#date_offer\').val(\'' . $this->created . '\');$(\'#name\').val(\'' . $this->User->name . '\');" value="' . $this->id . '" role="button" data-toggle="modal" class="btn btn-success">
                <i class="icon-ok"></i>
            </button>';
            }
        }
        if (user()->id == $this->created_user_id) {
            echo'
            <form action="' . Yii::app()->controller->createUrl('donation/confirmCancel') . '" method="post">
            <input type="hidden" name="created_donation" value="' . $this->created . '"/>
            <input type="hidden" name="id_donation" value="' . $this->id . '"/>
            <input type="hidden" name="name_donation" value="' . $this->User->name . '"/>
            <input type="hidden" name="amount_donation" value="' . $this->amount . '"/>
            <input type="hidden" name="amount_less" value="' . $this->amount_less . '"/>
            <input type="hidden" name="type_donation" value="' . $this->type . '"/>
            <input type="hidden" name="created_user_id_donation" value="' . $this->created_user_id . '"/>
            <button type="sumbit" class="btn btn-warning" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
            </form>';
        }
    }

    public function process($type, $saldo = 0, $coin, $donationGiveAmount) {
        $user = User::model()->findByPk(Yii::app()->user->id);

        $other = array();
        if ($type == 'request') {
            $others = json_decode($user->others, true);
            if (isset($others['mlm_silver'])) {
                $other['mlm_silver'] = $others['mlm_silver'] + ($coin * 15000);
            } else {
                $other['mlm_silver'] = $coin * 15000;
            }
            if (isset($others['mlm_gold_slot'])) {
                $other['mlm_gold_slot'] = $others['mlm_gold_slot'] + $donationGiveAmount;
            } else {
                $other['mlm_gold_slot'] = $donationGiveAmount;
            }
            if (isset($others['bank_name'])) {
                $other['bank_name'] = $others['bank_name'];
            } else {
                $other['bank_name'] = $others['bank_name'];
            }
            if (isset($others['bank_account'])) {
                $other['bank_account'] = $others['bank_account'];
            } else {
                $other['bank_account'] = $others['bank_account'];
            }
            if (isset($others['bank_account_name'])) {
                $other['bank_account_name'] = $others['bank_account_name'];
            } else {
                $other['bank_account_name'] = $others['bank_account_name'];
            }

            $user->saldo -= $saldo;
            $user->others = json_encode($other);
            $user->save();
        } elseif ($type == 'offer') {
            $others = json_decode($user->others, true);
            if (isset($others['mlm_silver'])) {
                $other['mlm_silver'] = $others['mlm_silver'];
            } else {
                $other['mlm_silver'] = $others['mlm_silver'];
            }
            if (isset($others['mlm_gold_slot'])) {
                $other['mlm_gold_slot'] = $others['mlm_gold_slot'] - $donationGiveAmount;
            } else {
                $other['mlm_gold_slot'] = $donationGiveAmount;
            }
            $user->saldo += $donationGiveAmount;
            $user->others = json_encode($other);
            $user->save();
        } elseif ($type == 'user_bank') {
            
        }

        Donation::model()->process($type, $saldo, $coin, $donationGiveAmount);
    }

    public function changeStatus() {
        $user = User::model()->findByPk(user()->id);
        $others = json_decode($user->others, true);

        if (isset($others['time_action']) && strtotime($others['time_action']) > time()) {
            //do nothing
        } else {
            $status = ($others['mlm_action'] == 'active') ? 'pasif' : 'active';
            $others['mlm_action'] = $status;

            $plus = (isset($settings['mlm_action_time'][user()->roles_id])) ? $settings['mlm_action_time'][user()->roles_id] : 7;
            $time_action = (isset($others['time_action'])) ? $others['time_action'] : date('Y-m-d, H:i');

            $siteConfig = SiteConfig::model()->listSiteConfig();
            $settings = $siteConfig->settings;
            $newdate = date('Y-m-d H:i', strtotime($time_action . '+ ' . $plus . 'day'));
            $others['time_action'] = $newdate;

            $user->others = json_encode($others);
            $user->save();
        }
    }

}
