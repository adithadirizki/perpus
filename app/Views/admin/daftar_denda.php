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
               <div class="table-responsive">
                  <table id="daftar-denda" class="table table-striped table-hover">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>No pinjam</th>
                           <th>Denda</th>
                           <th>Tanggal</th>
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
   function to_rupiah(amount) {
      var sisa = String(amount).length % 3;
      rupiah = String(amount).substr(0, sisa),
         ribuan = String(amount).substr(sisa).match(/\d{3}/g);
      if (ribuan) {
         separator = sisa ? '.' : ''
         rupiah += separator + ribuan.join('.')
      }
      return rupiah
   }
   var daftar_denda = $('#daftar-denda').DataTable({
      processing: true,
      serverSide: true,
      scrollX: false,
      ajax: {
         url: "<?= base_url('admin/get_daftar_denda') ?>",
         type: "post"
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
            "data": "denda",
            "mRender": function(denda) {
               return "Rp " + to_rupiah(denda);
            }
         },
         {
            "data": "tanggal"
         },
         {
            "data": "id",
            "mRender": function(denda_id) {
               return '<a href="<?= base_url('admin/detail_denda') ?>/' + denda_id + '" class="btn btn-xs btn-primary font-weight-bold mr-1"><i class="fa fa-edit mr-1"></i>Detail</a><button class="btn btn-xs btn-danger hapus-denda font-weight-bold" data-id="' + denda_id + '"><i class="fa fa-trash-alt mr-1"></i>Hapus</button>';
            },
            "className": "text-nowrap",
            "orderable": false
         }
      ]
   })
   $(document).on('click', 'button.hapus-denda', function() {
      var denda_id = $(this).data('id');
      swal({
         title: "Peringatan!",
         text: "Yakin ingin menghapus denda ini ?",
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
               url: "<?= base_url('admin/hapus_denda') ?>/" + denda_id,
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
                        daftar_denda.ajax.reload();
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