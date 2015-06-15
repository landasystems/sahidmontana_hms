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
            array('city_id, article_socialmedia, article_comment,product_layout,departement_id', 'numerical', 'integerOnly' => true),
            array('client_name, client_logo, address, url_facebook, url_twitter,url_ym, lat, lng, article_comment_color', 'length', 'max' => 255),
            array('phone, email, language_default', 'length', 'max' => 45),
            array('report_sell, settings', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('mail_register,mail_sell,mail_payment,mail_order,mail_confirm,mail_reject,mail_delivered', 'safe'),
            array('id, client_name, city_id, address, phone, email', 'safe', 'on' => 'search'),
            array('bank_account,method,format_sell,format_in,format_out,format_opname, sms_provider, sms_number, sms_ip, sms_port', 'safe'),
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
            'client_logo' => 'Client Logo',
            'city_id' => 'City',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
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

    public function getFullAddress() {
        return $this->address . ', ' . $this->City->name . ', ' . $this->City->Province->name;
    }

    public function listSiteConfig() {

//        return SiteConfig::model()->findByPk(param('id'));
//        trace ($this->cache['listSiteConfig']);

        if (empty(Yii::app()->session['site'])) {
//            trace('bb');
            app()->session['site'] = $this->findByPk(param('id'));

            if (isset(app()->session['site']['settings']))
                app()->session['site']['settings'] = json_decode(app()->session['site']['settings'], true);

//            if (isset(app()->session['site']['settings']['format']))
//                app()->session['site']['settings']['format'] = app()->session['site']['settings']['format'];
        }
//        trace ($this->cache['listSiteConfig']);
        return app()->session['site'];
    }

    public function formatting($type, $x = true) {
        $siteConfig = $this->listSiteConfig();
        if ($type == 'buy') {
//            $textFormat = $siteConfig['format_buy'];
//            $lastID = Buy::model()->find(array('order'=>'id DESC'));
        } elseif ($type == 'buyorder') {
//            $textFormat = $siteConfig['format_buy_order'];
//            $lastID = BuyOrder::model()->find(array('order'=>'id DESC'));
        } elseif ($type == 'buyretur') {
//            $textFormat = $siteConfig['format_buy_retur'];
//            $lastID = BuyRetur::model()->find(array('order'=>'id DESC'));
        } elseif ($type == 'sell') {
            $textFormat = $siteConfig['format_sell'];
            $lastID = Sell::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'sellorder') {
//            $textFormat = $siteConfig['format_sell_order'];
//            $lastID = SellOrder::model()->find(array('order'=>'id DESC'));
        } elseif ($type == 'sellretur') {
//            $textFormat = $siteConfig['format_sell_retur'];
//            $lastID = SellRetur::model()->find(array('order'=>'id DESC'));
        } elseif ($type == 'in') {
            $textFormat = $siteConfig['format_in'];
            $lastID = In::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'out') {
            $textFormat = $siteConfig['format_out'];
            $lastID = Out::model()->find(array('order' => 'id DESC'));
        } elseif ($type == 'opname') {
            $textFormat = $siteConfig['format_opname'];
            $lastID = Opname::model()->find(array('order' => 'id DESC'));
//            $lastID = (empty($lastID)) ? 1 : $lastID + 1 ;
        } elseif ($type == 'user') {
            $textFormat = $siteConfig['settings']['format']['user'];
            $lastID = User::model()->find(array('order' => 'id DESC'));
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
