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


	<style>
		.bd-placeholder-img-lg {
    		font-size: 3.5rem;
		}
		.bd-placeholder-img {
    		text-anchor: middle;
		}
	</style>
</head>
<body>

	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="/">
			<img src="<?=base_url('uploads/' . $config['imagen']['logo_header'])?>" alt="" style="max-width: 60px;">
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
	<div class="">

		<?php $this->load->view($vista) ?>

	</div>
	<div class="btn-group" style="position: fixed; right: 0px; top: 50vh; z-index: 100; display: none;">
		<button type="button" class="btn btn-share dropdown-toggle" data-toggle="dropdown"> <i class="icon-share"></i> <span class="caret"></span></button>

		<ul class="dropdown-menu dropdown-menu-right floating-icons" id="#iconosSocialMedia" role="menu">
			<a class="icon-little fb" onclick="javascript:window.open('https://www.facebook.com/sharer/sharer.php?u='+document.URL,'','width=600,height=400,left=50,top=50,toolbar=yes');return false" title="Compartir en Facebook"><i class="icon-fonts icon-facebook2"></i>Facebook</a>

			<a class="icon-little instagram" onclick="javascript:window.open('https://www.instagram.com/share?text=esGratuito.&amp;url='+document.URL+'','Comparte en Instagram','location=no,toolbar=no,width=350,height=350');return false" title="Compartir en Instagram"><i class="icon-fonts icon-instagram2"></i>Instagram</a>

		</ul>
	</div>
	<!-- Footer -->
	<footer class="footer mt-5">

		<div id="creditos">
			<div class="text-center">

					<a href="<?php echo $config['redes']['facebook'] ?>" target="_blank">
						<i class="icon-facebook" style="font-size:33px"></i>
					</a>
					<a href="<?php echo $config['redes']['instagram'] ?>" target="_blank">
						<i class="icon-instagram" style="font-size:33px"></i>
					</a>


			</div>

			<div class="text-center">
				<p><a title="<?= $this->lang->line('aviso_legal') ?>" rel="shadowbox" href="<?=site_url('aviso-legal')?>"><?= $this->lang->line('aviso_legal') ?></a> - &copy; <?=date('Y')?> <?=SITIO_WEB?> - <?= $this->lang->line('design') ?>: <a href="https://www.hementxe.com">Hementxe Comunicación</a></p>

				<!--<a href="/es/sitemap/">Sitemap</a>--><br>

			</div>


		</div>
		<!-- <a href="#arriba" id="toTop" class="flecha scroll"><i class="fa fa-chevron-up"></i></a> -->
	</footer>

	<!-- jQuery and JS bundle w/ Popper.js -->
	<!-- <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery/jquery-1.11.0.min.js"></script> -->

	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

	<script type="text/javascript" src="<?= base_url() ?>assets/js/shadowbox/shadowbox.js"></script>
	<script src="<?= base_url()?>assets/js/main.js"></script>

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
		// var altura = window.innerHeight;
		$(function(){
			margin = 50;
			posicionInicial = 0;
			dom = {}
			st = {
				stickyElement: '.div_flotante',
				modulo : '.modulos',
				footer : 'footer'
			};
			catchDom = function(){
				dom.stickyElement = $(st.stickyElement);
				dom.modulo = $(st.modulo);
				dom.footer = $(st.footer);
			}
			afterCatchDom = function(){
				functions.ubicarPosicionInicial()
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
			functions = {
				ubicarPosicionInicial : function(){
					var newPosition = $(window).height() + margin;
					$(st.stickyElement).css('top', newPosition + "px");
					posicionInicial = newPosition;
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