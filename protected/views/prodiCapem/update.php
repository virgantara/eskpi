 <?php
$this->breadcrumbs=array(
	array('name'=>'Prodi Capem','url'=>array('index')),
	array('name'=>'Update'),
);

?>



<style>
	.errorMessage, .errorSummary{
		color:red;
	}
</style>
<div class="row">
	<div class="col-xs-12">
<?php $this->renderPartial('_form', [
	'model'=>$model,
	'params' => $params
]); ?>
	</div>
</div>
