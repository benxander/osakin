<?php
	$config = getConfig();
	$siteLang = $this->session->userdata('site_lang');
	$idioma = $siteLang == 'euskera' ? 'EUS' : 'CAS';
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $config['metadata']['titulo-web'] ?></title>
	<meta name="description" content="<?php echo $config['metadata']['meta-description'] ?>">
	<meta name="keywords" content="<?php echo $config['metadata']['meta-keywords'] ?>">
	<meta name="author" content="<?php echo $config['metadata']['meta-author'] ?>" />
	<link rel="canonical" href="" />
	<link rel="shortcut icon" href="<?php echo base_url() . 'uploads/' . $config['imagen']['favicon']?>">

	<!-- CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="<?= base_url()?>assets/css/custom.css">
	<link rel="stylesheet" href="<?= base_url()?>assets/css/fonts/icomoon/style.css">
	<link rel="stylesheet" href="<?= base_url()?>assets/js/shadowbox/shadowbox.css">


</head>
<body>

	<nav id="navegacion" class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="/">
			<img src="<?=base_url('uploads/' . $config['imagen']['logo_header'])?>" alt="" style="max-width: 30px;">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="<?= base_url() ?>">INICIO <span class="sr-only">(current)</span></a>
				</li>
				<?php foreach ($listaMenu as $item):  ?>
					<li class="nav-item">
						<a class="nav-link" href="<?= $item['link'] ?>"><?php echo $item['descripcion'] ?></a>
					</li>
				<?php endforeach; ?>

			</ul>
			<div class="my-2 my-lg-0 idioma">
				<a href="<?=base_url('main/switchLang/EUS') ?>" class="btn btn-link my-2 my-sm-0 <?= $idioma == 'EUS'? 'active':'' ?>">EUS</a>
				<a href="<?=base_url('main/switchLang/CAS') ?>" class="btn btn-link my-2 my-sm-0 <?= $idioma == 'CAS'? 'active':'' ?>">CAS</a>
			</div>
		</div>
	</nav>


	<!-- Principal -->
	<div>

		<?php $this->load->view($vista) ?>

	</div>

	<!-- Footer -->
	<footer class="footer mt-5">

		<div id="creditos">
			<div class="text-center">

					<a href="<?php echo $config['redes']['facebook'] ?>" target="_blank">
						<i class="icon-facebook" style="font-size:33px"></i>
					</a>
					<a href="<?php echo $config['redes']['youtube'] ?>" target="_blank">
						<i class="icon-youtube" style="font-size:33px"></i>
					</a>
					<a href="<?php echo $config['redes']['instagram'] ?>" target="_blank">
						<i class="icon-instagram" style="font-size:33px"></i>
					</a>


			</div>

			<div class="text-center mt-3">
				<p><a title="<?= $this->lang->line('aviso_legal') ?>" rel="shadowbox" href="<?=site_url('aviso-legal')?>"><?= $this->lang->line('aviso_legal') ?></a> - &copy; <?=date('Y')?> <?=SITIO_WEB?> - <?= $this->lang->line('design') ?>: <a href="https://www.hementxe.com">Hementxe Comunicación</a></p>

				<!--<a href="/es/sitemap/">Sitemap</a>--><br>

			</div>


		</div>
		<!-- <a href="#arriba" id="toTop" class="flecha scroll"><i class="fa fa-chevron-up"></i></a> -->
	</footer>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<!-- <script
		src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
		integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
		crossorigin="anonymous"></script> -->

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

	<script type="text/javascript" src="<?= base_url() ?>assets/js/shadowbox/shadowbox.js"></script>
	<!-- <script src="<?= base_url()?>assets/js/main.js"></script> -->


	<script type="text/javascript" src="<?=base_url();?>assets/js/cookies.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			// Cookie setting script wrapper
			var cookieScripts = function () {
				// Internal javascript called
				console.log("Running");

				// Loading external javascript file
				$.cookiesDirective.loadScript({
					uri:'external.js',
					appendTo: 'eantics'
				});
			}

			$.cookiesDirective({
				explicitConsent: false,
				duration: 10,
				backgroundOpacity: '90',
				privacyPolicyUri: 'politica-de-cookies',
				inlineAction: true,
				message: '<div style="font-size:1.5em"><b>ATENCION</b></div>',
				multipleCookieScriptBeginningLabel: ' Este sitio usa scripts de ',
				and: ' y ',
				multipleCookieScriptEndLabel: ' los cuales emplean cookies.',
				impliedSubmitText: 'OK',
				impliedDisclosureText: ' Para más información, vea nuestra ',
				position : 'bottom',
				cookieScripts: 'google,session',
				scriptWrapper: cookieScripts,
				privacyPolicyLinkText: ' politica de cookies',
				backgroundColor: 'rgb(56, 169, 163)',
				linkColor: '#FDC609',
				explicitCookieDeletionWarning: ' Tu puedes borrar los cookies.<br>'
			});
		});
	</script>
	<script type="text/javascript">
		Shadowbox.init();
	</script>

	<script>
		$(function(){

			var num = 0;
			$(window).bind('scroll', function() {
				if ($(window).scrollTop() > num) {
					$('#navegacion').addClass('menu-fijo').fadeIn();
					// console.log('Alto ventana', window.innerHeight - 55);
				} else {
					$('#navegacion').removeClass('menu-fijo');
				}
			});

			// Subir hacia arriba fijo
			// var num3 = 586;
			// var num3 = window.innerHeight;
			// $(window).bind('scroll', function() {
			// 	if ($(window).scrollTop() > num3) {
			// 		$('#toTop').addClass('visible');
			// 	} else {
			// 		$('#toTop').removeClass('visible');
			// 	}
			// });
			// var altura = window.innerHeight;
			var margin = 100;
			var posicionInicial = 0;
			var dom = {}
			var st = {
				stickyElement: '.div_flotante',
				modulo : '.modulos',
				footer : 'footer'
			};
			var pos = 0;
			catchDom = function(){
				dom.stickyElement = $(st.stickyElement);
				dom.modulo = $(st.modulo);
				dom.footer = $(st.footer);
			}
			afterCatchDom = function(){
				var newPosition = $(window).height() + margin;
				$(st.stickyElement).css('top', newPosition + "px");
				posicionInicial = newPosition;
			}
			suscribeEvents = function(){
				$(window).on('scroll', events.moveStick);
			}
			events = {
				moveStick : function(){
					var w = window.innerWidth;
					if( w < 910){
						console.log('mobile', w);
						dom.stickyElement.removeClass("div_flotante");
						return;
					}else{
						dom.stickyElement.addClass("div_flotante");

					}
					windowpos = $(window).scrollTop();
					// console.log('windowpos', windowpos);
					box = dom.stickyElement;
					modulo = dom.modulo.offset();

					// console.log('modulo', modulo);

					footer = dom.footer.offset();

					if ( (box.height() + windowpos + margin) >= footer.top ) {
						// console.log('primer if');
						pos = footer.top - (box.height() + margin);
						dom.stickyElement.css({
							top: pos + "px",
							bottom: ''
						});
					}else{
						if ($(window).height() + margin  < (windowpos)) {
							pos = windowpos + margin;
							dom.stickyElement.css({
								top: pos + "px",
								bottom: ''
							});
						} else{
							pos = modulo.top;
							dom.stickyElement.css({
								top: pos + "px",
								bottom: ''
							});
						}
					}
				}
			}

			var init = function(){
				catchDom();
				afterCatchDom();
				suscribeEvents();
			};
			init();

		});
	</script>
	<?= empty($scripts)? null : $scripts ?>

</body>
</html>