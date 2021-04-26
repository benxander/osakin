<div class="row ficha fondo_gris pt-5">
	<?php if(empty($ficha)):?>

		<span class="waterMarkEmptyData">ESTA FICHA NO EXISTE</span>

	<?else:?>
		<?php if($ficha['estado_fi'] == 1): ?>
			<h3 class="col-sm-12 titulo"><?=$ficha['titulo']?></h3>

			<hr style="border-color: #2D62A9;" />

			<div class="col-md-3 mt-6">
				<img src="<?= $dir_imagen . $ficha['imagen'] ?>" border="0" class="thumbnail-galeria" style="width: 100%"/>
			</div>

			<div class="col-md-9 mt-6">
				<?=$ficha['descripcion']?>
			</div>
			

		<?else:?>
			<h3 class="titulo"><?=$ficha['titulo']?></h3>
			<span class="waterMarkEmptyData">ESTA FICHA NO ESTÁ ACTIVA</span>

		<?endif;?>

	<?endif;?>
	<div class="col-12 mt-4">

		<a class="btn btn-primary float-right" href="<?= base_url() ?>">Volver</a>
	</div>
</div>