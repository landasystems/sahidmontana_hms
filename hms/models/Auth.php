<?php

/**
 * This is the model class for table "{{auth}}".
 *
 * The followings are the available columns in table '{{auth}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $alias
 * @property string $module
 * @property string $crud
 */
class Auth extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{auth}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, description', 'length', 'max' => 255),
            array('module, crud', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, description, crud', 'safe', 'on' => 'search'),
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
            'description' => 'Description',
//            'alias' => 'Alias',
//            'module' => 'Module',
            'crud' => 'Crud',
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
//        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
//        $criteria->compare('alias', $this->alias, true);
//        $criteria->compare('module', $this->module, true);
        $criteria->compare('crud', $this->crud, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @return CDbConnection the database connection used for this class
     */
    public function getDbConnection() {
        return Yii::app()->db;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Auth the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function modules() {

        return array(
            array('label' => '<span class="icon16 icomoon-icon-screen"></span>Dashboard', 'url' => array('/dashboard')),
            array('visible' => user()->isSuperUser, 'label' => '<span class="icon16 icomoon-icon-cog"></span>Settings', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('label' => '<span class="icon16 iconic-icon-new-window"></span>Site config', 'url' => array('/siteConfig/update/1')),
                    array('label' => '<span class="icon16 entypo-icon-users"></span>Access', 'url' => array('/landa/roles')),
                ),
            ),
            array('visible' => landa()->checkAccess('User', 'r'), 'label' => '<span class="icon16  entypo-icon-contact"></span>User', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('GroupUser', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group User', 'url' => url('landa/roles/user'), 'auth_id' => 'GroupUser'),
                    array('visible' => landa()->checkAccess('User', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>User', 'url' => url('/user'), 'auth_id' => 'User'),
                )),
            array('visible' => landa()->checkAccess('GroupGuest', 'r'), 'label' => '<span class="icon16  icomoon-icon-accessibility"></span>Guest', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('GroupGuest', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group Guest', 'url' => url('landa/roles/guest'), 'auth_id' => 'GroupGuest'),
                    array('visible' => landa()->checkAccess('User', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>Guest', 'url' => url('/user/guest'), 'auth_id' => 'Guest'),
                )),
            array('visible' => landa()->checkAccess('Room', 'r'), 'label' => '<span class="icon16 wpzoom-factory"></span>Rooms', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('RoomType', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Type', 'url' => array('/roomType'), 'auth_id' => 'RoomType'),
                    array('visible' => landa()->checkAccess('Room', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Room', 'url' => array('/room'), 'auth_id' => 'Room'),
                )),
            array('visible' => landa()->checkAccess('MarketSegment', 'r'), 'label' => '<span class="icon16 entypo-icon-map"></span>Market Segment', 'url' => array('/marketSegment'), 'auth_id' => 'MarketSegment', 'items' => array()),
            array('visible' => landa()->checkAccess('Forecast', 'r'), 'label' => '<span class="icon16 entypo-icon-google-circles"></span>Forecast', 'url' => array('/forecast'), 'auth_id' => 'Forecast', 'items' => array()),
            array('visible' => landa()->checkAccess('Account', 'r'), 'label' => '<span class="icon16 entypo-icon-creative-commons"></span>Account', 'url' => array('/account'), 'auth_id' => 'Account', 'items' => array()),
            array('visible' => landa()->checkAccess('ChargeAdditionalCategory', 'r'), 'label' => '<span class="icon16 entypo-icon-suitcase-2"></span>Departement', 'url' => array('/chargeAdditionalCategory'), 'auth_id' => 'ChargeAdditionalCategory', 'items' => array()),
            array('visible' => landa()->checkAccess('ChargeAdditional', 'r'), 'label' => '<span class="icon16 entypo-icon-add"></span>Additional Charge', 'url' => array('/chargeAdditional'), 'auth_id' => 'ChargeAdditional', 'items' => array()),
            array('visible' => landa()->checkAccess('Registration', 'r'), 'label' => '<span class="icon16 minia-icon-monitor"></span>Front Office', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('RoomCharting', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Room Blocking', 'url' => array('/roomCharting'), 'auth_id' => 'RoomCharting'),
                    array('visible' => landa()->checkAccess('RoomCharting', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Room List', 'url' => array('/roomCharting/stay'), 'auth_id' => 'RoomCharting'),
                    array('visible' => landa()->checkAccess('Deposit', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Deposite', 'url' => array('/deposite'), 'auth_id' => 'Deposit'),
                    array('visible' => landa()->checkAccess('Reservation', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Reservation', 'url' => array('/reservation'), 'auth_id' => 'Reservation'),
                    array('visible' => landa()->checkAccess('Registration', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Registration', 'url' => array('/registration'), 'auth_id' => 'Registration'),
                )),
            array('visible' => landa()->checkAccess('HouseKeeping', 'r'), 'label' => '<span class="icon16 silk-icon-trashcan"></span>House Keeping', 'url' => array('/roomCharting/houseKeeping'), 'auth_id' => 'HouseKeeping',),
            array('visible' => landa()->checkAccess('BillCharge', 'r'), 'label' => '<span class="icon16 icomoon-icon-calculate"></span>Cashier', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('BillCharge', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Transaction', 'url' => array('/billCharge'), 'auth_id' => 'BillCharge'),
                    array('visible' => landa()->checkAccess('Room', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Change Rate', 'url' => array('roomBill/editPaxExtrabed'), 'auth_id' => 'RoomBill'),
                    array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Move', 'url' => array('roomBill/move'), 'auth_id' => 'RoomBill'),
                    array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Extend', 'url' => array('roomBill/extend'), 'auth_id' => 'RoomBill'),
                    array('visible' => landa()->checkAccess('Bill', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Guest Bill', 'url' => array('/bill'), 'auth_id' => 'Bill'),
                    array('visible' => landa()->checkAccess('BillChasier', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Cashier Sheet', 'url' => array('billCashier/create'), 'auth_id' => 'BillChasier'),
                )),
            array('visible' => landa()->checkAccess('NightAudit', 'r'), 'label' => '<span class="icon16 wpzoom-night"></span>Night Audit', 'url' => array('/na'), 'auth_id' => 'NightAudit'),
            array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => '<span class="icon16 brocco-icon-wrench"></span>Tools', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Tax Export', 'url' => array('roomBill/taxExport'), 'auth_id' => 'TaxExport'),
                    array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Initial Forecast', 'url' => array('/initialForecast'), 'auth_id' => 'initialForecast'),
                    array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>History Login', 'url' => array('/user/auditUser'), 'auth_id' => 'auditUser'),
                )),
            array('visible' => landa()->checkAccess('Report_ProductSold', 'r'), 'label' => '<span class="icon16 cut-icon-printer-2"></span>View & Report', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('RoomCharting', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Guest History', 'url' => array('/user/history'), 'auth_id' => 'GuestHistory'),
                    array('visible' => landa()->checkAccess('Report_Arr/Dep', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Arrival/Departure', 'url' => array('/report/arrivdepar'), 'auth_id' => 'Report_Arr/Dep'),
                    array('visible' => landa()->checkAccess('Report_GuestInHouse', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Guest In House', 'url' => array('/report/guesthouse'), 'auth_id' => 'Report_GuestInHouse'),
                    array('visible' => landa()->checkAccess('Report_ExpectedGuest', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Expected Guest', 'url' => array('/report/expected'), 'auth_id' => 'Report_ExpectedGuest'),
                    array('visible' => landa()->checkAccess('Report_ProductSold', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Product Sold', 'url' => array('/report/productSold'), 'auth_id' => 'Report_ProductSold'),
                    array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Geographical', 'url' => array('report/geographical'), 'auth_id' => 'Report_RoomSales'),
                    array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Source Of Business', 'url' => array('report/sourceOfBusiness'), 'auth_id' => 'Report_RoomSales'),
                    array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Top Producers', 'url' => array('report/topProducers'), 'auth_id' => 'Report_RoomSales'),
                    array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Top Ten', 'url' => array('report/topTen'), 'auth_id' => 'Report_RoomSales'),
                )),
//            array('label' => '<span class="icon16 icomoon-icon-bars"></span>Chart', 'url' => array('/User'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
//                )),
        );
    }

}
