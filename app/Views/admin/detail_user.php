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
      <form action="" id="ubah-user" enctype="multipart/form-data">
         <div class="card-header bg-primary">
            <div class="card-title text-white font-weight-bold"><?= $title ?></div>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="username">Username</label>
                     <input type="text" name="username" class="form-control font-weight-bold" id="username" value="<?= $data->username ?>" readonly>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="nama">Nama <span class="text-danger">*</span></label>
                     <input type="text" name="nama" class="form-control" id="nama" value="<?= $data->nama ?>" placeholder="Enter Nama" required>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="email">Email <span class="text-danger">*</span></label>
                     <input type="email" name="email" class="form-control" id="email" value="<?= $data->email ?>" placeholder="Enter Email" required>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="password">Password</label>
                     <input type="text" name="password" class="form-control" id="password" placeholder="Enter Password">
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="role">Role <span class="text-danger">*</span></label>
                     <select name="role" class="form-control form-control" id="role" required>
                        <option value="user" <?= $data->role == 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $data->role == 'admin' ? 'selected' : '' ?>>Admin</option>
                     </select>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="status">Status <span class="text-danger">*</span></label>
                     <select name="status" class="form-control form-control" id="status" required>
                        <option value="1" <?= $data->status == '1' ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= $data->status == '0' ? 'selected' : '' ?>>Tidak aktif</option>
                     </select>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="nohp">No HP</label>
                     <input type="number" name="nohp" class="form-control" id="nohp" value="<?= $data->no_hp ?>" placeholder="08312xxxxx">
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="alamat">Alamat</label>
                     <input type="text" name="alamat" class="form-control" id="alamat" value="<?= $data->alamat ?>" placeholder="Jl. Halim">
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="foto">Foto <span class="small">(max: 2MB)</span></label>
                     <div class="input-group">
                        <div class="custom-file">
                           <input type="file" name="foto" class="custom-file-input" accept="image/*" id="foto">
                           <label class="custom-file-label font-weight-normal" for="foto"><?= $data->foto ?></label>
                        </div>
                     </div>
                     <img id="preview-foto" src="<?= base_url('assets/img/' . $data->foto) ?>" class="mt-3" alt="Foto" style="width: 120px;">
                  </div>
               </div>
            </div>
         </div>
         <div class="card-footer d-flex justify-content-between">
            <button type="button" id="hapus-user" class="btn btn-danger"><i class="fa fa-trash-alt mr-2"></i>Hapus</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Simpan</button>
         </div>
      </form>
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
   $('#ubah-user').submit(function(e) {
      e.preventDefault();
      var data = new FormData($(this)[0]);
      $.ajax({
         url: "<?= base_url('admin/ubah_user/' . $data->username) ?>",
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
   $('#hapus-user').click(function() {
      $.ajax({
         url: "<?= base_url('admin/hapus_user/' . $data->username) ?>",
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
   })
</script>
<?= $this->endSection() ?>