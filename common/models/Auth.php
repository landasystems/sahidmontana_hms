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
        return Yii::app()->db2;
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

    public function modules($arg = NULL) {
        if (empty($arg)) {
            $appName = app()->name;
        } else {
            $appName = $arg;
        }
        if ($appName == 'Content Management Systems') {
            return array(
                array('label' => '<span class="icon16 icomoon-icon-screen"></span>Dashboard', 'url' => array('/dashboard')),
                array('visible' => landa()->checkAccess('User', 'r'), 'label' => '<span class="icon16 icomoon-icon-user-3"></span>User', 'url' => array('/user'), 'auth_id' => 'User'),
                array('visible' => in_array('sms', param('menu')) && landa()->checkAccess('GroupContact', 'r'), 'label' => '<span class="icon16  entypo-icon-contact"></span>Contact', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('GroupContact', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group Contact', 'url' => url('landa/roles/contact'), 'auth_id' => 'GroupContact'),
                        array('visible' => landa()->checkAccess('Contact', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>Contact', 'url' => url('user/contact'), 'auth_id' => 'Contact'),
                    )),
                array('visible' => in_array('product', param('menu')) && landa()->checkAccess('GroupCustomer', 'r'), 'label' => '<span class="icon16 wpzoom-user-2"></span>Customer', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('GroupCustomer', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group Customer', 'url' => url('/landa/roles/customer'), 'auth_id' => 'GroupCustomer'),
                        array('visible' => landa()->checkAccess('Customer', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>Customer', 'url' => url('/user/customer'), 'auth_id' => 'Customer'),
                    )),
                array('visible' => in_array('invoice', param('menu')) && landa()->checkAccess('GroupCLient', 'r'), 'label' => '<span class="icon16 wpzoom-user-2"></span>Client', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('GroupClient', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group CLient', 'url' => url('/landa/roles/client'), 'auth_id' => 'GroupClient'),
                        array('visible' => landa()->checkAccess('Client', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>Client', 'url' => url('/user/client'), 'auth_id' => 'Client'),
                    )),
                array('visible' => in_array('school', param('menu')) && (landa()->checkAccess('GroupTeacher', 'r') || landa()->checkAccess('Teacher', 'r')), 'label' => '<span class="icon16  entypo-icon-contact"></span>Guru', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('GroupTeacher', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group Guru', 'url' => url('landa/roles/teacher'), 'auth_id' => 'GroupTeacher'),
                        array('visible' => landa()->checkAccess('Teacher', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>Guru', 'url' => url('user/teacher'), 'auth_id' => 'Teacher'),
                    )),
                array('visible' => in_array('school', param('menu')) && (landa()->checkAccess('GroupStudent', 'r') || landa()->checkAccess('Student', 'r')), 'label' => '<span class="icon16  entypo-icon-contact"></span>Murid', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('GroupStudent', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group Murid', 'url' => url('landa/roles/student'), 'auth_id' => 'GroupStudent'),
                        array('visible' => landa()->checkAccess('Student', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>Murid', 'url' => url('user/student'), 'auth_id' => 'Student'),
                    )),
                array('visible' => in_array('menu', param('menu')) && landa()->checkAccess('Menu', 'r'), 'label' => '<span class="icon16 icomoon-icon-menu"></span>Menu', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('MenuCategory', 'r'), 'label' => '<span class="icon16 eco-list"></span>Kategori', 'url' => array('/menuCategory'), 'auth_id' => 'MenuCategory'),
                        array('visible' => landa()->checkAccess('Menu', 'r'), 'label' => '<span class="icon16 minia-icon-list"></span>Menu Item', 'url' => array('/menu'), 'auth_id' => 'Menu'),
                    )),
                array('visible' => in_array('content', param('menu')) && landa()->checkAccess('Article', 'r'), 'label' => '<span class="icon16 iconic-icon-book-alt2"></span>Content', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('ArticleCategory', 'r'), 'label' => '<span class="icon16 eco-list"></span>Category', 'url' => array('/articleCategory'), 'auth_id' => 'ArticleCategory'),
                        array('visible' => landa()->checkAccess('Article', 'r'), 'label' => '<span class="icon16 iconic-icon-article"></span>Artikel', 'url' => array('/article'), 'auth_id' => 'Article'),
                    )),
                array('visible' => in_array('ecommerce', param('menu')) && landa()->checkAccess('City', 'r'), 'label' => '<span class="icon16 wpzoom-truck"></span>Pengiriman', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('City', 'r'), 'label' => '<span class="icon16 entypo-icon-home"></span>Provinsi', 'url' => array('/landa/province'), 'auth_id' => 'provinsi'),
                        array('visible' => landa()->checkAccess('City', 'r'), 'label' => '<span class="icon16 icomoon-icon-home"></span>kota', 'url' => array('/landa/city'), 'auth_id' => 'kota'),
                    )),
                array('visible' => in_array('tour', param('menu')) && landa()->checkAccess('Article', 'r'), 'label' => '<span class="icon16 icomoon-icon-cars"></span>Tour', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('ArticleCategory', 'r'), 'label' => '<span class="icon16 iconic-icon-article"></span>Paket', 'url' => array('/paket'), 'auth_id' => 'ArticleCategory'),
                        array('visible' => landa()->checkAccess('Article', 'r'), 'label' => '<span class="icon16 iconic-icon-article"></span>Hotel', 'url' => array('/hotel'), 'auth_id' => 'Article'),
                        array('visible' => landa()->checkAccess('Article', 'r'), 'label' => '<span class="icon16 iconic-icon-article"></span>Kamar', 'url' => array('/kamar'), 'auth_id' => 'Article'),
                        array('visible' => landa()->checkAccess('Article', 'r'), 'label' => '<span class="icon16 iconic-icon-article"></span>Kapal', 'url' => array('/kapal'), 'auth_id' => 'Article'),
                    )),
                array('visible' => in_array('download', param('menu')) && landa()->checkAccess('Download', 'r'), 'label' => '<span class="icon16 minia-icon-download"></span>Download', 'url' => array('/downloadCategory'), 'auth_id' => 'Download'),
                array('visible' => in_array('gallery', param('menu')) && landa()->checkAccess('Gallery', 'r'), 'label' => '<span class="icon16 entypo-icon-images"></span>Gallery', 'url' => array('/galleryCategory'), 'auth_id' => 'Gallery'),
                array('visible' => in_array('testimonial', param('menu')) && landa()->checkAccess('Testimonial', 'r'), 'label' => '<span class="icon16 brocco-icon-comment"></span>Testimonial', 'url' => array('/testimonial'), 'auth_id' => 'Testimonial'),
                array('visible' => in_array('weblink', param('menu')) && landa()->checkAccess('Weblink', 'r'), 'label' => '<span class="icon16  icomoon-icon-link"></span>Weblink', 'url' => array('/weblink'), 'auth_id' => 'Weblink'),
                array('visible' => in_array('ticket', param('menu')) && landa()->checkAccess('TicketCategory', 'r'), 'label' => '<span class="icon16  wpzoom-chat"></span>Ticket Category', 'url' => array('/ticketCategory'), 'auth_id' => 'TicketCategory'),
                array('visible' => in_array('event', param('menu')) && landa()->checkAccess('Event', 'r'), 'label' => '<span class="icon16 brocco-icon-calendar"></span>Event', 'url' => array('/event'), 'auth_id' => 'Event'),
                array('visible' => in_array('invoice', param('menu')) && landa()->checkAccess('Invoice', 'r'), 'label' => '<span class="icon16 brocco-icon-calendar"></span>Invoices', 'url' => array('/invoices'), 'auth_id' => 'Invoice'),
                array('visible' => in_array('school', param('menu')) && (landa()->checkAccess('SchoolYear', 'r') || landa()->checkAccess('Classroom', 'r') || landa()->checkAccess('UserClassroom', 'r')), 'label' => '<span class="icon16 typ-icon-cog"></span>Akademik', 'url' => array('/User'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('SchoolYear', 'r'), 'label' => '<span class="icon16 minia-icon-calendar"></span>Tahun Ajaran', 'url' => array('/schoolYear'), 'auth_id' => 'SchoolYear'),
                        array('visible' => landa()->checkAccess('Classroom', 'r'), 'label' => '<span class="icon16 minia-icon-office"></span>Kelas', 'url' => array('/classroom'), 'auth_id' => 'Classroom'),
                        array('visible' => landa()->checkAccess('UserClassroom', 'r'), 'label' => '<span class="icon16 typ-icon-users"></span>Penempatan Siswa', 'url' => array('/userClassroom'), 'auth_id' => 'UserClassroom'),
                    )),
                array('visible' => in_array('donation', param('menu')), 'label' => '<span class="icon16 icomoon-icon-file-openoffice"></span>Donation', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('donation', 'r'), 'label' => '<span class="icon16 eco-list"></span>Request', 'url' => array('/donation'), 'auth_id' => 'Donation'),
                        array('visible' => landa()->checkAccess('donation', 'r'), 'label' => '<span class="icon16 brocco-icon-gift"></span>Give', 'url' => array('/donationGive'), 'auth_id' => 'DonationGive'),
                        array('visible' => landa()->checkAccess('donation', 'r'), 'label' => '<span class="icon16  iconic-icon-transfer"></span>Tukar Kolom Kosong', 'url' => array('/mlmExchange'), 'auth_id' => 'MlmExchange'),
                    )),
                array('visible' => in_array('portofolio', param('menu')), 'label' => '<span class="icon16 icomoon-icon-file-openoffice"></span>Portofolio', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('PortofolioCategory', 'r'), 'label' => '<span class="icon16 eco-list"></span>Category', 'url' => array('/portfolioCategory'), 'auth_id' => 'PortofolioCategory'),
                        array('visible' => landa()->checkAccess('Portofolio', 'r'), 'label' => '<span class="icon16 icomoon-icon-trophy"></span>Portofolio Item', 'url' => array('/portfolio'), 'auth_id' => 'Portofolio'),
                    )),
                array('visible' => in_array('form', param('menu')), 'label' => '<span class="icon16 brocco-icon-window"></span>Form Manager', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('Form', 'r'), 'label' => '<span class="icon16 icomoon-icon-trophy"></span>Form ', 'url' => array('/formCategory'), 'auth_id' => 'FormCategory'),
                        array('visible' => landa()->checkAccess('FormCategory', 'r'), 'label' => '<span class="icon16 icomoon-icon-trophy"></span>List Form', 'url' => array('/formBuilder'), 'auth_id' => 'FormBuilder'),
                    )),
                array('visible' => in_array('tour', param('menu')) && landa()->checkAccess('FormTour', 'r'), 'label' => '<span class="icon16 brocco-icon-calendar"></span>Form Pesanan', 'url' => array('/formTour'), 'auth_id' => 'Tour'),
                array('visible' => in_array('game', param('menu')) && landa()->checkAccess('Play', 'r') && landa()->checkAccess('PlayResult', 'r'), 'label' => '<span class="icon16 brocco-icon-window"></span>Permainan', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('Play', 'r'), 'label' => '<span class="icon16 icomoon-icon-trophy"></span>Pemasangan ', 'url' => array('/play'), 'auth_id' => 'Play'),
                        array('visible' => landa()->checkAccess('PlayResult', 'r'), 'label' => '<span class="icon16 icomoon-icon-trophy"></span>Keluaran', 'url' => array('/playResult'), 'auth_id' => 'PlayResult'),
                    )),
                array('visible' => in_array('payment', param('menu')) && landa()->checkAccess('Payment', 'r'), 'label' => '<span class="icon16 brocco-icon-tag"></span>Payment Manager', 'url' => array('/payment'), 'auth_id' => 'PaymentManager'),
                array('visible' => in_array('sms', param('menu')) && landa()->checkAccess('Sms', 'r'), 'label' => '<span class="icon16 wpzoom-phone-3"></span>SMS', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('SmsKeyword', 'r'), 'label' => '<span class="icon16 icomoon-icon-key-2"></span>Keyword', 'url' => url('/smsKeyword'), 'auth_id' => 'SmsKeyword'),
                        array('visible' => landa()->checkAccess('Outbox', 'r'), 'label' => '<span class="icon16 entypo-icon-comment"></span>Outbox', 'url' => url('/sms/outbox'), 'auth_id' => 'Outbox'),
                        array('visible' => landa()->checkAccess('Sms', 'r'), 'label' => '<span class="icon16 entypo-icon-inbox"></span>Inbox & Sentitems', 'url' => url('/sms'), 'auth_id' => 'Sms'),
                        array('visible' => landa()->checkAccess('LogModem', 'r'), 'label' => '<span class="icon16 icomoon-icon-mobile-2"></span>Log Status Modem', 'url' => url('/sms/logModem'), 'auth_id' => 'LogModem'),
                    )),
                array('visible' => in_array('product', param('menu')) && landa()->checkAccess('Customer', 'r'), 'label' => '<span class="icon16 silk-icon-notebook"></span>Inventory', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('ProductBrand', 'r'), 'label' => '<span class="icon16 eco-list"></span>Merek', 'url' => array('/productBrand'), 'auth_id' => 'ProductBrand'),
                        array('visible' => landa()->checkAccess('ProductMeasure', 'r'), 'label' => '<span class="icon16 entypo-icon-document"></span>Satuan', 'url' => array('/productMeasure'), 'auth_id' => 'ProductMeasure'),
                        array('visible' => landa()->checkAccess('ProductCategory', 'r'), 'label' => '<span class="icon16 cut-icon-tree"></span>Kategori', 'url' => array('/productCategory'), 'auth_id' => 'ProductCategory'),
                        array('visible' => landa()->checkAccess('ProductSupplier', 'r'), 'label' => '<span class="icon16 icomoon-icon-accessibility"></span>Supplier', 'url' => array('/productSupplier')),
                        array('visible' => landa()->checkAccess('Product', 'r'), 'label' => '<span class="icon16 cut-icon-list"></span>Barang', 'url' => array('/product'), 'auth_id' => 'Product'),
                        array('visible' => landa()->checkAccess('Product', 'r'), 'label' => '<span class="icon16 cut-icon-list"></span>Supplier Invoice', 'url' => array('/product'), 'auth_id' => 'Product'),
                    )),
                array('visible' => in_array('ecommerce', param('menu')) && landa()->checkAccess('Stock', 'r'), 'label' => '<span class="icon16 cut-icon-checkbox-checked"></span>Stock', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('StockIn', 'r'), 'label' => '<span class="icon16 entypo-icon-reply"></span>In', 'url' => array('/in'), 'auth_id' => 'StockIn'),
                        array('visible' => landa()->checkAccess('StockOut', 'r'), 'label' => '<span class="icon16 entypo-icon-forward"></span>Out', 'url' => array('/out'), 'auth_id' => 'StockOut'),
                        array('visible' => landa()->checkAccess('StockOpname', 'r'), 'label' => '<span class="icon16 iconic-icon-transfer"></span>Opname', 'url' => array('/opname'), 'auth_id' => 'StockOpname'),
                    )),
                array('visible' => in_array('ecommerce', param('menu')) && landa()->checkAccess('Sell', 'r'), 'label' => '<span class="icon16 silk-icon-calculator"></span>Sell', 'url' => array('/sell'), 'auth_id' => 'Sell'),
                array('visible' => in_array('saldo', param('menu')) && landa()->checkAccess('Deposit', 'r'), 'label' => '<span class="icon16 icomoon-icon-calculate"></span>Saldo', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('Withdrawal', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Withdrawal', 'url' => array('/saldoWithdrawal'), 'auth_id' => 'Withdrawal'),
                        array('visible' => landa()->checkAccess('Deposit', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Deposit', 'url' => array('/saldoDeposit'), 'auth_id' => 'Deposit'),
                        array('visible' => landa()->checkAccess('donation', 'r'), 'label' => '<span class="icon16  iconic-icon-transfer"></span>Transfer', 'url' => array('/transfer'), 'auth_id' => 'Transfer'),
                        array('visible' => landa()->checkAccess('Saldo', 'r'), 'label' => '<span class="icon16  iconic-icon-transfer"></span>Saldo', 'url' => array('/saldo'), 'auth_id' => 'Saldo'),
                    )),
                array('visible' => in_array('mlm', param('menu')) && landa()->checkAccess('TicketCategory', 'r'), 'label' => '<span class="icon16 icomoon-icon-bell"></span>Memo', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('TicketCategory', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Memo Category', 'url' => array('/ticketCategory'), 'auth_id' => 'TicketCategory'),
                        array('visible' => landa()->checkAccess('Ticket', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Memo', 'url' => array('/ticket'), 'auth_id' => 'Ticket'),
                    )),
                array('visible' => in_array('poker', param('menu')) && landa()->checkAccess('Hall', 'r'), 'label' => '<span class="icon16  minia-icon-list-3"></span>Hall', 'url' => array('/hall'), 'auth_id' => 'Hall'),
                array('visible' => in_array('poker', param('menu')) && landa()->checkAccess('JackpotWin', 'r'), 'label' => '<span class="icon16 entypo-icon-trophy"></span>Jacpot Win', 'url' => array('/jackpotWin'), 'auth_id' => 'JackpotWin'),
                array('visible' => in_array('message', param('menu')) && landa()->checkAccess('UserMessage', 'r'), 'label' => '<span class="icon16 entypo-icon-email"></span>User Message', 'url' => array('/userMessage'), 'auth_id' => 'UserMessage'),
                array('visible' => landa()->checkAccess('Report_Pasang', 'r'), 'label' => '<span class="icon16 cut-icon-printer-2"></span>View & Report', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => in_array('ecommerce', param('menu')) && landa()->checkAccess('Report_Sell', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Sell', 'url' => array('/report/sell'), 'auth_id' => 'Report_Sell'),
                        array('visible' => in_array('ecommerce', param('menu')) && landa()->checkAccess('Report_StockItem', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Stock Barang', 'url' => array('/report/stockItem'), 'auth_id' => 'Report_StockItem'),
                        array('visible' => in_array('ecommerce', param('menu')) && landa()->checkAccess('Report_StockCard', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Stock Card', 'url' => array('/report/stockCard'), 'auth_id' => 'Report_StockCard'),
                        array('visible' => in_array('sms', param('menu')) && landa()->checkAccess('Sms', 'r'), 'label' => '<span class="icon16 icomoon-icon-mail"></span>Pesan Terkirim', 'url' => url('/report/sentItem'), 'auth_id' => 'Report_SentItem'),
                        array('visible' => in_array('game', param('menu')) && landa()->checkAccess('Report_Pasang', 'r'), 'label' => '<span class="icon16 icomoon-icon-mail"></span>Pemasangan', 'url' => url('/report/pasang'), 'auth_id' => 'Report_Pasang'),
                        array('visible' => in_array('game', param('menu')) && landa()->checkAccess('Play', 'r'), 'label' => '<span class="icon16 icomoon-icon-mail"></span>Ranking Pemasangan', 'url' => url('/report/playRank'), 'auth_id' => 'Report_PlayRank'),
                        array('visible' => in_array('game', param('menu')) && landa()->checkAccess('Report_Pembuangan', 'r'), 'label' => '<span class="icon16 icomoon-icon-mail"></span>Pembuangan', 'url' => url('/report/pembuangan'), 'auth_id' => 'Report_Pembuangan'),
                    )),
            );
        } elseif ($appName == 'Inventory Management Systems') {
            return array(
                array('visible' => landa()->checkAccess('Dashboard', 'r'), 'label' => '<span class="icon16 icomoon-icon-screen"></span>Dashboard', 'url' => array('/dashboard'), 'auth_id' => 'Dashboard'),
                array('visible' => landa()->checkAccess('User', 'r'), 'label' => '<span class="icon16 icomoon-icon-user-3"></span>User', 'url' => array('/user'), 'auth_id' => 'User'),
                array('visible' => (in_array('inventory', param('menu'))) && (landa()->checkAccess('ProductBrand', 'r') || landa()->checkAccess('ProductMeasure', 'r') || landa()->checkAccess('ProductCategory', 'r') || landa()->checkAccess('Product', 'r')), 'label' => '<span class="icon16 silk-icon-notebook"></span>Inventory', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('ProductBrand', 'r'), 'label' => '<span class="icon16 eco-list"></span>Merek', 'url' => array('/productBrand'), 'auth_id' => 'ProductBrand'),
                        array('visible' => landa()->checkAccess('ProductMeasure', 'r'), 'label' => '<span class="icon16 entypo-icon-document"></span>Satuan', 'url' => array('/productMeasure'), 'auth_id' => 'ProductMeasure'),
                        array('visible' => landa()->checkAccess('ProductCategory', 'r'), 'label' => '<span class="icon16 cut-icon-tree"></span>Kategori', 'url' => array('/productCategory'), 'auth_id' => 'ProductCategory'),
                        array('visible' => landa()->checkAccess('Product', 'r'), 'label' => '<span class="icon16 cut-icon-list"></span>Barang', 'url' => array('/product'), 'auth_id' => 'Product'),
                    )),
                array('visible' => landa()->checkAccess('GroupSupplier', 'r') || landa()->checkAccess('Supplier', 'r'), 'label' => '<span class="icon16 wpzoom-user-2"></span>Supplier', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('GroupSupplier', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group Supplier', 'url' => array('/landa/roles/supplier'), 'auth_id' => 'GroupSupplier'),
                        array('visible' => landa()->checkAccess('Supplier', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>Supplier', 'url' => array('/user/supplier'), 'auth_id' => 'Supplier'),
                        array('visible' => landa()->checkAccess('Supplier', 'r'), 'label' => '<span class="icon16  cut-icon-list"></span>Supplier Payment', 'url' => array('/user/userInvoice','type' => 'supplier'), 'auth_id' => 'Supplier'),
                    )),
                array('visible' => landa()->checkAccess('Customer', 'r') || landa()->checkAccess('Customer', 'r'), 'label' => '<span class="icon16 wpzoom-user-2"></span>Customer', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('label' => '<span class="icon16 entypo-icon-users"></span>Group Customer', 'url' => array('/landa/roles/customer'), 'auth_id' => 'GroupCustomer'),
                        array('label' => '<span class="icon16  entypo-icon-user"></span>Customer', 'url' => array('/user/customer'), 'auth_id' => 'Customer'),
                        array('label' => '<span class="icon16  cut-icon-list"></span>Customer Invoice', 'url' => array('/user/userInvoice','type' => 'customer'), 'auth_id' => 'Customer'),
                    )),
                array('visible' => in_array('manufacture', param('menu')), 'label' => '<span class="icon16 wpzoom-user-2"></span>Pegawai', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('GroupEmployment', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group Pegawai', 'url' => array('/landa/roles/employment'), 'auth_id' => 'GroupEmployment'),
                        array('visible' => landa()->checkAccess('Employment', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>Pegawai', 'url' => array('/user/employment'), 'auth_id' => 'Employment'),
                    )),
                array('visible' => (landa()->checkAccess('StockIn', 'r') || landa()->checkAccess('StockOut', 'r') || landa()->checkAccess('StockOpname', 'r')) && in_array('inventory', param('menu')), 'label' => '<span class="icon16 cut-icon-checkbox-checked"></span>Stock', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('StockIn', 'r'), 'label' => '<span class="icon16 entypo-icon-reply"></span>Masuk', 'url' => array('/in'), 'auth_id' => 'StockIn'),
                        array('visible' => landa()->checkAccess('StockOut', 'r'), 'label' => '<span class="icon16 entypo-icon-forward"></span>Keluar', 'url' => array('/out'), 'auth_id' => 'StockOut'),
                        array('visible' => landa()->checkAccess('StockOpname', 'r'), 'label' => '<span class="icon16 iconic-icon-transfer"></span>Opname', 'url' => array('/opname'), 'auth_id' => 'StockOpname'),
                    )),
                array('visible' => (landa()->checkAccess('BuyOrder', 'r') || landa()->checkAccess('Buy', 'r') || landa()->checkAccess('BuyRetur', 'r')) && in_array('inventory', param('menu')), 'label' => '<span class="icon16 cut-icon-cart"></span>Pembelian', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('BuyOrder', 'r'), 'label' => '<span class="icon16 icomoon-icon-checkbox"></span>Pemesanan Pembelian', 'url' => array('/buyOrder'), 'auth_id' => 'BuyOrder'),
                        array('visible' => landa()->checkAccess('Buy', 'r'), 'label' => '<span class="icon16 entypo-icon-reply"></span>Pembelian', 'url' => array('/buy'), 'auth_id' => 'Buy'),
                        array('visible' => landa()->checkAccess('BuyRetur', 'r'), 'label' => '<span class="icon16 entypo-icon-forward"></span>Retur', 'url' => array('/buyRetur'), 'auth_id' => 'BuyRetur'),
                    )),
                array('visible' => in_array('inventory', param('menu')) || in_array('manufacture', param('menu')) && (landa()->checkAccess('SellOrder', 'r') || landa()->checkAccess('Sell', 'r') || landa()->checkAccess('SellRetur', 'r')), 'label' => '<span class="icon16 silk-icon-calculator"></span>Penjualan', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => in_array('inventory', param('menu')) || in_array('manufacture', param('menu')) && landa()->checkAccess('SellOrder', 'r'), 'label' => '<span class="icon16 icomoon-icon-checkbox"></span>Pemesanan Penjualan', 'url' => array('/sellOrder'), 'auth_id' => 'SellOrder'),
                        array('visible' => in_array('inventory', param('menu')) && landa()->checkAccess('Sell', 'r'), 'label' => '<span class="icon16 entypo-icon-forward"></span>Penjualan', 'url' => array('/sell'), 'auth_id' => 'Sell'),
                        array('visible' => in_array('inventory', param('menu')) && landa()->checkAccess('SellRetur', 'r'), 'label' => '<span class="icon16 entypo-icon-reply"></span>Retur', 'url' => array('/sellRetur'), 'auth_id' => 'SellRetur'),
                    )),
                array('visible' => in_array('manufacture', param('menu')) && landa()->checkAccess('ProsesStatus', 'r'), 'label' => '<span class="icon16 silk-icon-notebook"></span>Produksi', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('WorkOrder', 'r'), 'label' => '<span class="icon16 entypo-icon-document"></span>SPK', 'url' => array('/workorder'), 'auth_id' => 'WorkOrder'),
                        array('visible' => landa()->checkAccess('WorkOrderIntruction', 'r'), 'label' => '<span class="icon16 entypo-icon-document"></span>Rencana Marker', 'url' => array('/workorderIntruction'), 'auth_id' => 'WorkOrderIntruction'),
                        array('visible' => landa()->checkAccess('WorkOrderIntructionDet', 'r'), 'label' => '<span class="icon16 entypo-icon-document"></span>SPP & NOPOT', 'url' => array('/workorderIntructionDet'), 'auth_id' => 'WorkOrderIntructionDet'),
                        array('visible' => landa()->checkAccess('ProsesStatus', 'r'), 'label' => '<span class="icon16 entypo-icon-document"></span>PROCESS STATUS', 'url' => array('/workorder/process'), 'auth_id' => 'ProsesStatus'),
                    )),
                array('visible' => in_array('manufacture', param('menu')) && landa()->checkAccess('Salary', 'r'), 'label' => '<span class="icon16 icomoon-icon-newspaper"></span>Gaji', 'url' => array('/salaryOut'), 'auth_id' => 'Salary'),
                array('visible' => in_array('accounting', param('menu')) && (landa()->checkAccess('AccCoa', 'r') || landa()->checkAccess('AccJurnal', 'r') || landa()->checkAccess('BeginningBalance', 'r') || landa()->checkAccess('BeginningBalanceKartu', 'r')), 'label' => '<span class="icon16 icomoon-icon-clipboard-2"></span>Accounting', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('AccCoa', 'r'), 'label' => '<span class="icon16 iconic-icon-pin"></span>Daftar Perkiraan', 'url' => array('/accCoa'), 'auth_id' => 'AccCoa'),
                        array('visible' => landa()->checkAccess('AccJurnal', 'r'), 'label' => '<span class="icon16 iconic-icon-pen"></span>Jurnal', 'url' => array('/accJurnal'), 'auth_id' => 'AccJurnal'),
                        array('visible' => landa()->checkAccess('BeginningBalance', 'r'), 'label' => '<span class="icon16 silk-icon-checklist"></span>Saldo Awal', 'url' => array('/accCoa/beginningbalance'), 'auth_id' => 'BeginningBalance'),
                    )),
                array('visible' => in_array('accounting', param('menu')) && (landa()->checkAccess('AccCashIn', 'r') || landa()->checkAccess('AccCashOut', 'r')), 'label' => '<span class="icon16 icomoon-icon-clipboard-2"></span>Transaksi Uang', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('AccCashIn', 'r'), 'label' => '<span class="icon16 iconic-icon-pen"></span>Masuk', 'url' => array('/accCashIn'), 'auth_id' => 'AccCashIn'),
                        array('visible' => landa()->checkAccess('AccCashOut', 'r'), 'label' => '<span class="icon16 iconic-icon-pen"></span>Keluar', 'url' => array('/accCashOut'), 'auth_id' => 'AccCashOut'),
                    )),
                array('label' => '<span class="icon16 cut-icon-printer-2"></span>Laporan', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => in_array('inventory', param('menu')), 'label' => '<span class="icon16 entypo-icon-book"></span>Pembelian', 'url' => array('/report/buy'), 'auth_id' => 'Report_Buy'),
                        array('visible' => in_array('inventory', param('menu')), 'label' => '<span class="icon16 entypo-icon-book"></span>Pembelian Retur', 'url' => array('/report/buyretur'), 'auth_id' => 'Report_BuyRetur'),
                        array('visible' => in_array('inventory', param('menu')), 'label' => '<span class="icon16 entypo-icon-book"></span>Penjualan', 'url' => array('/report/sell'), 'auth_id' => 'Report_Sell'),
                        array('visible' => in_array('inventory', param('menu')), 'label' => '<span class="icon16 entypo-icon-book"></span>Penjualan Retur', 'url' => array('/report/sellretur'), 'auth_id' => 'Report_SellRetur'),
                        array('visible' => in_array('inventory', param('menu')), 'label' => '<span class="icon16 entypo-icon-book"></span>Stock Barang', 'url' => array('/report/stockItem'), 'auth_id' => 'Report_StockItem'),
                        array('visible' => in_array('inventory', param('menu')), 'label' => '<span class="icon16 entypo-icon-book"></span>Stock Card', 'url' => array('/report/stockCard'), 'auth_id' => 'Report_StockCard'),
                        array('visible' => in_array('manufacture', param('menu')) && landa()->checkAccess('ProsesStatus', 'r'), 'label' => '<span class="icon16 entypo-icon-document"></span>Laporan Proses Produksi', 'url' => array('/workorder/takingNote'), 'auth_id' => 'ProsesStatus'),
                        array('visible' => in_array('manufacture', param('menu')) && landa()->checkAccess('ProsesStatus', 'r'), 'label' => '<span class="icon16 entypo-icon-document"></span>Laporan NOPOT', 'url' => array('/report/nopot'), 'auth_id' => 'ProsesStatus'),
//                        array('visible' => in_array('manufacture', param('menu')) && landa()->checkAccess('Report_MaterialPurchasing', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Pembelian Bahan', 'url' => array('/report/buy'), 'auth_id' => 'Report_MaterialPurchasing'),
//                        array('visible' => in_array('manufacture', param('menu')) && landa()->checkAccess('Report_ProductionProgress', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Progress Produksi', 'url' => array('/report/buy'), 'auth_id' => 'Report_ProductionProgress'),
//                        array('visible' => in_array('manufacture', param('menu')) && landa()->checkAccess('Report_ProductionLoss', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Barang Hilang', 'url' => array('/report/productionLoss'), 'auth_id' => 'Report_ProductionLoss'),
//                        array('visible' => in_array('manufacture', param('menu')) && landa()->checkAccess('Report_Salary', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Gaji', 'url' => array('/report/salaryisPaid'), 'auth_id' => 'Report_Salary'),
//                        array('visible' => in_array('manufacture', param('menu')) && landa()->checkAccess('Report_SalaryUnpaid', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Salary Unpaid', 'url' => array('/report/salaryUnpaid'), 'auth_id' => 'Report_SalaryUnpaid'),
                        array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_Jurnal', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Jurnal', 'url' => array('/report/jurnalUmum'), 'auth_id' => 'Report_Jurnal'),
                        array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_Kasharian', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Kas Harian', 'url' => array('/report/kasHarian'), 'auth_id' => 'Report_Kasharian'),
                        array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_Generalledger', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Buku Besar', 'url' => array('/report/generalLedger'), 'auth_id' => 'Report_Generalledger'),
                        array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_NeracaSaldo', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Neraca Saldo', 'url' => array('/report/neracaSaldo'), 'auth_id' => 'Report_NeracaSaldo'),
//                        array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_LabaRugi', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Laba Rugi', 'url' => array('/report/labaRugi'), 'auth_id' => 'Report_LabaRugi'),
//                        array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_Neraca', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Neraca', 'url' => array('/report/neraca'), 'auth_id' => 'Report_Neraca'),
                        array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_Jurnal', 'r'), 'label' => '<span class="icon16 cut-icon-printer-2"></span>Buku Pembantu', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                                array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_Piutang', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Kartu Piutang', 'url' => array('/report/kartuPiutang'), 'auth_id' => 'Report_Piutang'),
                                array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_Piutang', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Rekap Kartu Piutang', 'url' => array('/report/RekapPiutang'), 'auth_id' => 'Report_Piutang'),
                                array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_Hutang', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Kartu Hutang', 'url' => array('/report/kartuHutang'), 'auth_id' => 'Report_Hutang'),
                                array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_Hutang', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Rekap Kartu Hutang', 'url' => array('/report/RekapHutang'), 'auth_id' => 'Report_Hutang'),
//                                array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_Stock', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Kartu Stock', 'url' => array('/report/kartuStock'), 'auth_id' => 'Report_Stock'),
//                                array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('Report_Stock', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Rekap Kartu Stock', 'url' => array('/report/RekapStock'), 'auth_id' => 'Report_Stock'),
                            )),
                    )),
                array('visible' => in_array('accounting', param('menu')) && (landa()->checkAccess('dateConfig', 'r') || landa()->checkAccess('accFormatting', 'r')), 'label' => '<span class="icon16 wpzoom-settings"></span>Tools', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('dateConfig', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Auto Number', 'url' => array('dateConfig/index'), 'auth_id' => 'DateConfig'),
                        array('visible' => in_array('accounting', param('menu')) && landa()->checkAccess('accFormatting', 'r'), 'label' => '<span class="icon16 entypo-icon-book"></span>Account Formatting', 'url' => array('accFormatting/index'), 'auth_id' => 'accFormatting'),
                    )),
                array('visible' => landa()->checkAccess('Report_Jurnal', 'r'), 'label' => '<span class="icon16 icomoon-icon-bars"></span>Chart', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => in_array('manufacture', param('menu')), 'label' => '<span class="icon16 icomoon-icon-stats-up"></span>Employment Compare', 'url' => array('#'), 'auth_id' => 'Chart.EmploymnetCompare'),
                    ), 'auth_id' => 'report_chart'),
            );
        } elseif ($appName == 'Hotel Management Systems') {
            return array(
                array('label' => '<span class="icon16 icomoon-icon-screen"></span>Dashboard', 'url' => array('/dashboard')),
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
//                        array('visible' => landa()->checkAccess('BillChasierApproving', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Cashier Approval', 'url' => array('/billCashier/approving'), 'auth_id' => 'BillChasierApproving'),
                    )),
                array('visible' => landa()->checkAccess('NightAudit', 'r'), 'label' => '<span class="icon16 wpzoom-night"></span>Night Audit', 'url' => array('/na'), 'auth_id' => 'NightAudit'),
                array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => '<span class="icon16 brocco-icon-wrench"></span>Tools', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Tax Export', 'url' => array('roomBill/taxExport'), 'auth_id' => 'TaxExport'),
                        array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Initial Forecast', 'url' => array('/initialForecast'), 'auth_id' => 'initialForecast'),
                        array('visible' => landa()->checkAccess('RoomBill', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>History Login', 'url' => array('/user/auditUser'), 'auth_id' => 'auditUser'),
//                        array('label' => '<span class="icon16 entypo-icon-settings"></span>Setting Transaction', 'url' => array('chargeAdditionalCategory/settingTransaction')),
                    )),
                array('visible' => landa()->checkAccess('Report_ProductSold', 'r'), 'label' => '<span class="icon16 cut-icon-printer-2"></span>View & Report', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('RoomCharting', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Guest History', 'url' => array('/user/history'), 'auth_id' => 'GuestHistory'),
                        array('visible' => landa()->checkAccess('Report_Arr/Dep', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Arrival/Departure', 'url' => array('/report/arrivdepar'), 'auth_id' => 'Report_Arr/Dep'),
                        array('visible' => landa()->checkAccess('Report_GuestInHouse', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Guest In House', 'url' => array('/report/guesthouse'), 'auth_id' => 'Report_GuestInHouse'),
                        array('visible' => landa()->checkAccess('Report_ExpectedGuest', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Expected Guest', 'url' => array('/report/expected'), 'auth_id' => 'Report_ExpectedGuest'),
//                        array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Daily Sales', 'url' => array('/report/daily'), 'auth_id' => 'Report_RoomSales'),
                        array('visible' => landa()->checkAccess('Report_ProductSold', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Product Sold', 'url' => array('/report/productSold'), 'auth_id' => 'Report_ProductSold'),
//                        array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Guest Ledger', 'url' => array('product/new'), 'auth_id' => 'Report_RoomSales'),
//                        array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Market Segmentation', 'url' => array('product/new'), 'auth_id' => 'Report_RoomSales'),
//                        array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Sumary of Sales', 'url' => array('product/new'), 'auth_id' => 'Report_RoomSales'),
                        array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Geographical', 'url' => array('report/geographical'), 'auth_id' => 'Report_RoomSales'),
                        array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Source Of Business', 'url' => array('report/sourceOfBusiness'), 'auth_id' => 'Report_RoomSales'),
                        array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Top Producers', 'url' => array('report/topProducers'), 'auth_id' => 'Report_RoomSales'),
                        array('visible' => landa()->checkAccess('Report_RoomSales', 'r'), 'label' => '<span class="icon16 icomoon-icon-arrow-right"></span>Top Ten', 'url' => array('report/topTen'), 'auth_id' => 'Report_RoomSales'),
                    )),
                array('label' => '<span class="icon16 icomoon-icon-bars"></span>Chart', 'url' => array('/User'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                    )),
            );
        } elseif ($appName == 'Academic Management Systems') {
            return array(
                array('label' => '<span class="icon16 icomoon-icon-screen"></span>Halaman Depan', 'url' => array('/dashboard')),
                array('visible' => landa()->checkAccess('User', 'r'), 'label' => '<span class="icon16 icomoon-icon-user-3"></span>User', 'url' => array('/user'), 'auth_id' => 'User'),
                array('visible' => landa()->checkAccess('GroupTeacher', 'r') || landa()->checkAccess('Teacher', 'r'), 'label' => '<span class="icon16  entypo-icon-contact"></span>Guru', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('GroupTeacher', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group Guru', 'url' => url('landa/roles/teacher'), 'auth_id' => 'GroupTeacher'),
                        array('visible' => landa()->checkAccess('Teacher', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>Guru', 'url' => url('user/teacher'), 'auth_id' => 'Teacher'),
                    )),
                array('visible' => landa()->checkAccess('GroupStudent', 'r') || landa()->checkAccess('Student', 'r'), 'label' => '<span class="icon16  entypo-icon-contact"></span>Murid', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('GroupStudent', 'r'), 'label' => '<span class="icon16 entypo-icon-users"></span>Group Murid', 'url' => url('landa/roles/student'), 'auth_id' => 'GroupStudent'),
                        array('visible' => landa()->checkAccess('Student', 'r'), 'label' => '<span class="icon16  entypo-icon-user"></span>Murid', 'url' => url('user/student'), 'auth_id' => 'Student'),
                    )),
                array('visible' => landa()->checkAccess('Download', 'r'), 'label' => '<span class="icon16 minia-icon-download"></span>Dokumen', 'url' => array('/downloadCategory'), 'auth_id' => 'Download'),
                array('visible' => landa()->checkAccess('SchoolYear', 'r') || landa()->checkAccess('Classroom', 'r') || landa()->checkAccess('UserClassroom', 'r'), 'label' => '<span class="icon16 typ-icon-cog"></span>Akademik', 'url' => array('/User'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('SchoolYear', 'r'), 'label' => '<span class="icon16 minia-icon-calendar"></span>Tahun Ajaran', 'url' => array('/schoolYear'), 'auth_id' => 'SchoolYear'),
                        array('visible' => landa()->checkAccess('Classroom', 'r'), 'label' => '<span class="icon16 minia-icon-office"></span>Kelas', 'url' => array('/classroom'), 'auth_id' => 'Classroom'),
                        array('visible' => landa()->checkAccess('UserClassroom', 'r'), 'label' => '<span class="icon16 typ-icon-users"></span>Penempatan Siswa', 'url' => array('/userClassroom'), 'auth_id' => 'UserClassroom'),
                    )),
                array('visible' => landa()->checkAccess('ExamCategory', 'r') || landa()->checkAccess('Exam', 'r') || landa()->checkAccess('Test', 'r') || landa()->checkAccess('TestResult', 'r'), 'label' => '<span class="icon16 minia-icon-book"></span>Ujian', 'url' => array('/User'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('ExamCategory', 'r'), 'label' => '<span class="icon16 iconic-icon-list-nested"></span>Kategori', 'url' => array('/examCategory'), 'auth_id' => 'ExamCategory'),
                        array('visible' => landa()->checkAccess('Exam', 'r'), 'label' => '<span class="icon16 typ-icon-views"></span>Soal', 'url' => array('/exam'), 'auth_id' => 'Exam'),
                        array('visible' => landa()->checkAccess('Test', 'r'), 'label' => '<span class="icon16 wpzoom-timer"></span>Jadwal', 'url' => array('/test'), 'auth_id' => 'Test'),
                        array('visible' => landa()->checkAccess('TestResult', 'r'), 'label' => '<span class="icon16 minia-icon-checked"></span>Hasil Ujian', 'url' => array('/testResult'), 'auth_id' => 'TestResult'),
                    )),
//                array('visible' => landa()->checkAccess('Ongoing', 'r'), 'label' => '<span class="icon16 cut-icon-arrow-right"></span>Mulai Ujian', 'url' => array('/test/ongoing'), 'auth_id' => 'Ongoing', 'submenuOptions' => array('class' => 'sub'), 'items' => array(
//                    )),
                array('visible' => in_array('sms', param('menu')) && landa()->checkAccess('Sms', 'r'), 'label' => '<span class="icon16 wpzoom-phone-3"></span>SMS', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('Sms', 'r'), 'label' => '<span class="icon16 icomoon-icon-key-2"></span>Keyword', 'url' => url('/smsKeyword'), 'auth_id' => 'Customer'),
                        array('visible' => landa()->checkAccess('Sms', 'r'), 'label' => '<span class="icon16 entypo-icon-comment"></span>Outbox', 'url' => url('/sms/outbox'), 'auth_id' => 'Sms'),
                        array('visible' => landa()->checkAccess('Sms', 'r'), 'label' => '<span class="icon16 entypo-icon-inbox"></span>Inbox & Sentitems', 'url' => url('/sms'), 'auth_id' => 'Sms'),
                        array('visible' => landa()->checkAccess('Sms', 'r'), 'label' => '<span class="icon16 icomoon-icon-mobile-2"></span>Log Status Modem', 'url' => url('/sms/logModem'), 'auth_id' => 'Sms'),
                    )),
                array('visible' => landa()->checkAccess('ReportMonth', 'r') || landa()->checkAccess('ReportDay', 'r'), 'label' => '<span class="icon16 cut-icon-list"></span>Absensi', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('Holiday', 'r'), 'label' => '<span class="icon16 brocco-icon-calendar"></span>Hari Libur', 'url' => url('/siteConfigHoliday'), 'auth_id' => 'ReportMonth'),
                        array('visible' => landa()->checkAccess('ReportMonth', 'r'), 'label' => '<span class="icon16 brocco-icon-calendar"></span>Rekap', 'url' => url('/ReportAbsent'), 'auth_id' => 'ReportMonth'),
                    )),
                array('visible' => landa()->checkAccess('Report_Exam', 'r') || landa()->checkAccess('Report_Userlist', 'r'), 'label' => '<span class="icon16 cut-icon-printer-2"></span>Laporan', 'url' => array('#'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('Sms', 'r'), 'label' => '<span class="icon16 icomoon-icon-mail"></span>Pesan Terkirim', 'url' => url('/report/sentItem'), 'auth_id' => 'Customer'),
                        array('visible' => landa()->checkAccess('Report_Exam', 'r'), 'label' => '<span class="icon16 entypo-icon-list"></span>Nilai Ujian', 'url' => array('/report/examReport'), 'auth_id' => 'Report_Exam'),
                        array('visible' => landa()->checkAccess('Report_Day', 'r'), 'label' => '<span class="icon16  brocco-icon-window"></span>Absensi Harian', 'url' => url('/ReportAbsent/ReportHarian'), 'auth_id' => 'Report_Day'),
                    //array('label' => '<span class="icon16 entypo-icon-list"></span>Classroom', 'url' => array('#')),
//                        array('visible'=>landa()->checkAccess('Report_Userlist','r'),'label' => '<span class="icon16 entypo-icon-list"></span>Daftar User', 'url' => array('/user'),'auth_id'=>'Report_Userlist'),
//                        array('visible'=>landa()->checkAccess('Report_Classroomlist','r'),'label' => '<span class="icon16 entypo-icon-list"></span>Daftar Kelas', 'url' => array('/classroom'),'auth_id'=>'Report_ClassroomList'),
                    )),
                array('visible' => landa()->checkAccess('Chart_ExamComparison', 'r'), 'label' => '<span class="icon16 icomoon-icon-bars"></span>Chart', 'url' => array('/User'), 'submenuOptions' => array('class' => 'sub'), 'items' => array(
                        array('visible' => landa()->checkAccess('Chart_ExamComparison', 'r'), 'label' => '<span class="icon16 entypo-icon-list"></span>Ujian Pembanding', 'url' => array('/chart/examComparison'), 'auth_id' => 'Chart_ExamComparison'),
                    )),
            );
        }
    }

}
