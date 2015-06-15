<?php

/**
 * This is the model class for table "{{sms_keyword}}".
 *
 * The followings are the available columns in table '{{sms_keyword}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $options
 * @property string $autoreplys
 */
class SmsKeyword extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{sms_keyword}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
        return array(
            array('', 'length', 'max' => 45),
            array('name, description', 'length', 'max' => 255),
            array('type', 'length', 'max' => 20),
            array('options, autoreplys', 'safe'),
            // The following rule is used by search().
// @todo Please remove those attributes that should not be searched.
            array('id, name, description, type, options, autoreplys', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Keyword',
            'description' => 'Description',
            'type' => 'Type',
            'options' => 'Options',
            'autoreplys' => 'Autoreplys',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('options', $this->options, true);
        $criteria->compare('autoreplys', $this->autoreplys, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SmsKeyword the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function ses() {
        if (empty(Yii::app()->session['SmsKeyword'])) {
            app()->session['SmsKeyword'] = $this->findAll();
        }
        return app()->session['SmsKeyword'];
    }

    public function type() {
        $result = array();
        $result['info'] = 'Informasi';
        $result['register'] = 'Register';
        $result['register_reff'] = 'Register Referal';
        $result['update'] = 'Update';
        if (in_array('game', param('menu'))) {
            $result['saldo'] = 'Saldo';
            $result['transfer'] = 'Transfer';
            $result['2d'] = 'Play 2D';
            $result['3d'] = 'Play 3D';
            $result['4d'] = 'Play 4D';
            $result['cj_satuan'] = 'Colok Jitu Satuan';
            $result['cj_puluhan'] = 'Colok Jitu Puluhan';
            $result['cj_ratusan'] = 'Colok Jitu Ratusan';
            $result['cj_ribuan'] = 'Colok Jitu Ribuan';
            $result['cr'] = 'Colok Raun';
            $result['deposit'] = 'Deposit';
            $result['withdrawal'] = 'Withdrawal';
            $result['playresult'] = 'Rekap Pemasangan';
            $result['result'] = 'Keluaran Angka';
        }
        return $result;
    }

    public function getKey() {
        $result = explode("#", $this->name);
        return (isset($result[0])) ? strtolower($result[0]) : '';
    }

    public function check($text, $phone) {
        $sDisabled = 'Account anda sedang non aktif,selesaikan tagihan Anda terlebih dahulu. Silahkan kontak Customer Service kami';
        $is_keyword = FALSE;
        $arrText = explode("#", strtolower(trim($text)));
        $sesSmsKeyword = $this->findAll();
//        logs("aaaa");
        foreach ($sesSmsKeyword as $val) {
            if ($val->key == $arrText[0]) {
                $is_keyword = TRUE;
                $arrOptions = json_decode($val->options, true);
                $arrAutoreplys = json_decode($val->autoreplys, true);
                $arrVal = explode("#", strtolower(trim($val->name)));

//check wrong keyword
                if (count($arrText) == count($arrVal)) {
//do nothing
                } else {
                    $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_register']);
                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                    break;
                }
                if ($val->type == 'register') {

//check number is register or not
                    $oUser = User::model()->find(array('condition' => 'phone="' . $phone . '"'));
                    if (empty($oUser)) {
//do nothing
                    } elseif ($oUser->enabled == 0) {
                        $sReply = str_replace("{phone}", landa()->hp($phone), $sDisabled);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
                        $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_any_number']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    }

//insert to user table
                    $arrOthers = array();
                    $oUserNew = new User();

//set for options
                    if (isset($arrOptions['register_is_generate_code']) && $arrOptions['register_is_generate_code'] == 1)
                        $oUserNew->code = SiteConfig::model()->formatting('user', false);

                    if (isset($arrOptions['status_register']) && $arrOptions['status_register'] == 1)
                        $oUserNew->enabled = true;

                    if (isset($arrOptions['register_is_generate_password']) && $arrOptions['register_is_generate_password'] == 1) {
                        $pwdText = rand(1000, 9999);
                        $oUserNew->password = sha1($pwdText);
                    }

                    $oUserNew->roles_id = $arrOptions['roles_id'];
                    $oUserNew->saldo = $arrOptions['saldo'];
//                    $arrOthers['saldo_credit'] = ($arrOptions['saldo'] * 75) / 100;
                    $arrOthers['saldo_prize'] = 0;

                    $oUserNew->phone = $phone;

//replace insert value from keyword
                    foreach ($arrVal as $no => $keyVal) {
//                        echo $val;
                        if ($keyVal == '{name}')
                            $oUserNew->name = $arrText[$no];

                        if ($keyVal == '{address}')
                            $oUserNew->address = $arrText[$no];

                        if ($keyVal == '{bank_name}')
                            $arrOthers['bank_name'] = $arrText[$no];
                        if ($keyVal == '{bank_account}')
                            $arrOthers['bank_account'] = $arrText[$no];

                        if ($keyVal == '{bank_account_name}')
                            $arrOthers['bank_account_name'] = $arrText[$no];
                    }
                    $oUserNew->others = json_encode($arrOthers);
                    $oUserNew->save();

                    $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['success']);
                    $sReply = str_replace("{code}", $oUserNew->code, $sReply);
                    $sReply = str_replace("{password}", $pwdText, $sReply);
//                    logs($sReply);
                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                }elseif ($val->type == 'info') {
                    $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['success']);
                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                } elseif ($val->type == 'register_reff') {
                    //check number is register 
                    $oUser = User::model()->find(array('condition' => 'phone="' . $phone . '"'));
                    if (empty($oUser)) {
                        $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_any_number']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } elseif ($oUser->enabled == 0) {
                        $sReply = str_replace("{phone}", landa()->hp($phone), $sDisabled);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
                        //do nothing
                    }

                    //insert to user table
                    $arrOthers = array();
                    $oUserNew = new User();

                    //set for options
                    if (isset($arrOptions['register_is_generate_code']) && $arrOptions['register_is_generate_code'] == 1)
                        $oUserNew->code = SiteConfig::model()->formatting('user', false);

                    if (isset($arrOptions['status_register']) && $arrOptions['status_register'] == 1)
                        $oUserNew->enabled = true;

                    if (isset($arrOptions['register_is_generate_password']) && $arrOptions['register_is_generate_password'] == 1) {
                        $pwdText = rand(1000, 9999);
                        $oUserNew->password = sha1($pwdText);
                    }

                    $oUserNew->referal_user_id = $oUser->id;
                    $oUserNew->roles_id = $arrOptions['roles_id'];
                    $oUserNew->saldo = $arrOptions['saldo'];
//                    $arrOthers['saldo_credit'] = ($arrOptions['saldo'] * 75) / 100;
                    $arrOthers['saldo_prize'] = 0;

                    //replace insert value from keyword
                    $password = '';
                    foreach ($arrVal as $no => $keyVal) {
//                        echo $val;
                        if ($keyVal == '{name}')
                            $oUserNew->name = $arrText[$no];

                        if ($keyVal == '{address}')
                            $oUserNew->address = $arrText[$no];

                        if ($keyVal == '{bank_name}')
                            $arrOthers['bank_name'] = $arrText[$no];

                        if ($keyVal == '{bank_account}')
                            $arrOthers['bank_account'] = $arrText[$no];

                        if ($keyVal == '{bank_account_name}')
                            $arrOthers['bank_account_name'] = $arrText[$no];

                        if ($keyVal == '{phone_destination}')
                            $oUserNew->phone = substr($arrText[$no], 1);

                        if ($keyVal == '{password}')
                            $password = trim($arrText[$no]);
                    }

                    //check number is register 
                    if (!empty($oUserNew->phone)) {
                        $oUserDestination = User::model()->find(array('condition' => 'phone="' . $oUserNew->phone . '"'));
                        if (empty($oUserDestination)) {
                            //do nothing
                        } else {
                            $sReply = str_replace("{phone_destination}", landa()->hp($oUserNew->phone), $arrAutoreplys['failed_wrong_destination']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        }
                    }

                    if (isset($password)) { //check password
                        if (sha1($password) == $oUser->password) {
                            //do nothing
                        } else {
                            $sReply = str_replace("{password}", $password, $arrAutoreplys['failed_password']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        }
                    }

                    $oUserNew->others = json_encode($arrOthers);
                    $oUserNew->save();

                    //save ke tabel diagram
                    MlmDiagram::model()->create($oUserNew, $oUser->id);

                    $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['success']);
                    $sReply = str_replace("{code}", $oUser->code, $sReply);
                    $sReply = str_replace("{phone_destination}", landa()->hp($oUserNew->phone), $sReply);
                    $sReply = str_replace("{code_destination}", $oUserNew->code, $sReply);
                    $sReply = str_replace("{password_destination}", $pwdText, $sReply);
                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);

                    //kirim sms ke yang di daftarkan
                    $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['success_destination']);
                    $sReply = str_replace("{code}", $oUser->code, $sReply);
                    $sReply = str_replace("{phone_destination}", landa()->hp($oUserNew->phone), $sReply);
                    $sReply = str_replace("{code_destination}", $oUserNew->code, $sReply);
                    $sReply = str_replace("{password_destination}", $pwdText, $sReply);
                    Sms::model()->insertMsgNumber(0, $oUserNew->phone, $sReply, false, '', true);
                } elseif ($val->type == 'update') {

                    //check number is register or not
                    $oUser = User::model()->find(array('condition' => 'phone="' . $phone . '"'));
                    if (empty($oUser)) { //not register
                        $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_any_number']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } elseif ($oUser->enabled == 0) {
                        $sReply = str_replace("{phone}", landa()->hp($phone), $sDisabled);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
                        //do nothing
                    }

                    //update to user table
                    $arrOthers = json_decode($oUser->others, true);

                    //replace insert value from keyword
                    foreach ($arrVal as $no => $keyVal) {
                        //echo $val;
                        if ($keyVal == '{name}')
                            $oUser->name = $arrText[$no];

                        if ($keyVal == '{address}')
                            $oUser->address = $arrText[$no];

                        if ($keyVal == '{bank_name}')
                            $arrOthers['bank_name'] = $arrText[$no];

                        if ($keyVal == '{bank_account}')
                            $arrOthers['bank_account'] = $arrText[$no];

                        if ($keyVal == '{bank_account_name}')
                            $arrOthers['bank_account_name'] = $arrText[$no];

                        if ($keyVal == '{password}')
                            $password = trim($arrText[$no]);

                        if ($keyVal == '{password_new}')
                            $passwordNew = trim($arrText[$no]);
                    }


                    if (isset($password)) { //check password
                        if (sha1($password) == $oUser->password) {
                            //do nothing
                        } else {
                            $sReply = str_replace("{password}", $password, $arrAutoreplys['failed_password']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        }
                    }

                    if (isset($passwordNew)) { //any new password, meaning this action is update password
                        $oUser->password = sha1($passwordNew);
                    }

                    $pwdText = '';
                    if (isset($arrOptions['is_generate_password']) && $arrOptions['is_generate_password'] == 1) {
                        $pwdText = rand(1000, 9999);
                        $oUser->password = sha1($pwdText);
                    }

                    $oUser->others = json_encode($arrOthers);
                    $oUser->save();

                    $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['success']);
                    $sReply = str_replace("{code}", $oUser->code, $sReply);
                    $sReply = str_replace("{password}", $pwdText, $sReply);
                    $sReply = str_replace("{password_new}", $passwordNew, $sReply);

                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                } elseif ($val->type == 'saldo') {
                    foreach ($arrVal as $no => $keyVal) {
                        if ($keyVal == '{password}')
                            $password = trim($arrText[$no]);
                    }

                    //check number is register or not
                    $oUser = User::model()->find(array('condition' => 'phone="' . $phone . '"'));
                    if (empty($oUser)) { //not register
                        $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_any_number']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } elseif ($oUser->enabled == 0) {
                        $sReply = str_replace("{phone}", landa()->hp($phone), $sDisabled);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
                        //do nothing
                    }

                    if (isset($password)) { //check password
                        if (sha1($password) == $oUser->password) {
                            //do nothing
                        } else {
                            $sReply = str_replace("{password}", $password, $arrAutoreplys['failed_password']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        }
                    }

                    $sReply = str_replace("{saldo}", $oUser->saldoMlt, $arrAutoreplys['success']);
                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                } elseif ($val->type == 'transfer') {
                    //detect value
                    foreach ($arrVal as $no => $keyVal) {
                        if ($keyVal == '{phone}')
                            $keyPhone = substr($arrText[$no], 1); //remove zero number in front

                        if ($keyVal == '{amount}')
                            $keyAmount = $arrText[$no] * 1000;

                        if ($keyVal == '{type}')
                            $type = $arrText[$no];

                        if ($keyVal == '{password}')
                            $password = trim($arrText[$no]);
                    }

                    if (isset($type) && ($type == 'saldo' || $type == 'bonus')) {
                        // do nothing
                    } else {
                        $sReply = 'Type transfer yang anda tuju salah. type tersedia : SALDO / BONUS';
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    }

                    //check number is register or not
                    $oUser = User::model()->find(array('condition' => 'phone="' . $phone . '"'));
                    $others = json_decode($oUser->others, true);
                    if (empty($oUser)) { //not register
                        $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_any_number']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } elseif ($oUser->enabled == 0) {
                        $sReply = str_replace("{phone}", landa()->hp($phone), $sDisabled);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
                        //do nothing
                    }

                    //check saldo is enought
                    if ($type == 'saldo') {
                        if ($oUser->saldo < $keyAmount) {
                            $sReply = str_replace("{saldo}", $oUser->saldoMlt, $arrAutoreplys['saldo_not_enough']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        } else {
                            //do nothing
                        }
                    } elseif ($type == 'bonus') {
                        if (!isset($others['saldo_prize']) || $others['saldo_prize'] < $keyAmount) {
                            $sReply = 'Saldo bonus Anda tidak mencukupi untuk di transfer';
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        } else {
                            //do nothing
                        }
                    }

                    //check destination number is any or not
                    $oUserDestination = User::model()->find(array('condition' => 'phone="' . $keyPhone . '"'));
                    if (empty($oUserDestination)) { //not register
                        $sReply = str_replace("{phone}", landa()->hp($keyPhone), $arrAutoreplys['failed_wrong_destination']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
                        //do nothing
                    }

                    if (isset($password)) { //check password
                        if (sha1($password) == $oUser->password) {
                            //do nothing
                        } else {
                            $sReply = str_replace("{password}", $password, $arrAutoreplys['failed_password']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        }
                    }

                    //save to transfer table
                    $mTransfer = new Transfer();
                    $mTransfer->amount = $keyAmount;
                    $mTransfer->to_user_id = $oUserDestination->id;
                    $mTransfer->created_user_id = $oUser->id;
                    $mTransfer->save();

                    //change saldo each user
                    if ($type == 'saldo') {
                        $oUser->saldo -= $keyAmount;
                        $oUser->save();

                        //tambahkan tagihan khusus member pasca bayar, diskon 30%
                        if ($oUser->roles_id == 27) {
                            $others = json_decode($oUser->others, true);
                            if (isset($others['saldo_credit'])) {
                                $others['saldo_credit'] += ($keyAmount * 30) / 100;
                            } else {
                                $others['saldo_credit'] = ($keyAmount * 30) / 100;
                            }
                        }

                        $oUserDestination->saldo += $keyAmount;
                        $oUserDestination->save();
                    } elseif ($type == 'bonus') {
                        $others = json_decode($oUser->others, true);
                        $others['saldo_prize'] -= $keyAmount;
                        $oUser->others = json_encode($others);
                        $oUser->save();

                        $others = json_decode($oUserDestination->others, true);
                        if (isset($others['saldo_prize'])) {
                            $others['saldo_prize'] +=$keyAmount;
                        } else {
                            $others['saldo_prize'] = $keyAmount;
                        }
                        $oUserDestination->others = json_encode($others);
                        $oUserDestination->save();
                    }

//                    
                    //notif to user destination
                    $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['success_destination']);
                    $sReply = str_replace("{phone_transfer}", landa()->hp($keyPhone), $sReply);
                    $sReply = str_replace("{amount}", landa()->rp($keyAmount), $sReply);
                    Sms::model()->insertMsgNumber(0, $keyPhone, $sReply, false, '', true);

                    $sReply = str_replace("{phone_transfer}", landa()->hp($keyPhone), $arrAutoreplys['success']);
                    $sReply = str_replace("{phone}", landa()->hp($phone), $sReply);
                    $sReply = str_replace("{amount}", landa()->rp($keyAmount), $sReply);
                    $sReply = str_replace("{saldo}", $oUser->saldoMlt, $sReply);
                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                } elseif ($val->type == 'deposit') {
                    //detect value
                    foreach ($arrVal as $no => $keyVal) {
                        if ($keyVal == '{bank_account}')
                            $keyBankAccount = $arrText[$no]; //remove zero number in front

                        if ($keyVal == '{amount}')
                            $keyAmount = $arrText[$no] * 1000;

                        if ($keyVal == '{password}')
                            $password = trim($arrText[$no]);;
                    }

                    //check number is register or not
                    $oUser = User::model()->find(array('condition' => 'phone="' . $phone . '"'));
                    if (empty($oUser)) { //not register
                        $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_any_number']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } elseif ($oUser->enabled == 0) {
                        $sReply = str_replace("{phone}", landa()->hp($phone), $sDisabled);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
                        //do nothing
                    }

                    if (isset($password)) { //check password
                        if (sha1($password) == $oUser->password) {
                            //do nothing
                        } else {
                            $sReply = str_replace("{password}", $password, $arrAutoreplys['failed_password']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        }
                    }

                    //check destination number is any or not
                    $oUserDestination = User::model()->find(array('condition' => 'phone="' . $phone . '"'));
                    if (empty($oUserDestination)) { //not register
                        $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_any_number']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
                        //do nothing
                    }

                    //insert to deposit
                    $mDeposit = new Payment();
                    $mDeposit->bank_account = $keyBankAccount;
                    $mDeposit->amount = $keyAmount;
                    $mDeposit->module = 'deposit';
                    $mDeposit->created_user_id = $oUser->id;
                    $mDeposit->save();

                    $sReply = str_replace("{bank_account}", $keyBankAccount, $arrAutoreplys['success']);
                    $sReply = str_replace("{amount}", landa()->rp($keyAmount), $sReply);
                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                } elseif ($val->type == 'withdrawal') {
                    //detect value
                    foreach ($arrVal as $no => $keyVal) {
                        if ($keyVal == '{amount}')
                            $keyAmount = $arrText[$no] * 1000;

                        if ($keyVal == '{password}')
                            $password = trim($arrText[$no]);;
                    }

                    //check destination number is any or not
                    $oUser = User::model()->find(array('condition' => 'phone="' . $phone . '"'));
                    $others = json_decode($oUser->others, true);
                    if (empty($oUser)) { //not register
                        $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_any_number']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } elseif ($oUser->enabled == 0) {
                        $sReply = str_replace("{phone}", landa()->hp($phone), $sDisabled);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
                        //do nothing
                    }

                    //check saldo is enought
                    if ($oUser->saldoPrize < $keyAmount) { //not register
                        $sReply = str_replace("{saldo}", $oUser->saldoMlt, $arrAutoreplys['saldo_not_enough']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
                        //do nothing
                    }

                    if (isset($password)) { //check password
                        if (sha1($password) == $oUser->password) {
                            //do nothing
                        } else {
                            $sReply = str_replace("{password}", $password, $arrAutoreplys['failed_password']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        }
                    }

                    //insert to saldo withdrawal
                    $mDeposit = new SaldoWithdrawal();
                    $mDeposit->amount = $keyAmount;
                    $mDeposit->created_user_id = $oUser->id;
                    $mDeposit->save();

                    $sReply = str_replace("{amount}", landa()->rp($keyAmount), $arrAutoreplys['success']);
                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                } elseif ($val->type == 'playresult') {
                    $oUser = User::model()->find(array('condition' => 'phone="' . $phone . '"'));
                    if (empty($oUser)) { //not register
                        $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_any_number']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } elseif ($oUser->enabled == 0) {
                        $sReply = str_replace("{phone}", landa()->hp($phone), $sDisabled);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
                        //do nothing
                    }
                    //detect value

                    foreach ($arrVal as $no => $keyVal) {
                        if ($keyVal == '{output}')
                            $keyOutput = $arrText[$no];

                        if ($keyVal == '{password}')
                            $password = trim($arrText[$no]);
                    }

                    //check output
                    if ($keyOutput == 's' || $keyOutput == 'h') {
                        //do nothing
                    } else {
                        $sReply = str_replace("{output}", $keyOutput, $arrAutoreplys['wrong_output']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    }

                    if (isset($password)) { //check password
                        if (sha1($password) == $oUser->password) {
                            //do nothing
                        } else {
                            $sReply = str_replace("{password}", $password, $arrAutoreplys['failed_password']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        }
                    }

                    $mPlay = Play::model()->findAll(array('order' => 'id Desc', 'select' => 'number, sum(amount) as sum_amount', 'group' => 'number', 'condition' => 'DATE_FORMAT( created,  "%Y-%m-%d" )="' . date('Y-m-d') . '" AND output="' . $keyOutput . '" AND created_user_id=' . $oUser->id));
                    $sReply = $keyOutput . ' : ';
                    foreach ($mPlay as $valPlay) {
                        $sReply .= $valPlay->number . 'x' . ($valPlay->sum_amount / 1000) . '.';
//                        $sReply .= $valPlay->number . 'x' . landa()->rp($valPlay->sum_amount) . '.';
                    }
                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                } elseif ($val->type == 'result') {
//                    $oUser = User::model()->find(array('condition' => 'phone="' . $phone . '"'));
//                    if (empty($oUser)) { //not register
//                        $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_any_number']);
//                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
//                        break;
//                        } elseif ($oUser->enabled == 0) {
//                        $sReply = str_replace("{phone}", landa()->hp($phone), $sDisabled);
//                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
//                        break;
//                    } else {
//                        //do nothing
//                    }
                    //detect value
                    foreach ($arrVal as $no => $keyVal) {
                        if ($keyVal == '{output}')
                            $keyOutput = $arrText[$no];
                        if ($keyVal == '{password}')
                            $password = trim($arrText[$no]);
                    }

                    //check output
                    if ($keyOutput == 's' || $keyOutput == 'h') {
                        //do nothing
                    } else {
                        $sReply = str_replace("{output}", $keyOutput, $arrAutoreplys['wrong_output']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    }

                    if (isset($password)) { //check password
                        if (sha1($password) == $oUser->password) {
                            //do nothing
                        } else {
                            $sReply = str_replace("{password}", $password, $arrAutoreplys['failed_password']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        }
                    }

                    $mPlayResult = PlayResult::model()->findAll(array('condition' => 'output="' . $keyOutput . '"', 'order' => 'id Desc', 'limit' => 3));
                    $sReply = 'Hasil keluaran ' . $keyOutput . ' : ';
                    foreach ($mPlayResult as $valPlayResult) {
                        $sReply .= date('d-M-Y', strtotime($valPlayResult->date_number)) . ' => ' . $valPlayResult->number . '. ';
                    }
                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                } elseif ($val->type == '2d' || $val->type == '3d' || $val->type == '4d' || $val->type == 'cr' || $val->type == 'cj_satuan' || $val->type == 'cj_puluhan' || $val->type == 'cj_ratusan' || $val->type == 'cj_ribuan') {
//detect value
                    foreach ($arrVal as $no => $keyVal) {
                        if ($keyVal == '{output}')
                            $keyOutput = $arrText[$no];
//                        if ($keyVal == '{number}')
//                            $keyNumber = $arrText[$no];
//                        if ($keyVal == '{amount}'){
//                            $keyAmount = $arrText[$no] * 1000;
//                        }
                        if ($keyVal == '{numberxamount}') {
                            $sNumberAmount = explode('.', $arrText[$no]);
                            $keyNumber = array();
                            $keyAmount = array();
                            $keyAmountTotal = 0;
                            foreach ($sNumberAmount as $valNumberAmount) {
                                $sTemp = explode('x', $valNumberAmount);
                                if (count($sTemp) == 2) {
                                    $keyNumber[] = $sTemp[0];
                                    $keyAmount[] = $sTemp[1] * 1000;
                                    $keyAmountTotal += $sTemp[1] * 1000;
                                } else {
                                    $sReply = 'Digit angka & jumlah pemasangan salah format';
                                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                                    break 3;
                                }
                            }
                        }
                        if ($keyVal == '{password}')
                            $password = trim($arrText[$no]);
                    }

//check register or not
                    $oUser = User::model()->find(array('condition' => 'phone="' . $phone . '"'));
                    if (empty($oUser)) { //not register
                        $sReply = str_replace("{phone}", landa()->hp($phone), $arrAutoreplys['failed_any_number']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } elseif ($oUser->enabled == 0) {
                        $sReply = str_replace("{phone}", landa()->hp($phone), $sDisabled);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
//do nothing
                    }

//check saldo is enought
                    if ($oUser->saldo < $keyAmountTotal) { //not register
                        $sReply = str_replace("{saldo}", $oUser->saldoMlt, $arrAutoreplys['saldo_not_enough']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    } else {
//do nothing
                    }

//check output
                    if ($keyOutput == 's' || $keyOutput == 'h') {
//do nothing
                    } else {
                        $sReply = str_replace("{output}", $keyOutput, $arrAutoreplys['wrong_output']);
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    }

                    //check number which put
                    foreach ($keyNumber as $valKeyNumber) {
                        if ((strlen($valKeyNumber) == 2 && $val->type == '2d') || (strlen($valKeyNumber) == 3 && $val->type == '3d') || (strlen($valKeyNumber) == 4 && $val->type == '4d') || (strlen($valKeyNumber) == 1 && ($val->type == 'cr' || $val->type == 'cj_satuan' || $val->type == 'cj_puluhan' || $val->type == 'cj_ratusan' || $val->type == 'cj_ribuan'))) {
                            //do nothing
                        } else {
                            //echo strlen($keyNumber) . '--' .;
                            $sReply = str_replace("{number}", $valKeyNumber, $arrAutoreplys['wrong_digit']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break 2;
                        }
                    }

                    if (isset($password)) { //check password
                        if (sha1($password) == $oUser->password) {
                            //do nothing
                        } else {
                            $sReply = str_replace("{password}", $password, $arrAutoreplys['failed_password']);
                            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                            break;
                        }
                    }

                    //check hour of transaction
                    $siteConfig = SiteConfig::model()->findByPk(1);
                    $settings = json_decode($siteConfig->settings);
                    $hkg_day = (isset($settings->game->hkg_day)) ? $settings->game->hkg_day : array();
                    $hkg_time_start = (isset($settings->game->hkg_time_start)) ? strtotime($settings->game->hkg_time_start) : '00:00';
                    $hkg_time_end = (isset($settings->game->hkg_time_end)) ? strtotime($settings->game->hkg_time_end) : '00:00';
                    $sgp_day = (isset($settings->game->sgp_day)) ? $settings->game->sgp_day : array();
                    $sgp_time_start = (isset($settings->game->sgp_time_start)) ? strtotime($settings->game->sgp_time_start) : '00:00';
                    $sgp_time_end = (isset($settings->game->sgp_time_end)) ? strtotime($settings->game->sgp_time_end) : '00:00';

                    $discount_2d = (isset($settings->game->{$oUser->roles_id}->discount_2d)) ? $settings->game->{$oUser->roles_id}->discount_2d : 0;
                    $discount_3d = (isset($settings->game->{$oUser->roles_id}->discount_3d)) ? $settings->game->{$oUser->roles_id}->discount_3d : 0;
                    $discount_4d = (isset($settings->game->{$oUser->roles_id}->discount_4d)) ? $settings->game->{$oUser->roles_id}->discount_4d : 0;
                    $discount_cj = (isset($settings->game->{$oUser->roles_id}->discount_cj)) ? $settings->game->{$oUser->roles_id}->discount_cj : 0;
                    $discount_cr = (isset($settings->game->{$oUser->roles_id}->discount_cr)) ? $settings->game->{$oUser->roles_id}->discount_cr : 0;

                    if ($val->type == '2d') {
                        $tempDiscount = $discount_2d;
                    } else if ($val->type == '3d') {
                        $tempDiscount = $discount_3d;
                    } else if ($val->type == '4d') {
                        $tempDiscount = $discount_4d;
                    } else if ($val->type == 'cj_satuan' || $val->type == 'cj_puluhan' || $val->type == 'cj_ratusan' || $val->type == 'cj_ribuan') {
                        $tempDiscount = $discount_cj;
                    } else {
                        $tempDiscount = $discount_cr;
                    }
                    $discount = ($keyAmountTotal * $tempDiscount) / 100;


//                    if ($keyOutput == 's' && ((date('N') == 1 || date('N') == 3 || date('N') == 4 || date('N') == 6 || date('N') == 7) && (date('H') >= 8 && date('H') < 17))) {
//                        //do nothing
//                    } elseif ($keyOutput == 'h' && (date('H') >= 17 && date('H') < 22)) {
                    if ($keyOutput == 's' && ((in_array(date('N'), $sgp_day)) && (time() >= $sgp_time_start && time() < $sgp_time_end))) {
                        //do nothing
                    } elseif ($keyOutput == 'h' && ((in_array(date('N'), $hkg_day)) && (time() >= $hkg_time_start && time() < $hkg_time_end))) {
                        //do nothing
                    } else {
                        $sReply = $arrAutoreplys['failed_hour'];
                        Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                        break;
                    }

                    foreach ($keyNumber as $keyKn => $valKn) {
                        //save to play
                        $mPlay = new Play();
                        $mPlay->number = $valKn;
                        $mPlay->output = $keyOutput;
                        $mPlay->amount = $keyAmount[$keyKn];
                        $mPlay->type = $val->type;
                        $mPlay->created_user_id = $oUser->id;
                        $mPlay->save();
                    }

                    $saldo = $keyAmountTotal - $discount;

                    //jika pasca bayar, tambahkan juga ke tagihan
                    if ($oUser->roles_id == 27) {
                        $others = json_decode($oUser->others, true);
                        if (isset($others['saldo_credit'])) {
                            $others['saldo_credit'] += $saldo;
                        } else {
                            $others['saldo_credit'] = $saldo;
                        }
                        $oUser->others = json_encode($others);
                    }

                    //less the user saldo
                    $oUser->saldo -= $saldo;
                    $oUser->save();




                    //bonus referall
//                    MlmPrize::model()->mlt($oUser->id,$keyAmountTotal);

                    $sReply = $arrAutoreplys['success'];
                    $sReply = str_replace("{saldo}", $oUser->saldoMlt, $sReply);
                    Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
                }
            }
        }

        //check if any sms with #, but wrong and not match in keyword in database
        if (count($arrText) >= 2 && $is_keyword == false && strlen($phone) >= 10) {
            $sReply = 'Keyword yang anda tuju, tidak terdaftar di system kami. Mohon cek lagi keyword anda.';
            Sms::model()->insertMsgNumber(0, $phone, $sReply, false, '', true);
        }
    }

}
