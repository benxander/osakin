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


<div class="container">
	<div>
		<h1 class="mt-5 text-center"><?=$pag_din['titulo']?></h1>
		<p class="text-center"><?=$pag_din['contenido'] ?></p>
	</div>

	<div class="row">
		<? foreach($sedes AS $sede): ?>
		<div class="col-md-6">
			<div class="ficha p-3">
				<div class="row">
					<div class="col-md-3">
						<img class="w-100" src="uploads/sedes/<?=$sede['icono'] ?>" alt="">
					</div>
					<div class="col-md-9">
						<h3 class="titulo"><?= $sede['descripcion_se'] ?></h3>
						<p><?= $sede['direccion'] ?><br/>
						<?= $sede['telefono'] . ' - ' . $sede['email'] ?><br/>
						<?= $sede['horario'] ?></p>
					</div>
				</div>
				<div class="row align-items-center">
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

