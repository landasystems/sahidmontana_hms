
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
                    array('visible' => landa()->checkAccess('Roles', 'r'), 'auth_id' => 'Roles', 'label' => 'Access', 'url' => array('/roles'), 'crud' => array("c" => 1, "r" => 1, "u" => 1, "d" => 1)),
                    array('visible' => landa()->checkAccess('User', 'r'), 'label' => 'User', 'url' => url('/user'), 'auth_id' => 'User'),
                )),
            array('visible' => landa()->checkAccess('GroupGuest', 'r'), 'label' => 'Guest', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('GroupGuest', 'r'), 'label' => 'Group Guest', 'url' => url('/guestGroup'), 'auth_id' => 'GroupGuest'),
                    array('visible' => landa()->checkAccess('User', 'r'), 'label' => 'Guest', 'url' => url('/guest'), 'auth_id' => 'Guest'),
                )),
            array('visible' => landa()->checkAccess('Room', 'r'), 'label' => 'Rooms', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('RoomType', 'r'), 'label' => 'Type', 'url' => array('/roomType'), 'auth_id' => 'RoomType'),
                    array('visible' => landa()->checkAccess('Room', 'r'), 'label' => 'Room', 'url' => array('/room'), 'auth_id' => 'Room'),
                )),
            array('visible' => landa()->checkAccess('MarketSegment', 'r'), 'label' => 'Market Segment', 'url' => array('/marketSegment'), 'auth_id' => 'MarketSegment', 'items' => array()),
            array('visible' => landa()->checkAccess('Forecast', 'r'), 'label' => 'Forecast', 'url' => array('/forecast'), 'auth_id' => 'Forecast', 'items' => array()),
            array('visible' => landa()->checkAccess('Account', 'r'), 'label' => 'Account', 'url' => array('/account'), 'auth_id' => 'Account', 'items' => array()),
            array('visible' => landa()->checkAccess('ChargeAdditionalCategory', 'r'), 'label' => 'Departement', 'url' => array('/chargeAdditionalCategory'), 'auth_id' => 'ChargeAdditionalCategory', 'items' => array()),
            array('visible' => landa()->checkAccess('ChargeAdditional', 'r'), 'label' => 'Additional Charge', 'url' => array('/chargeAdditional'), 'auth_id' => 'ChargeAdditional', 'items' => array()),
            array('visible' => landa()->checkAccess('Registration', 'r'), 'label' => 'Front Office', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('RoomCharting', 'r'), 'label' => 'Room Blocking', 'url' => array('/roomCharting'), 'auth_id' => 'RoomCharting'),
                    array('visible' => landa()->checkAccess('RoomCharting', 'r'), 'label' => 'Room List', 'url' => array('/roomCharting/stay'), 'auth_id' => 'RoomCharting'),
                    array('visible' => landa()->checkAccess('Deposit', 'r'), 'label' => 'Deposite', 'url' => array('/deposite'), 'auth_id' => 'Deposit'),
                    array('visible' => landa()->checkAccess('Reservation', 'r'), 'label' => 'Reservation', 'url' => array('/reservation'), 'auth_id' => 'Reservation'),
                    array('visible' => landa()->checkAccess('Registration', 'r'), 'label' => 'Registration', 'url' => array('/registration'), 'auth_id' => 'Registration'),
                )),
            array('visible' => landa()->checkAccess('HouseKeeping', 'r'), 'label' => 'House Keeping', 'url' => array('/roomCharting/houseKeeping'), 'auth_id' => 'HouseKeeping',),
            array('visible' => landa()->checkAccess('BillCharge', 'r'), 'label' => 'Cashier', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('BillCharge', 'r'), 'label' => 'Transaction', 'url' => array('/billCharge'), 'auth_id' => 'BillCharge'),
                    array('visible' => landa()->checkAccess('Room', 'r'), 'label' => 'Change Rate', 'url' => array('roomBill/editPaxExtrabed'), 'auth_id' => 'RoomBill'),
                    array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => 'Move', 'url' => array('roomBill/move'), 'auth_id' => 'RoomBill'),
                    array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => 'Extend', 'url' => array('roomBill/extend'), 'auth_id' => 'RoomBill'),
                    array('visible' => landa()->checkAccess('Bill', 'r'), 'label' => 'Guest Bill', 'url' => array('/bill'), 'auth_id' => 'Bill'),
                    array('visible' => landa()->checkAccess('BillChasier', 'r'), 'label' => 'Cashier Sheet', 'url' => array('billCashier/create'), 'auth_id' => 'BillChasier'),
                )),
            array('visible' => landa()->checkAccess('NightAudit', 'r'), 'label' => 'Night Audit', 'url' => array('/na'), 'auth_id' => 'NightAudit'),
            array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => 'Tools', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => 'Tax Export', 'url' => array('roomBill/taxExport'), 'auth_id' => 'TaxExport'),
                    array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => 'Initial Forecast', 'url' => array('/initialForecast'), 'auth_id' => 'initialForecast'),
                    array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => 'History Login', 'url' => array('/user/auditUser'), 'auth_id' => 'auditUser'),
                )),
            array('visible' => landa()->checkAccess('Report_ProductSold', 'r'), 'label' => 'View & Report', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('RoomCharting', 'r'), 'label' => 'Guest History', 'url' => array('/user/history'), 'auth_id' => 'GuestHistory'),
                    array('visible' => landa()->checkAccess('Report_Arr/Dep', 'r'), 'label' => 'Arrival/Departure', 'url' => array('/report/arrivdepar'), 'auth_id' => 'Report_Arr/Dep'),
                    array('visible' => landa()->checkAccess('Report_GuestInHouse', 'r'), 'label' => 'Guest In House', 'url' => array('/report/guesthouse'), 'auth_id' => 'Report_GuestInHouse'),
                    array('visible' => landa()->checkAccess('Report_ExpectedGuest', 'r'), 'label' => 'Expected Guest', 'url' => array('/report/expected'), 'auth_id' => 'Report_ExpectedGuest'),
                    array('visible' => landa()->checkAccess('Report_ProductSold', 'r'), 'label' => 'Product Sold', 'url' => array('/report/productSold'), 'auth_id' => 'Report_ProductSold'),
                    array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => 'Geographical', 'url' => array('report/geographical'), 'auth_id' => 'Report_RoomSales'),
                    array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => 'Source Of Business', 'url' => array('report/sourceOfBusiness'), 'auth_id' => 'Report_RoomSales'),
                    array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => 'Top Producers', 'url' => array('report/topProducers'), 'auth_id' => 'Report_RoomSales'),
                    array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => 'Top Ten', 'url' => array('report/topTen'), 'auth_id' => 'Report_RoomSales'),
                )),
        );
    }

}

?>