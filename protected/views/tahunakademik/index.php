 <?php
$this->breadcrumbs=array(
	array('name'=>'Tahunakademik','url'=>['index']),
	array('name'=>'Manage'),
);

?>
<h1>Manage Tahunakademik</h1>

<script type="text/javascript">
	$(document).ready(function(){
		$('#search,#size').change(function(){
			$('#tahunakademik-grid').yiiGridView.update('tahunakademik-grid', {
			    url:'<?php echo Yii::app()->createUrl("Tahunakademik/index"); ?>&filter='+$('#search').val()+'&size='+$('#size').val()
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
                        <div class="pull-left"> <?php	echo CHtml::link('Tambah Baru',array('Tahunakademik/create'),['class'=>'btn btn-success']);
?></div>


                         <div class="pull-right">Data per halaman
                              <?php                            echo CHtml::dropDownList('Tahunakademik[PAGE_SIZE]',isset($_GET['size'])?$_GET['size']:'',[10=>10,50=>50,100=>100,],['id'=>'size']); 
                            ?> 
                             <?php        echo CHtml::textField('Tahunakademik[SEARCH]','',['placeholder'=>'Cari','id'=>'search']);
        ?> 	 </div>
                    
<?php $this->widget('application.components.ComplexGridView', array(
	'id'=>'tahunakademik-grid',
	'dataProvider'=>$model->search(),
	
	'columns'=>[
	[
		'header'=>'No',
		'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)'
	],
		'id',
		'tahun_id',
		'tahun',
		'semester',
		'nama_tahun',
		'buka',
		[
			'class'=>'CButtonColumn',
		],
	],
	'htmlOptions'=>[
		'class'=>'table'
	],
	'pager'=>[
		'class'=>'SimplePager',
		'header'=>'',
		'firstPageLabel'=>'Pertama',
		'prevPageLabel'=>'Sebelumnya',
		'nextPageLabel'=>'Selanjutnya',
		'lastPageLabel'=>'Terakhir',
		'firstPageCssClass'=>'btn btn-info',
		'previousPageCssClass'=>'btn btn-info',
		'nextPageCssClass'=>'btn btn-info',
		'lastPageCssClass'=>'btn btn-info',
		'hiddenPageCssClass'=>'disabled',
		'internalPageCssClass'=>'btn btn-info',
		'selectedPageCssClass'=>'btn btn-sky',
		'maxButtonCount'=>5
	],
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

