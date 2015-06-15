<?php

/**
 * This is the model class for table "{{m_article}}".
 *
 * The followings are the available columns in table '{{m_article}}':
 * @property integer $id
 * @property string $title
 * @property integer $article_category_id
 * @property string $content
 * @property string $primary_image
 * @property string $created
 * @property string $created_user_id
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property ArticleCategory $articleCategory
 */
class Article extends CActiveRecord {
//    public $param;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Article the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{article}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, article_category_id, content', 'required'),
            array('article_category_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 100),
            array('created_user_id', 'length', 'max' => 11),
            array('created, modified, param, alias', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, publish, article_category_id, content, primary_image, created, created_user_id, modified,description, keyword', 'safe', 'on' => 'search'),
            array('primary_image', 'unsafe'),
//            array('primary_image', 'file', 'types' => 'jpg, gif, png, jpeg'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ArticleCategory' => array(self::BELONGS_TO, 'ArticleCategory', 'article_category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'article_category_id' => 'Article Categoryid',
            'content' => 'Content',
            'primary_image' => 'Primary Image',
            'created' => 'Created',
            'created_user_id' => 'Created Userid',
            'modified' => 'Modified',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('article_category_id', $this->article_category_id);
        $criteria->compare('publish', $this->publish);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('primary_image', $this->primary_image, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => $this->getTableAlias(false, false) . '.id Desc')
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

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }
    
    public function getImg(){
        return landa()->urlImg('article/', $this->primary_image, $this->id);
    }
    
    public function getUrl(){
        if (isset($_GET['access']) && $_GET['access']=='login') {
            $pre = 'r/';
        }else{
            $pre = 'read/';
        }
        return url($pre . $this->ArticleCategory->alias . '/' . $this->alias);
    }
    
    public function introText($limitChars=250){
        return substr(strip_tags($this->content), 0, $limitChars);
    }
     function getTitleCat() {
        return '[' . $this->ArticleCategory->name . '] - ' . $this->title;
    }
    
    public function getPublishdata(){
        if($this->publish == 1){
            echo'<span id="" class="label label-info">Publish</span>';
        }else{
            echo'<span id="" class="label label-warning">Unpublish</span>';
        }
    }
}