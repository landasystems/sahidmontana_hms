
<?php

class Auth extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function modules() {
        return array(
            array('label' => 'Dashboard', 'url' => array('/dashboard')),
            array('visible' => landa()->checkAccess('SiteConfig', 'r') || landa()->checkAccess('Roles', 'r') || landa()->checkAccess('User', 'r'), 'label' => 'Settings', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('SiteConfig', 'r'), 'auth_id' => 'SiteConfig', 'label' => 'Site config', 'url' => array('/siteConfig/update/1'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Roles', 'r'), 'auth_id' => 'Roles', 'label' => 'Access', 'url' => array('/roles'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('User', 'r'), 'auth_id' => 'User', 'label' => 'User', 'url' => url('/user'), 'crud' => array("r" => 1)),
                )),
            array('visible' => landa()->checkAccess('GroupGuest', 'r') || landa()->checkAccess('User', 'r'), 'label' => 'Guest', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('GroupGuest', 'r'), 'auth_id' => 'GroupGuest', 'label' => 'Group Guest', 'url' => url('/guestGroup'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Guest', 'r'), 'auth_id' => 'Guest', 'label' => 'Guest', 'url' => url('/guest'), 'crud' => array("r" => 1)),
                )),
            array('visible' => landa()->checkAccess('Room', 'r') || landa()->checkAccess('RoomType', 'r'), 'label' => 'Rooms', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('RoomType', 'r'), 'auth_id' => 'RoomType', 'label' => 'Type', 'url' => array('/roomType'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Room', 'r'), 'auth_id' => 'Room', 'label' => 'Room', 'url' => array('/room'), 'crud' => array("r" => 1)),
                )),
            array('visible' => landa()->checkAccess('MarketSegment', 'r'), 'auth_id' => 'MarketSegment', 'label' => 'Market Segment', 'url' => array('/marketSegment'), 'crud' => array("r" => 1)),
            array('visible' => landa()->checkAccess('Forecast', 'r'), 'auth_id' => 'Forecast', 'label' => 'Forecast', 'url' => array('/forecast'), 'crud' => array("r" => 1)),
            array('visible' => landa()->checkAccess('Account', 'r'), 'auth_id' => 'Account', 'label' => 'Account', 'url' => array('/account'), 'crud' => array("r" => 1)),
            array('visible' => landa()->checkAccess('ChargeAdditionalCategory', 'r'), 'auth_id' => 'ChargeAdditionalCategory', 'label' => 'Departement', 'url' => array('/chargeAdditionalCategory'), 'crud' => array("r" => 1)),
            array('visible' => landa()->checkAccess('ChargeAdditional', 'r'), 'auth_id' => 'ChargeAdditional', 'label' => 'Additional Charge', 'url' => array('/chargeAdditional')),
            array('visible' => landa()->checkAccess('Registration', 'r') || landa()->checkAccess('Reservation', 'r') ||landa()->checkAccess('Deposit', 'r') || landa()->checkAccess('RoomCharting', 'r'), 'label' => 'Front Office', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('RoomCharting', 'r'), 'auth_id' => 'RoomCharting', 'label' => 'Room Blocking', 'url' => array('/roomCharting'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('RoomCharting', 'r'), 'auth_id' => 'RoomCharting', 'label' => 'Room List', 'url' => array('/roomCharting/stay'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Deposit', 'r'), 'auth_id' => 'Deposit', 'label' => 'Deposite', 'url' => array('/deposite'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Reservation', 'r'), 'auth_id' => 'Reservation', 'label' => 'Reservation', 'url' => array('/reservation'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Registration', 'r'), 'auth_id' => 'Registration', 'label' => 'Registration', 'url' => array('/registration'), 'crud' => array("r" => 1)),
                )),
            array('visible' => landa()->checkAccess('HouseKeeping', 'r'), 'label' => 'House Keeping', 'url' => array('/roomCharting/houseKeeping'), 'auth_id' => 'HouseKeeping', 'crud' => array("r" => 1)),
            array('visible' => landa()->checkAccess('BillCharge', 'r') ||landa()->checkAccess('BillChasier', 'r')|| landa()->checkAccess('Bill', 'r') || landa()->checkAccess('RoomBill', 'r')|| landa()->checkAccess('Room', 'r') , 'label' => 'Cashier', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('BillCharge', 'r'), 'auth_id' => 'BillCharge', 'label' => 'Transaction', 'url' => array('/billCharge'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Room', 'r'), 'auth_id' => 'Room', 'label' => 'Change Rate', 'url' => array('roomBill/editPaxExtrabed'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('RoomBill', 'r'), 'auth_id' => 'RoomBill', 'label' => 'Move', 'url' => array('roomBill/move'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('RoomBill_extend', 'r'), 'auth_id' => 'RoomBill_extend', 'label' => 'Extend', 'url' => array('roomBill/extend'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Bill', 'r'), 'auth_id' => 'Bill', 'label' => 'Guest Bill', 'url' => array('/bill'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('BillChasier', 'r'), 'auth_id' => 'BillChasier', 'label' => 'Cashier Sheet', 'url' => array('billCashier/create'), 'crud' => array("r" => 1)),
                )),
            array('visible' => landa()->checkAccess('NightAudit', 'r'), 'auth_id' => 'NightAudit', 'label' => 'Night Audit', 'url' => array('/na'), 'crud' => array("r" => 1)),
            array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => 'Tools', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('taxExport', 'r'), 'auth_id' => 'TaxExport', 'label' => 'Tax Export', 'url' => array('roomBill/taxExport'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('initialForecast', 'r'), 'auth_id' => 'initialForecast', 'label' => 'Initial Forecast', 'url' => array('/initialForecast'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('auditUser', 'r'), 'auth_id' => 'auditUser', 'label' => 'History Login', 'url' => array('/user/auditUser'), 'crud' => array("r" => 1)),
                )),
            array('visible' => landa()->checkAccess('Report_ProductSold', 'r')||landa()->checkAccess('Report_RoomSales', 'r')||landa()->checkAccess('Report_ExpectedGuest', 'r')||landa()->checkAccess('Report_GuestInHouse', 'r')||landa()->checkAccess('Report_Arr/Dep', 'r')|| landa()->checkAccess('RoomCharting', 'r'), 'label' => 'View & Report', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('GuestHistory', 'r'), 'auth_id' => 'GuestHistory', 'label' => 'Guest History', 'url' => array('/user/history'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Report_Arr/Dep', 'r'), 'auth_id' => 'Report_Arr/Dep', 'label' => 'Arrival/Departure', 'url' => array('/report/arrivdepar'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Report_GuestInHouse', 'r'), 'auth_id' => 'Report_GuestInHouse', 'label' => 'Guest In House', 'url' => array('/report/guesthouse'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Report_ExpectedGuest', 'r'), 'auth_id' => 'Report_ExpectedGuest', 'label' => 'Expected Guest', 'url' => array('/report/expected'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Report_ProductSold', 'r'), 'auth_id' => 'Report_ProductSold', 'label' => 'Product Sold', 'url' => array('/report/productSold'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Report_geographical', 'r'), 'auth_id' => 'Report_geographical', 'label' => 'Geographical', 'url' => array('report/geographical'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Report_sourceOfBusiness', 'r'), 'auth_id' => 'Report_sourceOfBusiness', 'label' => 'Source Of Business', 'url' => array('report/sourceOfBusiness'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Report_topProducers', 'r'), 'auth_id' => 'Report_topProducers', 'label' => 'Top Producers', 'url' => array('report/topProducers'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Report_topTen', 'r'), 'auth_id' => 'Report_topTen', 'label' => 'Top Ten', 'url' => array('report/topTen'), 'crud' => array("r" => 1)),
                )),
        );
    }

}

?>
