<link rel="stylesheet" href="<?=base_url();?>css/fancybox/jquery.fancybox.css" />
<div class="contenedor_medio">
	<div class="row ficha fondo_gris pt-lg pb-lg pl pr">
		<?if($anuncio['estado_ficha'] == 1):?>
			<h3 class="col-sm-12 titulo"><?=$anuncio['titulo']?></h3>

			<hr style="border-color: #2D62A9;" />
			<div class="col-md-3 mt-xl">

				<?$x=0;?>
				<?foreach($fotos as $foto):?>
					<?if($x === 0):?>
					<a class="fancybox" rel="gallery" href="<?=base_url();?>uploads/anuncios/<?=$foto['foto'];?>" >
						<img src="<?=base_url();?>uploads/thumbs/<?=$foto['foto'];?>" border="0" class="thumbnail-galeria" style="width: 100%"/>
					</a>
					<?else:?>
					<a class="fancybox" rel="gallery" href="<?=base_url();?>uploads/anuncios/<?=$foto['foto'];?>" >
						<img src="<?=base_url();?>uploads/thumbs/<?=$foto['foto'];?>" border="0" style="display:none"/>
					</a>
					<?endif;?>
					<?$x++;?>
				<?endforeach;?>
				<?if($x > 1):?>
					<br>
					<span>Click en la imagen para ver mas</span>
				<?endif;?>
			</div>

			<div class="col-md-9 mt-xl">
				<?=$anuncio['descripcion']?>
			</div>
			<div class="col-md-12 mt-xl text-center">
				<? if( $anuncio['idtipoficha']  == 3 ): // video ?>
					<div class="videoWrapper">
						<iframe src="https://www.youtube.com/embed/<?=$anuncio['enlace']?>" frameborder="0" width="560" height="349" allowfullscreen></iframe>
					</div>
				<? endif; ?>
			</div>
			<div class="col-md-12 mt-xl">
				<div onclick="history.back(-1)" class="btn btn-danger">Volver</div>
				<? if( $anuncio['idtipoficha'] == 1 ): // pdf ?>
					<a rel="shadowbox" href="<?=$anuncio['enlace'] ?>"><div class="btn btn-info">Ver PDF</div></a>
				<? elseif( $anuncio['idtipoficha'] == 2 ): // calameo ?>
					<a rel="shadowbox" href="<?=$anuncio['enlace'] ?>"><div class="btn btn-info">Ver Calameo</div></a>
				<? endif; ?>
			</div>
			<div class="clear"></div>
		<?else:?>
			<?if(empty($anuncio)):?>
				<span class="waterMarkEmptyData">ESTA FICHA NO EXISTE</span>
			<?else:?>
				<h3 class="titulo"><?=$anuncio['nombre']?></h3>
				<span class="waterMarkEmptyData">ESTA FICHA NO ESTÁ ACTIVA</span>
			<?endif;?>
		<?endif;?>
	</div>
</div>

