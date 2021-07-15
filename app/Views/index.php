<?= $this->extend('layout/home') ?>
<?= $this->section('content') ?>
<div class="page-inner">
   <style>
      .img-buku {
         height: 200px;
         width: 100%;
         object-fit: cover;
      }

      .img-buku img {
         width: 100%;
         height: 100%;
      }

      .desc-buku p {
         font-size: 12px;
      }
   </style>
   <div class="card">
      <div class="card-body">
         <form action="">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <div class="input-icon">
                        <input type="text" name="search" class="form-control border-primary" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" placeholder="Cari buku...">
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <select class="form-control border-primary" name="kategori" id="kategori">
                        <option value="" selected>Semua Kategori</option>
                        <?php foreach ($kategori as $row) : ?>
                           <?php if ($row->id == (isset($_GET['kategori']) ? $_GET['kategori'] : '')) : ?>
                              <option value="<?= $row->id ?>" selected><?= $row->nama_kategori ?></option>
                           <?php else :  ?>
                              <option value="<?= $row->id ?>"><?= $row->nama_kategori ?></option>
                           <?php endif; ?>
                        <?php endforeach; ?>
                     </select>
                  </div>
               </div>
               <div class="col-12">
                  <div class="form-group">
                     <button class="btn btn-primary mb-5"><i class="fa fa-search mr-2"></i>Cari</button>
                  </div>
               </div>
            </div>
         </form>
         <div class="row">
            <?php
            if ($buku == []) {
            ?>
               <h3 class="text-center w-100">Data buku tidak ada</h3>
            <?php
            }
            foreach ($buku as $row) :
            ?>
               <div class="col-6 col-sm-4 col-md-3 col-lg-2 my-3">
                  <div class="img-buku">
                     <a href="<?= base_url('buku/' . $row->id) ?>">
                        <img src="<?= base_url('assets/img/' . $row->foto) ?>" alt="<?= $row->nama_buku ?>">
                     </a>
                  </div>
                  <div class="desc-buku my-2">
                     <a href="<?= base_url('buku/' . $row->id) ?>" class="title-buku"><?= $row->nama_buku ?></a>
                     <p class="small">
                        Penerbit: <?= $row->penerbit ?><br>
                        Pengarang: <?= $row->pengarang ?>
                     </p>
                  </div>
                  <div class="">
                     <a href="<?= base_url('buku/' . $row->id) ?>" class="btn btn-primary btn-block btn-sm"><i class="fa fa-eye mr-2"></i>Lihat</a>
                     <button class="btn btn-success btn-block btn-sm pinjam-buku" data-id="<?= $row->id ?>"><i class="fa fa-ticket-alt mr-2"></i>Pinjam</button>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
      <w class="100 mt-5"></w>
      <?= $pager->links('default', 'my_pager') ?>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('footer') ?>
<script>
   $(document).on('click', '.pinjam-buku', function(e) {
      e.preventDefault();
      var buku_id = $(this).data('id');
      $.ajax({
         url: "<?= base_url('pinjam_buku') ?>",
         type: "post",
         dataType: "json",
         data: "id=" + buku_id,
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
               var cart = $('.cart');
               cart.text(eval(cart.text() + " + 1"))
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