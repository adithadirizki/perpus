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
      <form action="" id="ubah-buku" enctype="multipart/form-data">
         <div class="card-header bg-primary">
            <div class="card-title text-white font-weight-bold"><?= $title ?></div>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="nama-buku">Nama buku <span class="text-danger">*</span></label>
                     <input type="text" name="nama_buku" class="form-control" id="nama-buku" placeholder="Malin Kundang" value="<?= $data->nama_buku ?>" required>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="kode-buku">Kode buku</label>
                     <input type="text" name="kode_buku" class="form-control" id="kode-buku" placeholder="B1059" value="<?= $data->kode_buku ?>" required>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="deskripsi">Deskripsi buku</label>
                     <textarea name="deskripsi" class="form-control" id="deskripsi" rows="5"><?= $data->deskripsi ?></textarea>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="kategori">Kategori <span class="text-danger">*</span></label>
                     <select name="kategori" class="form-control form-control" id="kategori" required>
                        <?php foreach ($kategori as $row) : ?>
                           <?php if ($row->id == $data->kategori_id) : ?>
                              <option value="<?= $row->id ?>" selected><?= $row->nama_kategori ?></option>
                           <?php else : ?>
                              <option value="<?= $row->id ?>"><?= $row->nama_kategori ?></option>
                           <?php endif; ?>
                        <?php endforeach; ?>
                     </select>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="jumlah-buku">Jumlah buku <span class="text-danger">*</span></label>
                     <input type="number" name="jumlah_buku" class="form-control" id="jumlah-buku" value="<?= $data->jumlah ?>" required>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="penerbit">Penerbit <span class="text-danger">*</span></label>
                     <input type="text" name="penerbit" class="form-control" id="penerbit" placeholder="J. Halim" value="<?= $data->penerbit ?>">
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="pengarang">Pengarang <span class="text-danger">*</span></label>
                     <input type="text" name="pengarang" class="form-control" id="pengarang" placeholder="Moh. Hatta" value="<?= $data->pengarang ?>">
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="tahun-terbit">Tahun terbit <span class="text-danger">*</span></label>
                     <input type="text" name="tahun_terbit" class="form-control" id="tahun-terbit" placeholder="2010" value="<?= $data->tahun_terbit ?>">
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="isbn">ISBN</label>
                     <input type="number" name="isbn" class="form-control" id="isbn" placeholder="978623xxxx" value="<?= $data->isbn ?>">
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="sampul-buku">Sampul buku <span class="small">(max: 2MB)</span></label>
                     <div class="input-group">
                        <div class="custom-file">
                           <input type="file" name="foto" class="custom-file-input" id="sampul-buku">
                           <label class="custom-file-label font-weight-normal" for="sampul-buku"><?= $data->foto ?></label>
                        </div>
                     </div>
                     <img id="preview-sampul" src="<?= base_url('assets/img/' . $data->foto) ?>" class="mt-3" alt="Sampul Buku" style="width: 120px;">
                  </div>
               </div>
            </div>
         </div>
         <div class="card-footer d-flex justify-content-between">
            <button type="button" id="hapus-buku" class="btn btn-danger"><i class="fa fa-trash-alt mr-2"></i>Hapus</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Simpan</button>
         </div>
      </form>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<script>
   $('#sampul-buku').change(function() {
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
               $('#preview-sampul').attr('src', e.target.result)
            }
            reader.readAsDataURL(this.files[0]);
            $('.custom-file-label').text(this.files[0].name)
         }
      }
   })
   $('#ubah-buku').submit(function(e) {
      e.preventDefault();
      var data = new FormData($(this)[0]);
      $.ajax({
         url: "<?= base_url('admin/ubah_buku/' . $data->id) ?>",
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
                  window.location.href = "<?= base_url('admin/daftar_buku') ?>";
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
   $('#hapus-buku').click(function() {
      $.ajax({
         url: "<?= base_url('admin/hapus_buku/' . $data->id) ?>",
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
                  window.location.href = "<?= base_url('admin/daftar_buku') ?>";
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