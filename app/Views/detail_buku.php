<?= $this->extend('layout/home') ?>
<?= $this->section('content') ?>
<div class="page-inner">
   <style>
      @media (min-width: 0px) {
         .box-buku {
            display: block;
         }

         .img-buku {
            text-align: center;
            width: 50%;
            margin: auto;
            margin-bottom: 20px;
         }

         .img-buku img {
            width: 100%;
         }
      }

      @media (min-width: 576px) {
         .box-buku {
            display: flex;
         }

         .img-buku {
            width: 25%;
            margin: 0;
            margin-right: 20px;
            margin-bottom: 20px;
         }

         .img-buku img {
            width: 100%;
         }
      }
   </style>
   <div class="card">
      <div class="card-body">
         <div class="box-buku">
            <div class="img-buku">
               <img src="<?= base_url('assets/img/' . $data->foto) ?>" alt="<?= $data->nama_buku ?>">
            </div>
            <div class="desc-buku col-lg-6">
               <h1 class="font-weight-bold"><?= $data->nama_buku ?></h1>
               <table>
                  <tbody>
                     <tr>
                        <td>
                           <h5 class="font-weight-bold mr-4">Penerbit</h5>
                        </td>
                        <td>
                           <h5><?= $data->penerbit ?></h5>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <h5 class="font-weight-bold mr-4">Pengarang</h5>
                        </td>
                        <td>
                           <h5><?= $data->pengarang ?></h5>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <h5 class="font-weight-bold mr-4">Tahun terbit</h5>
                        </td>
                        <td>
                           <h5><?= $data->tahun_terbit ?></h5>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <h5 class="font-weight-bold mr-4">Kode buku</h5>
                        </td>
                        <td>
                           <h5><?= $data->kode_buku ?></h5>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <h5 class="font-weight-bold mr-4">ISBN</h5>
                        </td>
                        <td>
                           <h5><?= $data->isbn ?></h5>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <h5 class="font-weight-bold mr-4">Deskripsi buku</h5>
                        </td>
                        <td>
                           <h5><?= $data->deskripsi ?></h5>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <button class="btn btn-primary pinjam-buku float-right mt-3 m-md-0"><i class="fa fa-ticket-alt mr-2"></i>Pinjam buku</button>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('footer') ?>
<script>
   $(document).on('click', '.pinjam-buku', function(e) {
      e.preventDefault();
      $.ajax({
         url: "<?= base_url('pinjam_buku') ?>",
         type: "post",
         dataType: "json",
         data: "id=<?= $data->id ?>",
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