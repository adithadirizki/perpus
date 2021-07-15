<?= $this->extend('layout/dashboard') ?>
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
      <div class="card-header">
         <div class="card-title font-weight-bold">#<?= $data[0]->no_pinjam ?></div>
      </div>
      <div class="card-body">
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
                  <?php $i = 1;
                  foreach ($data as $row) : ?>
                     <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $row->nama_buku ?></td>
                        <td><?= $row->kode_buku ?></td>
                        <td><?= $row->penerbit ?></td>
                        <td><?= $row->isbn ?></td>
                        <td class="text-nowrap">
                           <a href="<?= base_url('admin/detail_buku/' . $row->buku_id) ?>" class="btn btn-xs btn-primary font-weight-bold mr-1"><i class="fa fa-edit mr-2"></i>Detail</a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
         <?php
         switch ($data[0]->status) {
            case 0:
               $status = 'Belum dikembalikan';
               break;
            case 1:
               $status = 'Sudah dikembalikan';
               break;
            case 2:
               $status = 'Menunggu dikonfirmasi';
               break;
            default:
               $status = 'Status Error';
               break;
         }
         ?>
         <div class="row">
            <div class="col-md-6">
               <table id="buku-pinjaman" class="table">
                  <tbody>
                     <tr>
                        <td class="font-weight-bold">Status</td>
                        <td><?= $status ?></td>
                     </tr>
                     <tr>
                        <td class="font-weight-bold">Tgl. Pinjam</td>
                        <td><?= $data[0]->start ?></td>
                     </tr>
                     <tr>
                        <td class="font-weight-bold">Tgl. Tempo</td>
                        <td><?= $data[0]->end != null ? $data[0]->end : '-' ?></td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div class="col-md-6">
               <table id="buku-pinjaman" class="table">
                  <tbody>
                     <tr>
                        <td class="font-weight-bold">Tgl. Dikembalikan</td>
                        <td><?= $data[0]->tgl_return != null ? $data[0]->tgl_return : '-' ?></td>
                     </tr>
                     <tr>
                        <?php
                        helper('number');
                        $return = $data[0]->tgl_return == null ? date('Y-m-d') : $data[0]->tgl_return;
                        $end = ($data[0]->end == null) ? date('Y-m-d', strtotime('+ 1 Hours')) : $data[0]->end;
                        if (strtotime($return) > strtotime($end)) {
                           $terlambat = date_diff(date_create($return), date_create($end))->days . ' hari';
                        } else {
                           $terlambat = '-';
                        }
                        ?>
                        <td class="font-weight-bold">Terlambat</td>
                        <td><?= $terlambat ?></td>
                     </tr>
                     <tr>
                        <td class="font-weight-bold">Denda</td>
                        <td><?= ($denda = $data[0]->denda) != null ? number_to_currency($denda, 'IDR', 'id_ID') : '-' ?></td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div class="col-12">
               <td><span class="font-weight-bold px-4">Ket. Denda: </span></td>
               <td><?= ($ket_denda = $data[0]->ket_denda != null) ? $ket_denda : '-' ?></td>
            </div>
            <div class="col-12">
               <button class="btn btn-danger hapus-pinjaman float-right mt-3"><i class="fa fa-trash-alt mr-2"></i>Hapus</button>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <?php
      if ($data[0]->status == 2) {
      ?>
         <div class="col-md-6">
            <div class="card">
               <form action="" id="konfirmasi-pinjaman" enctype="multipart/form-data">
                  <div class="card-header bg-primary">
                     <div class="card-title text-white font-weight-bold">Konfirmasi pinjaman</div>
                  </div>
                  <div class="card-body">
                     <div class="form-group">
                        <label for="end">Tgl. Tempo <span class="text-danger">*</span></label>
                        <input type="date" name="end" class="form-control" id="end" value="<?= $data[0]->end ?>" required>
                     </div>
                  </div>
                  <div class="card-footer text-right">
                     <button type="submit" class="btn btn-block btn-primary"><i class="far fa-check-circle mr-2"></i>Konfirmasi</button>
                  </div>
               </form>
            </div>
         </div>
      <?php
      } elseif ($data[0]->status == 0) {
      ?>
         <div class="col-md-6">
            <div class="card">
               <form action="" id="ubah-pinjaman" enctype="multipart/form-data">
                  <div class="card-header bg-primary">
                     <div class="card-title text-white font-weight-bold">Pengembalian pinjaman</div>
                  </div>
                  <div class="card-body">
                     <div class="form-group">
                        <label for="denda">Denda</label>
                        <div class="input-group mb-3">
                           <div class="input-group-prepend">
                              <span class="input-group-text">Rp</span>
                           </div>
                           <input type="text" name="denda" class="form-control" id="denda" value="">
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="ket-denda">Ket. Denda <small>(optional)</small></label>
                        <textarea name="ket_denda" class="form-control" id="ket-denda" cols="30" rows="10"></textarea>
                     </div>
                  </div>
                  <div class="card-footer text-right">
                     <button type="submit" class="btn btn-primary"><i class="far fa-dot-circle mr-2"></i>Submit</button>
                  </div>
               </form>
            </div>
         </div>
      <?php
      }
      ?>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script>
   function live_to_rupiah(obj) {
      var num_to_string = $(obj).val().replace(/[^\d]/g, '').toString(),
         split = num_to_string.split(','),
         sisa = split[0].length % 3,
         rupiah = split[0].substr(0, sisa),
         ribuan = split[0].substr(sisa).match(/\d{3}/gi);
      if (ribuan) {
         separator = sisa ? '.' : '';
         rupiah += separator + ribuan.join('.');
      }
      rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      $(obj).val(rupiah)
   }

   $(document).on('keyup', '#denda', function() {
      live_to_rupiah(this)
   })

   $('#ubah-pinjaman').submit(function(e) {
      e.preventDefault();
      var data = $(this).serialize();
      $.ajax({
         url: "<?= base_url('admin/ubah_pinjaman/' . $data[0]->no_pinjam) ?>",
         type: "post",
         dataType: "json",
         data: data,
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
                  window.location.href = "<?= base_url('admin/daftar_pinjaman') ?>";
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
      return false;
   })

   $('#konfirmasi-pinjaman').submit(function(e) {
      e.preventDefault();
      var data = $(this).serialize();
      $.ajax({
         url: "<?= base_url('admin/konfirmasi_pinjaman/' . $data[0]->no_pinjam) ?>",
         type: "post",
         dataType: "json",
         data: data,
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
                  window.location.href = "<?= base_url('admin/daftar_pinjaman') ?>";
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
      return false;
   })

   $(document).on('click', 'button.hapus-pinjaman', function() {
      swal({
         title: "Peringatan!",
         text: "Yakin ingin menghapus pinjaman ini ?",
         icon: "warning",
         buttons: {
            confirm: {
               text: "Ya, hapus saja!",
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
               url: "<?= base_url('admin/hapus_pinjaman/' . $data[0]->no_pinjam) ?>",
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
                        detail_pinjaman.ajax.reload();
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
</script>
<?= $this->endSection() ?>