<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <?php if ($this->session->flashdata('success')) {
          echo '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">'. $this->session->flashdata('success') .'</div>';
        } ?>
        <div class="card card-info card-outline">
          <div class="card-header">
            <h3 class="card-title"><?= $header ?></h3>
            <div class="card-tools">
              <a href="<?= site_url('signa') ?>" class="btn btn-tool"><i class="fas fa-times"></i></a>
            </div>
          </div>
          <div class="card-body">
            <div class="form-group">
              <select name="signa_id" id="signa_id" class="form-control select2" style="width: 100%;">
                <option value="">Cari Signa</option>
                <?php foreach ($signa as $o) {
                  echo '<option value="'. base64_encode($o->signa_id) .'">'. $o->signa_kode .' - '. $o->signa_nama .'</option>';
                } ?>
              </select>
            </div>

            <?php $list_fields = array(
              'Kode Signa'       => $row->signa_kode,
              'Nama Signa'       => $row->signa_nama,
              'Keterangan'      => $row->additional_data ? $row->additional_data : '-',
              'Dibuat Tanggal'  => $row->created_date ? $this->include->datetime($row->created_date) : '-',
              'Dibuat Oleh'     => $row->created_by ? $row->created_name : '-',
              'Diubah Tanggal'  => $row->last_modified_date ? $this->include->datetime($row->last_modified_date) : '-',
              'Diubah Oleh'     => $row->last_modified_by ? $row->modified_name : '-',
              'Aktif'           => $row->is_active == 1 ? 'Ya' : 'Tidak',
            ); ?>

            <div class="table-responsive">
              <table class="table" style="width: 100%;">
                <?php foreach ($list_fields as $key => $value): ?>
                  <tr>
                    <td style="width: 25%; padding-left: 0px;"><?= $key ?></td>
                    <td style="width: 5%;">:</td>
                    <td style="padding-right: 0px;"><?= $value ?></td>
                  </tr>
                <?php endforeach ?>
                <tr>
                  <td colspan="3" style="text-align: right; padding-right: 0px; padding-bottom: 0px;">
                    <a href="<?= site_url('edit/signa/'. base64_encode($row->signa_id)) ?>" class="btn btn-success btn-sm" style="font-weight: bold;"><i class="fas fa-edit"></i> Ubah</a>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  $(function() {

    $('[name="signa_id"]').change(function() {
      if ($(this).val()) {
        window.location.href = '<?= site_url('detail/signa') ?>/' + $(this).val();
      }
    });

  });

</script>