<?php

/**
 * This is the model class for table "{{form_tour}}".
 *
 * The followings are the available columns in table '{{form_tour}}':
 * @property integer $id
 * @property string $gender
 * @property string $nama
 * @property string $email
 * @property string $code
 * @property string $code_type
 * @property string $phone
 * @property string $pin_bb
 * @property string $city_from
 * @property string $paket
 * @property string $hotel
 * @property string $lama_menginap
 * @property integer $jumlah_peserta
 * @property string $daftar_nama
 * @property string $description
 */
class FormTour extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{form_tour}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nama, email, phone,code, gender', 'required'),
            array('jumlah_peserta', 'numerical', 'integerOnly' => true),
            array('gender, pin_bb', 'length', 'max' => 15),
            array('nama, email, code, code_type, phone, city_from, paket, hotel', 'length', 'max' => 255),
            array('lama_menginap, daftar_nama', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, gender, nama, email, code, code_type, phone, pin_bb, city_from, paket, hotel,kamar,kapal,harga, date_start, date_end, jumlah_peserta, daftar_nama, description', 'safe', 'on' => 'search'),
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
            'gender' => 'Gender',
            'nama' => 'Nama',
            'email' => 'Email',
            'code' => 'Code',
            'code_type' => 'Code Type',
            'phone' => 'Phone',
            'pin_bb' => 'Pin Bb',
            'city_from' => 'City From',
            'paket' => 'Paket',
            'hotel' => 'Hotel',
            'date_start' => 'Tanggal Berangkat',
            'date_end' => 'Tanggal Pulang',
            'jumlah_peserta' => 'Jumlah Peserta',
            'daftar_nama' => 'Daftar Nama',
            'description' => 'Description',
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
        $criteria->compare('gender', $this->gender, true);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('code_type', $this->code_type, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('pin_bb', $this->pin_bb, true);
        $criteria->compare('city_from', $this->city_from, true);
        $criteria->compare('paket', $this->paket, true);
        $criteria->compare('hotel', $this->hotel, true);
        $criteria->compare('kamar', $this->kamar, true);
        $criteria->compare('kapal', $this->kapal, true);
        $criteria->compare('harga', $this->harga, true);
        $criteria->compare('date_start', $this->date_start, true);
        $criteria->compare('date_end', $this->date_end, true);
        $criteria->compare('jumlah_peserta', $this->jumlah_peserta);
        $criteria->compare('daftar_nama', $this->daftar_nama, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'id DESC')
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FormTour the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    

    public function getNamaHotel() {
        if ($this->hotel == '4d_1') {
            // =================PAKET 4 HARI 3 MALAM====================
            $hotel = 'Homestay';
            
        } elseif ($this->hotel == '4d_2') {
           $hotel = 'Hotel Cikal Karimun Jawa';
        } elseif ($this->hotel == '4d_3') {
           
            $hotel = 'Hotel New Ocean';
        } elseif ($this->hotel == '4d_4') {
           
            $hotel = 'Karimun Jawa Inn';
        } elseif ($this->hotel == '4d_5') {
           
            $hotel = 'Wisma Apung';
        } elseif ($this->hotel == '4d_6') {
         
            $hotel = 'Mangrove Inn';
        } elseif ($this->hotel == '4d_7') {
          
            $hotel = 'Dewanfaru Resort';
        } elseif ($this->hotel == '4d_8') {
          
            $hotel = 'Wisma Wisata';
        }elseif($this->hotel == '3d_1'){
            // ========================PAKET 3 HARI 2 MALAM========================
           $hotel = 'Homestay';
        } elseif ($this->hotel == '3d_2') {
            $hotel = 'Hotel CIkal Karimun Jawa';
        }elseif ($this->hotel == '3d_3') {
           
            $hotel = 'Nirwana Resort Hotel';
        }elseif ($this->hotel == '3d_4') {
            
            $hotel = 'Hotel New Ocean';
        }elseif ($this->hotel == '3d_5') {
            
            $hotel = 'Karimun Jawa Inn';
        }elseif ($this->hotel == '3d_6') {
           
            $hotel = 'Wisma Apung';
        }elseif ($this->hotel == '3d_7') {
          
            $hotel = 'Mangrove Inn';
        }elseif ($this->hotel == '3d_8') {
          
            $hotel = 'Dewandaru Resort';
        }elseif ($this->hotel == '3d_9') {
           $hotel = 'Wisma Wisata';
        }elseif($this->hotel == '2d_1'){
            // =======================PAKET 2 HARI 1 ============================
           $hotel = 'Homestay';
        } elseif ($this->hotel == '2d_2') {
             $hotel = 'Hotel CIkal Karimun Jawa';
        }elseif ($this->hotel == '2d_3') {
          
              
            $hotel = 'Nirwana Resort Hotel';
        }elseif ($this->hotel == '2d_4') {
        
            $hotel = 'Hotel New Ocean';
        }elseif ($this->hotel == '2d_5') {
            $hotel = 'Karimun Jawa Inn';
        }elseif ($this->hotel == '2d_6') {
            $hotel = 'Wisma Apung';
        }elseif ($this->hotel == '2d_7') {
           $hotel = 'Mangrove Inn';
        }elseif ($this->hotel == '2d_8') {
           $hotel = 'Dewan Daru Resort';
        }elseif ($this->hotel == '2d_9') {
          $hotel = 'Wisma Wisata';
        }else{
            $hotel = 'Wisma Wisata';
        }
        echo $hotel;
    }

    public function getNamaPaket() {
        $a = explode('_', $this->hotel);
        if ($this->paket == '4d3n') {
            echo '4 Hari 3 Malam';
        } elseif ($this->paket == '3d2n') {
            echo '3 Hari 2 Malam';
        } else {
            echo '2 Hari 1 Malam';
        }
    }

    public function getNamaKapal() {
        if($this->kapal == '1'){
            $kapal =' Express (executive)';
        }elseif($this->kapal == '2'){
            $kapal = ' Express (executive)2';
        }elseif($this->kapal == '3'){
            $kapal = 'Express (executive) - Siginjai (ekonomi)';
        }elseif($this->kapal == '4'){
            $kapal = ' Express (executive)';
        }elseif($this->kapal == '5'){
            $kapal = 'Siginjai (ekonomi)';
        }elseif($this->kapal == '6'){
            $kapal = ' Express (executive) - Siginjai (ekonomi)';
        }elseif($this->kapal == '7'){
            $kapal = 'Express (executive)';
        }elseif($this->kapal == '8'){
            $kapal = 'Siginjai (ekonomi)';
        }elseif($this->kapal == '9'){
            $kapal = 'Express (executive) - Siginjai (ekonomi)';
        }elseif($this->kapal == '10'){
            $kapal = ' Express (executive)';
        }elseif($this->kapal == '11'){
            $kapal = 'Siginjai (ekonomi)';
        }elseif($this->kapal == '12'){
            $kapal = ' Express (executive) - Siginjai (ekonomi)';
        }elseif($this->kapal == '13'){
            $kapal = 'Express (executive)';
        }elseif($this->kapal == '14'){
            $kapal = 'Siginjai (ekonomi) ';
        }elseif($this->kapal == '15'){
            $kapal = 'Express (executive) - Siginjai (ekonomi)';
        }elseif($this->kapal == '16'){
            $kapal = 'Express (executive)';
        }elseif($this->kapal == '17'){
            $kapal = ' Siginjai (ekonomi)';
        }elseif($this->kapal == '18'){
            $kapal = ' Express (executive) - Siginjai (ekonomi)';
        }elseif($this->kapal == '19'){
            $kapal = 'Express (executive) ';
        }elseif($this->kapal == '20'){
            $kapal = 'Siginjai (ekonomi) ';
        }elseif($this->kapal == '21'){
            $kapal = 'Express (executive) - Siginjai (ekonomi)  ';
        }elseif($this->kapal == '22'){
            $kapal = ' Express (executive) ';
        }elseif($this->kapal == '23'){
            $kapal = 'Siginjai (ekonomi) ';
        }elseif($this->kapal == '24'){
            $kapal = 'Express (executive) - Siginjai (ekonomi)  ';
        }elseif($this->kapal == '25'){
            $kapal = ' Express (executive) ';
        }elseif($this->kapal == '26'){
            $kapal = ' Siginjai (ekonomi) ';
        }elseif($this->kapal == '27'){
            $kapal = 'Express (executive) - Siginjai (ekonomi) ';
        }elseif($this->kapal == '28'){
            $kapal = 'Express (executive) ';
        }elseif($this->kapal == '29'){
            $kapal = ' Siginjai (ekonomi) ';
        }elseif($this->kapal == '30'){
            $kapal = 'Express (executive) - Siginjai (ekonomi) ';
        }elseif($this->kapal == '31'){
            $kapal = ' Express (executive)';
        }elseif($this->kapal == '32'){
            $kapal = ' Siginjai (ekonomi) ';
        }elseif($this->kapal == '33'){
            $kapal = ' Express (executive) - Siginjai (ekonomi) ';
        }elseif($this->kapal == '34'){
            $kapal = ' Express (executive)';
        }elseif($this->kapal == '35'){
            $kapal = ' Siginjai (ekonomi) ';
        }elseif($this->kapal == '36'){
            $kapal = ' Express (executive) - Siginjai (ekonomi) ';
        }elseif($this->kapal == '37'){
            $kapal = ' Express (executive)';
        }elseif($this->kapal == '38'){
            $kapal = ' Siginjai (ekonomi) ';
        }elseif($this->kapal == '39'){
            $kapal = ' Express (executive) - Siginjai (ekonomi) ';
        }elseif($this->kapal == '40'){
            $kapal = ' Express (executive) ';
        }elseif($this->kapal == '41'){
            $kapal = 'Siginjai (ekonomi) ';
        }elseif($this->kapal == '42'){
            $kapal = 'Express (executive) - Siginjai (ekonomi) ';
        }elseif($this->kapal == '43'){
            $kapal = ' Express (executive) ';
        }elseif($this->kapal == '44'){
            $kapal = ' Siginjai (ekonomi)';
        }elseif($this->kapal == '45'){
            $kapal = 'Express (executive) - Siginjai (ekonomi) ';
        }elseif($this->kapal == '46'){
            $kapal = 'Express (executive)';
        }elseif($this->kapal == '47'){
            $kapal = ' Siginjai (ekonomi) ';
        }elseif($this->kapal == '48'){
            $kapal = ' Maaf tidak tersedia  
                     ';
        }elseif($this->kapal == '49'){
            $kapal = 'Express (executive) ';
        }elseif($this->kapal == '50'){
            $kapal = ' Siginjai (ekonomi) ';
        }elseif($this->kapal == '51'){
            $kapal = ' Maaf tidak tersedia  
                     ';
        }elseif($this->kapal == '52'){
            $kapal = ' Express (executive) ';
        }elseif($this->kapal == '53'){
            $kapal = ' Siginjai (ekonomi) ';
        }elseif($this->kapal == '54'){
            $kapal = ' Maaf tidak tersedia  
                     ';
        }elseif($this->kapal == '55'){
            $kapal = ' Express (executive) ';
        }elseif($this->kapal == '56'){
            $kapal = ' Siginjai (ekonomi)  ';
        }elseif($this->kapal == '57'){
            $kapal = ' Maaf tidak tersedia  
                     ';
        }elseif($this->kapal == '58'){
            $kapal = 'Express (executive) ';
        }elseif($this->kapal == '59'){
            $kapal = ' Siginjai (ekonomi)   ';
        }elseif($this->kapal == '60'){
            $kapal = ' Maaf tidak tersedia  
                    ';
        }elseif($this->kapal == '61'){
            $kapal = ' Express (executive)  ';
        }elseif($this->kapal == '62'){
            $kapal = ' Siginjai (ekonomi)   ';
        }elseif($this->kapal == '63'){
            $kapal = ' Maaf tidak tersedia  
                     ';
        }elseif($this->kapal == '64'){
            $kapal = ' Express (executive)  ';
        }elseif($this->kapal == '65'){
            $kapal = ' Siginjai (ekonomi)  ';
        }elseif($this->kapal == '66'){
            $kapal = ' Maaf tidak tersedia  
                     ';
        }elseif($this->kapal == '67'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '68'){
            $kapal = ' Siginjai (ekonomi)  ';
        }elseif($this->kapal == '69'){
            $kapal = ' Maaf tidak tersedia  
                    ';
        }elseif($this->kapal == '70'){
            $kapal = 'Express (executive)   ';
        }elseif($this->kapal == '71'){
            $kapal = 'Siginjai (ekonomi)   ';
        }elseif($this->kapal == '72'){
            $kapal = ' Maaf tidak tersedia  
                   ';
        }elseif($this->kapal == '73'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '74'){
            $kapal = ' Siginjai (ekonomi)  ';
        }elseif($this->kapal == '75'){
            $kapal = ' Maaf tidak tersedia  
                     ';
        }elseif($this->kapal == '76'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '77'){
            $kapal = ' Siginjai (ekonomi)  ';
        }elseif($this->kapal == '78'){
            $kapal = ' Maaf tidak tersedia  
                     ';
        }elseif($this->kapal == '79'){
            $kapal = 'Express (executive)  ';
        }elseif($this->kapal == '80'){
            $kapal = ' Siginjai (ekonomi)   ';
        }elseif($this->kapal == '81'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '82'){
            $kapal = ' Express (executive) ';
        }elseif($this->kapal == '83'){
            $kapal = 'Siginjai (ekonomi)   ';
        }elseif($this->kapal == '84'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '85'){
            $kapal = ' Express (executive) ';
        }elseif($this->kapal == '86'){
            $kapal = 'Siginjai (ekonomi)  ';
        }elseif($this->kapal == '87'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '88'){
            $kapal = ' 11   ';
        }elseif($this->kapal == '89'){
            $kapal = ' Siginjai (ekonomi)   ';
        }elseif($this->kapal == '90'){
            $kapal = ' Maaf tidak tersedia  
                       ';
        }elseif($this->kapal == '91'){
            $kapal = 'Express (executive)   ';
        }elseif($this->kapal == '92'){
            $kapal = ' Siginjai (ekonomi)   ';
        }elseif($this->kapal == '93'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '94'){
            $kapal = 'Express (executive)  ';
        }elseif($this->kapal == '95'){
            $kapal = 'Siginjai (ekonomi)   ';
        }elseif($this->kapal == '96'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '97'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '98'){
            $kapal = ' Siginjai (ekonomi)   ';
        }elseif($this->kapal == '99'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '100'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '101'){
            $kapal = ' Siginjai (ekonomi)   ';
        }elseif($this->kapal == '102'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '103'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '104'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '105'){
            $kapal = ' Maaf tidak tersedia  
                       ';
        }elseif($this->kapal == '106'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '107'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '108'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '109'){
            $kapal = 'Express (executive)  ';
        }elseif($this->kapal == '110'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '112'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '113'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '114'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '115'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '116'){
            $kapal = ' Express (executive)  ';
        }elseif($this->kapal == '117'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '118'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '119'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '120'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '121'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '122'){
            $kapal = ' Express (executive)  ';
        }elseif($this->kapal == '123'){
            $kapal = ' Maaf tidak tersedia  
                  0   ';
        }elseif($this->kapal == '124'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '125'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '126'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '127'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '128'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '129'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '130'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '131'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '132'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '133'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '134'){
            $kapal = 'Express (executive)  ';
        }elseif($this->kapal == '135'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '136'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '137'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '138'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '139'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '140'){
            $kapal = 'Express (executive)   ';
        }elseif($this->kapal == '141'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '142'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '143'){
            $kapal = 'Express (executive)  ';
        }elseif($this->kapal == '144'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '145'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '146'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '147'){
            $kapal = ' Maaf tidak tersedia  
                     ';
        }elseif($this->kapal == '148'){
            $kapal = ' Maaf tidak tersedia  
                     ';
        }elseif($this->kapal == '149'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '150'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '151'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '152'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '153'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '154'){
            $kapal = ' Maaf tidak tersedia  
                       ';
        }elseif($this->kapal == '155'){
            $kapal = 'Express (executive)  ';
        }elseif($this->kapal == '156'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '157'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '158'){
            $kapal = ' Express (executive)  ';
        }elseif($this->kapal == '159'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '160'){
            $kapal = ' Maaf tidak tersedia  
                      ';
        }elseif($this->kapal == '161'){
            $kapal = ' Express (executive)   ';
        }elseif($this->kapal == '162'){
            $kapal = ' Maaf tidak tersedia  
                  0   ';
        }elseif($this->kapal == '163'){
            $kapal = ' Maaf tidak tersedia  
                  0   ';
        }
        echo $kapal;
    }

    public function getHarga() {
        
        echo landa()->rp($this->hotel);
    }
    
    public function getNamaKamar(){
        if($this->kamar == 'km1_4d_1'){
            // ================= 4 HARI 3 MALAM===============
            $result ='Fan , KM Luar';
        }elseif($this->kamar == 'km2_4d_1'){
            $result ='Fan , KM Luar';
        }elseif($this->kamar == 'km3_4d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km3_4d_2'){
            $result ='Deluxe AC';
        }elseif($this->kamar == 'km4_4d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km4_4d_2'){
            $result ='Suite AC';
        }elseif($this->kamar == 'km4_4d_3'){
            $result ='Family AC';
        }elseif($this->kamar == 'km5_4d_1'){
            $result ='Non AC kamar mandi luar';
        }elseif($this->kamar == 'km5_4d_2'){
            $result ='Non AC kamar mandi dalam';
        }elseif($this->kamar == 'km5_4d_3'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km6_4d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km7_4d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km7_4d_2'){
            $result ='Bungalow AC';
        }elseif($this->kamar == 'km7_4d_3'){
            $result ='Villa AC';
        }elseif($this->kamar == 'km8_4d_1'){
            $result ='Standart AC ';
        }elseif($this->kamar == 'km1_3d_1'){
            // ===================== 3 HARI 2 MALAM =================
            $result ='Fan, Kamar Mandi Luar';
        }elseif($this->kamar == 'km2_3d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km3_3d_1'){
            $result ='Joglo bisnis';
        }elseif($this->kamar == 'km3_3d_2'){
            $result ='Joglo Executive';
        }elseif($this->kamar == 'km3_3d_3'){
            $result ='Room Suite';
        }elseif($this->kamar == 'km3_3d_4'){
            $result ='Master Suite';
        }elseif($this->kamar == 'km4_3d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km4_3d_2'){
            $result ='Deluxe AC';
        }elseif($this->kamar == 'km5_3d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km5_3d_2'){
            $result ='Suite AC';
        }elseif($this->kamar == 'km5_3d_3'){
            $result ='Family AC';
        }elseif($this->kamar == 'km6_3d_1'){
            $result ='Non AC kamar mandi luar';
        }elseif($this->kamar == 'km6_3d_2'){
            $result ='Non AC kamar mandi dalam';
        }elseif($this->kamar == 'km6_3d_3'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km7_3d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km8_3d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km8_3d_2'){
            $result ='Bungalow AC';
        }elseif($this->kamar == 'km8_3d_3'){
            $result ='Villa AC';
        }elseif($this->kamar == 'km9_3d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km1_2d_1'){
            // ===================== 2 HARI 1 MALAM =================
            $result ='Fan, Kamar Mandi Luar';
        }elseif($this->kamar == 'km2_2d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km3_2d_1'){
            $result ='Joglo Bisnis';
        }elseif($this->kamar == 'km3_2d_2'){
            $result ='Joglo Executive';
        }elseif($this->kamar == 'km3_2d_3'){
            $result ='Room Suite';
        }elseif($this->kamar == 'km3_2d_4'){
            $result ='Master Suite';
        }elseif($this->kamar == 'km4_2d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km4_2d_2'){
            $result ='Deluxe AC';
        }elseif($this->kamar == 'km5_2d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km5_2d_2'){
            $result ='Suite AC';
        }elseif($this->kamar == 'km5_2d_3'){
            $result ='Family AC';
        }elseif($this->kamar == 'km6_2d_1'){
            $result ='Non AC kamar mandi luar';
        }elseif($this->kamar == 'km6_2d_2'){
            $result ='Non AC kamar mandi dalam';
        }elseif($this->kamar == 'km6_2d_3'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km7_2d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km8_2d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km8_2d_2'){
            $result ='Bungalow AC';
        }elseif($this->kamar == 'km8_2d_3'){
            $result ='Villa AC';
        }elseif($this->kamar == 'km9_2d_1'){
            $result ='Standart AC';
        }elseif($this->kamar == 'km_hn_1'){
             $result ='Standart AC';
        }
        return $result;
    }

    

}
