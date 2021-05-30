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
<div id="contacto" class="container mt-5">
		<h3>CONTACTO</h3>
		<hr class="separetor">
	<div class="row">
		<div class="col-lg-8">
			<div class="boxed-grey">
			<form class="validate" id="contact-form" method="post" action="<?= site_url('contactar/enviar') ?>">
				<div class="row">
					<div id="errores" style="color:red"></div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="name"><?= $this->lang->line('nombre'); ?></label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="icon-user"></i></span>
								</div>
								<input type="text" class="form-control" id="name" name="nombre" placeholder="Ingrese su Nombre" required="required" />
							</div>
						</div>
						<div class="form-group">
							<label for="email"><?= $this->lang->line('correo'); ?></label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="icon-mail"></i></span>
								</div>
								<input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su correo" required="required" />
							</div>
						</div>
						<div class="form-group">
							<label for="subject">Asunto</label>
							<select id="subject" name="asunto" class="form-control" required="required">
								<option value="" selected="">Elija uno:</option>
								<option value="Consultas Generales">Consultas Generales</option>
								<option value="Sugerencias">Sugerencias</option>
								<option value="Soporte del Producto">Soporte del Producto</option>
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="name">Mensaje</label>
							<textarea name="comentario" id="message" class="form-control" rows="9" cols="25" required="required"
								placeholder="Ingrese aqui su mensaje"></textarea>
						</div>
					</div>

					<div class="col-md-10">

					</div>

					<div class="col-md-10 checkbox" style="padding-left: 40px;">
						<input style="width: 15px;" type="checkbox" name="politica_privacidad" id="politica_privacidad" class="checkbox" value="" required="required"/>
						<p>Acepto la <a rel="shadowbox;width=860;height=600;" href="<?=site_url('politica-de-privacidad')?>" target="_blank">política de privacidad </a> de <?=SITIO_WEB?></p>
					</div>

					<div class="col-md-12 mt-2 text-center">
						<button type="submit" class="btn btn-info pull-right" id="btnContactUs">Enviar</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="widget-contact">
			<h6><strong><?php echo $sede['descripcion_se'] ?></strong></h6>

			<address>
				<i class="icon-location2"></i>  <?php echo $sede['direccion']?>
			</address>

			<address>
				<i class="icon-phone"></i> <?php echo $sede['telefono'] ?>
			</address>

			<address style="font-weight: bolder;letter-spacing: 2px;">
				<i class="icon-mail"></i> <?php echo safe_mailto($sede['email'], $sede['email'] )?>
			</address>
		</div>
	</div>
</div>

</div>