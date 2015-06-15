<?php

/**
 * This is the model class for table "{{mlm_prize}}".
 *
 * The followings are the available columns in table '{{mlm_prize}}':
 * @property integer $id
 * @property string $type
 * @property integer $amount
 * @property integer $prize_user_id
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 */
class MlmPrize extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{mlm_prize}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('amount, prize_user_id, created_user_id', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 13),
            array('created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type, amount, prize_user_id, created, created_user_id, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'type' => 'Type',
            'amount' => 'Besar Bonus',
            'prize_user_id' => 'Prize User',
            'created' => 'Tanggal',
            'created_user_id' => 'Dari',
            'modified' => 'Modified',
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
        $criteria->compare('type', $this->type, true);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('prize_user_id', $this->prize_user_id);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
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

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MlmPrize the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function mlt($created_user_id, $amount) {
        //user yang mereferalkan mendapatkan 10%
//        $mReffReferal = User::model()->findByPk($created_user_id);
//        if (isset($mReffReferal->referal_user_id)) {
//            $model = new MlmPrize();
//            $model->prize_user_id = $mReffReferal->referal_user_id;
//            $model->type = 'reff_downline';
//            $model->amount = (10 * $amount) / 100;
//            $model->created_user_id = $created_user_id;
//            $model->save();
//
//            //tambah saldonya
//            User::model()->updateCounters(array('saldo' => $model->amount), 'id=' . $model->prize_user_id);
//        }
        //user bapaknya kaki ke kaki mendapatkan 5%
        $category = MlmDiagram::model()->find(array('condition' => 'created_user_id=' . $created_user_id));
        $mReffDownline = $category->ancestors()->findAll();
        $pieces = array();
        foreach ($mReffDownline as $key) {
//            if (isset($mReffReferal->referal_user_id) && $mReffReferal->referal_user_id == $key->created_user_id) { //selain yang mereferall kan
//                //do nothing
//            }else{
            $model = new MlmPrize();
            $model->prize_user_id = $key->created_user_id;
            $model->type = 'reff_trans';
            $model->amount = (5 * $amount) / 100;
            $model->created_user_id = $created_user_id;
            $model->save();
            $pieces[] = $key->created_user_id;
//            }
        }
        //tambah saldonya
        if (count($pieces) > 0)
            User::model()->updateCounters(array('saldo' => $model->amount), 'id IN (' . implode(',', $pieces) . ')');
    }

}
