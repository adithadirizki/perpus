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
   <div class="row">
      <div class="col-md-6">
         <div class="card">
            <form action="" id="tambah-kategori" enctype="multipart/form-data">
               <div class="card-header bg-primary">
                  <div class="card-title text-white font-weight-bold"><?= $title ?></div>
               </div>
               <div class="card-body">
                  <div class="row">
                     <div class="col">
                        <div class="form-group">
                           <label for="nama-kategori">Nama kategori <span class="text-danger">*</span></label>
                           <input type="text" name="nama_kategori" class="form-control" id="nama-kategori" placeholder="Dongeng" required>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card-footer d-flex justify-content-between">
                  <button type="button" id="hapus-kategori" class="btn btn-danger"><i class="fa fa-trash-alt mr-2"></i>Hapus</button>
                  <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Simpan</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<script>
   $('#tambah-kategori').submit(function(e) {
      e.preventDefault();
      var data = new FormData($(this)[0]);
      $.ajax({
         url: "<?= base_url('admin/tambah_kategori') ?>",
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
                  window.location.href = "<?= base_url('admin/daftar_kategori') ?>";
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
</script>
<?= $this->endSection() ?>