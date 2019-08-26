<?php
/* @var $this UnivController */
/* @var $model Univ */

$this->breadcrumbs=array(
	array('name'=>'Univ','url'=>array('admin')),
	array('name'=>'Univ'),
);

$this->menu=array(
	array('label'=>'List Univ', 'url'=>array('index')),
	array('label'=>'Create Univ', 'url'=>array('create')),
	array('label'=>'Update Univ', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Univ', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Univ', 'url'=>array('admin')),
);
?>

<h1>View Univ #<?php echo $model->id; ?></h1>
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
		'nama',
		'nama_en',
		'created_at',
		'updated_at',
	),
)); ?>
	</div>
</div>
