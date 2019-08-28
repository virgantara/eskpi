<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl;?>/bootstrap/css/bootstrap.min.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl;?>/css/jquery-ui.css"> 

<script src="<?php echo Yii::app()->baseUrl;?>/js/jquery.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl;?>/js/jquery-ui.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl;?>/bootstrap/js/bootstrap.min.js"></script>


	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<style type="text/css">
	
.navbar-custom {
    background-color:#C3292D;
    color:#ffffff;
    border-radius:0;
}


.navbar-custom .navbar-nav > li > a {
    color:#fff;
}

.navbar-custom .navbar-nav > .active > a {
    color: #ffffff;
    background-color:transparent;
}

.navbar-custom .navbar-nav > li > a:hover,
.navbar-custom .navbar-nav > li > a:focus,
.navbar-custom .navbar-nav > .active > a:hover,
.navbar-custom .navbar-nav > .active > a:focus,
.navbar-custom .navbar-nav > .open >a {
    text-decoration: none;
    background-color: #FF4000;
}


.navbar-custom .navbar-brand {
    color:#eeeeee;
}
.navbar-custom .navbar-toggle {
    background-color:#eeeeee;
}
.navbar-custom .icon-bar {
    background-color:#C3292D;
}
</style>
<link rel="stylesheet" href="<?=Yii::app()->baseUrl;?>/css/main.css">   
<body>

	<nav class="navbar navbar-custom ">
		<div class="container-fluid">
			<div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><?php echo CHtml::encode(Yii::app()->name); ?></a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions'=>array('class'=>'nav navbar-nav'),
	        'submenuHtmlOptions'=>array('class'=>'dropdown-menu'),

	        // 'itemCssClass'=>'hover',
	        'encodeLabel'=>false,
			'items'=>array(
				['label'=>'Home', 'url'=>array('/site/index#slider-area')],
				[
					'label' => 'How to <span class="caret"></span>',
					'url' => '#',

					'itemOptions' => ['class'=>'dropdown-toggle'],
					'linkOptions' => ['class'=>'dropdown-toggle','data-toggle'=>"dropdown",'role' =>'button'],
					'items' => [
						array('label'=>'use Apps', 'url'=>'#'),
						array('label'=>'use SKPI', 'url'=>'#'),
					]
				],
				['label'=>'Important Dates', 'url'=>array('/site/index#dates')],
				[
					'label' => 'SKPI <span class="caret"></span>',
					'url' => '#',
					'itemOptions' => ['class'=>'dropdown-toggle'],
					'linkOptions' => ['class'=>'dropdown-toggle','data-toggle'=>"dropdown",'role' =>'button'],
					'items' => [
						array('label'=>'About SKPI', 'url'=>array('/site/about')),
						array('label'=>'Publishing Flow', 'url'=>array('/site/index#flow')),
					]
				],
				// array('label'=>'Unggah index', 'url'=>array('/index/uploadindex'),'visible'=>!Yii::app()->user->isGuest),
				
				// // array('label'=>'Unggah PA', 'url'=>array('/mastermahasiswa/uploadPA'),'visible'=>!Yii::app()->user->isGuest),
				// array('label'=>'index Personal', 'url'=>array('/index/cetakPerDosen')),
				
				// array('label'=>'Catatan Revisi', 'url'=>array('/indexLog/admin'),'visible'=>Yii::app()->user->checkAccess(array(WebUser::R_SA))),
				
				// array('label'=>'Data Belum Input Nilai', 'url'=>array('/krs/nilai'),'visible'=>Yii::app()->user->checkAccess(array(WebUser::R_SA))),
				// [
				// 	'label'=>'Laporan <span class="caret"></span>', 
				// 	'url'=>'#',
				// 	'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA]),
				// 	'itemOptions' => ['class'=>'dropdown-toggle'],
				// 	'linkOptions' => ['class'=>'dropdown-toggle','data-toggle'=>"dropdown",'role' =>'button'],
				// 	'items' => [
				// 		['label'=>'Laporan Input Nilai', 'url'=>['/krs/nilai'],'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA])],
				// 		['label'=>'Mahasiswa Belum Lengkap Data Ortu', 'url'=>['/mastermahasiswa/dataortu'],'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA])],
				// 		['label'=>'Lampiran SK', 'url'=>array('/indexLampiranSk/admin'),'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA])],
				// 	]
				// ],
				[
					'label'=>'Master <span class="caret"></span>', 
					'url'=>'#',
					'visible'=>!Yii::app()->user->isGuest,
					'itemOptions' => ['class'=>'dropdown-toggle'],
					'linkOptions' => ['class'=>'dropdown-toggle','data-toggle'=>"dropdown",'role' =>'button'],
					'items' => [
						['label'=>'Mahasiswa', 'url'=>['/mastermahasiswa/admin'],'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA])],
						['label'=>'Kategori Capaian Pembelajaran', 'url'=>['/capemKategori/admin'],'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA])],
						['label'=>'Prodi Capaian Pembelajaran', 'url'=>['/prodiCapem/admin'],'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA])],
						// ['label'=>'User di SIAKAD', 'url'=>['/users/index'],'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA])],
						['label'=>'Data KKNI & DIKTI', 'url'=>['/univ/admin'],'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA])],
						['label'=>'User', 'url'=>['/user/index'],'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA])],
						// ['label'=>'Data KRS di SIAKAD', 'url'=>['/datakrs/index'],'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA])],
						// ['label'=>'TTD Rektor', 'url'=>['/utils/ttd'],'visible'=>Yii::app()->user->checkAccess([WebUser::R_SA])],
					]
				],
				// array('label'=>'Biodata Mahasiswa', 'url'=>array('/mastermahasiswa/dataortu'),'visible'=>Yii::app()->user->checkAccess(array(WebUser::R_SA))),
				// array('label'=>'Log', 'url'=>array('/logs/admin'),'visible'=>Yii::app()->user->checkAccess(array(WebUser::R_SA))),
				// array('label'=>'Foto', 'url'=>array('/utils/foto'),'visible'=>Yii::app()->user->checkAccess(array(WebUser::R_SA))),
				
				['label'=>'Contact Us', 'url'=>array('/site/contact')],
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),


			),
		)); ?>
	</div>
	</div>
	</nav><!-- mainmenu -->
      <div class="container-fluid">
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<footer>
		Head Office : Main Campus University of Darussalam Gontor Demangan Siman Ponorogo East Java Indonesia 63471<br>
Phone : (+62352) 483762, Fax : (+62352) 488182, Email : rektorat@unida.gontor.ac.id
	</footer><!-- footer -->
</div>
</body>
</html>
