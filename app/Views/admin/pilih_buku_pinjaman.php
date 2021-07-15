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
      <div class="col-md-12">
         <div class="card">
            <div class="card-body">
               <table id="daftar-user" class="table table-striped table-hover">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Nama</th>
                     </tr>
                  </thead>
               </table>
            </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               .card-tools
            </div>
            <div class="card-body">
               <form action="<?= base_url('admin/tambah_pinjaman') ?>" method="post" id="pilih-buku">
                  <button class="btn btn-primary tambah-pinjaman mb-3"><i class="fa fa-check-circle mr-2"></i>Tambah ke pinjaman</button>
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
                     <button class="btn btn-primary tambah-pinjaman mt-3"><i class="fa fa-check-circle mr-2"></i>Tambah ke pinjaman</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script src="<?= base_url('assets/js/plugin/datatables/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/js/plugin/datatables/datatables.checkboxes.min.js') ?>"></script>

<script>
   var daftar_buku = $('#daftar-buku').DataTable({
      processing: true,
      serverSide: true,
      scrollX: false,
      order: [
         [1, 'asc']
      ],
      ajax: {
         url: "<?= base_url('admin/get_daftar_buku') ?>",
         type: "post"
      },
      columns: [{
            "data": "buku_id",
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
      scrollX: true,
      ajax: {
         url: "<?= base_url('admin/daftar_user') ?>",
         type: "post"
      },
      columns: [{
            "data": "id",
            "mRender": function(id) {
               return '<input type="radio" name="id" value="' + id + '">';
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
               return '<a href="<?= base_url('admin/detail_user') ?>/' + username + '" class="btn btn-xs btn-primary"></a>';
            }
         }
      ]
   })
</script>
<?= $this->endSection() ?>