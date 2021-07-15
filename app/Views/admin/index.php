<?= $this->extend('layout/dashboard') ?>
<?= $this->section('content') ?>
<div class="panel-header bg-primary-gradient">
   <div class="page-inner py-5">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
         <div>
            <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
            <!-- <h5 class="text-white op-7 mb-2">Informasi statistik hari ini</h5> -->
         </div>
      </div>
   </div>
</div>
<?php helper('number') ?>
<div class="page-inner mt--5">
   <div class="row">
      <div class="col-sm-6 col-lg-3">
         <div class="card mb-0 mb-lg-5 p-3">
            <div class="d-flex align-items-center">
               <span class="stamp stamp-md bg-secondary mr-3">
                  <i class="fa fa-dollar-sign"></i>
               </span>
               <div>
                  <h5 class="mb-1"><b><a href="#"><?= number_to_currency($total_denda, 'IDR', 'id_ID') ?> <small></small></a></b></h5>
                  <small class="text-muted">Pendapatan denda</small>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-6 col-lg-3">
         <div class="card mb-0 mb-lg-5 p-3">
            <div class="d-flex align-items-center">
               <span class="stamp stamp-md bg-success mr-3">
                  <i class="fa fa-shopping-cart"></i>
               </span>
               <div>
                  <h5 class="mb-1"><b><a href="#"><?= $total_pinjaman ?> <small>Pinjaman</small></a></b></h5>
                  <small class="text-muted"><?= count($pinjaman_today) ?> today</small>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-6 col-lg-3">
         <div class="card mb-0 mb-lg-5 p-3">
            <div class="d-flex align-items-center">
               <span class="stamp stamp-md bg-danger mr-3">
                  <i class="fa fa-users"></i>
               </span>
               <div>
                  <h5 class="mb-1"><b><a href="<?= base_url('admin/daftar_user') ?>"><?= $total_user ?> <small>Users</small></a></b></h5>
                  <small class="text-muted"><?= count($user_today) ?> registered today</small>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-6 col-lg-3">
         <div class="card mb-5 mb-lg-5 p-3">
            <div class="d-flex align-items-center">
               <span class="stamp stamp-md bg-warning mr-3">
                  <i class="fa fa-comment-alt"></i>
               </span>
               <div>
                  <h5 class="mb-1"><b><a href="#"><?= $total_jatuh_tempo ?> <small>PInjaman</small></a></b></h5>
                  <small class="text-muted">Jatuh Tempo</small>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row mt--2">
      <div class="col-lg-7">
         <div class="row">
            <div class="col-6 pr-1">
               <div class="card card-stats card-round">
                  <div class="card-body">
                     <div class="row align-items-center">
                        <div class="col-icon">
                           <div class="icon-big text-center text-warning bubble-shadow-small">
                              <i class="fa fa-user-alt"></i>
                           </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                           <div class="numbers">
                              <p class="card-category">User</p>
                              <h4 class="card-title"><?= count($user_today) ?></h4>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-6 pl-1">
               <div class="card card-stats card-round">
                  <div class="card-body">
                     <div class="row align-items-center">
                        <div class="col-icon">
                           <div class="icon-big text-center text-secondary bubble-shadow-small">
                              <i class="fa fa-ticket-alt"></i>
                           </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                           <div class="numbers">
                              <p class="card-category">Pinjaman</p>
                              <h4 class="card-title"><?= count($pinjaman_today) ?></h4>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="card">
            <div class="card-body">
               <div class="card-title">Statistik Pinjaman dalam 1 Minggu</div>
               <div class="row py-3">
                  <div class="col-12 d-flex ">
                     <div>
                        <h6 class="fw-bold text-uppercase text-success op-8">Total Pinjaman</h6>
                        <h3 class="fw-bold"><?= $pinjaman['this_week'] ?></h3>
                     </div>
                  </div>
                  <div class="col-12">
                     <div id="chart-container">
                        <canvas id="totalIncomeChart"></canvas>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-5">
         <div class="card">
            <div class="card-body">
               <div class="card-title fw-mediumbold">New User</div>
               <div class="card-list">
                  <?php
                  if ($user_today == []) :
                  ?>
                     <h4 class="text-center font-weight-light m-0">Tidak ada data</h4>
                     <?php
                  else :
                     foreach ($user_today as $kay => $value) :
                        $nama = $value->nama;
                        $username = $value->username;
                        $foto = $value->foto;
                     ?>
                        <div class="item-list">
                           <div class="avatar">
                              <img src="<?= base_url('assets/img/' . $foto) ?>" alt="..." class="avatar-img rounded-circle">
                           </div>
                           <div class="info-user ml-3">
                              <div class="username"><?= $nama ?></div>
                              <div class="status"><?= $username ?></div>
                           </div>
                           <a href="<?= base_url('admin/detail_user/' . $username) ?>" class="btn btn-icon btn-primary btn-round btn-xs">
                              <i class="fa fa-arrow-right py-1"></i>
                           </a>
                        </div>
                  <?php
                     endforeach;
                  endif;
                  ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script src="<?= base_url('assets/js/plugin/chart.js/chart.min.js') ?>"></script>

<script>
   var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');

   var mytotalIncomeChart = new Chart(totalIncomeChart, {
      type: 'line',
      data: {
         labels: [<?= $pinjaman['label'] ?>],
         datasets: [{
            label: "Pinjaman",
            backgroundColor: '#1572e842',
            borderColor: 'rgb(23, 125, 255)',
            data: [<?= $pinjaman['on_this_week'] ?>]
         }],
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         legend: {
            display: false,
         },
         scales: {
            yAxes: [{
               ticks: {
                  display: false //this will remove only the label
               },
               gridLines: {
                  drawBorder: false,
                  display: false
               }
            }],
            xAxes: [{
               gridLines: {
                  drawBorder: false,
                  display: false
               }
            }]
         },
      }
   });
</script>
<?= $this->endSection() ?>