<?php

class MastermahasiswaController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','templatePA','skpi'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','uploadPA','ortu','dataortu','updatebio','ajaxFindWilayah','ajaxFindWilayahOne','ajaxFindNegara','uploadMhs','ajaxSync','getListMahasiswa'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionGetListMahasiswa()
	{
		$model = null;

		$result = [];
		if(Yii::app()->request->isAjaxRequest)
		{
			

			$post = $_GET['term'];
			// print_r($post);exit;
			if(!empty($post)){
				

				$params = [
					'key' => $post,
				];

				$hasil = Yii::app()->rest->getListMahasiswa($params);
				
			
				$result = $hasil->values;
				$results = [];
				foreach($result as $m)
				{

					$results[] = array(
						'id' => $m->nim_mhs,
						'value' => $m->nim_mhs.' - '.ucwords(strtolower($m->nama_mahasiswa)),

					);
				}

		        echo CJSON::encode($results);
			    
		
			}
		}
	}

	public function actionSkpi($eid)
	{
		$id = base64_decode($eid);
		$model = $this->loadModel($id);
		$bulans =  array(
	    	'01' => 'Januari',
	    	'02' => 'Februari',
	    	'03' => 'Maret',
	    	'04' => 'April',
	    	'05' => 'Mei',
	    	'06' => 'Juni',
	    	'07' => 'Juli',
	    	'08' => 'Agustus',
	    	'09' => 'September',
	    	'10' => 'Oktober',
	    	'11' => 'November',
	    	'12' => 'Desember'
	    );

	    $tgl = explode('-', $model->tgl_lahir);

	    $dob = $tgl[2].' '.$bulans[$tgl[1]].' '.$tgl[0];
	    $tgl_sk = $model->tgl_sk_yudisium ?: '1970-01-01';
	    $tgl_sk = explode('-', $tgl_sk);
	    $tgl_sk_yudisium = $tgl_sk[2].' '.$bulans[$tgl_sk[1]].' '.$tgl_sk[0];
		$style = array(
		    'border' => false,
			'padding' => 0,
		    'fgcolor' => array(0,0,0),
		    'bgcolor' => false, //array(255,255,255)
		    'position' => 'R'
		);
		
		$user = Yii::app()->db->createCommand()
	    ->select('sum( nilai * ( 1 - abs( sign( id_jenis_kegiatan -1 ) ) ) ) AS "1", sum( nilai * ( 1 - abs( sign( id_jenis_kegiatan -2 ) ) ) ) AS "2", sum( nilai * ( 1 - abs( sign( id_jenis_kegiatan -3 ) ) ) ) AS "3", sum( nilai * ( 1 - abs( sign( id_jenis_kegiatan -4 ) ) ) ) AS "4", sum( nilai * ( 1 - abs( sign( id_jenis_kegiatan -5 ) ) ) ) AS "5", sum( nilai * ( 1 - abs( sign( id_jenis_kegiatan -6 ) ) ) ) AS "6", sum(nilai) as total_nilai')
	    ->from('simak_mastermahasiswa m')
	    ->leftJoin('simak_kegiatan_mahasiswa', 'simak_kegiatan_mahasiswa.nim = m.nim_mhs')
	    ->where('m.nim_mhs=:id', array(':id'=>$model->nim_mhs))
	    ->andWhere('penilai is not null')
	    ->andWhere('penilai != ""')
	    ->group('nim_mhs')
	    ->queryRow();

		$pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 
			                'P', 'mm', 'A4', true, 'UTF-8');

		$pdf->SetTitle('Surat Keterangan Pendamping Ijazah');
 		$pdf->SetSubject('SKPI');
		$pdf->setPrintHeader(false);

// add a page
		$pdf->AddPage();
		// get the current page break margin
		
		
		$pdf->SetAutoPageBreak(false, 30);
		$img_file = Yii::app()->baseUrl.'/images/header_skpi.png';
		$top = 0;
		$pdf->Image($img_file, 0, $top, $pdf->getPageWidth(), 30);
		
		$no_ijazah = $model->no_ijazah ?: 'xxx/UNIDA/XXXX/X/XXX/'.date('Y');
		
		$margin_limit = 20;
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
		$top += 35;		
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >Nomor: '.$no_ijazah.'</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('dejavusans', '', 8);

		$pdf->writeHTMLCell(65, 10, $pdf->getPageWidth()-$margin_limit-55, $top, $html);

		// $pdf->Rect($pdf->getPageWidth()-$margin_limit-32, 37, 42, 6, 'DF', '', array(220, 220, 200));
		// $pdf->Rect($pdf->getPageWidth()-$margin_limit-32, 37, 56, 20, 'D', array('all' => $style3));
		$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(30, 30, 30));
		$pdf->Line($margin_limit-10, $top, $pdf->getPageWidth()-$margin_limit+10,$top,$style);
		
		$pdf->SetFont('times', 'B', 20);
		$html = 'SURAT KETERANGAN<br>PENDAMPING IJAZAH';
		$pdf->writeHTMLCell(110, 10, $margin_limit, $top, $html);

		$top += 10;
		$urlToSKPI = Yii::app()->createAbsoluteUrl('mastermahasiswa/skpi',['eid'=>$eid]);
		$pdf->write2DBarcode($urlToSKPI, 'QRCODE,Q', $pdf->getPageWidth() - 30, $top, 20, 20, $style, 'T');

		$pdf->SetFont('times', 'I', 14);
		$html = 'Diploma Supplement';
		$top += 10;
		$pdf->writeHTMLCell(110, 10, $margin_limit, $top, $html);

		$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(100, 100, 100));

		$top += 11;
		$pdf->Line($margin_limit-10, $top, $pdf->getPageWidth()-$margin_limit+10,$top,$style);

		$html = '<p>Surat Keterangan Pendamping Ijazah (SKPI) ini mengacu pada Kerangka Kualifikasi Nasional Indonesia (KKNI) dan Konvensi Unesco tentang pengakuan studi, ijazah dan gelar pendidikan tinggi. Tujuan dari SKPI ini adalah menjadi dokumen yang menyatakan kemampuan kerja, penguasaan pengetahuan, dan sikap/moral pemegangnya<p>';
		$pdf->SetFont('times', '', 8);
		$top += 1;
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);
		$html = '<p style="font-style:italic;">This Diploma Supplement refers to the Indonesian Qualification Framework and UNESCO Convention on the Recognition of Studies, Diplomas and Degrees in Higher Education. The purpose of the supplement is to provide a description of the nature, level, context and status of the studies that were pursued and successfully completed by the individual named on the original qualification to which this supplement is appended.</p>';
		$top += 11;
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);		
		
		$style = array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(200, 200, 200));
		$top += 11;
		$pdf->Line($margin_limit, $top, $pdf->getPageWidth()-$margin_limit,$top,$style);
		
		$top += 2;
		$pdf->SetFont('times', 'B', 7);
		$html = '1. INFORMASI TENTANG IDENTITAS DIRI PEMEGANG SKPI';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);

		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = '1. Information Identifying the Holder of Diploma Supplement';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);

		$top += 5;
		$pdf->SetFont('times', 'B', 7);
		$html = 'NAMA LENGKAP';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);

		$pdf->SetFont('times', 'B', 7);
		$html = 'TAHUN LULUS';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth()  / 2 + 8, $top, $html);

		$pdf->SetFont('times', 'I', 7);
		$html = 'Fullname';
		$top += 3;
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);


		$pdf->SetFont('times', 'I', 7);
		$html = 'Year of Completion';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);


		$top += 3;
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >'.ucwords(strtolower($model->nama_mahasiswa)).'</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('times', '', 8);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $margin_limit-1, $top, $html);

		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >'.ucwords(strtolower($tgl_sk_yudisium)).'</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('times', '', 8);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $pdf->getPageWidth() / 2 + 7, $top, $html);


		$top += 9;
		$pdf->SetFont('times', 'B', 7);
		$html = 'TEMPAT DAN TANGGAL LAHIR';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);

		$pdf->SetFont('times', 'B', 7);
		$html = 'NOMOR IJAZAH';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = 'Date and Place of Birth';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$pdf->SetFont('times', 'I', 7);
		$html = 'Diploma Number';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >'.ucwords(strtolower($model->tempat_lahir.', '.$dob));
		$html .= '<br><i>'.ucwords(strtolower($model->tempat_lahir.', '.date('F d, Y',strtotime($model->tgl_lahir)))).'</i></td></tr>';
		$html .= '</table>';
		$pdf->SetFont('times', '', 8);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $margin_limit-1, $top, $html);

		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >'.ucwords(strtolower($no_ijazah)).'</td></tr>';		
		$html .= '</table>';
		$pdf->SetFont('times', '', 8);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $pdf->getPageWidth() / 2 + 7, $top, $html);

		$top += 12;
		$pdf->SetFont('times', 'B', 7);
		$html = 'NOMOR INDUK MAHASISWA';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$pdf->SetFont('times', 'B', 7);
		$html = 'GELAR';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = 'Student Identification Number';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$pdf->SetFont('times', 'I', 7);
		$html = 'Name of Qualification';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >'.ucwords(strtolower($model->nim_mhs)).'</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('times', '', 8);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $margin_limit-1, $top, $html);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >'.ucwords(strtolower($model->kodeProdi->gelar_lulusan)).' ('.$model->kodeProdi->gelar_lulusan_short.')';
		$html .= '<br><i>'.ucwords(strtolower($model->kodeProdi->gelar_lulusan_en)).'</i></td></tr>';
		$html .= '</table>';
		$pdf->SetFont('times', '', 8);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $pdf->getPageWidth() / 2 + 7, $top, $html);

		$top += 12;
		$style = array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(200, 200, 200));
		$pdf->Line($margin_limit, $top, $pdf->getPageWidth()-$margin_limit,$top,$style);
		
		$top += 1;
		$pdf->SetFont('times', 'B', 7);
		$html = '2. INFORMASI TENTANG IDENTITAS PENYELENGGARA PROGRAM';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);
		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = '2. Information Identifying the Awarding Institution';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);

		$top += 6;
		$pdf->SetFont('times', 'B', 7);
		$html = 'SK PENDIRIAN PERGURUAN TINGGI';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'PERSYARATAN PENERIMAAN';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = 'Awarding Institution\'s License';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'Entry Requirements';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$pdf->SetFont('times', '', 8);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >No:197/E/O/2014, Tanggal 4 Juli 2014';
		$html .= '<br><i>No:197/E/O/2014, Date July 4, 2014</i></td></tr>';
		$html .= '</table>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $margin_limit-1, $top, $html);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >Lulus pendidikan menengah atas/sederajat';
		$html .= '<br><i>Completed from high school or similar level of education</i></td></tr>';
		$html .= '</table>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $pdf->getPageWidth() / 2 + 8 -1, $top, $html);

		$top += 12;
		$pdf->SetFont('times', 'B', 7);
		$html = 'NAMA PERGURUAN TINGGI';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'BAHASA PENGANTAR KULIAH';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = 'Awarding Institution';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'Language of Instruction';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >Universitas Darussalam Gontor</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('times', '', 8);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $margin_limit-1, $top, $html);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >Indonesia, Arab, Inggris<br><i>Indonesia, Arabic, English</i></td></tr>';
		$html .= '</table>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $pdf->getPageWidth() / 2 + 8-1, $top, $html);

		$top += 12;
		$pdf->SetFont('times', 'B', 7);
		$html = 'PROGRAM STUDI';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'SISTEM PENILAIAN';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = 'Major';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'Grading System';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >'.$model->kodeProdi->nama_prodi.'<br><i>'.ucwords(strtolower($model->kodeProdi->nama_prodi_en)).'</i></td></tr>';
		$html .= '</table>';
		$pdf->SetFont('times', '', 8);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $margin_limit-1, $top, $html);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >Skala 0-4;A+=4.00, A=3.75, A-=3.50, B+=3.25, B=3.00, B-=2.75, C+=2.50, C=2.25, C-=2.00, D=1.00, E=0.00<br><i>';
		$html .= 'Scale 0-4;A+=4.00, A=3.75, A-=3.50, B+=3.25, B=3.00, B-=2.75, C+=2.50, C=2.25, C-=2.00, D=1.00, E=0.00';
		$html .= '</i></td></tr>';
		$html .= '</table>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $pdf->getPageWidth() / 2 + 8-1, $top, $html);

		$top += 18;
		$pdf->SetFont('times', 'B', 7);
		$html = 'JENIS & JENJANG PENDIDIKAN';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'LAMA STUDI REGULER';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = 'Type & Level of Education';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'Regular Length of Study';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >Akademik & Sarjana (Strata 1)<br><i>Academic & Bachelor Degree</i></td></tr>';
		$html .= '</table>';
		$pdf->SetFont('times', '', 8);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $margin_limit-1, $top, $html);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >8 Semester</td></tr>';
		$html .= '</table>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $pdf->getPageWidth() / 2 + 8-1, $top, $html);

		$top += 13;
		$pdf->SetFont('times', 'B', 7);
		$html = 'JENJANG KUALIFIKASI SESUAI KKNI';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'JENIS DAN JENJANG PENDIDIKAN LANJUTAN';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = 'Level of Qualification in the National Qualification Framework';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'Access to Further Study';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >Level 6</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('times', '', 8);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $margin_limit-1, $top, $html);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" >Program Magister & Doktoral<br><i>';
		$html .= 'Master & Doctoral Program';
		$html .= '</i></td></tr>';
		$html .= '</table>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $pdf->getPageWidth() / 2 + 8-1, $top, $html);

		$top += 13;
		$html = 'STATUS PROFESI (BILA ADA)';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = 'Professional Status (If Applicable)';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 3;
		$pdf->SetFont('times', '', 8);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td bgcolor="#D8D8D8" ></td></tr>';
		$html .= '</table>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $pdf->getPageWidth() / 2 + 8-1, $top, $html);

		$pdf->AddPage();
		$img_file = Yii::app()->baseUrl.'/images/header_sub_skpi.png';
		$top = 0;
		$pdf->Image($img_file, 0, $top, $pdf->getPageWidth(), 5);
		$img_file = Yii::app()->baseUrl.'/images/logonew.jpg';
		$top += 8;
		$pdf->Image($img_file, $margin_limit, $top, 20, 20);
		$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(100, 100, 100));
		$top += 21;
		$pdf->Line($margin_limit-10, $top, $pdf->getPageWidth()-$margin_limit+10,$top,$style);

		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td style="color:#6e6e6e">'.ucwords(strtolower($model->nama_mahasiswa)).' | No: '.$no_ijazah.'</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('dejavusans', '', 8);

		$pdf->writeHTMLCell(200, 10, 0, $top-15, $html,0,0,0,1,'R');

		$top += 1;
		$pdf->SetFont('times', 'B', 7);
		$html = '3. INFORMASI TENTANG KUALIFIKASI DAN HASIL YANG DICAPAI';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);
		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = '3. Information Identifying the Qualification and Outcomes Obtained';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);

		$top += 6;
		$pdf->SetFont('times', 'B', 7);
		$html = 'A. CAPAIAN PEMBELAJARAN';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'A. LEARNING OUTCOMES';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$pdf->SetFont('times', '', 7);
		$k = CapemKategori::model()->findByPk(1);
		
		$top += 4;
		$html = $k->nama;
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = $k->nama_en;
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$listCapemProdi = ProdiCapem::model()->findAllByAttributes([
			'prodi_id' => $model->kode_prodi,
			'capem_kategori_id' => $k->id
		]);				

		foreach($listCapemProdi as $cp)
		{
			$html = $cp->label;
			$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
			$html = $cp->label_en;
			$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);				
		}

		$k = CapemKategori::model()->findByPk(2);
		
		$top += $pdf->getPageHeight() / 3;

		$html = $k->nama;
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = $k->nama_en;
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$listCapemProdi = ProdiCapem::model()->findAllByAttributes([
			'prodi_id' => $model->kode_prodi,
			'capem_kategori_id' => $k->id
		]);				

		foreach($listCapemProdi as $cp)
		{
			$top += 4;
			$html = $cp->label;
			$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
			$html = $cp->label_en;
			$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);				
		}
		
		$pdf->AddPage();
		$img_file = Yii::app()->baseUrl.'/images/header_sub_skpi.png';
		$top = 0;
		
		$pdf->Image($img_file, 0, $top, $pdf->getPageWidth(), 5);
		$img_file = Yii::app()->baseUrl.'/images/logonew.jpg';
		$top += 8;
		$pdf->Image($img_file, $margin_limit, $top, 20, 20);
		$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(100, 100, 100));
		$top += 21;
		$pdf->Line($margin_limit-10, $top, $pdf->getPageWidth()-$margin_limit+10,$top,$style);
		// $pdf->SetFont('times', '', 8);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td style="color:#6e6e6e">'.ucwords(strtolower($model->nama_mahasiswa)).' | No: '.$no_ijazah.'</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('dejavusans', '', 8);

		$pdf->writeHTMLCell(200, 10, 0, $top-15, $html,0,0,0,1,'R');
		$k = CapemKategori::model()->findByPk(3);
		
		$top += 3;
		$pdf->SetFont('times', '', 8);
		$html = $k->nama;
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = $k->nama_en;
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$listCapemProdi = ProdiCapem::model()->findAllByAttributes([
			'prodi_id' => $model->kode_prodi,
			'capem_kategori_id' => $k->id
		]);				

		foreach($listCapemProdi as $cp)
		{
			$top += 4;
			$html = $cp->label;
			$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
			$html = $cp->label_en;
			$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);				
		}

		$pdf->AddPage();
		$img_file = Yii::app()->baseUrl.'/images/header_sub_skpi.png';
		$top = 0;
		
		$pdf->Image($img_file, 0, $top, $pdf->getPageWidth(), 5);
		$img_file = Yii::app()->baseUrl.'/images/logonew.jpg';
		$top += 8;
		$pdf->Image($img_file, $margin_limit, $top, 20, 20);
		$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(100, 100, 100));
		$top += 21;
		$pdf->Line($margin_limit-10, $top, $pdf->getPageWidth()-$margin_limit+10,$top,$style);
		// $pdf->SetFont('times', '', 8);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td style="color:#6e6e6e">'.ucwords(strtolower($model->nama_mahasiswa)).' | No: '.$no_ijazah.'</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('dejavusans', '', 8);

		$pdf->writeHTMLCell(200, 10, 0, $top-15, $html,0,0,0,1,'R');

		$top += 4;
		$pdf->SetFont('times', 'B', 7);
		$html = 'B. AKTIVITAS, PRESTASI DAN PENGHARGAAN';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'B. Activities, Achievements, and Awards';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$pdf->SetFont('times', '', 7);
		$top += 4;
		$html = 'Pemegang Surat Keterangan Pendamping Ijazah ini memiliki sertifikat profesional:';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = '<i>The propietor of this Diploma Supplement has following professional certifications:</i>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 8;
		$html = '1. ';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = '<i>1.</i>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 4;
		$html = '2. ';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = '<i>2.</i>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 4;
		$html = 'Mahasiswa tersebut  telah mengikuti program kegiatan atau telah memenuhi tanggung jawab yang tercantum dalam Indeks Prestasi  Kesantrian sebagai berikut:';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = '<i>The student participated in the activity program or has fulfilled the responsibilities listed in the Kesantrian Achievement Index as follows:</i>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 7;



		$html = '<table width="100%" border="1" cellspacing="0" cellpadding="3">';
		$html .= '<tr><td width="10%">No</td><td width="50%">Program</td><td width="25%" style="text-align:center">Poin<br>(min-max)</td><td width="15%" style="text-align:center">Poin</td></tr>';
		$listJenisKegiatan = JenisKegiatan::model()->findAll();
		foreach($listJenisKegiatan as $q => $k)
		{
			$html .= '<tr><td width="10%" style="text-align:center">'.($q+1).'</td><td width="50%">'.$k->nama_jenis_kegiatan.'</td><td width="25%" style="text-align:center">('.$k->nilai_minimal.'-'.$k->nilai_maximal.')</td><td width="15%" style="text-align:center">'.$user[$k->id].'</td></tr>';
		}

		$html .= '<tr><td width="60%" style="text-align:center" colspan="2">Total</td><td width="25%" style="text-align:center">(200 - 400)</td><td width="15%" style="text-align:center">'.$user['total_nilai'].'</td></tr>';

		$index = $user['total_nilai'] / 400 * 4;
		$html .= '<tr><td width="60%" style="text-align:center" colspan="2">Indeks</td><td width="25%" style="text-align:center">(2.00 - 4.00)</td><td width="15%" style="text-align:center">'.$index.'</td></tr>';
		$html .= '</table>';

		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $margin_limit-1, $top, $html);

		
// 		
		$html = '<table width="100%" border="1" cellspacing="0" cellpadding="3">';
		$html .= '<tr><td width="10%">No</td><td width="50%">Program</td><td width="25%" style="text-align:center">Point<br>(min-max)</td><td width="15%" style="text-align:center">Point</td></tr>';
		$listJenisKegiatan = JenisKegiatan::model()->findAll();
		foreach($listJenisKegiatan as $q => $k)
		{
			$html .= '<tr><td width="10%" style="text-align:center">'.($q+1).'</td><td width="50%">'.$k->nama_jenis_kegiatan_en.'</td><td width="25%" style="text-align:center">('.$k->nilai_minimal.'-'.$k->nilai_maximal.')</td><td width="15%" style="text-align:center">'.$user[$k->id].'</td></tr>';
		}

		$html .= '<tr><td width="60%" style="text-align:center" colspan="2">Total</td><td width="25%" style="text-align:center">(200 - 400)</td><td width="15%" style="text-align:center">'.$user['total_nilai'].'</td></tr>';
		$html .= '<tr><td width="60%" style="text-align:center" colspan="2">Index</td><td width="25%" style="text-align:center">(2.00 - 4.00)</td><td width="15%" style="text-align:center">'.$index.'</td></tr>';
		$html .= '</table>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-50) / 2, 10, $pdf->getPageWidth() / 2 + 8-1, $top, $html);
		
		$pdf->setFont('times','','6');
		$top += 51;
		$html = 'Skala 1-4; A+=4.00, A=3.75, A-=3.50, B+=3.25, B=3.00, B-=2.75, C+=2.50, C=2.00<br>
Baik (2.00-2.75), Memuaskan (2.76-3.00), Sangat Memuaskan (3.01-3.50), Dengan Pujian (3.76-4.00)';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = '<i>Scale 1-4; A+=4.00, A=3.75, A-=3.50, B+=3.25, B=3.00, B-=2.75, C+=2.50, C=2.00
// Good (2.00-2.75), Very Good (2.76-3.00), Excellent (3.01-3.50), Cum Laude
//  (3.76-4.00)</i>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$pdf->setFont('times','','7');
		$top += 10;
		$html = 'Program kegiatan tambahan dan pelatihan yang telah diikuti selama menjadi mahasiswa adalah sebagai berikut:<ol>';
    
    $listProgram = MahasiswaProgramTambahan::getListProgram($model->nim_mhs);
    foreach($listProgram as $p)
    {
    	$html .= '<li>'.$p->nama.' ('.$p->durasi.'),</li>';	
    }
    // $html .= '<li>Internship (592 jam),</li>';
    // $html .= '<li>Pre-Graduation Program (120 jam).</li>';
    // $html .= '<li>Freshmen Enrichment Program (120 jam),</li> ';
    // $html .= '<li>Community Engagement (30 jam), </li>';
    // $html .= '<li>Latihan Dasar Kepemimpinan (24 jam), Latihan Kemimpinan Manajemen Mahasiswa (15 jam),</li>';
    // $html .= '<li>Induksi Pemimpin Organisasi Kemahasiswaan (30 jam).</li>';

    $html .= '</ol>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'Program kegiatan tambahan dan pelatihan yang telah diikuti selama menjadi mahasiswa adalah sebagai berikut:<ol>';
	    foreach($listProgram as $p)
	    {
	    	$html .= '<li>'.$p->nama_en.' ('.$p->durasi_en.'),</li>';	
	    }

	    $html .= '</ol>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 32;
		$html = 'Catatan:<br>
Program-program tersebut di atas terdiri 
atas kegiatan untuk mengembangkan soft 
skills mahasiswa. Daftar kegiatan ko-kurikuler 
dan ekstra-kurikuler yang diikuti oleh pemegang SKPI ini terlampir.
    ';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = 'Note:<br>
The above-mentioned programs comprise of activities that develop student’s soft skills. A list of co-curricular and extracurricular activities taken by the holder of this supplement is attached.
    ';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);
	
		$pdf->AddPage();
		$img_file = Yii::app()->baseUrl.'/images/header_sub_skpi.png';
		$top = 0;
		
		$pdf->Image($img_file, 0, $top, $pdf->getPageWidth(), 5);
		$img_file = Yii::app()->baseUrl.'/images/logonew.jpg';
		$top += 8;
		$pdf->Image($img_file, $margin_limit, $top, 20, 20);
		$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(100, 100, 100));
		$top += 21;
		$pdf->Line($margin_limit-10, $top, $pdf->getPageWidth()-$margin_limit+10,$top,$style);
		// $pdf->SetFont('times', '', 8);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td style="color:#6e6e6e">'.ucwords(strtolower($model->nama_mahasiswa)).' | No: '.$no_ijazah.'</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('dejavusans', '', 8);

		$pdf->writeHTMLCell(200, 10, 0, $top-15, $html,0,0,0,1,'R');

		$top += 1;
		$pdf->SetFont('times', 'B', 7);
		$html = '4. INFORMASI TENTANG SISTEM PENDIDIKAN TINGGI DI INDONESIA';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);
		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = '4. Information on the Indonesian Higher Education System and the Indonesian National Qualifications Framework';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);

		$pdf->SetFont('times', '', 7);
		$top += 4;
		$html = 'SISTEM PENDIDIKAN TINGGI DI INDONESIA';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = '<i>Higher Education System in Indonesia</i>';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);
		$top += 3;
		$pdf->SetAutoPageBreak(true,10);
		$univ = Univ::model()->findByAttributes(['kode'=>'DIKTI1']);
		$html = $univ->nama;
		$pdf->SetFont('times', '', 5);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = $univ->nama_en;

		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$pdf->AddPage();
		$img_file = Yii::app()->baseUrl.'/images/header_sub_skpi.png';
		$top = 0;
		
		$pdf->Image($img_file, 0, $top, $pdf->getPageWidth(), 5);
		$img_file = Yii::app()->baseUrl.'/images/logonew.jpg';
		$top += 8;
		$pdf->Image($img_file, $margin_limit, $top, 20, 20);
		$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(100, 100, 100));
		$top += 21;
		$pdf->Line($margin_limit-10, $top, $pdf->getPageWidth()-$margin_limit+10,$top,$style);
		// $pdf->SetFont('times', '', 8);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td style="color:#6e6e6e">'.ucwords(strtolower($model->nama_mahasiswa)).' | No: '.$no_ijazah.'</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('dejavusans', '', 8);

		$pdf->writeHTMLCell(200, 10, 0, $top-15, $html,0,0,0,1,'R');

		$pdf->SetFont('times', '', 7);
		$top += 3;
		$pdf->SetAutoPageBreak(true,10);
		$univ = Univ::model()->findByAttributes(['kode'=>'DIKTI2']);
		$html = $univ->nama;
		$pdf->SetFont('times', '', 5);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = $univ->nama_en;

		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);

		$pdf->AddPage();
		$img_file = Yii::app()->baseUrl.'/images/header_sub_skpi.png';
		$top = 0;
		
		$pdf->Image($img_file, 0, $top, $pdf->getPageWidth(), 5);
		$img_file = Yii::app()->baseUrl.'/images/logonew.jpg';
		$top += 8;
		$pdf->Image($img_file, $margin_limit, $top, 20, 20);
		$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(100, 100, 100));
		$top += 21;
		$pdf->Line($margin_limit-10, $top, $pdf->getPageWidth()-$margin_limit+10,$top,$style);
		// $pdf->SetFont('times', '', 8);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td style="color:#6e6e6e">'.ucwords(strtolower($model->nama_mahasiswa)).' | No: '.$no_ijazah.'</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('dejavusans', '', 8);

		$pdf->writeHTMLCell(200, 10, 0, $top-15, $html,0,0,0,1,'R');

		$top += 1;
		$pdf->SetFont('times', 'B', 7);
		$html = '5. KERANGKA KUALIFIKASI NASIONAL INDONESIA (KKNI)';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);
		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = '5. Indonesian Qualification Framework';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);

		$pdf->SetFont('times', '', 7);
		$top += 5;

		$univ = Univ::model()->findByAttributes(['kode'=>'KKNI1']);
		$html = $univ->nama;
		$pdf->SetFont('times', '', 5);
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$html = $univ->nama_en;

		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);
		$top += 8;
		$img_file = Yii::app()->baseUrl.'/images/sskkni.png';
		$pdf->Image($img_file, $pdf->getPageWidth()/2 - 50, $pdf->getPageHeight()/2 - 50, 100, 100);

		$pdf->AddPage();
		$img_file = Yii::app()->baseUrl.'/images/header_sub_skpi.png';
		$top = 0;
		
		$pdf->Image($img_file, 0, $top, $pdf->getPageWidth(), 5);
		$img_file = Yii::app()->baseUrl.'/images/logonew.jpg';
		$top += 8;
		$pdf->Image($img_file, $margin_limit, $top, 20, 20);
		$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(100, 100, 100));
		$top += 21;
		$pdf->Line($margin_limit-10, $top, $pdf->getPageWidth()-$margin_limit+10,$top,$style);
		// $pdf->SetFont('times', '', 8);
		$html = '<table border="0" cellspacing="3" cellpadding="4">';
		$html .= '<tr><td style="color:#6e6e6e">'.ucwords(strtolower($model->nama_mahasiswa)).' | No: '.$no_ijazah.'</td></tr>';
		$html .= '</table>';
		$pdf->SetFont('dejavusans', '', 8);

		$pdf->writeHTMLCell(200, 10, 0, $top-15, $html,0,0,0,1,'R');

		$top += 1;
		$pdf->SetFont('times', 'B', 7);
		$html = '6. PENGESAHAN SKPI';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);
		$top += 3;
		$pdf->SetFont('times', 'I', 7);
		$html = '6. SKPI Legalization';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);
		
		$pdf->SetFont('times', '', 9);
		$top += 25;
		$html = 'Ponorogo, '.date('d').' '.$bulans[date('m')].' '.date('Y');
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$top += 4;
		$pdf->SetFont('times', 'I', 9);
		$html = 'Ponorogo, '.date('F d, Y');
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);

		$pdf->SetFont('times', '', 12);
		$top += 25;
		$html = $model->kodeProdi->kodeFakultas->pejabat0->nama_dosen;
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);
		$kaprodi = Masterdosen::model()->findByAttributes(['nidn'=>$model->kodeProdi->nidn_ketua_prodi]);
		$html = $kaprodi->nama_dosen;
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);
		$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(100, 100, 100));
		$top += 6;
		$pdf->Line($margin_limit, $top, $pdf->getPageWidth() / 2 - $margin_limit,$top,$style);
		$pdf->Line($pdf->getPageWidth() / 2 + 8, $top, $pdf->getPageWidth() - $margin_limit,$top,$style);

		$top += 2;
		$pdf->SetFont('times', '', 8);
		$html = 'DEKAN FAKULTAS '.$model->kodeProdi->kodeFakultas->nama_fakultas;
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);
		$html = 'KETUA PROGRAM STUDI '.$model->kodeProdi->nama_prodi;
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10,  $pdf->getPageWidth() / 2 + 8, $top, $html);

		$top += 4;
		$pdf->SetFont('times', 'I', 8);
		$html = 'Dean of ';
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);
		$html = 'Dean of '.$model->kodeProdi->nama_prodi;
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10,  $pdf->getPageWidth() / 2 + 8, $top, $html);
		$top += 7;
		$pdf->SetFont('times', '', 8);
		$html = 'NOMOR INDUK YAYASAN: '.$model->kodeProdi->kodeFakultas->pejabat0->niy;
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);
		$html = 'NOMOR INDUK YAYASAN '.$kaprodi->niy;
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10,  $pdf->getPageWidth() / 2 + 8, $top, $html);
		$top += 4;
		$pdf->SetFont('times', 'I', 8);
		$html = 'Foundation ID Number: '.$model->kodeProdi->kodeFakultas->pejabat0->niy;
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10, $margin_limit, $top, $html);
		$html = 'Foundation ID Number '.$kaprodi->niy;
		$pdf->writeHTMLCell($pdf->getPageWidth()-2*$margin_limit, 10,  $pdf->getPageWidth() / 2 + 8, $top, $html);

		$pdf->SetFont('times', '', 7);
		// $top += 6;
		$top += 21;
		$pdf->Line($margin_limit-10, $top, $pdf->getPageWidth()-$margin_limit+10,$top,$style);
		$top += 3;
		$html = '
		<p style="text-align:justify">CATATAN:</p>

<ul>
	<li>
	<p style="text-align:justify"><span style="font-size:7px"><span style="font-family:Times New Roman,Times,serif"><span style="color:#333333">SKPI dikeluarkan oleh institusi pendidikan tinggi yang berwenang</span></span></span> <span style="font-size:7px"><span style="font-family:Times New Roman,Times,serif"><span style="color:#333333">mengeluarkan ijazah sesuai dengan paraturan perundang-undangan yang berlaku.</span></span></span></p>
	</li>
	<li>
	<p style="text-align:justify"><span style="font-size:7px"><span style="font-family:Times New Roman,Times,serif"><span style="color:#333333">SKPI hanya diterbitkan setelah mahasiswa dinyatakan lulus dari suatu program studi secara resmi oleh Perguruan Tinggi.</span></span></span></p>
	</li>
	<li>
	<p style="text-align:justify"><span style="font-size:7px"><span style="font-family:Times New Roman,Times,serif"><span style="color:#333333">SKPI diterbitkan dalam Bahasa Indonesia dan Bahasa Inggris.</span></span></span></p>
	</li>
	<li>
	<p style="margin-right:61px; text-align:justify"><span style="font-size:7px"><span style="font-family:Times New Roman,Times,serif"><span style="color:#333333">SKPI yang asli diterbitkan mengunakan kertas khusus (barcode/halogram security paper) berlogo Perguruan Tinggi, yang diterbitkan secara khusus oleh Perguruan Tinggi.</span></span></span></p>
	</li>
	<li>
	<p style="margin-right:61px; text-align:justify"><span style="font-size:7px"><span style="font-family:Times New Roman,Times,serif"><span style="color:#333333">Penerima SKPI dicantumkan dalam situs resmi Perguruan Tinggi.</span></span></span></p>
	</li>
</ul>

<p style="text-align:justify"><em>Official Notes: </em></p>

<ul>
	<li>
	<p style="margin-right:47px; text-align:justify"><em><span style="font-size:7px"><span style="font-family:Times New Roman,Times,serif"><span style="color:#333333"><em>This Diploma Supplement is issued by University of Darussalam Gontor, a higher education institution authorized to issue diplomas in accordance with the applicable Laws.</em></span></span></span></em></p>
	</li>
	<li>
	<p style="margin-right:47px; text-align:justify"><em><span style="font-size:7px"><span style="font-family:Times New Roman,Times,serif"><span style="color:#333333"><em>This Diploma Supplement is issued after the student is offcially declared a graduate of a study program by the University of Darussalam Gontor.</em></span></span></span></em></p>
	</li>
	<li>
	<p style="text-align:justify"><em><span style="font-size:7px"><span style="font-family:Times New Roman,Times,serif"><span style="color:#333333"><em>This Diploma Supplement is written in both Bahasa Indonesia English.</em></span></span></span></em></p>
	</li>
	<li>
	<p style="margin-right:56px; text-align:justify"><em><span style="font-size:7px"><span style="font-family:Times New Roman,Times,serif"><span style="color:#333333"><em>The original copy of this Diploma Supplement is on barcoded/halogram security paper, sealed with the higher education institution&rsquo;s logo, and issued exclusively by </em></span><span style="color:#333333"><em>University of Darussalam Gontor</em></span><span style="color:#333333"><em>.</em></span></span></span></em></p>
	</li>
	<li>
	<p style="text-align:justify"><em><span style="font-size:7px"><span style="font-family:Times New Roman,Times,serif"><span style="color:#333333"><em>The awardee of this Diploma Supplement is officially listed in the University&rsquo;s oﬃcial website.</em></span></span></span></em></p>
	</li>
</ul>



    ';
		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $margin_limit, $top, $html);

		$html = '<p><span style="font-size:8px"><span style="font-family:Times New Roman,Times,serif">ALAMAT<br />
<em>Contact Details</em><br />
UNIVERSITAS DARUSSALAM GONTOR</span></span></p>

<p><br />
<span style="font-size:8px"><span style="font-family:Times New Roman,Times,serif">Jl. Raya Siman No. 5<br />
Ponorogo, Jawa Timur<br />
Indonesia<br />
Tel: (0352) 357 4562<br />
Fax: (0352) 488 182<br />
Website: http://unida.gontor.ac.id<br />
Email: skpi@unida.gontor.ac.id</span></span></p>

<p>&nbsp;</p>
';

		$pdf->writeHTMLCell(($pdf->getPageWidth()-2*$margin_limit) / 2, 10, $pdf->getPageWidth() / 2 + 8, $top, $html);
		$filename = 'SKPI_'.$model->nim_mhs.'_'.date('Y-m-d H:i:s').'.pdf';
		// $string = $pdf->Output($filename, 'I');
		$pdf->Output($filename, 'I');
	}

	public function actionAjaxSync()
	{
		$nim = $_POST['nim'];
		$m = Mastermahasiswa::model()->findByAttributes(['nim_mhs'=>$nim]);
		$ayah = MahasiswaOrtu::model()->findByAttributes([
			'hubungan'=> 'AYAH',
			'nim' => $nim
		]);

		$ibu = MahasiswaOrtu::model()->findByAttributes([
			'hubungan'=> 'IBU',
			'nim' => $nim
		]);

		$wali = MahasiswaOrtu::model()->findByAttributes([
			'hubungan'=> 'WALI',
			'nim' => $nim
		]);

		
		$host = Yii::app()->rest->baseurl_apigateway;
		

		$api = new RestClient;
		$headers = [
			'Content-Type' => 'application/x-www-form-urlencoded'
		];

		$url = $host."/feeder/record";

		$hasil = null;

		$api = new RestClient;
		$headers = [
			'Content-Type' => 'application/x-www-form-urlencoded'
		];

		$params = [
			'table'		=> 'mahasiswa_pt',
			'filter' 	=> 'nipd = \''.$nim.'\'',
		];

		
		$result = $api->post($url, $params, $headers);
		
		try
		{
			
			$hasil = $result->decode_response();
			if(empty($hasil->values->output->result->id_pd))
			{
				
				$url = $host."/feeder/record/insert";
				$params = [
					'table' => 'mahasiswa',
					'nm_pd'		=> ucwords(strtolower($m->nama_mahasiswa)),
					'id_kk' 	=> '0',
					'tmpt_lahir' 			=> ucwords(strtolower($m->tempat_lahir)),
					'tgl_lahir' 		=> $m->tgl_lahir,
					'jk'			=> $m->jenis_kelamin,
					'id_agama' 				=> '1',
					'nik'					=> $m->ktp,
					'kewarganegaraan'		=> $m->warga_negara,
					'jln'					=> $m->alamat,
					'nm_dsn'					=> $m->dusun,
					'rt'					=> $m->rt,
					'rw'					=> $m->rw,
					'ds_kel'				=> $m->desa,
					'kode_pos'				=> $m->kode_pos,
					'id_wil' 			=> $m->kecamatan_feeder,
					'id_jns_tinggal'		=> 4, //asrama,
					'id_alat_transport'	=> 1,//jalan kaki,
					'no_tel_rmh' 				=> $m->telepon,
					'no_hp'				=> $m->hp,
					'email'					=> $m->email,
					'a_terima_kps'		=> 0,
					'nm_ayah'				=> ucwords(strtolower($ayah->nama)),
					'id_pekerjaan_ayah'		=> $ayah->pekerjaan0->kode_feeder,
					'id_jenjang_pendidikan_ayah'	=> $ayah->pendidikan0->kode_feeder,
					'id_penghasilan_ayah'	=> $ayah->penghasilan0->kode_feeder,
					'id_kebutuhan_khusus_ayah' 	=> '0', 
					'nm_ibu_kandung'		=> ucwords(strtolower($ibu->nama)),
					'id_pekerjaan_ibu'		=> $ibu->pekerjaan0->kode_feeder,
					'id_jenjang_pendidikan_ibu'		=> $ibu->pendidikan0->kode_feeder,
					'id_penghasilan_ibu'	=> $ibu->penghasilan0->kode_feeder,
					'id_kebutuhan_khusus_ibu' 	=> '0',
					'nm_wali'				=> !empty($wali) ? ucwords(strtolower($wali->nama)) : '',
					'id_pekerjaan_wali'		=> !empty($wali) ? $wali->pekerjaan0->kode_feeder : '',
					'id_jenjang_pendidikan_wali'	=> !empty($wali) ? $wali->pendidikan0->kode_feeder: '',
					'id_penghasilan_wali'	=> !empty($wali) ? $wali->penghasilan0->kode_feeder : '',
					
				];

				$result = $api->post($url, $params, $headers);
				
				try{
					
					$hasil = $result->decode_response();
				}

				catch(RestClientException  $e){
					print_r($e);
					//throw new RestClientException;
					$hasil = null;
				}

				if(!empty($hasil->values->output->result->id_pd))
				{
					$hsl = (array) $hasil->values->output->result->id_pd;
					$id_pd = $hsl['$value'];
					$m->kode_pd = $id_pd;
					$m->save();
					$prodi = Masterprogramstudi::model()->findByAttributes(['kode_prodi'=> $m->kode_prodi]);

					$params = [
						'id_pd'		=> $id_pd,
						'id_sp' 	=> '715253d2-bafa-429a-9ff7-a85b34ff955d',
						'nipd' 			=> $m->nim_mhs,
						'tgl_masuk_sp' => $_POST['tgl_masuk'],
						'id_jns_daftar' => 1,
						'mulai_smt'	=> $_POST['ta_masuk'],
						'id_sms' => $prodi->kode_feeder,
						'table' => 'mahasiswa_pt',				
					];

					$url = $host."/feeder/record/insert";

					$api = new RestClient;

					$result = $api->post($url, $params, $headers);
					
					try{
						
						$hasil = $result->decode_response();
					}

					catch(RestClientException  $e){
						print_r($e);
						//throw new RestClientException;
						$hasil = null;
					}
				}
			}

			else
			{
				$hsl = (array) $hasil->values->output->result->id_pd;
				$id_pd = $hsl['$value'];
				$m->kode_pd = $id_pd;
				$m->save();
			}
		}

		catch(RestClientException  $e){
			print_r($e);
			//throw new RestClientException;
			$hasil = null;


		}

		
		
		echo json_encode($hasil);

	}

	private function readSheetOrtu($model, $sheetNum, $hubungan)
	{

		$uploadedFile = $model->uploadedFile;

		Yii::import('ext.PHPExcel.PHPExcel.**', true); 

        $fileName = $uploadedFile->getTempName();

        $objPHPExcel = PHPExcel_IOFactory::load($fileName);
        $sheet = $objPHPExcel->getSheet($sheetNum); 
        $highestRow = $sheet->getHighestRow(); 
        // $highestColumn = $sheet->getHighestColumn();
        // $highestColumn++;
        // print_r($highestColumn);
        //Loop through each row of the worksheet in turn
        $message = '';

        $list_pendidikan = [];
      	$pendidikans = Pilihan::model()->findAllByAttributes(['kode'=>'01']);

      	foreach($pendidikans as $p)
      	{
      		$list_pendidikan[$p->kode_feeder] = $p;
      	}

      	$list_pekerjaan = [];
      	$pekerjaans = Pilihan::model()->findAllByAttributes(['kode'=>'55']);

      	foreach($pekerjaans as $p)
      	{
      		$list_pekerjaan[$p->kode_feeder] = $p;
      	}

      	$list_penghasilan = [];
      	$penghasilans = Pilihan::model()->findAllByAttributes(['kode'=>'69']);

      	foreach($penghasilans as $p)
      	{
      		$list_penghasilan[$p->kode_feeder] = $p;
      	}


		$index = 1;
        for ($row = 2; $row <= $highestRow; $row++)
        {


        	$index++;
        	$nim = $sheet->getCell('B'.$row)->getValue();
        	$nik = strtoupper($sheet->getCell('C'.$row)->getValue());
        	$nama = strtoupper($sheet->getCell('D'.$row)->getValue());
        	$tgl_lahir = strtoupper($sheet->getCell('E'.$row)->getValue());
        	$pendidikan = strtoupper($sheet->getCell('F'.$row)->getValue());
        	$pekerjaan = strtoupper($sheet->getCell('G'.$row)->getValue());
        	$penghasilan = strtoupper($sheet->getCell('H'.$row)->getValue());
        	
        	$kd_feeder_pendidikan = !empty($pendidikan) ? explode(' - ', trim($pendidikan)) : [0=>6];
        	$kd_feeder_pekerjaan = !empty($pekerjaan) ? explode(' - ', trim($pekerjaan)) : [0=>99];
        	$kd_feeder_penghasilan = !empty($penghasilan) ? explode(' - ', trim($penghasilan)) : [0=>12];
        	
    		$ortu = new MahasiswaOrtu;
    		$ortu->hubungan = $hubungan;
    		$ortu->nama = $nama;
    		$ortu->nim = $nim;
    		$ortu->agama = 'I';
    		
   //  		try
			// {

    		$ortu->pendidikan = $list_pendidikan[$kd_feeder_pendidikan[0]]->value;
    		$ortu->pekerjaan = $list_pekerjaan[$kd_feeder_pekerjaan[0]]->value;
    		$ortu->penghasilan = $list_penghasilan[$kd_feeder_penghasilan[0]]->value;
    		// }

			// catch(Exception $e){
			// 	$model->addError('error',$e->getMessage());
			// 	exit;
			// 	throw new Exception();

			// }	

    		if($ortu->validate()){

    			$ortu->save();
    		}

    		else{

    			$errors = 'Baris ke-';
				$errors .= ($index + 1).' : ';
					
				foreach($ortu->getErrors() as $attribute){
					foreach($attribute as $error){
						$errors .= $error;
					}
				}
				$model->addError('error',$errors);
				throw new Exception();
    		}

			

        }

	        // $message .= '</ul>';
	     
	}


	public function actionUploadMhs()
	{

		$model = new Mastermahasiswa;
		$mhs = new Mastermahasiswa;
		if(isset($_POST['Mastermahasiswa']))
        {

			$model->uploadedFile=CUploadedFile::getInstance($model,'uploadedFile');

			Yii::import('ext.PHPExcel.PHPExcel.**', true); 

	        $fileName = $model->uploadedFile->getTempName();

	        $objPHPExcel = PHPExcel_IOFactory::load($fileName);
	        $sheet = $objPHPExcel->getSheet(0); 
	        $highestRow = $sheet->getHighestRow(); 
	        // $highestColumn = $sheet->getHighestColumn();
	        // $highestColumn++;
	        // print_r($highestColumn);
	        //Loop through each row of the worksheet in turn
	        $message = '';

	      
	        $transaction=Yii::app()->db->beginTransaction();
	        try
			{
				$index = 1;
		        for ($row = 3; $row <= $highestRow; $row++)
		        { 

		        	$index++;
		        	$nim = $sheet->getCell('B'.$row)->getValue();
		        	$nama = strtoupper($sheet->getCell('C'.$row)->getValue());
		        	$nama_ibu = strtoupper($sheet->getCell('D'.$row)->getValue());
		        	$nik = strtoupper($sheet->getCell('E'.$row)->getValue());
		        	$tmpt_lahir = strtoupper($sheet->getCell('F'.$row)->getValue());
		        	$tgl_lahir = strtoupper($sheet->getCell('G'.$row)->getValue());
		        	$jk = strtoupper($sheet->getCell('H'.$row)->getValue());
		        	$jalan = strtoupper($sheet->getCell('I'.$row)->getValue());
		        	$dusun = strtoupper($sheet->getCell('J'.$row)->getValue());
		        	$rt = strtoupper($sheet->getCell('K'.$row)->getValue());
		        	$rw = strtoupper($sheet->getCell('L'.$row)->getValue());
		        	$desa = strtoupper($sheet->getCell('M'.$row)->getValue());
		        	$kecamatan = strtoupper($sheet->getCell('N'.$row)->getValue());
		        	$kota = strtoupper($sheet->getCell('O'.$row)->getValue());
		        	$provinsi = strtoupper($sheet->getCell('P'.$row)->getValue());
		        	$kodepos = strtoupper($sheet->getCell('Q'.$row)->getValue());
		        	$telp = strtoupper($sheet->getCell('R'.$row)->getValue());
		        	$hp = strtoupper($sheet->getCell('S'.$row)->getValue());
		        	$email = strtoupper($sheet->getCell('T'.$row)->getValue());
		        	$fakultas = strtoupper($sheet->getCell('U'.$row)->getValue());
		        	$prodi = strtoupper($sheet->getCell('V'.$row)->getValue());
		        	$kampus = strtoupper($sheet->getCell('W'.$row)->getValue());
		        	$tahun_masuk = strtoupper($sheet->getCell('X'.$row)->getValue());
		        	$semester_awal = strtoupper($sheet->getCell('Y'.$row)->getValue());
		        	
	        		$mhs = new Mastermahasiswa;
	        		$mhs->nim_mhs = $nim;
	        		$mhs->nama_mahasiswa = $nama;
	        		$mhs->tempat_lahir = $tmpt_lahir;
	        		$mhs->tgl_lahir = $tgl_lahir;
	        		$mhs->jenis_kelamin = $jk;
	        		$mhs->tahun_masuk = $tahun_masuk;
	        		$mhs->semester_awal = $semester_awal;
	        		$mhs->status_aktivitas = 'A';
	        		$mhs->kode_prodi = $prodi;
	        		$mhs->kode_fakultas = $fakultas;
	        		$mhs->semester = '1';
	        		$mhs->telepon = $telp;
	        		$mhs->hp = $hp;
	        		$mhs->email = $email;
	        		$mhs->alamat = $jalan;
	        		$mhs->ktp = $nik;
	        		$mhs->rt = $rt;
	        		$mhs->rw = $rw;
	        		$mhs->dusun = $dusun;
	        		$mhs->kode_pos = $kodepos;
	        		$mhs->desa = $desa;
	        		$mhs->kampus = $kampus;
	        		$mhs->agama = 'I';
	        		$mhs->provinsi = $provinsi;
	        		$mhs->kecamatan = $kecamatan;
	        		$mhs->kabupaten = $kota;
	        		$mhs->kode_pt = '073090';
	        		$mhs->kode_jenjang_studi = 'C';
	        		$mhs->is_synced = 0;
	        		
	        		if($mhs->validate()){

	        			$mhs->save();
	        			
	        		}

	        		else{
	        			
	        			$errors = 'Baris ke-';
						$errors .= ($index + 1).' : ';
							
						foreach($mhs->getErrors() as $attribute){
							foreach($attribute as $error){
								$errors .= $error;
							}
						}
						$model->addError('error',$errors);
						throw new Exception();
	        		}

					

		        }
		        
		        $this->readSheetOrtu($model,1,'AYAH');
		        
		        $this->readSheetOrtu($model,2,'IBU');
		        $this->readSheetOrtu($model,3,'WALI');

		        // $message .= '</ul>';
		        // $this->redirect(array('trRawatInap/lainnya','id'=>$id));
		        $transaction->commit();

		        if(!empty($message))
		        	$message = '<strong style="color:red">Catatan</strong><div style="color:orange">Sebagian data sukses terunggah. Ada beberapa belum, yaitu:</div>'.$message;
		        // $message = empty($message) ? ' Namun, '.$message : '';
		        Yii::app()->user->setFlash('success', "Data Mahasiswa telah diunggah.".$message);
				$this->redirect(['index']);
	        }

			catch(Exception $e){
				Yii::app()->user->setFlash('error', print_r($e));
				$transaction->rollback();
			}	
	    }


		$this->render('upload_mhs',array(
			'model' => $model,
			

		));

	}

	public function actionAjaxFindNegara()
	{

		$params = ['key' => $_GET['term']];
		$hasil = Yii::app()->rest->getListNegara($params);
			


		$result = [];
		if(!empty($hasil->values))
		{
			foreach($hasil->values as $item)
			{
				$result[] = [
					'id' => $item->id_negara,
					'value' => $item->id_negara.' - '.$item->nm_negara,
				];
			}
		}

		echo CJSON::encode($result);
	}

	public function actionAjaxFindWilayahOne()
	{

		$params = ['key' => $_GET['term']];
		$hasil = Yii::app()->rest->getListWilayahOne($params);


		$result = [];
		if(!empty($hasil->values))
		{
			$item = $hasil->values;
			$result[] = [
				'id' => $item->id_wil,
				'value' => $item->nm_wil,
				'id_induk_wilayah' => $item->id_induk_wilayah
			];
		}

		echo CJSON::encode($result);
	}

	public function actionAjaxFindWilayah()
	{

		$params = ['key' => $_GET['term']];
		$hasil = Yii::app()->rest->getListWilayah($params);
			


		$result = [];
		if(!empty($hasil->values))
		{
			foreach($hasil->values as $item)
			{
				$result[] = [
					'id' => $item->id_wil,
					'value' => $item->id_wil.' - '.$item->nm_wil,
					'id_induk_wilayah' => $item->id_induk_wilayah
				];
			}
		}

		echo CJSON::encode($result);
	}

	public function actionUpdatebio()
	{
		if(!empty($_POST['kode_prodi']))
		{
			$c = new CDbCriteria;
			$c->condition = 'kode_prodi = :p1 AND kampus = :p2 AND semester_awal= :p3 ';
			$c->params = [
				':p1' => $_POST['kode_prodi'],
				':p2' => $_POST['kampus'],
				':p3' => $_POST['ta_masuk']
			];
			$c->order = 'nim_mhs ASC';
			$mahasiswas = Mastermahasiswa::model()->findAll($c);
			
			foreach($mahasiswas as $m)
			{	


				if(!empty($_POST['tgl_lahir_'.$m->nim_mhs]))
				{

					$m->tgl_lahir = $_POST['tgl_lahir_'.$m->nim_mhs];
				}

				if($m->tgl_lahir == '0000-00-00'){
					$m->tgl_lahir = NULL;
				}
				if(!empty($_POST['id_kecamatan_'.$m->nim_mhs]))
					$m->kecamatan_feeder = $_POST['id_kecamatan_'.$m->nim_mhs];

				if(!empty($_POST['nama_kecamatan_'.$m->nim_mhs]))
					$m->kecamatan = $_POST['nama_kecamatan_'.$m->nim_mhs];
				
				if(!empty($_POST['id_negara_'.$m->nim_mhs]))
					$m->warga_negara_feeder = $_POST['id_negara_'.$m->nim_mhs];
				
				$m->tempat_lahir = $_POST['tempat_lahir_'.$m->nim_mhs];
				$m->ktp = $_POST['ktp_'.$m->nim_mhs];
				$m->save();
			}
			Yii::app()->user->setFlash('success', "Data Saved.");
			$this->redirect([
				'mastermahasiswa/dataortu',
				'kode_prodi'=>$_POST['kode_prodi'],
				'kampus' => $_POST['kampus'],
				'ta_masuk' => $_POST['ta_masuk'],
				'tgl_masuk' => $_POST['tgl_masuk']
			]);
		}
	}

	public function actionDataortu($kode_prodi='',$kampus='', $ta_masuk='',$tgl_masuk='', $xls='')
	{

		$mahasiswas = new Mastermahasiswa;
		$mprodi = new Masterprogramstudi;
		if(!empty($_GET['kode_prodi']))
		{
			$c = new CDbCriteria;
			$c->condition = 'kode_prodi = :p1 AND kampus = :p2 AND semester_awal= :p3 ';
			$c->params = [
				':p1' => $_GET['kode_prodi'],
				':p2' => $_GET['kampus'],
				':p3' => $_GET['ta_masuk']
			];
			$c->order = 'nim_mhs ASC';
			$mahasiswas = Mastermahasiswa::model()->findAll($c);
			// $mahasiswas = Mastermahasiswa::model()->findAllByAttributes([
			// 	'kode_prodi' => $_GET['kode_prodi'],
			// 	'kampus' => $_GET['kampus']
			// ],['order' => 'nim_mhs DESC']);

			$mprodi = Masterprogramstudi::model()->findByAttributes([
				'kode_prodi'=> $kode_prodi
			]);
		}

		$tmp = Pilihan::model()->findAllByAttributes(['kode' => 51]);

		$list_agama = [];
		foreach($tmp as $v)
		{
			$list_agama[$v->value] = $v->label;
		}

		$tmp = Pilihan::model()->findAllByAttributes(['kode' => '01']);

		$list_pendidikan = [];
		foreach($tmp as $v)
		{
			$list_pendidikan[$v->value] = $v->label;
		}

		$tmp = Pilihan::model()->findAllByAttributes(['kode' => '55']);

		$list_pekerjaan = [];
		foreach($tmp as $v)
		{
			$list_pekerjaan[$v->value] = $v->label;
		}

		$tmp = Pilihan::model()->findAllByAttributes(['kode' => '69']);

		$list_penghasilan = [];
		foreach($tmp as $v)
		{
			$list_penghasilan[$v->value] = $v->label;
		}

		$tmp = Pilihan::model()->findAllByAttributes(['kode' => '53']);

		$list_keadaan = [];
		foreach($tmp as $v)
		{
			$list_keadaan[$v->value] = $v->label;
		}


		if($xls == 'y')
		{
			$mahasiswas = Mastermahasiswa::model()->findAllByAttributes([
				'kode_prodi' => $kode_prodi,
				'kampus' => $kampus,
				'semester_awal' => $ta_masuk
			],['order' => 'nama_mahasiswa ASC']);

			$mprodi = Masterprogramstudi::model()->findByAttributes(['kode_prodi'=> $kode_prodi]);

			Yii::import('ext.PHPExcel.PHPExcel');
			$objPHPExcel = new PHPExcel();
			$styleArray = array(
			    'font'  => array(
			        // 'bold'  => true,
			        // 'color' => array('rgb' => 'FF0000'),
			        'size'  => 8,
			        'name'  => 'Times New Roman'
			    ),
			    'borders' => array(
			    	'allborders' => array(
		                'style' => PHPExcel_Style_Border::BORDER_THIN,
		                'color' => array('rgb' => '000000')
		            )
			    )

			);
			$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
			$sheet = $objPHPExcel->setActiveSheetIndex(0);
			$style = array(
		        'alignment' => array(
		            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
		        )
		    );

		    $sheet->getDefaultStyle()->applyFromArray($style);
			$headers = array(
			   'A' => 'No',
			   'B' =>'NIM',
			   'C' =>'Nama',
			   'D' =>'TTL',
			   'E' =>'JK',
			   'F' =>'ALAMAT',
			   'G' =>'KTP',
			   'H' =>'PRODI',
			   'I' =>'FAKULTAS',
			   'J' =>'TAHUN MASUK',
			   'K' =>'AGAMA',
			   'L' => 'Nama',
			   'M' => 'ALAMAT',
			   'N' => 'Agama',
			   'O' => 'Pendidikan',
			   'P' => 'Pekerjaan',
			   'Q' => 'Penghasilan',
			   'R' => 'Keadaan',
			   'S' => 'Nama',
			   'T' => 'ALAMAT',
			   'U' => 'Agama',
			   'V' => 'Pendidikan',
			   'W' => 'Pekerjaan',
			   'X' => 'Penghasilan',
			   'Y' => 'Keadaan',
			);
			
			$idx = 1;
			$sheet->mergeCells('L1:R1');
			$sheet->setCellValue('L1', 'AYAH/WALI');
			$sheet->mergeCells('S1:Y1');
			$sheet->setCellValue('S1', 'IBU');
			foreach($headers as $q => $v)
		    {
		    	if($idx > 11) 
		    		break;

		    	$sheet->mergeCells($q.'1:'.$q.'2');
		    	$sheet->setCellValue($q.'1', strtoupper($v));
		    	// $sheet->setCellValueByColumnAndRow($idx,$row, strtoupper($v));
		    	
		    	// $cell = $sheet->getCellByColumnAndRow($idx,$row);
		    	$sheet->getStyle($q.'1')->applyFromArray(
		    		array(
		    			'fill' => array(
				            'type' => PHPExcel_Style_Fill::FILL_SOLID,
				            'color' => array('rgb' => '000000')
				        ),
				        'font' => array(
				        	'color' => array('rgb'=> 'ffffff')
				        ),
		    		)
		    	);

		    	
		    	
		    	$idx++;
		    	
		    }

		    $idx = 1;
		    foreach($headers as $q => $v)
		    {
		    	if($idx > 11) 
		    	{		
			    	$sheet->mergeCells($q.'1:'.$q.'2');
			    	$sheet->setCellValue($q.'2', strtoupper($v));
			    	// $sheet->setCellValueByColumnAndRow($idx,$row, strtoupper($v));
			    	
			    	// $cell = $sheet->getCellByColumnAndRow($idx,$row);
			    	$sheet->getStyle($q.'2')->applyFromArray(
			    		array(
			    			'fill' => array(
					            'type' => PHPExcel_Style_Fill::FILL_SOLID,
					            'color' => array('rgb' => '000000')
					        ),
					        'font' => array(
					        	'color' => array('rgb'=> 'ffffff')
					        ),
			    		)
			    	);

		    	}
		    	$idx++;
		    	
		    }
	    
		    $sheet = $objPHPExcel->setActiveSheetIndex(0);
		    $sheet->getColumnDimension('A')->setWidth(5);
		    $sheet->getColumnDimension('B')->setWidth(20);
		    $sheet->getColumnDimension('C')->setWidth(30);
		    $sheet->getColumnDimension('D')->setWidth(18);
		    $sheet->getColumnDimension('E')->setWidth(5);
		    $sheet->getColumnDimension('F')->setWidth(42);
		    $sheet->getColumnDimension('G')->setWidth(20);
		    $sheet->getColumnDimension('H')->setWidth(30);
		    $sheet->getColumnDimension('I')->setWidth(30);
		    $sheet->getColumnDimension('J')->setWidth(15);
		    $sheet->getColumnDimension('K')->setWidth(10);
		    $sheet->getColumnDimension('L')->setWidth(20);
		    $sheet->getColumnDimension('M')->setWidth(42);
		    $sheet->getColumnDimension('N')->setWidth(10);
		    $sheet->getColumnDimension('O')->setWidth(25);
		    $sheet->getColumnDimension('P')->setWidth(25);
		    $sheet->getColumnDimension('Q')->setWidth(25);
		    $sheet->getColumnDimension('R')->setWidth(25);
		    $sheet->getColumnDimension('S')->setWidth(20);
		    $sheet->getColumnDimension('T')->setWidth(42);
		    $sheet->getColumnDimension('U')->setWidth(10);
		    $sheet->getColumnDimension('V')->setWidth(25);
		    $sheet->getColumnDimension('W')->setWidth(25);
		    $sheet->getColumnDimension('X')->setWidth(25);
		    $sheet->getColumnDimension('Y')->setWidth(25);
		    
		     $sheet->setTitle('Data Ortu');
			 $i = 0;

			

			 $row = 2;
			foreach($mahasiswas as $m)
			{
				$row++;
				$q = $m->agama ?: 'I';
				$agama = $list_agama[$q];
				$sheet->setCellValueByColumnAndRow(0,$row, ($i+1));
				$sheet->setCellValueByColumnAndRow(1,$row, $m->nim_mhs);
				$sheet->setCellValueByColumnAndRow(2,$row, $m->nama_mahasiswa);
				$sheet->setCellValueByColumnAndRow(3,$row, $m->tempat_lahir.', '.date('d/m/Y',strtotime($m->tgl_lahir)));
				$sheet->setCellValueByColumnAndRow(4,$row, $m->jenis_kelamin);
				$sheet->setCellValueByColumnAndRow(5,$row, $m->alamat.' '.$m->rt.' '.$m->rw.' '.$m->dusun.' '.$m->desa.' '.$m->kecamatan.' '.$m->kabupaten.' '.$m->provinsi);
				$sheet->setCellValueByColumnAndRow(6,$row, $m->ktp);
				$sheet->setCellValueByColumnAndRow(7,$row, $m->kodeProdi->singkatan);
				$sheet->setCellValueByColumnAndRow(8,$row, $m->kodeProdi->fakultas->nama_fakultas);
				$sheet->setCellValueByColumnAndRow(9,$row, substr($m->nim_mhs, 2,4));
				$sheet->setCellValueByColumnAndRow(10,$row, $agama ?: 'ISLAM');
			
				$i++;

				if(!empty($m->ortus))
				{
					foreach($m->ortus as $ortu)
					{
						

						if($ortu->hubungan == 'AYAH' || $ortu->hubungan == 'WALI')
						{
							$sheet->setCellValueByColumnAndRow(11,$row, ucwords($ortu->nama));
							// $sheet->setCellValueByColumnAndRow(12,$row, $ortu->fullalamat);
							$sheet->setCellValueByColumnAndRow(12,$row, $ortu->agama ?: '-');
							$sheet->setCellValueByColumnAndRow(13,$row, $ortu->pendidikan ?: '-');
							$sheet->setCellValueByColumnAndRow(14,$row, $ortu->pekerjaan ?: '-');
							$sheet->setCellValueByColumnAndRow(15,$row, $ortu->penghasilan ?: '-');
							$sheet->setCellValueByColumnAndRow(16,$row, $ortu->hidup ?: '-');

						}

						else if($ortu->hubungan == 'IBU'){
							$sheet->setCellValueByColumnAndRow(17,$row, ucwords($ortu->nama));
							// $sheet->setCellValueByColumnAndRow(19,$row, $ortu->fullalamat);
							$sheet->setCellValueByColumnAndRow(18,$row, $ortu->agama ?: '-');
							$sheet->setCellValueByColumnAndRow(19,$row, $ortu->pendidikan ?: '-');
							$sheet->setCellValueByColumnAndRow(20,$row, $ortu->pekerjaan ?: '-');
							$sheet->setCellValueByColumnAndRow(21,$row, $ortu->penghasilan ?: '-');
							$sheet->setCellValueByColumnAndRow(22,$row, $ortu->hidup ?: '-');
						}
					}
				}
			}

			$sheet->getStyle('F1:F'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);

			
			$sheet->getStyle('F1:F'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setVertical(true); 
		    

		    ob_end_clean();
		    ob_start();
		    
		    header('Content-Type: application/vnd.ms-excel');
		    header('Content-Disposition: attachment;filename="dataortu_'.$mprodi->nama_prodi.'.xls"');
		    header('Cache-Control: max-age=0');
		    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		    $objWriter->save('php://output');
			// $this->renderPartial('_dataortu_table',[
			// 	'mahasiswas' => $mahasiswas,
			// 	'kdprodi' => $kdprodi,
			// 	'xls' => $xls,
			// 	'mprodi' => $mprodi
			// ]);

			exit;
		}

		$this->render('dataortu',[
			'mahasiswas' => $mahasiswas,
			'kdprodi' => $kode_prodi,
			'xls' => $xls,
			'mprodi' => $mprodi,
			'list_agama' => $list_agama,
			'list_pendidikan' => $list_pendidikan,
			'list_pekerjaan'=>$list_pekerjaan,
			'list_penghasilan' => $list_penghasilan,
			'list_keadaan' => $list_keadaan
		]);
	}

	public function actionTemplatePA()
	{
		Yii::import('ext.PHPExcel.PHPExcel');
		$objPHPExcel = new PHPExcel();

		$headers = array(
		   'Kode Dosen',
		   'Nama Dosen',
		   'NIM',
		   'Nama Mahasiswa',
		   
		);
    
	    $objPHPExcel->setActiveSheetIndex(0);

	    foreach($headers as $q => $v)
	    {
	    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($q,1, $v);
	    }
	    
	    $objPHPExcel->getActiveSheet()->setTitle('mhs_pa');
	 
	    $objPHPExcel->setActiveSheetIndex(0);
	     
	    ob_end_clean();
	    ob_start();
	    
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="templatePA.xls"');
	    header('Cache-Control: max-age=0');
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	    $objWriter->save('php://output');
	}

	public function actionUploadPA()
	{
		$model = new Mastermahasiswa;
		$m = new Mastermahasiswa;
		if(isset($_POST['Mastermahasiswa']))
        {
			$model->uploadedFile=CUploadedFile::getInstance($model,'uploadedFile');
			Yii::import('ext.PHPExcel.PHPExcel.**', true); 

	        $fileName = $model->uploadedFile->getTempName();

	        $objPHPExcel = PHPExcel_IOFactory::load($fileName);
	        $sheet = $objPHPExcel->getSheet(0); 
	        $highestRow = $sheet->getHighestRow(); 

	        $transaction=Yii::app()->db->beginTransaction();
	        try
			{
				$index = 1;
				for ($row = 2; $row <= $highestRow; $row++)
		        {
		        	$kd_dosen = trim($sheet->getCell('A'.$row));
		        	$nim = trim($sheet->getCell('C'.$row));

		        	$attr = array(
		        		'nim_mhs' => $nim
		        	);
		        	$mhs = Mastermahasiswa::model()->findByAttributes($attr);
		        	$dosen = Masterdosen::model()->findByAttributes(array('nidn'=>$kd_dosen));
		        	if(!empty($mhs) && !empty($dosen))
		        	{
		        		echo $mhs->nim_mhs.' '.$dosen->nidn.' - '.$dosen->id;
		        		$mhs->nip_promotor = $dosen->id;
		        		$mhs->save(false, array('nip_promotor'));
		        	// print_r($kd_dosen);	
		        	}

		        	else
		        	{
		        		$m->addError('error','Baris ke-'.($index+1).' : Data NIM : '.$nim.' tidak terdaftar atau berbeda di SIAKAD');
		        		// $m->addError('error','Terjadi kesalahan input data mk');
						throw new Exception();
		        	}
		        	
		        	$index++;	 
		        }

		        $transaction->commit();
		        Yii::app()->user->setFlash('success', "Data PA telah diunggah");
				$this->redirect(array('uploadPA'));
		        // exit;
				
				// exit;
			}

			catch(Exception $e)
			{
				$transaction->rollback();
			}	
		}

		$this->render('uploadPA',array(
			'model' => $model,
			'm' => $m,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Mastermahasiswa;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Mastermahasiswa']))
		{
			$model->attributes=$_POST['Mastermahasiswa'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Mastermahasiswa']))
		{
			$model->attributes=$_POST['Mastermahasiswa'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->actionAdmin();
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Mastermahasiswa('search');
		$model->unsetAttributes();  // clear any default values

		if(isset($_GET['filter']))
			$model->SEARCH=$_GET['filter'];

		if(isset($_GET['size']))
			$model->PAGE_SIZE=$_GET['size'];

		if(isset($_GET['Mastermahasiswa']))
			$model->attributes=$_GET['Mastermahasiswa'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Mastermahasiswa the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Mastermahasiswa::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Mastermahasiswa $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='mastermahasiswa-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
