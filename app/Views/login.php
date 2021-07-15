<?= $this->extend('layout/login_register') ?>

<?= $this->section('content') ?>
<div class="page-inner">
   <div class="row">
      <div class="col-md-8 col-lg-6 m-auto">
         <div class="card">
            <form action="" id="login">
               <div class="card-header">
                  <div class="card-title text-center"><?= $title ?></div>
               </div>
               <div class="card-body">
                  <?php
                  if (session()->getFlashdata('msg') != null) {
                  ?>
                     <div class="alert alert-info fade show" role="alert">
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
                  <div class="form-group">
                     <label for="password">Password</label>
                     <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                  </div>
               </div>
               <div class="card-action">
                  <button type="submit" class="btn btn-primary float-right"><i class="fa fa-sign-in-alt mr-2"></i>Login</button>
                  <a href="<?= base_url('register') ?>">Tidak memiliki akun ? Daftar disini</a>
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
   $('#login').submit(function(e) {
      e.preventDefault();
      var data = $(this).serialize();
      $.ajax({
         url: "<?= base_url('login') ?>",
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
                  title: "Berhasil Login!",
                  text: result.msg,
                  icon: "success",
                  buttons: false,
                  timer: 2000
               }).then(function() {
                  $('#loading').show();
                  window.location.href = result.redirect;
               })
            } else {
               swal({
                  title: "Gagal Login!",
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