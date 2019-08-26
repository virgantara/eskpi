<?php
/* @var $this ProdiCapemController */
/* @var $model ProdiCapem */

$this->breadcrumbs=array(
	array('name'=>'Prodi Capem','url'=>array('admin')),
	array('name'=>'Prodi Capem'),
);

$this->menu=array(
	array('label'=>'List ProdiCapem', 'url'=>array('index')),
	array('label'=>'Create ProdiCapem', 'url'=>array('create')),
	array('label'=>'Update ProdiCapem', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ProdiCapem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ProdiCapem', 'url'=>array('admin')),
);
?>

<h1>View ProdiCapem #<?php echo $model->id; ?></h1>
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
		'prodi_id',
		'capem_kategori_id',
		'label',
		'label_en',
		'created_at',
		'updated_at',
	),
)); ?>
	</div>
</div>
