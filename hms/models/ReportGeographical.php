<?php

/**
 * This is the model class for table "{{report_geographical}}".
 *
 * The followings are the available columns in table '{{report_geographical}}':
 * @property integer $id
 * @property integer $na_id
 * @property string $today_rno
 * @property string $today_pax
 * @property string $month_rno
 * @property string $month_pax
 * @property string $year_rno
 * @property string $year_pax
 * @property string $created
 * @property string $modified
 */
class ReportGeographical extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{report_geographical}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
//            array('id, na_id, today_rno, today_pax, month_rno, month_pax, year_rno, year_pax', 'required'),
//            array('id, na_id', 'numerical', 'integerOnly' => true),
//            array('created, modified', 'safe'),
//            // The following rule is used by search().
//            // @todo Please remove those attributes that should not be searched.
//            array('id, na_id, today_rno, today_pax, month_rno, month_pax, year_rno, year_pax, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Na' => array(self::BELONGS_TO, 'Na', 'na_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'na_id' => 'Na',
            'today_rno' => 'Today Rno',
            'today_pax' => 'Today Pax',
            'month_rno' => 'Month Rno',
            'month_pax' => 'Month Pax',
            'year_rno' => 'Year Rno',
            'year_pax' => 'Year Pax',
            'created' => 'Created',
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
        $criteria->compare('na_id', $this->na_id);
        $criteria->compare('today_rno', $this->today_rno, true);
        $criteria->compare('today_pax', $this->today_pax, true);
        $criteria->compare('month_rno', $this->month_rno, true);
        $criteria->compare('month_pax', $this->month_pax, true);
        $criteria->compare('year_rno', $this->year_rno, true);
        $criteria->compare('year_pax', $this->year_pax, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ReportGeographical the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function insertGeoGraphical($na_id, $dateNa) {
        $month = date("m", strtotime($dateNa));
        $year = date("Y", strtotime($dateNa));

        $monthLastReport = date("m", strtotime($dateNa));
        $yearLastReport = date("Y", strtotime($dateNa));

        $reportTodayGeo = array();
        $reportMonthGeo = array();
        $reportYearGeo = array();
        $reportProducer = array();
        $reportTopTen = array();
        $reportSobMonth = array();
        $reportSobYear = array();

        $monthGeo = array();
        $monthTopTen = array();
        $sobMonth = array();
        $yearGeo = array();
        $producer = array();
        $sobYear = array();

        $lastReport = ReportGeographical::model()->with('Na')->find(array('condition' => 'Na.date_na < "' . $dateNa . '"', 'order' => 'Na.date_na desc'));
        if (!empty($lastReport)) {
            $monthLastReport = date("m", strtotime($lastReport->Na->date_na));
            $yearLastReport = date("Y", strtotime($lastReport->Na->date_na));

            $LastMonthGeo = json_decode($lastReport->month_geo, true);
            $LastYearGeo = json_decode($lastReport->year_geo, true);
            $LastMonthProducer = json_decode($lastReport->top_producer, true);
            $lastMonthTopTen = json_decode($lastReport->top_ten, true);
            $lastSobMonth = json_decode($lastReport->sob_month, true);
            $lastSobYear = json_decode($lastReport->sob_year, true);

            if ($month == $monthLastReport) {
                $monthGeo = $LastMonthGeo;
                $monthTopTen = $lastMonthTopTen;
                $sobMonth = $lastSobMonth;
            }
            if ($year == $yearLastReport) {
                $yearGeo = $LastYearGeo;
                $producer = $LastMonthProducer;
                $sobYear = $lastSobYear;
            }
        }

//        $monthGeo = array();
//        $yearGeo = array();


        $data = Yii::app()->db->createCommand('SELECT acca_user.id as id, acca_room.status as room_status, acca_room_bill.id as bill_id, acca_user.nationality as nationality, acca_city.province_id as province_id, acca_user.city_id as city_id ,acca_room_bill.charge as charge, acca_registration.market_segment_id as market_segment_id, acca_room_bill.pax as pax, acca_roles.prefix as prefix ,acca_roles.id as roles_id FROM acca_room, acca_na, acca_room_bill, acca_registration, acca_user, landa_acca.acca_city as acca_city, acca_roles WHERE acca_room_bill.room_id = acca_room.id and acca_na.id = acca_room_bill.na_id and  acca_room_bill.registration_id = acca_registration.id and acca_registration.guest_user_id = acca_user.id and acca_user.city_id = acca_city.id and acca_user.roles_id = acca_roles.id and acca_room.status = "occupied" and acca_na.date_na =  "' . $dateNa . '"')->query();
        if (empty($data)) {
            
        } else {
            foreach ($data as $val) { 
                    // simpan kota guest
                    //simpan today
                    if (isset($reportTodayGeo['city'][$val['city_id']])) {
                        $reportTodayGeo['city'][$val['city_id']]['rno'] += 1;
                        $reportTodayGeo['city'][$val['city_id']]['pax'] += $val['pax'];
                    } else {
                        $reportTodayGeo['city'][$val['city_id']]['rno'] = 1;
                        $reportTodayGeo['city'][$val['city_id']]['pax'] = $val['pax'];
                    }

                    //simpan month
                    if (isset($reportMonthGeo['city'][$val['city_id']])) {
                        $reportMonthGeo['city'][$val['city_id']]['rno'] += 1;
                        $reportMonthGeo['city'][$val['city_id']]['pax'] += $val['pax'];
                    } else {
                        $reportMonthGeo['city'][$val['city_id']]['rno'] = (isset($monthGeo['city'][$val['city_id']]['rno']) ? $monthGeo['city'][$val['city_id']]['rno'] : 0 ) + 1;
                        $reportMonthGeo['city'][$val['city_id']]['pax'] = (isset($monthGeo['city'][$val['city_id']]['pax']) ? $monthGeo['city'][$val['city_id']]['pax'] : 0 ) + $val['pax'];
                    }
                    $monthGeo['city'][$val['city_id']]['rno'] = $reportMonthGeo['city'][$val['city_id']]['rno'];
                    $monthGeo['city'][$val['city_id']]['pax'] = $reportMonthGeo['city'][$val['city_id']]['pax'];

                    //simpan tahun
                    if (isset($reportYearGeo['city'][$val['city_id']])) {
                        $reportYearGeo['city'][$val['city_id']]['rno'] += 1;
                        $reportYearGeo['city'][$val['city_id']]['pax'] += $val['pax'];
                    } else {
                        $reportYearGeo['city'][$val['city_id']]['rno'] = (isset($yearGeo['city'][$val['city_id']]['rno']) ? $yearGeo['city'][$val['city_id']]['rno'] : 0 ) + 1;
                        $reportYearGeo['city'][$val['city_id']]['pax'] = (isset($yearGeo['city'][$val['city_id']]['pax']) ? $yearGeo['city'][$val['city_id']]['pax'] : 0 ) + $val['pax'];
                    }
                    $yearGeo['city'][$val['city_id']]['rno'] = $reportYearGeo['city'][$val['city_id']]['rno'];
                    $yearGeo['city'][$val['city_id']]['pax'] = $reportYearGeo['city'][$val['city_id']]['pax'];
                    //simpan provinsi guest
                    //simpan today
                    if (isset($reportTodayGeo['province'][$val['province_id']])) {
                        $reportTodayGeo['province'][$val['province_id']]['rno'] += 1;
                        $reportTodayGeo['province'][$val['province_id']]['pax'] += $val['pax'];
                    } else {
                        $reportTodayGeo['province'][$val['province_id']]['rno'] = 1;
                        $reportTodayGeo['province'][$val['province_id']]['pax'] = $val['pax'];
                    }
                    //simpan month
                    if (isset($reportMonthGeo['province'][$val['province_id']])) {
                        $reportMonthGeo['province'][$val['province_id']]['rno'] += 1;
                        $reportMonthGeo['province'][$val['province_id']]['pax'] += $val['pax'];
                    } else {
                        $reportMonthGeo['province'][$val['province_id']]['rno'] = (isset($monthGeo['province'][$val['province_id']]['rno']) ? $monthGeo['province'][$val['province_id']]['rno'] : 0 ) + 1;
                        $reportMonthGeo['province'][$val['province_id']]['pax'] = (isset($monthGeo['province'][$val['province_id']]['pax']) ? $monthGeo['province'][$val['province_id']]['pax'] : 0 ) + $val['pax'];
                    }
                    $monthGeo['province'][$val['province_id']]['rno'] = $reportMonthGeo['province'][$val['province_id']]['rno'];
                    $monthGeo['province'][$val['province_id']]['pax'] = $reportMonthGeo['province'][$val['province_id']]['pax'];
                    //simpan tahun
                    if (isset($reportYearGeo['province'][$val['province_id']])) {
                        $reportYearGeo['province'][$val['province_id']]['rno'] += 1;
                        $reportYearGeo['province'][$val['province_id']]['pax'] += $val['pax'];
                    } else {
                        $reportYearGeo['province'][$val['province_id']]['rno'] = (isset($yearGeo['province'][$val['province_id']]['rno']) ? $yearGeo['province'][$val['province_id']]['rno'] : 0 ) + 1;
                        $reportYearGeo['province'][$val['province_id']]['pax'] = (isset($yearGeo['province'][$val['province_id']]['pax']) ? $yearGeo['province'][$val['province_id']]['pax'] : 0 ) + $val['pax'];
                    }
                    $yearGeo['province'][$val['province_id']]['rno'] = $reportYearGeo['province'][$val['province_id']]['rno'];
                    $yearGeo['province'][$val['province_id']]['pax'] = $reportYearGeo['province'][$val['province_id']]['pax'];
                    //simpan nationality guest
                    //simpan today
                    if (isset($reportTodayGeo['nationality'][$val['nationality']])) {
                        $reportTodayGeo['nationality'][$val['nationality']]['rno'] += 1;
                        $reportTodayGeo['nationality'][$val['nationality']]['pax'] += $val['pax'];
                    } else {
                        $reportTodayGeo['nationality'][$val['nationality']]['rno'] = 1;
                        $reportTodayGeo['nationality'][$val['nationality']]['pax'] = $val['pax'];
                    }

                    //simpan month
                    if (isset($reportMonthGeo['nationality'][$val['nationality']])) {
                        $reportMonthGeo['nationality'][$val['nationality']]['rno'] += 1;
                        $reportMonthGeo['nationality'][$val['nationality']]['pax'] += $val['pax'];
                    } else {
                        $reportMonthGeo['nationality'][$val['nationality']]['rno'] = (isset($monthGeo['nationality'][$val['nationality']]['rno']) ? $monthGeo['nationality'][$val['nationality']]['rno'] : 0 ) + 1;
                        $reportMonthGeo['nationality'][$val['nationality']]['pax'] = (isset($monthGeo['nationality'][$val['nationality']]['pax']) ? $monthGeo['nationality'][$val['nationality']]['pax'] : 0 ) + $val['pax'];
                    }
                    $monthGeo['nationality'][$val['nationality']]['rno'] = $reportMonthGeo['nationality'][$val['nationality']]['rno'];
                    $monthGeo['nationality'][$val['nationality']]['pax'] = $reportMonthGeo['nationality'][$val['nationality']]['pax'];

                    //simpan tahun
                    if (isset($reportYearGeo['nationality'][$val['nationality']])) {
                        $reportYearGeo['nationality'][$val['nationality']]['rno'] += 1;
                        $reportYearGeo['nationality'][$val['nationality']]['pax'] += $val['pax'];
                    } else {
                        $reportYearGeo['nationality'][$val['nationality']]['rno'] = (isset($yearGeo['nationality'][$val['nationality']]['rno']) ? $yearGeo['nationality'][$val['nationality']]['rno'] : 0 ) + 1;
                        $reportYearGeo['nationality'][$val['nationality']]['pax'] = (isset($yearGeo['nationality'][$val['nationality']]['pax']) ? $yearGeo['nationality'][$val['nationality']]['pax'] : 0 ) + $val['pax'];
                    }
                    $yearGeo['nationality'][$val['nationality']]['rno'] = $reportYearGeo['nationality'][$val['nationality']]['rno'];
                    $yearGeo['nationality'][$val['nationality']]['pax'] = $reportYearGeo['nationality'][$val['nationality']]['pax'];


                    //simpan report producer dan top ten
                    if ($val['prefix'] == 0) {
                        //producer
                        if (isset($reportProducer[$month][$val['id']]['rno'])) {
                            $reportProducer[$month][$val['id']]['rno'] += 1;
                            $reportProducer[$month][$val['id']]['revenue'] += $val['charge'];
                        } else {
                            $reportProducer[$month][$val['id']]['rno'] = (isset($producer[$month][$val['id']]['rno']) and $month == $monthLastReport ? $producer[$month][$val['id']]['rno'] : 0 ) + 1;
                            $reportProducer[$month][$val['id']]['revenue'] = (isset($producer[$month][$val['id']]['revenue']) and $month == $monthLastReport ? $producer[$month][$val['id']]['revenue'] : 0) + $val['charge'];
                        }
                        $producer[$month][$val['id']]['rno'] = $reportProducer[$month][$val['id']]['rno'];
                        $producer[$month][$val['id']]['revenue'] = $reportProducer[$month][$val['id']]['revenue'];

                        //top ten
                        if (isset($reportTopTen[$val['roles_id']][$val['id']]['rno'])) {
                            $reportTopTen[$val['roles_id']][$val['id']]['rno'] += 1;
                            $reportTopTen[$val['roles_id']][$val['id']]['revenue'] += $val['charge'];
                        } else {
                            $reportTopTen[$val['roles_id']][$val['id']]['rno'] = (isset($monthTopTen[$val['roles_id']][$val['id']]['rno']) and $month == $monthLastReport ? $monthTopTen[$val['roles_id']][$val['id']]['rno'] : 0) + 1;
                            $reportTopTen[$val['roles_id']][$val['id']]['revenue'] = (isset($monthTopTen[$val['roles_id']][$val['id']]['revenue']) and $month == $monthLastReport ? $monthTopTen[$val['roles_id']][$val['id']]['revenue'] : 0) + $val['charge'];
                        }
                        $monthTopTen[$val['roles_id']][$val['id']]['rno'] = $reportTopTen[$val['roles_id']][$val['id']]['rno'];
                        $monthTopTen[$val['roles_id']][$val['id']]['revenue'] = $reportTopTen[$val['roles_id']][$val['id']]['revenue'];
                    }

                    //simpan report source of code
                    //source of busines bulan
                    if (isset($reportSobMonth[$val['market_segment_id']]['rno'])) {
                        $reportSobMonth[$val['market_segment_id']]['rno'] += 1;
                        $reportSobMonth[$val['market_segment_id']]['pax'] += $val['pax'];
                        $reportSobMonth[$val['market_segment_id']]['revenue'] += $val['charge'];
                    } else {
                        $reportSobMonth[$val['market_segment_id']]['rno'] = (isset($sobMonth[$val['market_segment_id']]['rno']) ? $sobMonth[$val['market_segment_id']]['rno'] : 0 ) + 1;
                        $reportSobMonth[$val['market_segment_id']]['pax'] = (isset($sobMonth[$val['market_segment_id']]['pax']) ? $sobMonth[$val['market_segment_id']]['pax'] : 0 ) + $val['pax'];
                        $reportSobMonth[$val['market_segment_id']]['revenue'] = (isset($sobMonth[$val['market_segment_id']]['revenue']) ? $sobMonth[$val['market_segment_id']]['revenue'] : 0 ) + $val['charge'];
                    }
                    $sobMonth[$val['market_segment_id']]['rno'] = $reportSobMonth[$val['market_segment_id']]['rno'];
                    $sobMonth[$val['market_segment_id']]['pax'] = $reportSobMonth[$val['market_segment_id']]['pax'];
                    $sobMonth[$val['market_segment_id']]['revenue'] = $reportSobMonth[$val['market_segment_id']]['revenue'];

                    //source of busines tahun
                    if (isset($reportSobYear[$val['market_segment_id']]['rno'])) {
                        $reportSobYear[$val['market_segment_id']]['rno'] += 1;
                        $reportSobYear[$val['market_segment_id']]['pax'] += $val['pax'];
                        $reportSobYear[$val['market_segment_id']]['revenue'] += $val['charge'];
                    } else {
                        $reportSobYear[$val['market_segment_id']]['rno'] = (isset($sobYear[$val['market_segment_id']]['rno']) ? $sobYear[$val['market_segment_id']]['rno'] : 0) + 1;
                        $reportSobYear[$val['market_segment_id']]['pax'] = (isset($sobYear[$val['market_segment_id']]['pax']) ? $sobYear[$val['market_segment_id']]['pax'] : 0) + $val['pax'];
                        $reportSobYear[$val['market_segment_id']]['revenue'] = (isset($sobYear[$val['market_segment_id']]['revenue']) ? $sobYear[$val['market_segment_id']]['revenue'] : 0 ) + $val['charge'];
                    }
                    $sobYear[$val['market_segment_id']]['rno'] = $reportSobYear[$val['market_segment_id']]['rno'];
                    $sobYear[$val['market_segment_id']]['pax'] = $reportSobYear[$val['market_segment_id']]['pax'];
                    $sobYear[$val['market_segment_id']]['revenue'] = $reportSobYear[$val['market_segment_id']]['revenue'];
                }
            

//        $report_month_geo = array_replace($monthGeo, $reportMonthGeo);
//        $report_year_geo = array_replace($yearGeo, $reportYearGeo);
//        $report_producer = array_replace($producer, $reportProducer);
//        $report_top_ten = array_replace($monthTopTen, $reportTopTen);
//        $report_sob_month = array_replace($sobMonth, $reportSobMonth);
//        $report_sob_year = array_replace($sobYear, $reportSobYear);
            //simpan report
            $report = new ReportGeographical;
            $report->na_id = $na_id;
            $report->today_geo = json_encode($reportTodayGeo);
            $report->month_geo = json_encode($monthGeo);
            $report->year_geo = json_encode($yearGeo);
            $report->top_producer = json_encode($producer);
            $report->top_ten = json_encode($monthTopTen);
            $report->sob_month = json_encode($sobMonth);
            $report->sob_year = json_encode($sobYear);
            $report->save();
        }
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

}
