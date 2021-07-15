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
               <a href="<?= base_url('admin/tambah_kategori') ?>" class="btn btn-primary mb-3"><i class="fa fa-plus mr-2"></i>Tambah kategori</a>
               <div class="table-responsive">
                  <table id="kategori" class="table table-striped table-hover">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Kategori</th>
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
   var kategori = $('#kategori').DataTable({
      processing: true,
      serverSide: true,
      scrollX: false,
      ajax: {
         url: "<?= base_url('admin/get_daftar_kategori') ?>",
         type: "post"
      },
      columns: [{
            "data": null,
            "mRender": function(data, row, type, meta) {
               return meta.row + meta.settings._iDisplayStart + 1;
            }
         },
         {
            "data": "nama_kategori"
         },
         {
            "data": "id",
            "mRender": function(kategori_id) {
               return '<a href="<?= base_url('admin/detail_kategori') ?>/' + kategori_id + '" class="btn btn-xs btn-primary font-weight-bold mr-1"><i class="fa fa-edit mr-1"></i>Detail</a><button class="btn btn-xs btn-danger hapus-kategori font-weight-bold" data-id="' + kategori_id + '"><i class="fa fa-trash-alt mr-1"></i>Hapus</button>';
            },
            "className": "text-nowrap",
            "orderable": false
         }
      ]
   })
   $(document).on('click', 'button.hapus-kategori', function() {
      var kategori_id = $(this).data('id');
      swal({
         title: "Peringatan!",
         text: "Yakin ingin menghapus kategori ini ?",
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
               url: "<?= base_url('admin/hapus_kategori') ?>/" + kategori_id,
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
                        kategori.ajax.reload();
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