<?php

/**
 * This is the model class for table "{{product_stock}}".
 *
 * The followings are the available columns in table '{{product_stock}}':
 * @property integer $id
 * @property string $product_id
 * @property string $created
 * @property integer $created_user_id
 * @property integer $departement_id
 * @property double $qty
 * @property integer $price
 */
class ProductStock extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{product_stock}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_user_id, departement_id, price', 'numerical', 'integerOnly' => true),
            array('qty', 'numerical'),
            array('product_id', 'length', 'max' => 45),
            array('created', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, product_id, created, created_user_id, departement_id, qty, price', 'safe', 'on' => 'search'),
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
            'product_id' => 'Product',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'departement_id' => 'Departement',
            'qty' => 'Qty',
            'price' => 'Price',
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
        $criteria->compare('product_id', $this->product_id, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('departement_id', $this->departement_id);
        $criteria->compare('qty', $this->qty);
        $criteria->compare('price', $this->price);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ProductStock the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function process($type, $product_id, $qty, $departement_id, $price = 0, $description = '') {
        $mProduct = Product::model()->findByPk($product_id);
        $oSiteConfig = SiteConfig::model()->listSiteConfig();
        $in = array();
        $out = array();
        $balance = array();

        if ($type == 'in') {
//            $mProductStock = $this->find(array('condition' => 'product_id=' . $product_id . ' AND departement_id=' . $departement_id . ' AND price=' . $price));
            $mProductStock = new ProductStock();
            $mProductStock->product_id = $product_id;
            $mProductStock->departement_id = $departement_id;
            $mProductStock->qty = $qty;
            $mProductStock->price = $price;
            $mProductStock->save();

            //set stock in product
            $boolStatus = false;
            $arrStock = json_decode($mProduct->stock);
            foreach ($arrStock as $arrDepartement_id => $arrQty){
                if ($arrDepartement_id == $departement_id){
                    $boolStatus = true;
                    $arrStock->$arrDepartement_id += $qty;
                }
            }
            if ($boolStatus == false){ //stock not any in departement
                $arrStock = (array) $arrStock + array($departement_id => $qty);
            }
            $mProduct->stock = json_encode($arrStock);

            //set for stock card
            $in = array('qty' => $qty, 'price' => $price);
            $mProductStockCard = ProductStockCard::model()->find(array('condition' => 'product_id=' . $product_id . ' AND departement_id=' . $departement_id, 'order' => 'id DESC'));
            if (!empty($mProductStockCard)) {
                $balance = json_decode($mProductStockCard->balance, true);

                //checking if same price , just update the qty
//                $boolStatus = true;
//                foreach ($balance as $no => $arr) {
//                    if ($arr['price'] == $price) {
//                        $balance[$no]['qty'] += $qty;
//                        $boolStatus = false;
//                    }
//                }
//                if ($boolStatus)
                $balance[] = $in;
            } else {
                $balance[] = $in;
            }
        } elseif ($type == 'out') {
            $ordering = ($oSiteConfig->method=='fifo') ? 'ASC' : 'DESC';
            $mProductStock = $this->findAll(array('condition' => 'product_id=' . $product_id . ' AND departement_id=' . $departement_id, 'order'=>'created ' . $ordering));
            $tempQty = $qty; //tempQty will be looping to less the stock
            $boolStatus = true;

            foreach ($mProductStock as $arr) {
                if ($boolStatus) {
                    if ($arr->qty > $tempQty) { // stock is more , than qty which will be less
                        $arr->qty -= $tempQty;
                        $arr->save();
                        
                        $boolStatus = false; //finish to lessing stock
                        $balance[] = array('qty' => $arr->qty, 'price' => $arr->price);
                        
                        $out[] = array('qty' => $tempQty, 'price' => $arr->price);
                    } else {
                        $out[] = array('qty' => $arr->qty, 'price' => $arr->price);
                        
                        $tempQty -= $arr->qty;
                        $arr->delete();
                    }
                } else {
                    $balance[] = array('qty' => $arr->qty, 'price' => $arr->price);
                }
            }

            //set stock in product
            $boolStatus = false;
            $arrStock = json_decode($mProduct->stock);
            foreach ($arrStock as $arrDepartement_id => $arrQty){
                if ($arrDepartement_id == $departement_id){
                    $boolStatus = true;
                    $arrStock->$arrDepartement_id -= $qty;
                }
            }
//            if ($boolStatus == false){ //stock not any in departement
//                $arrStock = (array) $arrStock + array($departement_id => $qty);
//            }
            $mProduct->stock = json_encode($arrStock);

            //set for stock card
//            $balance = json_encode($this->findAll(array('condition' => 'product_id=' . $product_id . ' AND departement_id=' . $departement_id)));
        }
        $mProduct->save(); //update stock in product
        //insert to stock card
        ProductStockCard::model()->process($description, $product_id, $in, $out, $balance, $departement_id);
    }

    public function behaviors() {
        return array(
            'timestamps' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }
    
    public function departement($stock, $departement_id){
        $stockDepartement = json_decode($stock);
        $stockDepartement = (empty($stockDepartement->$departement_id)) ? 0 : $stockDepartement->$departement_id;
        return $stockDepartement;
    }

}
