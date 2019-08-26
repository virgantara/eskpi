<?php
/* @var $this CapemKategoriController */
/* @var $model CapemKategori */

$this->breadcrumbs=array(
	array('name'=>'Capem Kategori','url'=>array('admin')),
	array('name'=>'Capem Kategori'),
);

$this->menu=array(
	array('label'=>'List CapemKategori', 'url'=>array('index')),
	array('label'=>'Create CapemKategori', 'url'=>array('create')),
	array('label'=>'Update CapemKategori', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CapemKategori', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CapemKategori', 'url'=>array('admin')),
);
?>

<h1>View CapemKategori #<?php echo $model->id; ?></h1>
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
