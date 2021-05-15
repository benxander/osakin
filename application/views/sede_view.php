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
<div class="container mt-5">
	<div>
		<h2 class="text-danger text-center"><?= $sede['titulo'] ?></h2>

		<p><?= $sede['descripcion'] ?></p>
	</div>

	<div>
		<h2 class="text-danger text-center">SERVICIOS</h2>

		<div class="row">
			<? foreach ($sede['servicios'] as $item): ?>
				<div class="col-md-4 mb-5">
					<div class="ficha p-5">
						<img class="w-100" src="<?= base_url('uploads/servicios/' . $item['icono']) ?>" alt="">
						<h4 class="text-center"><?= $item['servicio'] ?></h4>
					</div>
				</div>
			<? endforeach; ?>
		</div>
	</div>

	<div>
		<h2 class="text-danger text-center">UBICACION</h2>

		<div>
			<?= $sede{'ubicacion'} ?>
		</div>

	</div>
</div>