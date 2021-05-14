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

		<iframe allowfullscreen="" frameborder="0" height="450" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d11613.244049928804!2d-1.980978!3d43.3077484!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xa12448603421f8d8!2sSIDRERIA+SALABERRIA!5e0!3m2!1ses-419!2spe!4v1560657825106!5m2!1ses-419!2spe" style="border:0" width="100%"></iframe></div>
	</div>
</div>