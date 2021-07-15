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
   <form action="" id="tambah-pinjaman">
      <div class="row">
         <div class="col-md-4 h-100">
            <div class="card">
               <div class="card-body">
                  <div class="form-group">
                     <label for="start">Tgl. Pinjam</label>
                     <input type="date" name="start" id="start" class="form-control" value="<?= date('Y-m-d') ?>">
                  </div>
                  <div class="form-group">
                     <label for="end">Tgl. Tempo</label>
                     <input type="date" name="end" id="end" class="form-control" value="<?= date('Y-m-d', strtotime("+7 day", time())) ?>">
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-8">
            <div class="card">
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="daftar-user" class="table table-striped table-hover">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Username</th>
                              <th>Email</th>
                              <th>Aksi</th>
                           </tr>
                        </thead>
                     </table>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-12">
            <div class="card">
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="daftar-buku" class="table table-striped table-hover">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Nama buku</th>
                              <th>Kode buku</th>
                              <th>Pengarang</th>
                              <th>Penerbit</th>
                              <th>ISBN</th>
                           </tr>
                        </thead>
                     </table>
                     <button class="btn btn-primary tambah-pinjaman mt-3 float-right"><i class="fa fa-check-circle mr-2"></i>Tambah ke pinjaman</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script src="<?= base_url('assets/js/plugin/datatables/datatables.min.js') ?>"></script>

<script>
   var daftar_buku = $('#daftar-buku').DataTable({
      processing: true,
      serverSide: true,
      scrollX: false,
      order: [
         [1, 'asc']
      ],
      ajax: {
         url: "<?= base_url('admin/get_buku_pinjaman') ?>",
         type: "post"
      },
      columns: [{
            "data": "id",
            "mRender": function(buku_id) {
               return '<input type="checkbox" name="id[]" value="' + buku_id + '">';
            }
         },
         {
            "data": "nama_buku"
         },
         {
            "data": "kode_buku"
         },
         {
            "data": "penerbit"
         },
         {
            "data": "pengarang"
         },
         {
            "data": "isbn"
         }
      ],
      drawCallback: function() {
         item_checked.forEach(index => {
            $('input[type="checkbox"][value="' + index + '"]').prop('checked', true);
         });
      }
   })
   var item_checked = [];
   $(document).on('click', 'input[type="checkbox"]', function() {
      var id = $(this).val();
      if (this.checked) {
         item_checked.push(id);
      } else {
         item_checked.splice(item_checked.indexOf(id), 1);
      }
   })
   var daftar_user = $('#daftar-user').DataTable({
      processing: true,
      serverSide: true,
      scrollX: false,
      ajax: {
         url: "<?= base_url('admin/get_user_pinjaman') ?>",
         type: "post"
      },
      columns: [{
            "data": "username",
            "mRender": function(username) {
               return '<input type="radio" name="username" value="' + username + '" required>';
            }
         },
         {
            "data": "username"
         },
         {
            "data": "nama"
         },
         {
            "data": "username",
            "mRender": function(username) {
               return '<a href="<?= base_url('admin/detail_user') ?>/' + username + '" class="btn btn-xs btn-primary"><i class="fa fa-edit mr-2"></i>Detail</a>';
            }
         }
      ]
   })
   $(document).on('submit', '#tambah-pinjaman', function(e) {
      e.preventDefault();
      if (item_checked.length == 0) {
         swal({
            title: "Peringatan!",
            text: "Tidak ada buku yang dipinjam, pilih buku terlebih dahulu.",
            icon: "warning",
            buttons: false,
            timer: 2000
         })
         return false;
      }
      var data = $(this).serialize();
      $.ajax({
         url: "<?= base_url('admin/tambah_pinjaman') ?>",
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
                  window.location.href = "<?= base_url('admin/daftar_pinjaman') ?>";
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