<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <div class="card card-info card-outline">
          <div class="card-header">
            <h3 class="card-title"><?= $header ?></h3>
            <div class="card-tools">
              <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-tool"><i class="fas fa-times"></i></a>
            </div>
          </div>
          <div class="card-body">

            <?php $list_fields = array(
              'obatalkes_kode' => 'Kode Obat',
              'obatalkes_nama' => 'Nama Obat',
            ); ?>


            <form action="" method="post" id="form-data" enctype="multipart/form-data">

              <input type="hidden" name="obatalkes_id" value="<?= @$row->obatalkes_id ?>">

              <?php foreach ($list_fields as $key => $value): ?>
                <div class="form-group">
                  <label for="<?= $key ?>"><?= $value ?></label>
                  <input type="text" class="form-control" id="<?= $key ?>" name="<?= $key ?>" value="<?= @$row->$key ?>" placeholder="<?= $value ?>" autocomplete="off">
                  <span id="error-<?= $key ?>" class="error invalid-feedback"></span>
                </div>
              <?php endforeach ?>

              <?php if (isset($row->obatalkes_id)): ?>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="stok_awal">Stok Awal</label>
                      <input type="text" class="form-control" id="stok_awal" name="stok_awal" value="<?= intval($row->stok) ?>" placeholder="Stok Awal" autocomplete="off" disabled>
                      <span id="error-stok_awal" class="error invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="stok_tersedia">Stok Tersedia</label>
                      <input type="text" class="form-control" id="stok_tersedia" name="stok_tersedia" value="<?= $stok_tersedia ?>" placeholder="Stok Tersedia" autocomplete="off" disabled>
                      <span id="error-stok_tersedia" class="error invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="stok_terpakai">Stok Terpakai</label>
                      <input type="text" class="form-control" id="stok_terpakai" name="stok_terpakai" value="<?= $stok_terpakai ?>" placeholder="Stok Terpakai" autocomplete="off" disabled>
                      <span id="error-stok_terpakai" class="error invalid-feedback"></span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="stok">Stok</label>
                  <input type="text" class="form-control" id="stok" name="stok" value="" placeholder="Stok" autocomplete="off">
                  <span id="error-stok" class="error invalid-feedback"></span>
                </div>
              <?php else: ?>
                <div class="form-group">
                  <label for="stok">Stok</label>
                  <input type="text" class="form-control" id="stok" name="stok" value="" placeholder="Stok" autocomplete="off">
                  <span id="error-stok" class="error invalid-feedback"></span>
                </div>
              <?php endif ?>

              <div class="form-group">
                <label for="additional_data">Keterangan</label>
                <textarea name="additional_data" id="additional_data" class="form-control" placeholder="Keterangan"><?= @$row->additional_data ?></textarea>
                <span id="error-additional_data" class="error invalid-feedback"></span>
              </div>
              <div class="form-group">
                <label for="is_active">Aktif</label>
                <select name="is_active" id="is_active" class="form-control select2" style="width: 100%;">
                  <option value="1" <?php if (@$row->is_active == '1') echo 'selected'; ?>>Ya</option>
                  <option value="0" <?php if (@$row->is_active == '0') echo 'selected'; ?>>Tidak</option>
                </select>
                <span id="error-is_active" class="error invalid-feedback"></span>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-secondary btn-sm" style="font-weight: bold;"><i class="fas fa-angle-double-left"></i> Batal</a>
            <button type="button" id="btn-save" class="btn btn-success btn-sm float-right" style="font-weight: bold;"><i class="fas fa-save"></i> Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  $(function() {

    $('#btn-save').click(function() {
      $.ajax({
        url: '<?= base_url('save/obat') ?>',
        type: 'POST',
        dataType: 'json',
        data: new FormData($('#form-data')[0]),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function(response) {
          if (response.status) {
            window.location.href = '<?= base_url('detail/obat') ?>/' + response.obatalkes_id;
          } else {
            $.each(response.errors, function (key, val) {

                $('[name="' + key + '"]').addClass('is-invalid');
                $('#error-'+ key +'').text(val).show();

                if (val === '') {
                    $('[name="' + key + '"]').removeClass('is-invalid');
                    $('#error-'+ key +'').text('').hide();
                }

                $('[name="' + key + '"]').on('change keyup', function(event) {
                  $('[name="' + key + '"]').removeClass('is-invalid');
                  $('#error-'+ key +'').text('').hide();
                });
            });
          }

        }

      });

    });

  });

</script>