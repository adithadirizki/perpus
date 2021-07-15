<?= $this->extend('layout/home') ?>
<?= $this->section('content') ?>
<div class="page-inner">
   <div class="page-header">
      <h4 class="page-title"><?= $title ?></h4>
      <ul class="breadcrumbs">
         <li class="nav-home">
            <a href="<?= base_url('admin') ?>">
               <i class="flaticon-home"></i>
            </a>
         </li>
         <li class="separator">
            <i class="flaticon-right-arrow"></i>
         </li>
         <li class="nav-item">
            <?= $title ?>
         </li>
      </ul>
   </div>
   <div class="card">
      <div class="card-body">
         <a href="<?= base_url() ?>" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Tambah pinjaman</a>
         <div class="table-responsive">
            <table id="detail-pinjaman" class="table table-striped table-hover">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Buku</th>
                     <th>Kode buku</th>
                     <th>Penerbit</th>
                     <th>ISBN</th>
                     <th>Aksi</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  if ($data == []) {
                  ?>
                     <tr>
                        <td colspan="6">
                           <h3 class="text-center">Keranjang kosong</h3>
                        </td>
                     </tr>
                  <?php
                  }
                  $i = 1;
                  foreach ($data as $row) : ?>
                     <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $row->nama_buku ?></td>
                        <td><?= $row->kode_buku ?></td>
                        <td><?= $row->penerbit ?></td>
                        <td><?= $row->isbn ?></td>
                        <td class="text-nowrap">
                           <a href="<?= base_url('buku/' . $row->buku_id) ?>" class="btn btn-xs btn-primary font-weight-bold mr-1"><i class="fa fa-edit mr-2"></i>Detail</a>
                           <button class="btn btn-xs btn-danger buang-keranjang font-weight-bold mr-1" data-id="<?= $row->buku_id ?>"><i class="fa fa-minus mr-2"></i>Buang</button>
                        </td>
                     </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
         <div class="row">
            <div class="col-12">
               <button id="ajukan-pinjaman" class="btn btn-success float-right mt-3" <?= $data == [] ? 'disabled' : '' ?>><i class="fa fa-check-circle mr-2"></i>Ajukan Pinjaman</button>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script>
   $(document).on('click', '.buang-keranjang', function(e) {
      e.preventDefault();
      var buku_id = $(this).data('id');
      swal({
         title: "Peringatan!",
         text: "Yakin ingin buang buku ini dari keranjang ?",
         icon: "warning",
         buttons: {
            confirm: {
               text: "Ya, buang saja!",
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
            $.ajax({
               url: "<?= base_url('buang_keranjang') ?>",
               type: "post",
               dataType: "json",
               data: "id=" + buku_id,
               beforeSend: function() {
                  $('#loading').show();
               },
               success: function(result) {
                  $('#loading').hide();
                  if (result.error == false) {
                     swal({
                        title: "Berhasil!",
                        text: result.msg,
                        icon: "success",
                        buttons: false,
                        timer: 2000
                     }).then(function() {
                        window.location.reload()
                        $('#loading').show();
                     })
                  } else {
                     swal({
                        title: "Gagal!",
                        text: result.msg,
                        icon: "error",
                        buttons: false,
                        timer: 2000
                     })
                  }
               }
            })
         }
      })
   })
   $(document).on('click', '#ajukan-pinjaman', function(e) {
      e.preventDefault();
      $.ajax({
         url: "<?= base_url('ajukan_pinjaman') ?>",
         type: "post",
         dataType: "json",
         beforeSend: function() {
            $('#loading').show();
         },
         success: function(result) {
            $('#loading').hide();
            if (result.error == false) {
               swal({
                  title: "Berhasil!",
                  text: result.msg,
                  icon: "success",
                  buttons: false,
                  timer: 2000
               }).then(function() {
                  window.location.href = "<?= base_url('pinjaman') ?>";
                  $('#loading').show();
               })
            } else {
               swal({
                  title: "Gagal!",
                  text: result.msg,
                  icon: "error",
                  buttons: false,
                  timer: 2000
               })
            }
         }
      })
   })
</script>

<?= $this->endSection() ?>