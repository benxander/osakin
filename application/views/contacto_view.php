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
	<h3><?= $this->lang->line('contacto'); ?></h3>
	<hr class="separetor">
	<div class="row">
		<div class="col-lg-8">
			<div class="boxed-grey">
				<form class="validate" id="contact-form" method="post" action="javascript:">
					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
								<label for="nombre"><?= $this->lang->line('nombre'); ?></label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="icon-user"></i></span>
									</div>
									<input
										id="nombre"
										type="text"
										class="form-control"
										name="nombre"
										placeholder="<?= $this->lang->line('ingrese_nombre'); ?>"
										required="required"
									/>
								</div>
							</div>
							<div class="form-group">
								<label for="email"><?= $this->lang->line('correo'); ?></label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="icon-mail"></i></span>
									</div>
									<input
										id="email"
										type="email"
										class="form-control"
										name="email"
										placeholder="<?= $this->lang->line('ingrese_correo'); ?>"
										required="required"
									/>
								</div>
							</div>
							<div class="form-group">
								<label for="asunto"><?= $this->lang->line('asunto'); ?></label>
								<select id="asunto" name="asunto" class="form-control" required="required">
									<option value="" selected=""><?= $this->lang->line('elija'); ?></option>
									<option value="Consultas Generales"><?= $this->lang->line('consultas_generales'); ?></option>
									<option value="Sugerencias"><?= $this->lang->line('sugerencias'); ?></option>
									<option value="Cita previa"><?= $this->lang->line('cita_previa'); ?></option>
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="mensaje"><?= $this->lang->line('mensaje'); ?></label>
								<textarea
									id="mensaje"
									name="mensaje"
									class="form-control"
									rows="9"
									cols="25"
									placeholder="<?= $this->lang->line('ingrese_mensaje'); ?>"
									required="required"
								></textarea>
							</div>
						</div>

						<div class="col-md-10">

						</div>

						<div class="col-md-10 checkbox" style="padding-left: 40px;">
							<input
								id="terminos"
								style="width: 15px;"
								type="checkbox"
								name="politica_privacidad"
								class="checkbox"
								value=""
								required="required"
							/>
							<?php if($idioma === 'CAS' ): ?>
								<p>Acepto la <a rel="shadowbox;width=860;height=600;" href="<?=site_url('politica-de-privacidad')?>" target="_blank">pol??tica de privacidad </a> de <?=SITIO_WEB?></p>
							<?php else: ?>
								<p>Onartzen dut <?=SITIO_WEB?> <a rel="shadowbox;width=860;height=600;" href="<?=site_url('politica-de-privacidad')?>" target="_blank">pribatutasun politika </a></p>
							<?php endif; ?>
						</div>
						<input type="hidden" id="sede" value="<?= $sede['descripcion_se'] ?>" />
						<div class="col-md-12 mt-2 text-center">
							<button type="submit" class="btn btn-info pull-right" id="btnContactUs"><?= $this->lang->line('enviar'); ?></button>
						</div>
						<div id="errores" class="text-danger"></div>
						<div id="resultado" class="text-success"></div>
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