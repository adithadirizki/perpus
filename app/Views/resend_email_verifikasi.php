<?= $this->extend('layout/login_register') ?>

<?= $this->section('content') ?>
<div class="page-inner">
   <div class="row">
      <div class="col-md-8 col-lg-6 m-auto">
         <div class="card">
            <form action="" id="resend-email-verifikasi">
               <div class="card-header">
                  <div class="card-title text-center"><?= $title ?></div>
               </div>
               <div class="card-body">
                  <?php
                  if (session()->getFlashdata('msg') != null) {
                  ?>
                     <div class="alert alert-success fade show" role="alert">
                        <?= session()->getFlashdata('msg') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                  <?php
                  }
                  ?>
                  <div class="form-group">
                     <label for="email">Email Address</label>
                     <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
                  </div>
               </div>
               <div class="card-action">
                  <button type="submit" class="btn btn-primary float-right"><i class="fa fa-paper-plane mr-2"></i>Kirim</button>
                  <a href="<?= base_url('login') ?>">Sudah memiliki akun ? Login disini!</a>
                  <br>
                  <a href="<?= base_url('lupa_password') ?>">Lupa password</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<script>
   $('#resend-email-verifikasi').submit(function(e) {
      e.preventDefault();
      var data = $(this).serialize();
      $.ajax({
         url: "<?= base_url('resend_email_verifikasi') ?>",
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
                  window.location.href = "<?= base_url('login') ?>";
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