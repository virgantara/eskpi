<?php

/**
 * This is the model class for table "{{masterdosen}}".
 *
 * The followings are the available columns in table '{{masterdosen}}':
 * @property integer $id
 * @property string $kode_pt
 * @property string $kode_fakultas
 * @property string $kode_jurusan
 * @property string $kode_prodi
 * @property string $kode_jenjang_studi
 * @property string $no_ktp_dosen
 * @property string $nidn
 * @property string $niy
 * @property string $nama_dosen
 * @property string $gelar_depan
 * @property string $gelar_akademik
 * @property string $tempat_lahir_dosen
 * @property string $tgl_lahir_dosen
 * @property string $jenis_kelamin
 * @property string $kode_jabatan_akademik
 * @property string $kode_pendidikan_tertinggi
 * @property string $kode_status_kerja_pts
 * @property string $kode_status_aktivitas_dosen
 * @property string $tahun_semester
 * @property string $nip_pns
 * @property string $home_base
 * @property string $photo_dosen
 * @property string $no_telp_dosen
 * @property string $no_hp_dosen
 * @property string $email_dosen
 * @property string $alamat_dosen
 * @property string $alamat_domisili
 * @property string $kabupaten_dosen
 * @property integer $provinsi_dosen
 * @property string $agama_dosen
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Masterfakultas[] $masterfakultases
 */
class Masterdosen extends CActiveRecord
{

	public $SEARCH;
	public $PAGE_SIZE = 10;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{masterdosen}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kode_pt, kode_fakultas, kode_prodi, kode_jenjang_studi, no_ktp_dosen, nidn, nama_dosen', 'required'),
			array('provinsi_dosen', 'numerical', 'integerOnly'=>true),
			array('kode_pt, home_base', 'length', 'max'=>6),
			array('kode_fakultas, kode_jurusan, kode_jenjang_studi, kode_jabatan_akademik, kode_pendidikan_tertinggi, kode_status_kerja_pts, kode_status_aktivitas_dosen, tahun_semester', 'length', 'max'=>5),
			array('kode_prodi', 'length', 'max'=>15),
			array('no_ktp_dosen, nidn, nip_pns', 'length', 'max'=>30),
			array('niy, gelar_depan, no_hp_dosen', 'length', 'max'=>20),
			array('nama_dosen, tempat_lahir_dosen', 'length', 'max'=>50),
			array('gelar_akademik, kabupaten_dosen', 'length', 'max'=>10),
			array('jenis_kelamin', 'length', 'max'=>1),
			array('photo_dosen', 'length', 'max'=>255),
			array('no_telp_dosen', 'length', 'max'=>25),
			array('email_dosen', 'length', 'max'=>100),
			array('agama_dosen', 'length', 'max'=>2),
			array('tgl_lahir_dosen, alamat_dosen, alamat_domisili, created', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, kode_pt, kode_fakultas, kode_jurusan, kode_prodi, kode_jenjang_studi, no_ktp_dosen, nidn, niy, nama_dosen, gelar_depan, gelar_akademik, tempat_lahir_dosen, tgl_lahir_dosen, jenis_kelamin, kode_jabatan_akademik, kode_pendidikan_tertinggi, kode_status_kerja_pts, kode_status_aktivitas_dosen, tahun_semester, nip_pns, home_base, photo_dosen, no_telp_dosen, no_hp_dosen, email_dosen, alamat_dosen, alamat_domisili, kabupaten_dosen, provinsi_dosen, agama_dosen, created', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'masterfakultases' => array(self::HAS_MANY, 'Masterfakultas', 'pejabat'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'kode_pt' => 'Kode Pt',
			'kode_fakultas' => 'Kode Fakultas',
			'kode_jurusan' => 'Kode Jurusan',
			'kode_prodi' => 'Kode Prodi',
			'kode_jenjang_studi' => 'Kode Jenjang Studi',
			'no_ktp_dosen' => 'No Ktp Dosen',
			'nidn' => 'Nidn',
			'niy' => 'Niy',
			'nama_dosen' => 'Nama Dosen',
			'gelar_depan' => 'Gelar Depan',
			'gelar_akademik' => 'Gelar Akademik',
			'tempat_lahir_dosen' => 'Tempat Lahir Dosen',
			'tgl_lahir_dosen' => 'Tgl Lahir Dosen',
			'jenis_kelamin' => 'Jenis Kelamin',
			'kode_jabatan_akademik' => 'Kode Jabatan Akademik',
			'kode_pendidikan_tertinggi' => 'Kode Pendidikan Tertinggi',
			'kode_status_kerja_pts' => 'Kode Status Kerja Pts',
			'kode_status_aktivitas_dosen' => 'Kode Status Aktivitas Dosen',
			'tahun_semester' => 'Tahun Semester',
			'nip_pns' => 'Nip Pns',
			'home_base' => 'Home Base',
			'photo_dosen' => 'Photo Dosen',
			'no_telp_dosen' => 'No Telp Dosen',
			'no_hp_dosen' => 'No Hp Dosen',
			'email_dosen' => 'Email Dosen',
			'alamat_dosen' => 'Alamat Dosen',
			'alamat_domisili' => 'Alamat Domisili',
			'kabupaten_dosen' => 'Kabupaten Dosen',
			'provinsi_dosen' => 'Provinsi Dosen',
			'agama_dosen' => 'Agama Dosen',
			'created' => 'Created',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$sort = new CSort;

		$criteria->addSearchCondition('id',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('kode_pt',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('kode_fakultas',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('kode_jurusan',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('kode_prodi',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('kode_jenjang_studi',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('no_ktp_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('nidn',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('niy',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('nama_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('gelar_depan',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('gelar_akademik',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('tempat_lahir_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('tgl_lahir_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('jenis_kelamin',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('kode_jabatan_akademik',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('kode_pendidikan_tertinggi',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('kode_status_kerja_pts',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('kode_status_aktivitas_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('tahun_semester',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('nip_pns',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('home_base',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('photo_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('no_telp_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('no_hp_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('email_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('alamat_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('alamat_domisili',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('kabupaten_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('provinsi_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('agama_dosen',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('created',$this->SEARCH,true,'OR');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
			'pagination'=>array(
				'pageSize'=>$this->PAGE_SIZE,

			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Masterdosen the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
