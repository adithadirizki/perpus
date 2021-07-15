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
		<div class="main-header">
			<!-- Logo Header -->
			<div class="logo-header" data-background-color="blue">
				<a href="<?= base_url() ?>" class="logo">
					<img src="<?= base_url('assets/img/' . session()->logo) ?>" alt="navbar brand" class="navbar-brand">
				</a>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
			</div>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue">

				<div class="container-fluid">
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item">
							<a class="nav-link" href="<?= base_url('pinjaman') ?>">
								<i class="fas fa-ticket-alt"></i>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?= base_url('keranjang') ?>">
								<i class="fas fa-shopping-cart"></i>
								<span class="notification bg-danger cart"><?= isset($keranjang) ? $keranjang : 0 ?></span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link logout" href="<?= base_url('logout') ?>">
								<i class="fas fa-power-off"></i>
							</a>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link profile-pic" href="<?= base_url('profile') ?>">
								<div class="avatar-sm">
									<img src="<?= base_url('assets/img/' . session()->foto) ?>" alt="Profile" class="avatar-img rounded-circle">
								</div>
							</a>
						</li>
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
		</div>

		<?php
		$item = [
			'dashboard' => '',
			'user' => '',
			'buku' => '',
			'kategori' => '',
			'pinjam' => '',
			'denda' => '',
			'pengaturan' => '',
			'laporan' => '',
			'laporan_user' => '',
			'laporan_pinjaman' => '',
			'laporan_denda' => ''
		];
		switch ($title) {
			case 'Dashboard':
				$item['dashboard'] = 'active';
				break;
			case 'Daftar User':
			case 'Tambah User':
			case 'Detail User':
				$item['user'] = 'active';
				break;
			case 'Daftar Buku':
			case 'Tambah User':
			case 'Detail User':
				$item['buku'] = 'active';
				break;
			case 'Kategori':
			case 'Tambah Kategori':
			case 'Detail Kategori':
				$item['kategori'] = 'active';
				break;
			case 'Daftar Pinjaman':
			case 'Tambah Pinjaman':
			case 'Detail Pinjaman':
				$item['pinjam'] = 'active';
				break;
			case 'Daftar Denda':
			case 'Tambah Denda':
			case 'Detail Denda':
				$item['denda'] = 'active';
				break;
			case 'Pengaturan':
				$item['pengaturan'] = 'active';
				break;
			case 'Laporan User':
				$item['laporan'] = 'active submenu';
				$item['laporan_user'] = 'active';
				break;
			case 'Laporan Pinjaman':
				$item['laporan'] = 'active submenu';
				$item['laporan_pinjaman'] = 'active';
				break;
			case 'Laporan Denda':
				$item['laporan'] = 'active submenu';
				$item['laporan_denda'] = 'active';
				break;
			default:
				$item['dashboard'] = 'active';
				break;
		}
		?>

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

	<!-- Datatables -->
	<script src="<?= base_url('assets/js/plugin/datatables/datatables.min.js') ?>"></script>

	<!-- Atlantis JS -->
	<script src="<?= base_url('assets/js/atlantis.min.js') ?>"></script>

	<?= $this->renderSection('footer') ?>

	<script>
		$(document).on('click', '.logout', function() {
			swal({
				title: "Peringatan!",
				text: "Yakin ingin keluar dari akun ini ?",
				icon: "warning",
				buttons: {
					confirm: {
						text: "Ya, keluar!",
						className: "btn btn-success"
					},
					cancel: {
						visible: true,
						text: "Batal",
						className: "btn btn-danger"
					}
				}
			}).then((result) => {
				if (result) {
					window.location.href = $(this).attr('href');
				}
			})
			return false;
		})
	</script>
</body>

</html>