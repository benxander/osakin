<header>
<!-- Carrusel -->
	<div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
		<div class="carousel-inner">
			<? foreach ($banners as $banner):?>
			<div class="carousel-item <?= $banner['activo'] ?>">
				<img class="w-100" src="<?=base_url($banner['imagen'])?>" alt="<?= $banner['titulo'] ?>">
				<div class="caja-slide p-3 text-center">
					<h1 class="text-white"><?=$info_mensaje?></h1>
					<a href="<?= $info_url?>"><?=$info_btn?></a>
				</div>
			</div>
			<? endforeach; ?>
		</div>
	</div>
</header>


<div class="container modulos">
	<div>
		<h1 class="mt-5 text-center"><?=$pag_din['titulo']?></h1>
		<p class="text-center"><?=$pag_din['contenido'] ?></p>
	</div>

	<div class="row">
		<? foreach($sedes AS $sede): ?>
		<div class="col-md-6">
			<div class="ficha p-3" onClick="window.location.href='<?= base_url('centro/'.$sede['segmento_amigable']) ?>'">
				<div class="row" style="min-height: 250px;">
					<div class="col-md-3">
						<img class="w-100" src="uploads/sedes/<?=$sede['icono'] ?>" alt="">
					</div>
					<div class="col-md-9">
						<h3 class="titulo"><?= $sede['descripcion_se'] ?></h3>
						<div class="row">
							<div class="col-xs-1"><i class="icon-location2"></i></div>
							<div class="col-xs-11 ml-2"><?= $sede['direccion'] ?></div>
						</div>

						<div class="row">
							<div class="col-xs-12 ml-4" style="min-height: 27px;"><?= $sede['direccion2'] ?></div>
						</div>

						<div class="row">
							<div class="col-xs-1"><i class="icon-phone"></i></div>
							<div class="col-xs-11 ml-2"><?= $sede['telefono']  ?></div>
						</div>

						<div class="row">
							<div class="col-xs-1"><i class="icon-mail"></i></div>
							<div class="col-xs-11 ml-2"><?= $sede['email'] ?></div>
						</div>

						<div class="row">
							<div class="col-xs-1"><i class="icon-clock2"></i></div>
							<div class="col-xs-11 ml-2 horario"><?= $sede['horario'] ?></div>
						</div>


					</div>
				</div>
				<div class="row mt-3">
					<div class="col-md-6">
						<img class="w-100" src="uploads/sedes/<?=$sede['imagen_se'] ?>" alt="">
					</div>
					<div class="col-md-6">
						<div class="servicios">
							<ul class="px-4 pt-4 mb-2">
								<? foreach ($sede['servicios'] as $row): ?>
									<li><?=$row['servicio'] ?></li>
								<? endforeach; ?>
							</ul>
							<div class="text-center">
								<a class="btn font-weight-bold" href="<?= base_url('centro/'.$sede['segmento_amigable']) ?>"><?=$this->lang->line('info_ver_centro') . ' ' . $sede['descripcion_se'] ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<? endforeach; ?>
	</div>
</div>

