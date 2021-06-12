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
<div class="modulos"></div>

<div class="container mt-5">
	<div class="row">
			<div class="col-md-3">
				<div class="" style="">
					<div class="text-center div_flotante">
						<? foreach ($banners_laterales as $row) :?>
							<div class="mt10 promocion">
								<a href="<?php echo $row['url'] ?>">
									<img src="<?= base_url($row['imagen'])  ?>" alt="<?= $row['titulo'] ?>">
								</a>
							</div>
						<? endforeach ?>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div>
					<h2 class="text-principal text-center"><?= $sede['titulo'] ?></h2>

					<p><?= $sede['descripcion'] ?></p>
				</div>

				<div>
					<h2 class="text-principal text-center"><?= $this->lang->line('servicios'); ?></h2>

					<div class="row mt-5">
						<? foreach ($sede['servicios'] as $item): ?>
							<div class="col-md-4 mb-5">
								<div class="ficha text-center">
									<a href="<?= site_url('servicio/' . url_title(convert_accented_characters(($item['servicio'] . '-' . $item['id'])),'-',TRUE));?>">
										<img style="width:10rem" src="<?= base_url('uploads/servicios/iconos/' . $item['icono']) ?>" alt="">
										<h4 class="text-center mt-2" style="color: #1C3B85"><?= $item['servicio'] ?></h4>

									</a>
								</div>
							</div>
						<? endforeach; ?>
					</div>
				</div>

				<div>
					<h2 class="text-principal text-center"><?= $this->lang->line('ubicacion'); ?></h2>

					<div>
						<?= $sede['ubicacion'] ?>
					</div>

				</div>
			</div>
	</div>
</div>