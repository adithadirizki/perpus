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
            <form action="" id="ubah-pengaturan" enctype="multipart/form-data">
               <div class="card-header bg-primary">
                  <div class="card-title text-white font-weight-bold"><?= $title ?></div>
               </div>
               <div class="card-body">
                  <div class="form-group">
                     <label for="nama-app">Nama App <span class="text-danger">*</span></label>
                     <input type="text" name="nama_app" class="form-control" id="nama-app" value="<?= session()->nama_app ?>" required>
                  </div>
                  <div class="form-group">
                     <label for="logo">Logo <span class="small">(max: 1MB)</span></label>
                     <div class="input-group">
                        <div class="custom-file">
                           <input type="file" name="logo" class="custom-file-input" accept="image/*" id="logo">
                           <label class="custom-file-label font-weight-normal" for="logo"><?= session()->logo ?></label>
                        </div>
                     </div>
                     <img id="preview-logo" src="<?= base_url('assets/img/' . session()->logo) ?>" class="mt-3" alt="Sampul Buku" style="width: 120px;">
                  </div>
               </div>
               <div class="card-footer text-right">
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
   $('#logo').change(function() {
      var fileSize = this.files[0].size / 1024 / 1024;
      if (fileSize > 1) {
         Swal.fire({
            title: "Peringatan",
            text: "Ukuran tidak boleh lebih dari 1 MB",
            icon: "warning",
            buttons: false,
            timer: 2000
         })
      } else {
         if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
               $('#preview-logo').attr('src', e.target.result)
            }
            reader.readAsDataURL(this.files[0]);
            $('.custom-file-label').text(this.files[0].name)
         }
      }
   })
   $('#ubah-pengaturan').submit(function(e) {
      e.preventDefault();
      var data = new FormData($(this)[0]);
      $.ajax({
         url: "<?= base_url('admin/ubah_pengaturan/') ?>",
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