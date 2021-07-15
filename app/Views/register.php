<?= $this->extend('layout/login_register') ?>

<?= $this->section('content') ?>
<div class="page-inner">
   <div class="row">
      <div class="col-md-8 col-lg-6 m-auto">
         <div class="card">
            <form action="" id="register">
               <div class="card-header">
                  <div class="card-title text-center"><?= $title ?></div>
               </div>
               <div class="card-body">
                  <div class="form-group">
                     <label for="username">Username <span class="text-danger">*</span></label>
                     <input type="text" name="username" class="form-control" id="username" required>
                  </div>
                  <div class="form-group">
                     <label for="nama">Nama <span class="text-danger">*</span></label>
                     <input type="nama" name="nama" class="form-control" id="nama" required>
                  </div>
                  <div class="form-group">
                     <label for="email">Email Address <span class="text-danger">*</span></label>
                     <input type="email" name="email" class="form-control" id="email" required>
                  </div>
                  <div class="form-group">
                     <label for="password">Password <span class="text-danger">*</span></label>
                     <input type="password" name="password" class="form-control" id="password" required>
                  </div>
               </div>
               <div class="card-action">
                  <button type="submit" class="btn btn-primary float-right"><i class="fa fa-sign-in-alt mr-2"></i>Register</button>
                  <a href="<?= base_url('register') ?>">Sudah memiliki akun ? Login disini!</a><br>
                  <a href="<?= base_url('resend_email_verifikasi') ?>">Kirim ulang email verifikasi!</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<script>
   $('#register').submit(function(e) {
      e.preventDefault();
      var data = $(this).serialize();
      $.ajax({
         url: "<?= base_url('register') ?>",
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
                  title: "Berhasil Register!",
                  text: result.msg,
                  icon: "success",
                  buttons: false,
                  timer: 2000
               }).then(function() {
                  window.location.href = "<?= base_url('login') ?>";
                  $('#loading').show();
               })
            } else {
               swal({
                  title: "Gagal Register!",
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