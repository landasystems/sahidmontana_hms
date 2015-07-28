<?php

$a = explode('-', $_POST['AccCoaDet']['created']);
//$newdate = strtotime('-1 day', strtotime($start));
$parent = "";
$acc = AccCoa::model()->findByPk($id);
if ($acc->type == "detail") {
//    $sWhere = (!empty($pada)) ? ' AND ' : '';
    $lawan = '';
    $accCoaDet = AccCoaDet::model()->findAll(array('order' => 'date_coa , code', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and acc_coa_id =' . $id));
    if (!empty($pada)) {
        $detPada = array();
        foreach ($accCoaDet as $val) {
            $det = AccCoaDet::model()->findAll(array('order' => 'date_coa , code', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and acc_coa_id =' . $pada . ' and code = "' . $val->code . '"'));
            if (!empty($det)) {
                foreach ($det as $valPada) {
                    $detPada[] = $valPada;
                }
            }
        }
        $accCoaDet = $detPada;
    }
    $beginingBalance = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime($start)), $id, FALSE);
    $this->renderPartial('_generalLedgerDetail', array('start' => $start, 'pada' => $pada, 'end' => $end, 'acc' => $acc, 'beginingBalance' => $beginingBalance, 'accCoaDet' => $accCoaDet, 'start' => $start, 'checked' => $checked));
} else {
    $children = $acc->descendants()->findAll();
    foreach ($children as $val) {
        $accCoaDet = AccCoaDet::model()->findAll(array('order' => 'date_coa , code', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and acc_coa_id =' . $_POST['pada']));
        $beginingBalance = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime($start)), $id, FALSE);
        $this->renderPartial('_generalLedgerDetail', array('start' => $start, 'pada' => $pada, 'end' => $end, 'acc' => $val, 'beginingBalance' => $beginingBalance, 'accCoaDet' => $accCoaDet, 'checked' => $checked));
    }
}
?>