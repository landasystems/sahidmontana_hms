<?php

/**
 * This is the model class for table "{{email}}".
 *
 * The followings are the available columns in table '{{email}}':
 * @property integer $id
 * @property string $email_from
 * @property string $email_to
 * @property string $title
 * @property string $content
 * @property integer $is_send
 * @property string $client
 * @property string $created
 * @property string $created_user_name
 * @property string $modified
 */
class Email extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{email}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, is_send', 'numerical', 'integerOnly' => true),
            array('email_from, email_to', 'length', 'max' => 100),
            array('title, client, created_user_name', 'length', 'max' => 255),
            array('content, created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, email_from, email_to, title, content, is_send, client, created, created_user_name, modified', 'safe', 'on' => 'search'),
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
            'email_from' => 'Email From',
            'email_to' => 'Email To',
            'title' => 'Title',
            'content' => 'Content',
            'is_send' => 'Is Sent',
            'client' => 'Client',
            'created' => 'Created',
            'created_user_name' => 'Created User Name',
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
        $criteria->compare('email_from', $this->email_from, true);
        $criteria->compare('email_to', $this->email_to, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('is_send', $this->is_send);
        $criteria->compare('client', $this->client, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_name', $this->created_user_name, true);
        $criteria->compare('modified', $this->modified, true);

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
     * @return Email the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
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
        if (empty($this->created_user_name))
            $this->created_user_name = Yii::app()->user->name;
        return parent::beforeValidate();
    }

    public function sending($to, $name, $from, $subject, $content) {
//            foreach ($to as $arr){
        $name = '=?UTF-8?B?' . base64_encode($name) . '?=';
        $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers = "From: $name <{$from}>\r\n" .
                "Reply-To: {$from}\r\n" .
                "MIME-Version: 1.0\r\n" .
                "Content-type: text/html; charset: utf8\r\n";

        mail($to, $subject, $content, $headers);
//            }
    }

    public function send($from, $to, $title, $content, $cc_to_admin = FALSE) {
        $siteConfig = SiteConfig::model()->listSiteConfig();

        $mEmail = new Email;
        $mEmail->email_from = $from;
        $mEmail->email_to = $to;
        $mEmail->title = $title;
        $mEmail->content = $content;
        $mEmail->client = param('client');
        $mEmail->is_send = 1;
        $mEmail->save();
        $this->sending($mEmail->email_to, $mEmail->client, $mEmail->email_from, $mEmail->title, $mEmail->content);

        if ($cc_to_admin) {
            $mEmail = new Email;
            $mEmail->email_from = $from;
            $mEmail->email_to = $siteConfig->email;
            $mEmail->title = 'CC : ' . $title;
            $mEmail->content = $content;
            $mEmail->client = param('client');
            $mEmail->is_send = 1;
            $mEmail->save();
            $this->sending($mEmail->email_to, $mEmail->client, $mEmail->email_from, $mEmail->title, $mEmail->content);
        }
    }

}
