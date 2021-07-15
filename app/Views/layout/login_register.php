<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?= $title ?> <?= session()->nama_app != null ? ' - '.session()->nama_app : '' ?></title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="<?= base_url('assets/img/' . session()->logo) ?>" type="image/x-icon" />

	<!-- Fonts and icons -->
	<script src="<?= base_url('assets/js/plugin/webfont/webfont.min.js') ?>"></script>
	<script>
		WebFont.load({
			google: {
				"families": ["Lato:300,400,700,900"]
			},
			custom: {
				"families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
				urls: ['<?= base_url('assets/css/fonts.min.css') ?>']
			},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/atlantis.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body>
	<div class="wrapper">
		<div class="main-panel w-100">
			<div class="content">
				<?= $this->renderSection('content') ?>
			</div>
			<footer class="footer">
				<div class="container-fluid">
					<div class="copyright ml-auto">
						Copyright <i class="fa fa-heart heart text-danger"></i> <a href="<?= base_url() ?>" class="font-weight-bold">AHR</a> 2020
					</div>
				</div>
			</footer>
		</div>
	</div>
	<div id="loading" class="loading-smooth">
		<div class="body-loader-smooth">
			<div class="loader-smooth"></div>
			<div class="text-center mt-2 text-white">Loading ...</div>
		</div>
	</div>
	<!--   Core JS Files   -->
	<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/core/bootstrap.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') ?>"></script>

	<!-- Sweet Alert -->
	<script src="<?= base_url('assets/js/plugin/sweetalert/sweetalert.min.js') ?>"></script>

	<!-- Atlantis JS -->
	<script src="<?= base_url('assets/js/atlantis.min.js') ?>"></script>

	<?= $this->renderSection('footer') ?>
</body>

</html>