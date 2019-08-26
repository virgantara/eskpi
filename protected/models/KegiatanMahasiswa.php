<?php

/**
 * This is the model class for table "{{kegiatan_mahasiswa}}".
 *
 * The followings are the available columns in table '{{kegiatan_mahasiswa}}':
 * @property integer $id
 * @property string $nim
 * @property integer $id_jenis_kegiatan
 * @property integer $id_kegiatan
 * @property integer $nilai
 * @property string $waktu
 * @property string $keterangan
 * @property string $tema
 * @property string $instansi
 * @property string $bagian
 * @property string $bidang
 * @property string $nama_kegiatan_mahasiswa
 * @property string $tahun_akademik
 * @property string $semester
 * @property string $tahun
 * @property string $penilai
 * @property string $file
 */
class KegiatanMahasiswa extends CActiveRecord
{

	public $SEARCH;
	public $PAGE_SIZE = 10;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{kegiatan_mahasiswa}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nim, id_jenis_kegiatan, id_kegiatan, tahun_akademik', 'required'),
			array('id_jenis_kegiatan, id_kegiatan, nilai', 'numerical', 'integerOnly'=>true),
			array('nim', 'length', 'max'=>25),
			array('tema, instansi, bagian, bidang, nama_kegiatan_mahasiswa, penilai', 'length', 'max'=>255),
			array('tahun_akademik', 'length', 'max'=>5),
			array('semester', 'length', 'max'=>2),
			array('tahun', 'length', 'max'=>4),
			array('waktu, keterangan, file', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nim, id_jenis_kegiatan, id_kegiatan, nilai, waktu, keterangan, tema, instansi, bagian, bidang, nama_kegiatan_mahasiswa, tahun_akademik, semester, tahun, penilai, file', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nim' => 'Nim',
			'id_jenis_kegiatan' => 'Id Jenis Kegiatan',
			'id_kegiatan' => 'Id Kegiatan',
			'nilai' => 'Nilai',
			'waktu' => 'Waktu',
			'keterangan' => 'Keterangan',
			'tema' => 'Tema',
			'instansi' => 'Instansi',
			'bagian' => 'Bagian',
			'bidang' => 'Bidang',
			'nama_kegiatan_mahasiswa' => 'Nama Kegiatan Mahasiswa',
			'tahun_akademik' => 'Tahun Akademik',
			'semester' => 'Semester',
			'tahun' => 'Tahun',
			'penilai' => 'Penilai',
			'file' => 'File',
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
		$criteria->addSearchCondition('nim',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('id_jenis_kegiatan',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('id_kegiatan',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('nilai',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('waktu',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('keterangan',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('tema',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('instansi',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('bagian',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('bidang',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('nama_kegiatan_mahasiswa',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('tahun_akademik',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('semester',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('tahun',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('penilai',$this->SEARCH,true,'OR');
		$criteria->addSearchCondition('file',$this->SEARCH,true,'OR');

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
	 * @return KegiatanMahasiswa the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
