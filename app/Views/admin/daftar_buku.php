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
               <div class="row">
                  <div class="col-6 col-md-3">
                     <a href="<?= base_url('admin/tambah_buku') ?>" class="btn btn-primary mb-3"><i class="fa fa-plus mr-2"></i>Tambah buku</a>
                  </div>
                  <div class="col-6 col-md-3">
                     <div class="form-group">
                        <select class="form-control border-secondary" name="kategori" id="kategori">
                           <option value="" selected>Semua Kategori</option>
                           <?php foreach ($kategori as $row) : ?>
                              <option value="<?= $row->id ?>"><?= $row->nama_kategori ?></option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="table-responsive">
                  <table id="daftar-buku" class="table table-striped table-hover">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Nama buku</th>
                           <th>Kode buku</th>
                           <th>Kategori</th>
                           <th>Pengarang</th>
                           <th>Penerbit</th>
                           <th>Tahun terbit</th>
                           <th>ISBN</th>
                           <th>Aksi</th>
                        </tr>
                     </thead>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script src="<?= base_url('assets/js/plugin/datatables/datatables.min.js') ?>"></script>

<script>
   $(document).on('change', '#kategori', function() {
      daftar_buku.ajax.reload();
   })
   var daftar_buku = $('#daftar-buku').DataTable({
      processing: true,
      serverSide: true,
      scrollX: false,
      order: [
         [8, 'desc']
      ],
      ajax: {
         url: "<?= base_url('admin/get_daftar_buku') ?>",
         type: "post",
         data: function(data) {
            data.kategori = $('#kategori').val();
         }
      },
      columns: [{
            "data": null,
            "mRender": function(data, row, type, meta) {
               return meta.row + meta.settings._iDisplayStart + 1;
            }
         },
         {
            "data": "nama_buku"
         },
         {
            "data": "kode_buku"
         },
         {
            "data": "nama_kategori"
         },
         {
            "data": "penerbit"
         },
         {
            "data": "pengarang"
         },
         {
            "data": "tahun_terbit"
         },
         {
            "data": "isbn"
         },
         {
            "data": "id",
            "mRender": function(buku_id) {
               return '<a href="<?= base_url('admin/detail_buku') ?>/' + buku_id + '" class="btn btn-xs btn-primary font-weight-bold mr-1"><i class="fa fa-edit mr-1"></i>Detail</a><button class="btn btn-xs btn-danger hapus-buku font-weight-bold" data-id="' + buku_id + '"><i class="fa fa-trash-alt mr-1"></i>Hapus</button>';
            },
            "className": "text-nowrap",
            "orderable": false
         }
      ]
   })
   $(document).on('click', 'button.hapus-buku', function() {
      var buku_id = $(this).data('id');
      swal({
         title: "Peringatan!",
         text: "Yakin ingin menghapus buku ini ?",
         icon: "warning",
         buttons: {
            confirm: {
               text: "Ya, hapus saja!",
               className: "btn btn-success"
            },
            cancel: {
               visible: true,
               text: "Batal",
               className: "btn btn-danger"
            }
         }
      }).then((result) => {
         if (result) {
            $.ajax({
               url: "<?= base_url('admin/hapus_buku') ?>/" + buku_id,
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
                        daftar_buku.ajax.reload();
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
         }
      })
   })
</script>
<?= $this->endSection() ?>