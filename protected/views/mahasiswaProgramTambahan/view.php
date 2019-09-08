<?php
/* @var $this MahasiswaProgramTambahanController */
/* @var $model MahasiswaProgramTambahan */

$this->breadcrumbs=array(
	array('name'=>'Mahasiswa Program Tambahan','url'=>array('admin')),
	array('name'=>'Mahasiswa Program Tambahan'),
);

$this->menu=array(
	array('label'=>'List MahasiswaProgramTambahan', 'url'=>array('index')),
	array('label'=>'Create MahasiswaProgramTambahan', 'url'=>array('create')),
	array('label'=>'Update MahasiswaProgramTambahan', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MahasiswaProgramTambahan', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MahasiswaProgramTambahan', 'url'=>array('admin')),
);
?>

<h1>View MahasiswaProgramTambahan #<?php echo $model->id; ?></h1>
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
		'nim',
		'nama',
		'nama_en',
		'durasi',
		'created_at',
		'updated_at',
	),
)); ?>
	</div>
</div>
