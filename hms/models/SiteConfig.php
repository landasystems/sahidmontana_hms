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
            array('city_id', 'numerical', 'integerOnly' => true),
            array('client_name,npwp, client_logo, address', 'length', 'max' => 255),
            array('phone, email, language_default', 'length', 'max' => 45),
            array('format_reservation,others_include,date_system,settings,report_bill,report_bill_charge,report_deposite, report_registration, format_deposite,format_bill_charge,format_report_bill_charge,format_registration,format_bill', 'safe'),
            array('client_name,email,city_id,address,format_reservation,format_registration,format_bill,format_bill_charge,format_deposite,date_system', 'required'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, client_name, city_id, address, phone, email', 'safe', 'on' => 'search'),
            array('client_logo', 'unsafe'),
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
            'format_bill_charge' => 'Format Bill for Departement',
            'client_logo' => 'Client Logo',
            'city_id' => 'City',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'npwp' => 'NPWPD',
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
            'sort' => array('defaultOrder' => 't.id DESC')
        ));
    }

    public function getFullAddress() {
        return $this->address . ', ' . $this->City->name . ', ' . $this->City->Province->name;
    }

    public function getRolesGuest() {
        return array('user' => 'User', 'guest' => 'Guest');
    }

    public function getStandartTransactionMalang() {
        return array(
            'ATS' => 'Transaksi sewa kamar',
            'ATM' => 'Transaksi makan/minum',
            'ATF' => 'Transaksi fitness center',
            'ATH' => 'Transaksi health center',
            'ATK' => 'Transaksi kolam renang',
            'ATT' => 'Transaksi lapangan tenis',
            'ATO' => 'Transaksi klub malam/karaoke',
            'ATD' => 'Transaksi diskotik',
            'ATB' => 'Transaksi pub/bar/kafe',
            'ATN' => 'Transaksi spa',
            'ATP' => 'Transaksi telepon',
            'ATC' => 'Transaksi facsimile',
            'ATX' => 'Transaksi telex',
            'ATI' => 'Transaksi internet',
            'ATY' => 'Transaksi fotocopy',
            'ATL' => 'Transaksi laundry',
            'ATA' => 'Transaksi taxi',
            'ATV' => 'Transaksi service charge',
            'ATZ' => 'Transaksi lainnya',
        );
    }

    public function getKodeAreaMalang() {
        return '00001';
    }

    public function listSiteConfig() {

//        return SiteConfig::model()->findByPk(param('id'));
//        trace ($this->cache['listSiteConfig']);

        if (empty(Yii::app()->session['site'])) {
//            trace('bb');
            Yii::app()->session['site'] = $this->findByPk(1);
        }
//        trace ($this->cache['listSiteConfig']);
        return Yii::app()->session['site'];
    }

    public function formatting($type, $x = true) {
        $siteConfig = $this->listSiteConfig();
        if ($type == 'reservation') {
            $textFormat = $siteConfig['format_reservation'];
            $lastID = Reservation::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'registration') {
            $textFormat = $siteConfig['format_registration'];
            $lastID = Registration::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'bill') {
            $textFormat = $siteConfig['format_bill'];
            $lastID = Bill::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'billCharge') {
            $textFormat = $siteConfig['format_bill_charge'];
            $lastID = BillCharge::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'deposite') {
            $textFormat = $siteConfig['format_deposite'];
            $lastID = Deposite::model()->find(array('order' => 'id DESC'));
        }
        $lastID = (empty($lastID->id)) ? 1 : $lastID->id + 1;

        $textFormat = str_replace('{dd}', date('d'), $textFormat);
        $textFormat = str_replace('{mm}', date('m'), $textFormat);
        $textFormat = str_replace('{yy}', date('y'), $textFormat);

        if ($x) {
            $textFormat = str_replace('{ai|3}', '***', $textFormat);
            $textFormat = str_replace('{ai|4}', '****', $textFormat);
            $textFormat = str_replace('{ai|5}', '*****', $textFormat);
            $textFormat = str_replace('{ai|6}', '******', $textFormat);
        } else {
            $textFormat = str_replace('{ai|3}', substr('0000' . $lastID, -3), $textFormat);
            $textFormat = str_replace('{ai|4}', substr('0000' . $lastID, -4), $textFormat);
            $textFormat = str_replace('{ai|5}', substr('0000' . $lastID, -5), $textFormat);
            $textFormat = str_replace('{ai|6}', substr('0000' . $lastID, -6), $textFormat);
        }

        return $textFormat;
    }

}
