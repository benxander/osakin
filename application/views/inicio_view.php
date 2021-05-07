<header>
<!-- Carrusel -->
	<div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<img class="w-100" src="<?=base_url()?>uploads/banner/BANNER-1.jpg" alt="BANNER-1">
				<div class="caja-slide p-3 text-center">
					<h1 class="text-white"><?=$info_mensaje?></h1>
					<a href="<?= $info_url?>"><?=$info_btn?></a>
				</div>
			</div>
			<div class="carousel-item">
				<img class="w-100" src="<?=base_url()?>uploads/banner/BANNER-2.jpg" alt="BANNER-2">
				<div class="caja-slide p-3 text-center">
					<h1 class="text-white"><?=$info_mensaje?></h1>
					<a href="<?= $info_url?>"><?=$info_btn?></a>
				</div>
			</div>


		</div>
		<!-- <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a> -->
	</div>
</header>


<div class="container">
	<h1 class="mt-5 text-center"><?=$pag_din['titulo']?></h1>
	<p class="text-center"><?=$pag_din['contenido'] ?></p>
</div>

