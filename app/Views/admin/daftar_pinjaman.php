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
               <a href="<?= base_url('admin/tambah_pinjaman') ?>" class="btn btn-primary mb-3"><i class="fa fa-plus mr-2"></i>Tambah pinjaman</a>
               <div class="form-group col-sm-6 mb-4">
                  <label for="status">Status</label>
                  <select name="status" class="form-control border-dark" id="status">
                     <option value="">Semua</option>
                     <option value="0">Belum dikembalikan</option>
                     <option value="1">Sudah dikembalikan</option>
                     <option value="2">Menunggu konfirmasi</option>
                  </select>
               </div>
               <div class="table-responsive">
                  <table id="daftar-pinjaman" class="table table-striped table-hover">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>No pinjaman</th>
                           <th>Username</th>
                           <th>Buku</th>
                           <th>Tgl. pinjam</th>
                           <th>Tgl. tempo</th>
                           <th>Status</th>
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
   $(document).on('change', '#status', function() {
      daftar_pinjaman.ajax.reload();
   })
   var daftar_pinjaman = $('#daftar-pinjaman').DataTable({
      processing: true,
      serverSide: true,
      scrollX: false,
      order: [
         [4, 'desc']
      ],
      ajax: {
         url: "<?= base_url('admin/get_daftar_pinjaman') ?>",
         type: "post",
         data: function(data) {
            data.status = $('#status').val();
         }
      },
      columns: [{
            "data": null,
            "mRender": function(data, row, type, meta) {
               return meta.row + meta.settings._iDisplayStart + 1;
            }
         },
         {
            "data": "no_pinjam"
         },
         {
            "data": "username"
         },
         {
            "data": "nama_buku"
         },
         {
            "data": "start"
         },
         {
            "data": "end"
         },
         {
            "data": "status",
            "mRender": function(status) {
               switch (status) {
                  case "0":
                     return "Belum dikembalikan";
                     break;
                  case "1":
                     return "Sudah dikembalikan";
                     break;
                  case "2":
                     return "Menunggu konfirmasi";
                     break;
                  default:
                     return "Error status";
                     break;
               }
            }
         },
         {
            "data": "no_pinjam",
            "mRender": function(pinjaman_id) {
               return '<a href="<?= base_url('admin/detail_pinjaman') ?>/' + pinjaman_id + '" class="btn btn-xs btn-primary font-weight-bold mr-1"><i class="fa fa-edit mr-1"></i>Detail</a><button class="btn btn-xs btn-danger hapus-pinjaman font-weight-bold" data-id="' + pinjaman_id + '"><i class="fa fa-trash-alt mr-1"></i>Hapus</button>';
            },
            "className": "text-nowrap",
            "orderable": false
         }
      ]
   })
   $(document).on('click', 'button.hapus-pinjaman', function() {
      var pinjaman_id = $(this).data('id');
      swal({
         title: "Peringatan!",
         text: "Yakin ingin menghapus pinjaman ini ?",
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
               url: "<?= base_url('admin/hapus_pinjaman') ?>/" + pinjaman_id,
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
                        daftar_pinjaman.ajax.reload();
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