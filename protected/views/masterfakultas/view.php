<?php
/* @var $this MasterfakultasController */
/* @var $model Masterfakultas */

$this->breadcrumbs=array(
	array('name'=>'Masterfakultas','url'=>array('admin')),
	array('name'=>'Masterfakultas'),
);

$this->menu=array(
	array('label'=>'List Masterfakultas', 'url'=>array('index')),
	array('label'=>'Create Masterfakultas', 'url'=>array('create')),
	array('label'=>'Update Masterfakultas', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Masterfakultas', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Masterfakultas', 'url'=>array('admin')),
);
?>

<h1>View Masterfakultas #<?php echo $model->id; ?></h1>
 <?php    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
    }
?>
<div class="row">
	<div class="col-xs-12">
		
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'kode_badan_hukum',
		'kode_pt',
		'kode_fakultas',
		'nama_fakultas',
		'tgl_pendirian',
		'pejabat',
		'jabatan',
	),
)); ?>
	</div>
</div>
