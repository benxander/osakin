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

<div class="container mt-5 modulos">
	<div class="row pt-lg pb-lg pl pr">
		<?if($servicio['estado_ss'] == 1):?>
			<div class="col-lg-8">
				<div class="row">
					<h3 class="col-sm-12 titulo"><?=$servicio['titulo']?></h3>

					<hr style="border-color: #2D62A9;" />
					<div class="col-md-3 mt-xl">

						<? if( !empty($fotos) ):
						$x=0;?>
						<?foreach($fotos as $foto):?>
							<?if($x === 0):?>
							<a class="fancybox" rel="gallery" href="<?=base_url();?>uploads/servicios/thumbs/<?=$foto['foto'];?>" >
								<img src="<?=base_url();?>uploads/servicios/thumbs/<?=$foto['foto'];?>" border="0" class="thumbnail-galeria" style="width: 100%"/>
							</a>
							<?else:?>
							<a class="fancybox" rel="gallery" href="<?=base_url();?>uploads/servicios/thumbs/<?=$foto['foto'];?>" >
								<img src="<?=base_url();?>uploads/servicios/thumbs/<?=$foto['foto'];?>" border="0" style="display:none"/>
							</a>
							<?endif;?>
							<?$x++;?>
						<?endforeach;?>
						<?if($x > 1):?>
							<br>
							<div class="text-center"  style="font-size: 0.7rem; line-height: 0.6rem; margin-top: 0.3rem; color: #666;"><?php echo $this->lang->line('imagen_ver_mas') ?></div>
						<?endif; endif; ?>

						<div class="mt-5">
							<a href="<?php echo base_url('centro/'.$sede_url) ?>">
								<img src="<?php echo base_url('assets/images/icon-flecha.svg') ?>" alt="" style="width:100%">
								<h5 class="text-center"><?= $this->lang->line('volver'); ?></h5>
							</a>
						</div>
					</div>

					<div class="col-md-9 mt-xl">
						<?=$servicio['descripcion']?>
					</div>

				</div>

				<div class="row">
					<? if(!empty($servicio['codigo_vimeo'])): ?>
						<div class="col-sm-12 text-center video-responsive" style="min-height: 90px;">
							<iframe src="https://player.vimeo.com/video/<?=$servicio['codigo_vimeo']?>" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
						</div>
					<? endif; ?>
					<? if(!empty($servicio['codigo_youtube'])): ?>
						<div class="col-sm-12 text-center video-responsive" style="min-height: 90px;">
							<iframe width="560" height="315" src="https://www.youtube.com/embed/<?=$servicio['codigo_youtube']?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					<? endif; ?>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="fondo_gris p-3 text-center">
					<h5><?= $this->lang->line('solicitar'); ?></h5>
					<h3><?= $this->lang->line('cita_previa'); ?></h3>
					<form action="" method="post">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="icon-user"></i></span>
							</div>
							<input
								id="nombre"
								class="form-control"
								type="text"
								name="nombre"
								placeholder="<?= $this->lang->line('nombre'); ?>"
								required="required"
							>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="icon-mail"></i></span>
							</div>
							<input
								id="email"
								type="email"
								name="email"
								class="form-control"
								placeholder="<?= $this->lang->line('correo'); ?>"
								required="required"
							>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="icon-phone2"></i></span>
							</div>
							<input
								id="telefono"
								type="tel"
								name="telefono"
								class="form-control"
								placeholder="<?= $this->lang->line('telefono'); ?>"
								required="required"
							>
						</div>

						<div class="form-group">
							<label for="comment"><?= $this->lang->line('mensaje'); ?>:</label>
							<textarea id="mensaje" name="mensaje" class="form-control" rows="5" id="comment"></textarea>
						</div>

						<div class="checkbox" style="margin-bottom: 1rem;">
							<input
								style="width: 15px;"
								type="checkbox"
								name="politica_privacidad"
								id="terminos"
								class="checkbox"
								value=""
								required="required"
							/>
							<?php if($idioma === 'CAS' ): ?>
								<p style="font-size: 0.8rem;">He leído la <a rel="shadowbox;width=860;height=600;" href="<?=site_url('politica-de-privacidad')?>" target="_blank">información sobre protección de datos </a></p>
							<?php else: ?>
								<p style="font-size: 0.8rem;"> <a rel="shadowbox;width=860;height=600;" href="<?=site_url('politica-de-privacidad')?>" target="_blank">Datuen babesari buruzko informazioa </a>irakurri dut.</p>
							<?php endif; ?>
						</div>

						<input type="hidden" id="servicio" value="<?= $servicio['servicio'] ?>" />
						<input type="hidden" id="sede" value="<?= $servicio['descripcion_se'] ?>" />

						<button type="button" class="btn btn-primary" onclick="envio(); return false;"><?= $this->lang->line('enviar'); ?></button>
						<div id="resultado" class="text-success"></div>
						<div id="error" class="text-danger"></div>
					</form>
				</div>
				<?php if( !empty($servicio['telefono_contacto']) ): ?>
					<div class="mt-3 text-center">
						<a href="https://api.whatsapp.com/send?phone=34<?php echo $servicio['telefono_contacto'] ?>" target="_blank">
							<img src="<?php echo $servicio['btnWhatsapp'] ?>" alt="btnWhatsapp" style="width: 60%">
						</a>
					</div>
				<?php endif; ?>

				<div class="compartir">
					<span class="social-caption"><?= $this->lang->line('compartir'); ?></span>
					<ul class="social-list">

					<li>
						<a class="" href="javascript:window.open('https://www.facebook.com/sharer/sharer.php?u='+document.URL,'','width=600,height=400,left=50,top=50,toolbar=yes');void 0"><img title="Facebook" alt="1x1.trans" width="45" height="45" src="<?=base_url()?>assets/images/iconos/facebook.png" data-lazy-loaded="true" style="display: inline;"></a>
					</li>

					<!-- <li>
						<a class="" href="javascript:window.open('https://plus.google.com/share?url='+document.URL,'','width=600,height=400,left=50,top=50,toolbar=yes');void 0"><img title="Google" alt="1x1.trans" width="45" height="45" src="<?=base_url()?>assets/images/iconos/google.png" style="display: inline;"></a>
					</li> -->
					<li>
					<a class="" href="javascript:window.open('https://twitter.com/?status=Me+gusta+'+document.URL,'','width=600,height=400,left=50,top=50,toolbar=yes');void 0"><img title="Twitter" alt="1x1.trans" width="45" height="45" src="<?=base_url()?>assets/images/iconos/twitter.png"></a>
					</li>

					</ul>
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

