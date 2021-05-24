<header>
<!-- Carrusel -->
	<div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
		<div class="carousel-inner">
			<? foreach ($banners as $banner):?>
			<div class="carousel-item <?= $banner['activo'] ?>">
				<img class="w-100" src="<?=base_url($banner['imagen'])?>" alt="<?= $banner['titulo'] ?>">

			</div>
			<? endforeach; ?>
		</div>
	</div>
</header>
<link rel="stylesheet" href="<?=base_url();?>assets/js/fancybox/css/jquery.fancybox.css" />
<div class="container mt-5">
	<div class="row pt-lg pb-lg pl pr">
		<?if($servicio['estado_ss'] == 1):?>
			<div class="col-lg-9">
				<div class="row">
					<h3 class="col-sm-12 titulo"><?=$servicio['titulo']?></h3>

					<hr style="border-color: #2D62A9;" />
					<div class="col-md-3 mt-xl">

						<? if( !empty($fotos) ):
						$x=0;?>
						<?foreach($fotos as $foto):?>
							<?if($x === 0):?>
							<a class="fancybox" rel="gallery" href="<?=base_url();?>uploads/servicios/<?=$foto['foto'];?>" >
								<img src="<?=base_url();?>uploads/servicios/thumbs/<?=$foto['foto'];?>" border="0" class="thumbnail-galeria" style="width: 100%"/>
							</a>
							<?else:?>
							<a class="fancybox" rel="gallery" href="<?=base_url();?>uploads/anuncios/<?=$foto['foto'];?>" >
								<img src="<?=base_url();?>uploads/servicios/thumbs/<?=$foto['foto'];?>" border="0" style="display:none"/>
							</a>
							<?endif;?>
							<?$x++;?>
						<?endforeach;?>
						<?if($x > 1):?>
							<br>
							<span>Click en la imagen para ver mas</span>
						<?endif; endif; ?>
					</div>

					<div class="col-md-9 mt-xl">
						<?=$servicio['descripcion']?>
					</div>

				</div>
			</div>

			<div class="col-lg-3">
				<div class="ficha fondo_gris p-3 text-center">
					<h5><?= $this->lang->line('solicitar'); ?></h5>
					<h3><?= $this->lang->line('cita_previa'); ?></h3>
					<form action="" method="post">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="icon-user"></i></span>
							</div>
							<input type="text" class="form-control" placeholder="<?= $this->lang->line('nombre'); ?>">
						</div>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="icon-mail"></i></span>
							</div>
							<input type="email" class="form-control" placeholder="<?= $this->lang->line('correo'); ?>">
						</div>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="icon-phone2"></i></span>
							</div>
							<input type="tel" class="form-control" placeholder="<?= $this->lang->line('telefono'); ?>">
						</div>

						<div class="form-group">
							<label for="comment"><?= $this->lang->line('mensaje'); ?>:</label>
							<textarea class="form-control" rows="5" id="comment"></textarea>
						</div>
						<button type="button" class="btn btn-primary"><?= $this->lang->line('enviar'); ?></button>
					</form>
				</div>
			</div>


		<?else:?>
			<?if(empty($servicio)):?>
				<span class="waterMarkEmptyData">ESTA FICHA NO EXISTE</span>
			<?else:?>
				<h3 class="titulo"><?=$servicio['nombre_serv']?></h3>
				<span class="waterMarkEmptyData">ESTA FICHA NO ESTÁ ACTIVA</span>
			<?endif;?>
		<?endif;?>
	</div>
</div>

