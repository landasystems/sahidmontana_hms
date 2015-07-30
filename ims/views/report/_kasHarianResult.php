

<?php
$cashout = AccCashOut::model()->findAll(array('condition' => 'date_posting ="' . $a . '" AND acc_coa_id="' . $cash . '" AND acc_approval_id is not NULL','order'=>'code_acc'));
$cashin = AccCashIn::model()->findAll(array('condition' => 'date_posting ="' . $a . '" AND acc_coa_id="' . $cash . '" AND acc_approval_id is not NULL','order'=>'code_acc'));


$newdate = strtotime('-1 day', strtotime($akhir));
$start = date('Y-m-d', $newdate);

$idk = array();
foreach ($cashout as $ua) {
    $idk[] = $ua->id;
}
$idk = (empty($idk)) ? array(0 => 0) : $idk;
$cashoutdet = AccCashOutDet::model()->findAll(array('condition' => 'acc_cash_out_id IN (' . implode(',', $idk) . ')','with'=>'AccCashOut', 'order'=>'AccCashOut.code_acc'));

$idd = array();
foreach ($cashin as $ub) {
    $idd[] = $ub->id;
}
$idd = (empty($idd)) ? array(0 => 0) : $idd;
$cashindet = AccCashInDet::model()->findAll(array('condition' => 'acc_cash_in_id IN (' . implode(',', $idd) . ')','with'=>'AccCashIn', 'order'=>'AccCashIn.code_acc'));
$this->renderPartial('_kasHarianDetail', array('prefix'=>false,'cashoutdet' => $cashoutdet, 'cashindet' => $cashindet,'a'=>$a,'cash'=>$cash));
?>
