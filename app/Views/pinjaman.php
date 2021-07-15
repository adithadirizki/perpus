<?= $this->extend('layout/home') ?>
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
      <div class="card-body">
         <a href="<?= base_url() ?>" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Tambah pinjaman</a>
         <div class="table-responsive">
            <table id="detail-pinjaman" class="table table-striped table-hover">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>No Pinjam</th>
                     <th>Status</th>
                     <th>Aksi</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  if ($data == []) {
                  ?>
                     <tr>
                        <td colspan="6">
                           <h3 class="text-center">Keranjang kosong</h3>
                        </td>
                     </tr>
                  <?php
                  }
                  $i = 1;
                  foreach ($data as $row) :
                  switch ($row->status) {
                     case 0:
                        $status = 'Belum dikembalikan';
                        break;
                     case 1:
                        $status = 'Sudah dikembalikan';
                        break;
                     case 2:
                        $status = 'Menunggu dikonfirmasi';
                        break;
                     default:
                        $status = 'Status Error';
                        break;
                  }
                  ?>
                     <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $row->no_pinjam ?></td>
                        <td><?= $status ?></td>
                        <td class="text-nowrap">
                           <a href="<?= base_url('pinjaman/' . $row->no_pinjam) ?>" class="btn btn-xs btn-primary font-weight-bold mr-1"><i class="fa fa-edit mr-2"></i>Detail</a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>