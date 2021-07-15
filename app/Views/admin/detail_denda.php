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
      <form action="" id="ubah-denda" enctype="multipart/form-data">
         <div class="card-header bg-primary">
            <div class="card-title text-white font-weight-bold"><?= $title ?></div>
         </div>
         <div class="card-body">
            <div class="form-group">
               <label for="no-pinjam">No. Pinjam</label>
               <input type="text" class="form-control text-dark font-weight-bold" id="no-pinjam" value="<?= $data->no_pinjam ?>" readonly>
            </div>
            <div class="form-group">
               <label for="denda">Denda <span class="text-danger">*</span></label>
               <div class="input-group mb-3">
                  <div class="input-group-prepend">
                     <span class="input-group-text">Rp</span>
                  </div>
                  <input type="text" name="denda" class="form-control" id="denda" placeholder="Dongeng" value="<?= $data->denda ?>" required>
               </div>
            </div>
            <div class="form-group">
               <label for="ket-denda">Ket. Denda</label>
               <textarea name="ket_denda" class="form-control" id="ket-denda" cols="10" rows="10"><?= $data->ket_denda ?></textarea>
            </div>
         </div>
         <div class="card-footer d-flex justify-content-between">
            <button type="button" id="hapus-denda" class="btn btn-danger"><i class="fa fa-trash-alt mr-2"></i>Hapus</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Simpan</button>
         </div>
      </form>
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

   function to_rupiah(amount) {
      var sisa = String(amount).length % 3;
      rupiah = String(amount).substr(0, sisa),
         ribuan = String(amount).substr(sisa).match(/\d{3}/g);
      if (ribuan) {
         separator = sisa ? '.' : ''
         rupiah += separator + ribuan.join('.')
      }
      return rupiah
   }
   var denda = $('input[name="denda"]');
   denda.val(to_rupiah(denda.val()));
   $(document).on('keyup', '#denda', function() {
      live_to_rupiah(this);
   })
   $('#ubah-denda').submit(function(e) {
      e.preventDefault();
      var data = new FormData($(this)[0]);
      $.ajax({
         url: "<?= base_url('admin/ubah_denda/' . $data->id) ?>",
         type: "post",
         dataType: "json",
         data: data,
         cache: false,
         contentType: false,
         processData: false,
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
                  window.location.href = "<?= base_url('admin/daftar_denda') ?>";
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
   $('#hapus-denda').click(function() {
      $.ajax({
         url: "<?= base_url('admin/hapus_denda/' . $data->id) ?>",
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
                  window.location.href = "<?= base_url('admin/daftar_denda') ?>";
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