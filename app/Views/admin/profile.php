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
                     <label for="username">Username</label>
                     <input type="text" class="form-control font-weight-bold" id="username" value="<?= session()->username ?>" disabled>
                  </div>
                  <div class="form-group">
                     <label for="email">Email</label>
                     <input type="email" class="form-control" id="email" value="<?= session()->email ?>" disabled>
                  </div>
                  <div class="form-group">
                     <label for="nama">Nama <span class="text-danger">*</span></label>
                     <input type="text" name="nama" class="form-control" id="nama" value="<?= session()->nama ?>" required>
                  </div>
                  <div class="form-group">
                     <label for="no-hp">No HP</label>
                     <input type="text" name="no_hp" class="form-control" id="no-hp" value="<?= session()->no_hp ?>">
                  </div>
                  <div class="form-group">
                     <label for="alamat">Alamat</label>
                     <input type="text" name="alamat" class="form-control" id="alamat" value="<?= session()->alamat ?>">
                  </div>
                  <div class="form-group">
                     <label for="foto">Foto <span class="small">(max: 2MB)</span></label>
                     <div class="input-group">
                        <div class="custom-file">
                           <input type="file" name="foto" class="custom-file-input" accept="image/*" id="foto">
                           <label class="custom-file-label font-weight-normal" for="foto"><?= session()->foto ?></label>
                        </div>
                     </div>
                     <img id="preview-foto" src="<?= base_url('assets/img/' . session()->foto) ?>" class="mt-3" alt="Foto" style="width: 120px;">
                  </div>
               </div>
               <div class="card-footer text-right">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Simpan</button>
               </div>
            </form>
         </div>
      </div>
      <div class="col-md-6">
         <div class="card">
            <form action="" id="ubah-password" enctype="multipart/form-data">
               <div class="card-header bg-primary">
                  <div class="card-title text-white font-weight-bold">Ubah Password</div>
               </div>
               <div class="card-body">
                  <div class="form-group">
                     <label for="pass-lama">Password Lama <span class="text-danger">*</span></label><i class="fa fa-eye-slash show-hide-pass ml-2"></i>
                     <input type="password" name="pass_lama" class="form-control" id="pass-lama" required>
                  </div>
                  <div class="form-group">
                     <label for="pass-baru">Password Baru <span class="text-danger">*</span></label>
                     <input type="password" name="pass_baru" class="form-control" id="pass-baru" required>
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
   $('#foto').change(function() {
      var fileSize = this.files[0].size / 1024 / 1024;
      if (fileSize > 2) {
         Swal.fire({
            title: "Peringatan",
            text: "Ukuran tidak boleh lebih dari 2 MB",
            icon: "warning",
            buttons: false,
            timer: 2000
         })
      } else {
         if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
               $('#preview-foto').attr('src', e.target.result)
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
         url: "<?= base_url('ubah_profile') ?>",
         type: "post",
         dataType: "json",
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         beforeSend: function() {
            // 
         },
         success: function(result) {
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
   $('#ubah-password').submit(function(e) {
      e.preventDefault();
      var data = new FormData($(this)[0]);
      $.ajax({
         url: "<?= base_url('admin/ubah_password/') ?>",
         type: "post",
         dataType: "json",
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         beforeSend: function() {
            // 
         },
         success: function(result) {
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
   $(document).on('click', '.show-hide-pass', function() {
      var pass_lama = $('#pass-lama');
      var pass_baru = $('#pass-baru');
      if (pass_lama.attr('type') == 'text') {
         $(this).removeClass('fa-eye');
         $(this).addClass('fa-eye-slash');
         pass_lama.attr('type', 'password');
         pass_baru.attr('type', 'password');
      } else {
         $(this).removeClass('fa-eye-slash');
         $(this).addClass('fa-eye');
         pass_lama.attr('type', 'text');
         pass_baru.attr('type', 'text');
      }
   })
</script>
<?= $this->endSection() ?>