 <?php
$this->breadcrumbs=array(
	array('name'=>'Prodi Capem','url'=>array('admin')),
	array('name'=>'Manage'),
);

?>
<h1>Manage Prodi Capem</h1>

<script type="text/javascript">
	$(document).ready(function(){
		$('#search,#size').change(function(){
			$('#prodi-capem-grid').yiiGridView.update('prodi-capem-grid', {
			    url:'<?php echo Yii::app()->createUrl("ProdiCapem/admin"); ?>&filter='+$('#search').val()+'&size='+$('#size').val()
			});
		});
		
	});
</script>
<div class="row">
    <div class="col-xs-12">
        
<?php    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
    }
?>
<div class="pull-left"> <?php	echo CHtml::link('Tambah Baru',['ProdiCapem/create'],['class'=>'btn btn-success']);
?></div>


                         <div class="pull-right">Data per halaman
                              <?php                            echo CHtml::dropDownList('ProdiCapem[PAGE_SIZE]',isset($_GET['size'])?$_GET['size']:'',[10=>10,50=>50,100=>100],['id'=>'size']); 
                            ?> 
                             <?php        echo CHtml::textField('ProdiCapem[SEARCH]','',['placeholder'=>'Cari','id'=>'search']);
        ?> 	 </div>
                    
<?php $this->widget('application.components.ComplexGridView', array(
	'id'=>'prodi-capem-grid',
	'dataProvider'=>$model->search(),
	
	'columns'=>array(
	array(
		'header'=>'No',
		'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)'
		),
		'id',
		'prodi_id',
		'capem_kategori_id',
		'label',
		'label_en',
		'created_at',
		/*
		'updated_at',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
	'htmlOptions'=>array(
		'class'=>'table'
	),
	'pager'=>array(
		'class'=>'SimplePager',
		'header'=>'',
		'firstPageLabel'=>'Pertama',
		'prevPageLabel'=>'Sebelumnya',
		'nextPageLabel'=>'Selanjutnya',
		'lastPageLabel'=>'Terakhir',
		'firstPageCssClass'=>'btn btn-info btn-sm',
		'previousPageCssClass'=>'btn btn-info btn-sm',
		'nextPageCssClass'=>'btn btn-info btn-sm',
		'lastPageCssClass'=>'btn btn-info btn-sm',
		'hiddenPageCssClass'=>'disabled',
		'internalPageCssClass'=>'btn btn-info btn-sm',
		'selectedPageCssClass'=>'btn btn-sky btn-sm',
		'maxButtonCount'=>5
	),
	'itemsCssClass'=>'table  table-bordered table-hover',
	'summaryCssClass'=>'table-message-info',
	'filterCssClass'=>'filter',
	'summaryText'=>'menampilkan {start} - {end} dari {count} data',
	'template'=>'{items}{summary}{pager}',
	'emptyText'=>'Data tidak ditemukan',
	'pagerCssClass'=>'pagination pull-right no-margin',
)); ?>


	</div>
</div>

