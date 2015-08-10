<?php

class CityController extends Controller {

    public function actionListAjax() {
        $data = City::model()->with('Province')->findAll(array('condition'=>'t.name like "%'.$_GET['q'].'%" OR Province.name like "%'.$_GET['q'].'%"'));
        if (empty($data)) {
            $list[] = array("id" => "0", "text" => "No Results Found..");
        } else {
            foreach ($data as $val) {
                $list[] = array("id" => $val->id, "text" => $val->fullName);
            }
        }
        echo json_encode($list);
    }

}
