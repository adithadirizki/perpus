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

				<a href="<?= base_url('admin') ?>" class="logo">
					<img src="<?= base_url('assets/img/' . session()->logo) ?>" alt="navbar brand" class="navbar-brand">
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
				</button>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
				<div class="nav-toggle">
					<button class="btn btn-toggle toggle-sidebar">
						<i class="icon-menu"></i>
					</button>
				</div>
			</div>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">

				<div class="container-fluid">
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item">
							<a class="nav-link logout" href="<?= base_url('logout') ?>">
								<i class="fas fa-power-off"></i>
							</a>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link profile-pic" href="<?= base_url('admin/profile') ?>">
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

		<!-- Sidebar -->
		<div class="sidebar sidebar-style-2">
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<div class="user">
						<div class="avatar-sm float-left mr-2">
							<img src="<?= base_url('assets/img/' . $foto) ?>" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="info text-dark">
							<span class="d-block h4 m-0">
								<?= $nama ?>
							</span>
							<span class="user-level text-secondary"><?= $role ?></span>
						</div>
					</div>
					<ul class="nav nav-primary">
						<li class="nav-item <?= $item['dashboard'] ?>">
							<a href="<?= base_url('admin') ?>">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
							</a>
						</li>
						<li class="nav-item <?= $item['user'] ?>">
							<a href="<?= base_url('admin/daftar_user') ?>">
								<i class="fas fa-user"></i>
								<p>User</p>
							</a>
						</li>
						<li class="nav-item <?= $item['buku'] ?>">
							<a href="<?= base_url('admin/daftar_buku') ?>">
								<i class="fas fa-book"></i>
								<p>Buku</p>
							</a>
						</li>
						<li class="nav-item <?= $item['kategori'] ?>">
							<a href="<?= base_url('admin/daftar_kategori') ?>">
								<i class="fas fa-bookmark"></i>
								<p>Kategori</p>
							</a>
						</li>
						<li class="nav-item <?= $item['pinjam'] ?>">
							<a href="<?= base_url('admin/daftar_pinjaman') ?>">
								<i class="fas fa-ticket-alt"></i>
								<p>Pinjam</p>
							</a>
						</li>
						<li class="nav-item <?= $item['denda'] ?>">
							<a href="<?= base_url('admin/daftar_denda') ?>">
								<i class="fas fa-dollar-sign"></i>
								<p>Denda</p>
							</a>
						</li>
						<li class="nav-item <?= $item['laporan'] ?>">
							<a data-toggle="collapse" href="#laporan">
								<i class="fas fa-file-invoice"></i>
								<p>Laporan</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= $item['laporan'] == true ? 'show' : '' ?>" id="laporan">
								<ul class="nav nav-collapse">
									<li>
										<a href="<?= base_url('admin/laporan/user') ?>">
											<span class="sub-item">User</span>
										</a>
									</li>
									<li>
										<a href="<?= base_url('admin/laporan/pinjaman') ?>">
											<span class="sub-item">Pinjaman</span>
										</a>
									</li>
									<li class="<?= $item['laporan_denda'] ?>">
										<a href="<?= base_url('admin/laporan/denda') ?>">
											<span class="sub-item">Denda</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item <?= $item['pengaturan'] ?>">
							<a href="<?= base_url('admin/pengaturan') ?>">
								<i class="fas fa-cog"></i>
								<p>Pengaturan</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= base_url('logout') ?>" class="logout">
								<i class="fas fa-sign-out-alt"></i>
								<p>Logout</p>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->

		<div class="main-panel">
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

	<!-- jQuery Scrollbar -->
	<script src="<?= base_url('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') ?>"></script>

	<!-- Sweet Alert -->
	<script src="<?= base_url('assets/js/plugin/sweetalert/sweetalert.min.js') ?>"></script>

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