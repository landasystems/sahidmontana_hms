
<?php

class Auth extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function modules() {
        return array(
            array('label' => '<span class="icon16 icomoon-icon-screen"></span>Dashboard', 'url' => array('/dashboard')),
            array('visible' => landa()->checkAccess('SiteConfig', 'r') || landa()->checkAccess('Roles', 'r') || landa()->checkAccess('User', 'r'), 'label' => '<span class="icon16 icomoon-icon-cog"></span>Settings', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('SiteConfig', 'r'), 'auth_id' => 'SiteConfig', 'label' => '<span class="icon16 iconic-icon-new-window"></span>Site config', 'url' => array('/siteConfig/update/1'), 'crud' => array("r" => 1)),
                    array('visible' => landa()->checkAccess('Roles', 'r'), 'auth_id' => 'Roles', 'label' => '<span class="icon16 entypo-icon-users"></span>Access', 'url' => array('/roles'), 'crud' => array("c" => 1, "r" => 1, "u" => 1, "d" => 1)),
                    array('visible' => landa()->checkAccess('User', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>User', 'url' => url('/user'), 'auth_id' => 'User'),
                )),
            array('visible' => landa()->checkAccess('GroupGuest', 'r'), 'label' => '<span class="icon16  icomoon-icon-accessibility"></span>Guest', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    array('visible' => landa()->checkAccess('GroupGuest', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group Guest', 'url' => url('/guestGroup'), 'auth_id' => 'GroupGuest'),
                    array('visible' => landa()->checkAccess('User', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>Guest', 'url' => url('/guest'), 'auth_id' => 'Guest'),
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
        );
    }

}

?>