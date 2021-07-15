<?= $this->extend('layout/dashboard') ?>
<?= $this->section('content') ?>
<div class="panel-header bg-primary-gradient">
   <div class="page-inner py-5">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
         <div>
            <h2 class="text-white pb-2 fw-bold"><?= $title ?></h2>
         </div>
         <div class="ml-md-auto py-2 py-md-0">
            <a href="<?= base_url('admin/daftar_pinjaman') ?>" class="btn btn-white btn-border btn-round mr-2">Manage</a>
            <a href="<?= base_url('admin/tambah_pinjaman') ?>" class="btn btn-primary btn-round">Add Pinjaman</a>
         </div>
      </div>
   </div>
</div>
<div class="page-inner mt--5">
   <div class="row mt--2">
      <div class="col-md-7">
         <div class="card">
            <div class="card-body p-3">
               <div class="card-title pb-3">Statistik Pinjaman</div>
               <div class="row text-center">
                  <div class="col-6 col-md-3">
                     <div class="h1 m-0"><?= $data['today'] ?></div>
                     <div class="text-muted mb-3">Hari ini</div>
                  </div>
                  <div class="col-6 col-md-3">
                     <div class="h1 m-0"><?= $data['yesterday'] ?></div>
                     <div class="text-muted mb-3">Kemarin</div>
                  </div>
                  <div class="col-6 col-md-3">
                     <div class="h1 m-0"><?= $total_pinjaman ?></div>
                     <div class="text-muted mb-3">Total Pinjaman</div>
                  </div>
                  <div class="col-6 col-md-3">
                     <div class="h1 m-0"><?= $data['jatuh_tempo'] ?></div>
                     <div class="text-muted mb-3">Pinjaman Jatuh Tempo</div>
                  </div>
               </div>
            </div>
         </div>
         <div class="card">
            <div class="card-header">
               <div class="card-head-row">
                  <div class="card-title">Statistik Pinjaman dalam 1 Tahun</div>
               </div>
            </div>
            <div class="card-body">
               <div class="chart-container" style="min-height: 375px">
                  <canvas id="statisticsChart"></canvas>
               </div>
               <div id="myChartLegend"></div>
            </div>
         </div>
      </div>
      <div class="col-md-5">
         <div class="card">
            <div class="card-body">
               <div class="card-title">Statistik Pinjaman dalam 1 Minggu</div>
               <div class="row py-3">
                  <div class="col-12 d-flex ">
                     <div>
                        <h6 class="fw-bold text-uppercase text-success op-8">Total Pinjaman</h6>
                        <h3 class="fw-bold"><?= $data['this_week'] ?></h3>
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
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script src="<?= base_url('assets/js/plugin/chart.js/chart.min.js') ?>"></script>

<script>
   var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');

   var mytotalIncomeChart = new Chart(totalIncomeChart, {
      type: 'bar',
      data: {
         labels: [<?= $data['label'] ?>],
         datasets: [{
            label: "Pinjaman",
            backgroundColor: '#ff9e27',
            borderColor: 'rgb(23, 125, 255)',
            data: [<?= $data['on_this_week'] ?>]
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
   var ctx = document.getElementById('statisticsChart').getContext('2d');

   var statisticsChart = new Chart(ctx, {
      type: 'line',
      data: {
         labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
         datasets: [{
            label: "Pinjaman",
            borderColor: '#f3545d',
            pointBackgroundColor: 'rgba(243, 84, 93, 0.6)',
            pointRadius: 1,
            backgroundColor: 'rgba(243, 84, 93, 0.4)',
            legendColor: '#f3545d',
            fill: true,
            borderWidth: 2,
            data: [<?= $data['on_this_year'] ?>]
         }]
      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         legend: {
            display: false
         },
         tooltips: {
            bodySpacing: 4,
            mode: "nearest",
            intersect: 0,
            position: "nearest",
            xPadding: 10,
            yPadding: 10,
            caretPadding: 10
         },
         layout: {
            padding: {
               left: 5,
               right: 5,
               top: 15,
               bottom: 15
            }
         },
         scales: {
            yAxes: [{
               ticks: {
                  fontStyle: "500",
                  beginAtZero: false,
                  maxTicksLimit: 5,
                  padding: 10
               },
               gridLines: {
                  drawTicks: false,
                  display: false
               }
            }],
            xAxes: [{
               gridLines: {
                  zeroLineColor: "transparent"
               },
               ticks: {
                  padding: 10,
                  fontStyle: "500"
               }
            }]
         }
      }
   });
</script>

<?= $this->endSection() ?>