<?php

/**
 * This is the model class for table "{{m_menu}}".
 *
 * The followings are the available columns in table '{{m_menu}}':
 * @property integer $id
 * @property string $name
 * @property string $ordering
 * @property integer $access
 * @property integer $lft
 * @property integer $rgt
 * @property integer $root
 * @property string $link
 * @property string $type
 * @property string $type
 */
class Menu extends CActiveRecord {

    public $cache;

//    public function __construct() {
//        $this->cache = Yii::app()->cache;
//    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Menu the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{menu}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('lft, rgt, root, menu_category_id, parent_id', 'numerical', 'integerOnly' => true),
            array('name, ordering, type', 'length', 'max' => 45),
            array('access', 'length', 'max' => 100),
            array('link', 'length', 'max' => 255),
            array('param', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, ordering, access, lft, rgt, root, link, type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'MenuCategory' => array(self::BELONGS_TO, 'MenuCategory', 'menu_category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'ordering' => 'Ordering',
            'access' => 'Access',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'root' => 'Root',
            'link' => 'Link',
            'type' => 'Type',
            'textfield' => 'textfield',
            'datefield' => 'datefield',
            'menu_category_id' => 'Category',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('menu_category_id', $this->menu_category_id);
        $criteria->compare('ordering', $this->ordering, true);
        $criteria->compare('access', $this->access);
        $criteria->compare('lft', $this->lft);
        $criteria->compare('rgt', $this->rgt);
        $criteria->compare('root', $this->root);
        $criteria->compare('link', $this->link, true);
        $criteria->compare('type', $this->type, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'root, lft',
            ),
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
            'nestedSetBehavior' => array(
                'class' => 'common.extensions.NestedSetBehavior.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'hasManyRoots' => true,
            ),
        );
    }

    public function getNestedName() {
        return ($this->level == 1) ? $this->name : str_repeat("|—", $this->level - 1) . $this->name;
    }

    public function getMenuType() {

        if (app()->controller->action->id == 'update') {
            $arrUrl = array('menu/' . app()->controller->action->id, 'id' => $_GET['id']);
        } else {
            $arrUrl = array('menu/' . app()->controller->action->id);
        }

        $sAction = 'menu/create';
        $results = array(
            array('label' => 'Home', 'url' => array_merge($arrUrl, array('type' => 'home'))),
            array('label' => 'External Url', 'url' => array_merge($arrUrl, array('type' => 'url'))),
            array('visible' => in_array('ecommerce', param('menu')), 'label' => 'Product Category', 'url' => array_merge($arrUrl, array('type' => 'productCategory'))),
            array('label' => 'Content', 'items' =>
                array(
                    array('label' => 'Single Article', 'url' => array_merge($arrUrl, array('type' => 'singleArticle'))),
                    array('label' => 'Article LIst', 'url' => array_merge($arrUrl, array('type' => 'listArticle'))),
                    array('label' => 'Blog Layout', 'url' => array_merge($arrUrl, array('type' => 'blog'))),
                    array('label' => 'Timeline Layout', 'url' => array_merge($arrUrl, array('type' => 'timeline'))),
                ),
            ),
            array('visible' => in_array('ecommerce', param('menu')), 'label' => 'Transaction', 'items' =>
                array(
                    array('label' => 'List', 'url' => array_merge($arrUrl, array('type' => 'sell'))),
                    array('label' => 'Confirmation', 'url' => array_merge($arrUrl, array('type' => 'payment'))),
                    array('label' => 'View Cart', 'url' => array_merge($arrUrl, array('type' => 'chart'))),
                    array('label' => 'Checkout', 'url' => array_merge($arrUrl, array('type' => 'checkout'))),
                ),
            ),
            array('visible' => in_array('saldo', param('menu')), 'label' => 'Saldo', 'items' =>
                array(
                    array('label' => 'Deposit', 'url' => array_merge($arrUrl, array('type' => 'deposit'))),
                    array('label' => 'Withdrawal', 'url' => array_merge($arrUrl, array('type' => 'withdrawal'))),
                    array('label' => 'Transfer Saldo', 'url' => array_merge($arrUrl, array('type' => 'transferSaldo'))),
                    array('label' => 'History Transfer', 'url' => array_merge($arrUrl, array('type' => 'historyTransfer'))),
                ),
            ),
            array('visible' => in_array('mlm', param('menu')), 'label' => 'MLM', 'items' =>
                array(
                    array('label' => 'Referal', 'url' => array_merge($arrUrl, array('type' => 'referal'))),
                    array('label' => 'Downline', 'url' => array_merge($arrUrl, array('type' => 'downline'))),
                    array('label' => 'Bonus', 'url' => array_merge($arrUrl, array('type' => 'bonus'))),
                ),
            ),
            array('visible' => in_array('donation', param('menu')), 'label' => 'Donation', 'items' =>
                array(
                    array('label' => 'Request', 'url' => array_merge($arrUrl, array('type' => 'request'))),
                    array('label' => 'Give', 'url' => array_merge($arrUrl, array('type' => 'give'))),
                    array('label' => 'Transfer Coin Perak', 'url' => array_merge($arrUrl, array('type' => 'transferCoin'))),
                    array('label' => 'History Donation', 'url' => array_merge($arrUrl, array('type' => 'historyDonation'))),
                ),
            ),
            array('label' => 'User', 'url' => array_merge($arrUrl, array('type' => 'user'))),
            array('label' => 'Site Map', 'url' => array_merge($arrUrl, array('type' => 'siteMap'))),
            array('label' => 'List User', 'url' => array_merge($arrUrl, array('type' => 'listuser'))),
            array('visible' => in_array('portofolio', param('menu')), 'label' => 'Portfolio', 'url' => array_merge($arrUrl, array('type' => 'portfolio'))),
            array('label' => 'Gallery', 'url' => array_merge($arrUrl, array('type' => 'gallery'))),
            array('label' => 'Contact Us', 'url' => array_merge($arrUrl, array('type' => 'contact'))),
            array('visible' => in_array('download', param('menu')), 'label' => 'Download', 'url' => array_merge($arrUrl, array('type' => 'download'))),
            array('label' => 'Testimonial', 'url' => array_merge($arrUrl, array('type' => 'testimonial'))),
            array('visible' => in_array('event', param('menu')), 'label' => 'Event', 'items' =>
                array(
                    array('label' => 'Event List', 'url' => array_merge($arrUrl, array('type' => 'event'))),
                    array('label' => 'Event Calender', 'url' => array_merge($arrUrl, array('type' => 'eventCalender'))),
                ),
            ),
//            array('label' => 'Event', 'url' => array_merge($arrUrl, array('type' => 'event'))),
            array('visible' => in_array('form', param('menu')), 'label' => 'Form', 'url' => array_merge($arrUrl, array('type' => 'form'))),
            array('visible' => in_array('tour', param('menu')), 'label' => 'Form Tour', 'url' => array_merge($arrUrl, array('type' => 'formTour'))),
            array('visible' => in_array('mlm', param('menu')), 'label' => 'Ticket', 'url' => array_merge($arrUrl, array('type' => 'ticket'))),
            array('visible' => in_array('poker', param('menu')), 'label' => 'Poker', 'url' => array_merge($arrUrl, array('type' => 'flash'))),
            array('visible' => in_array('invoice', param('menu')), 'label' => 'Invoice', 'url' => array_merge($arrUrl, array('type' => 'invoice'))),
            array('visible' => in_array('message', param('menu')), 'label' => 'Message', 'url' => array_merge($arrUrl, array('type' => 'message'))),
        );

        return $results;
    }

    public function listMenu() {
//        $this->cache = Yii::app()->cache;
        if (!app()->session['listMenu']) {
            $sWhere = '';
            if (isset(user()->id)) {
                $sWhere = ' OR access="login" OR access="' . User::model()->role(user()->id) . '"';
            } else {
                $sWhere = ' OR access="guest"';
            }

            $criteria = new CDbCriteria;
            $criteria->condition = 'enabled = 1 AND (access="all"' . $sWhere . ')';
            $criteria->order = 'root, lft';

            app()->session['listMenu'] = $this->findAll($criteria);
            ;
        }

        return app()->session['listMenu'];
    }

}
