<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6">
        <div class="card card-info card-outline">
          <div class="card-header">
            <h3 class="card-title"><?= $header ?></h3>
            <div class="card-tools">
              <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-tool"><i class="fas fa-times"></i></a>
            </div>
          </div>
          <div class="card-body">
            <form action="" method="post" id="form-data" enctype="multipart/form-data">
              <input type="hidden" name="id_resep_obat" value="<?= @$row->id_resep_obat ?>">
              <div class="form-group">
                <label for="kode">Kode Non Racikan</label>
                <input type="text" class="form-control" id="kode" name="kode" value="<?= @$row->kode ?>" placeholder="Kode Non Racikan" autocomplete="off">
                <span id="error-kode" class="error invalid-feedback"></span>
              </div>
              <div class="form-group">
                <label for="id_obatalkes">Obat</label>
                <select name="id_obatalkes" id="id_obatalkes" class="form-control select2" style="width: 100%;"><?= $obat ?></select>
                <span id="error-id_obatalkes" class="error invalid-feedback"></span>
              </div>
              <div class="form-group">
                <label for="qty">Qty</label>
                <select name="qty" id="qty" class="form-control select2" style="width: 100%;">
                  <option value="">-- Qty --</option>
                  <?php if (isset($row->id_resep_obat)): ?>
                    <?php for ($i=1; $i <= $stok; $i++) { 
                      $selected = $i == $row->qty ? 'selected' : '';
                      echo '<option value="'. $i .'" '. $selected .'>'. $i .'</option>';
                    } ?>
                  <?php endif ?>
                </select>
                <span id="error-qty" class="error invalid-feedback"></span>
              </div>
              <div class="form-group">
                <label for="id_signa">Signa</label>
                <select name="id_signa" id="id_signa" class="form-control select2" style="width: 100%;">
                  <option value="">-- Signa --</option>
                  <?php foreach ($signa as $s) {
                    $selected = $s->signa_id == @$row->id_signa ? 'selected' : '';
                    echo '<option value="'. $s->signa_id .'" '. $selected .'>'. $s->signa_kode .' - '. $s->signa_nama .'</option>';
                  } ?>
                </select>
                <span id="error-id_signa" class="error invalid-feedback"></span>
              </div>
              <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan"><?= @$row->keterangan ?></textarea>
                <span id="error-keterangan" class="error invalid-feedback"></span>
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

    $('[name="id_obatalkes"]').change(function() {
      var id_obatalkes = $('[name="id_obatalkes"]').val() ? $('[name="id_obatalkes"]').val() : 0;
      $('[name="qty"]').find('option').not(':first').remove();
      $.getJSON('<?= site_url('list/qty') ?>/' + id_obatalkes, function(response) {
        var qty    = response;
        var option = [];
        for (var i = 1; i <= qty; i++) {
          option.push({
            id: i,
            text: i
          });
        }
        $('[name="qty"]').select2({data:option});
      });
    });

    $('#btn-save').click(function() {
      $.ajax({
        url: '<?= base_url('save/resepobat') ?>',
        type: 'POST',
        dataType: 'json',
        data: new FormData($('#form-data')[0]),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function(response) {
          if (response.status) {
            window.location.href = '<?= base_url('nonracikan') ?>';
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