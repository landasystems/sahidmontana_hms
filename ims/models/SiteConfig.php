<?php

/**
 * This is the model class for table "{{site_config}}".
 *
 * The followings are the available columns in table '{{site_config}}':
 * @property integer $id
 * @property string $client_name
 * @property string $client_logo
 * @property integer $city_id
 * @property string $address
 * @property string $phone
 * @property string $email
 */
class SiteConfig extends CActiveRecord {

    public $cache;

    public function __construct() {
        $this->cache = Yii::app()->cache;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SiteConfig the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{site_config}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('', 'numerical', 'integerOnly' => true),
            array('client_name, client_logo,', 'length', 'max' => 255),
            array('method, format_buy,format_buy_order,format_buy_retur,format_sell,format_sell_order,format_sell_retur,format_in,format_out,format_opname', 'length', 'max' => 45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('roles_customer', 'safe'),
            array('id, client_name', 'safe', 'on' => 'search'),
            array('client_logo', 'unsafe'),
            array('report_sell_order,report_sell,report_sell_retur,report_buy_order,report_buy,report_buy_retur,report_in,report_out,report_cash_out,report_cash_in,report_jurnal,date_system,autonumber,autopostnumber', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'City' => array(self::BELONGS_TO, 'City', 'city_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'client_name' => 'Client Name',
            'client_logo' => 'Client Logo',
            'city_id' => 'City',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'method' => 'Stocking Method',
            'format_workorder' => 'Format SPK',
            'format_spp' => 'Format SPP',
            'format_workorder_split' => 'Format NOPOT',
            'format_workorder_process' => 'Format Nota Jahit',
            'format_cash_in' => 'Kas Masuk',
            'format_cash_out' => 'Kas Keluar',
            'format_jurnal' => 'Jurnal',
            'format_cash_inks_acc' => 'Kas Masuk Approval',
            'format_cash_inbk_acc' => 'Bank Masuk Approval',
            'format_cash_outks_acc' => 'Kas Keluar Approval',
            'format_cash_outbk_acc' => 'Bank Keluar Approval',
            'format_jurnal_acc' => 'Jurnal Approval',
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
        $criteria->compare('client_name', $this->client_name, true);
        $criteria->compare('client_logo', $this->client_logo, true);
        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('email', $this->email, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

//    public function getFullAddress() {
//        return $this->address . ', ' . $this->City->name . ', ' . $this->City->Province->name;
//    }

    public function listSiteConfig() {

//        return SiteConfig::model()->findByPk(param('id'));
//        trace ($this->cache['listSiteConfig']);

        if (empty(Yii::app()->session['site'])) {
//            trace('bb');
            Yii::app()->session['site'] = $this->findByPk(param('id'));
        }
//        trace ($this->cache['listSiteConfig']);
        return Yii::app()->session['site'];
    }

    public function formatting($type, $x = true, $prefix = '', $param = '', $date = null) {
        $siteConfig = SiteConfig::model()->findByPk(param('id'));
        $format = DateConfig::model()->find(array('condition'=>'year="'.date('Y',  strtotime($date)).'" and departement_id='.User()->departement_id));
        $formating = AccFormatting::model()->findByAttributes(array('departement_id' => User()->departement_id));
//        logs(User()->departement_id);
        if ($type == 'buy') {
            $textFormat = $siteConfig['format_buy'];
            $lastID = Buy::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'buyorder') {
            $textFormat = $siteConfig['format_buy_order'];
            $lastID = BuyOrder::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'buyretur') {
            $textFormat = $siteConfig['format_buy_retur'];
            $lastID = BuyRetur::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'sell') {
            $textFormat = $siteConfig['format_sell'];
            $lastID = Sell::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'sellorder') {
            $textFormat = $siteConfig['format_sell_order'];
            $lastID = SellOrder::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'sellretur') {
            $textFormat = $siteConfig['format_sell_retur'];
            $lastID = SellRetur::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'in') {
            $textFormat = $siteConfig['format_in'];
            $lastID = In::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'out') {
            $textFormat = $siteConfig['format_out'];
            $lastID = Out::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'opname') {
            $textFormat = $siteConfig['format_opname'];
            $lastID = Opname::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'cashin') {
            $textFormat = $formating->cash_in;
            $lastID = AccCashIn::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'cashout') {
            $textFormat = $formating->cash_out;
            $lastID = AccCashOut::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'jurnal') {
            $textFormat = $formating->journal;
            $lastID = AccJurnal::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'cashinks_acc') {
            $textFormat = $formating->cash_in_approval;
            $lastID = $format->cash_in;
        } elseif ($type == 'cashinbk_acc') {
            $textFormat = $formating->bank_in_approval;
            $lastID = $format->bk_in;
        } elseif ($type == 'cashoutks_acc') {
            $textFormat = $formating->cash_out_approval;
            $lastID = $format->cash_out;
        } elseif ($type == 'cashoutbk_acc') {
            $textFormat = $formating->bank_out_approval;
            $lastID = $format->bk_out;
        } elseif ($type == 'jurnal_acc') {
            $textFormat = $formating->journal_approval;
            $lastID = $format->jurnal;
        }

        if ($type != 'spp' && $type != 'nopot' && $type != "cashinks_acc" && $type != "cashinbk_acc" && $type != "cashoutks_acc" && $type != "cashoutbk_acc" && $type != "jurnal_acc")
            $lastID = (empty($lastID->id)) ? 1 : $lastID->id + 1;

        if ($type == 'jurnal_acc' or $type == 'cashoutks_acc' or $type == 'cashoutbk_acc' or $type == 'cashinks_acc' or $type == 'cashinbk_acc') {
            $date = explode("-", $date);
            $textFormat = str_replace('{dd}', $date[2], $textFormat);
            $textFormat = str_replace('{mm}', $date[1], $textFormat);
            $textFormat = str_replace('{yy}', substr($date[0], 2, 3), $textFormat);
        } else {
            $textFormat = str_replace('{dd}', date('d'), $textFormat);
            $textFormat = str_replace('{mm}', date('m'), $textFormat);
            $textFormat = str_replace('{yy}', date('y'), $textFormat);
        }

        if ($x) {
            $textFormat = str_replace('{ai|2}', '***', $textFormat);
            $textFormat = str_replace('{ai|3}', '***', $textFormat);
            $textFormat = str_replace('{ai|3}', '***', $textFormat);
            $textFormat = str_replace('{ai|4}', '****', $textFormat);
            $textFormat = str_replace('{ai|5}', '*****', $textFormat);
            $textFormat = str_replace('{ai|6}', '******', $textFormat);
            $textFormat = str_replace('{sp}', '***', $textFormat);
            $textFormat = str_replace('{spk}', '***', $textFormat);
            $textFormat = str_replace('{spp}', '***', $textFormat);
            $textFormat = str_replace('{nopot}', '***', $textFormat);
        } else {
            $textFormat = str_replace('{ai|2}', substr('0000' . $lastID, -2), $textFormat);
            $textFormat = str_replace('{ai|3}', substr('0000' . $lastID, -3), $textFormat);
            $textFormat = str_replace('{ai|4}', substr('0000' . $lastID, -4), $textFormat);
            $textFormat = str_replace('{ai|5}', substr('0000' . $lastID, -5), $textFormat);
            $textFormat = str_replace('{ai|6}', substr('0000' . $lastID, -6), $textFormat);
            $textFormat = str_replace('{sp}', $prefix, $textFormat);
            $textFormat = str_replace('{spk}', $prefix, $textFormat);
            $textFormat = str_replace('{spp}', $prefix, $textFormat);
            $textFormat = str_replace('{nopot}', $prefix, $textFormat);
        }

        return $textFormat;
    }

}
