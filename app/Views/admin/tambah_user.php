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
      <form action="" id="tambah-user" enctype="multipart/form-data">
         <div class="card-header bg-primary">
            <div class="card-title text-white font-weight-bold"><?= $title ?></div>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="username">Username <span class="text-danger">*</span></label>
                     <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username" required>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="nama">Nama <span class="text-danger">*</span></label>
                     <input type="text" name="nama" class="form-control" id="nama" placeholder="Enter Nama" required>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="email">Email <span class="text-danger">*</span></label>
                     <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" required>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="password">Password <span class="text-danger">*</span></label>
                     <input type="text" name="password" class="form-control" id="password" placeholder="Enter Password" required>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="role">Role <span class="text-danger">*</span></label>
                     <select name="role" class="form-control form-control" id="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                     </select>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="status">Status <span class="text-danger">*</span></label>
                     <select name="status" class="form-control form-control" id="status" required>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak aktif</option>
                     </select>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="nohp">No HP</label>
                     <input type="number" name="nohp" class="form-control" id="nohp" placeholder="08312xxxxx">
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="alamat">Alamat</label>
                     <input type="text" name="alamat" class="form-control" id="alamat" placeholder="Jl. Halim">
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="foto">Foto <span class="small">(max: 2MB)</span></label>
                     <div class="input-group">
                        <div class="custom-file">
                           <input type="file" name="foto" class="custom-file-input" accept="image/*" id="foto">
                           <label class="custom-file-label font-weight-normal" for="foto">Pilih foto</label>
                        </div>
                     </div>
                     <img id="preview-foto" src="<?= base_url('assets/img/avatar.png') ?>" class="mt-3" alt="Foto" style="width: 120px;">
                  </div>
               </div>
            </div>
         </div>
         <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Simpan</button>
         </div>
      </form>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<script>
   var error = true;
   $(document).on('keyup', '#username', function() {
      var regex = /^[a-zA-Z0-9_]+$/;
      var val = $(this).val();
      if (val.search(regex) === -1) {
         $(this).parents('.form-group').addClass('has-error feedback');
         $(this).parents('.form-group').find('.form-text').removeClass('d-none');
         error = true;
      } else {
         $(this).parents('.form-group').removeClass('has-error feedback');
         $(this).parents('.form-group').find('.form-text').addClass('d-none');
         error = false;
      }
   })
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
   $('#tambah-user').submit(function(e) {
      e.preventDefault();
      var data = new FormData($(this)[0]);
      $.ajax({
         url: "<?= base_url('admin/tambah_user') ?>",
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
                  window.location.href = "<?= base_url('admin/daftar_user') ?>";
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