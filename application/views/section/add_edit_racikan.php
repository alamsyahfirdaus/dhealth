<section class="content">
  <div class="container-fluid">
    <form action="" method="post" id="form-data" enctype="multipart/form-data">
      <div class="row">
        <div class="col-lg-6">
          <div class="card card-info card-outline">
            <div class="card-header">
              <h3 class="card-title"><?= $header ?></h3>
              <div class="card-tools">
                <!-- <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-tool"><i class="fas fa-times"></i></a> -->
              </div>
            </div>
            <div class="card-body">
              <input type="hidden" name="id_resep_obat" value="<?= @$row->id_resep_obat ?>">
              <div class="form-group">
                <label for="kode">Kode Non Racikan</label>
                <input type="text" class="form-control" id="kode" name="kode" value="<?= @$row->kode ?>" placeholder="Kode Non Racikan" autocomplete="off">
                <span id="error-kode" class="error invalid-feedback"></span>
              </div>
              <div class="form-group">
                <label for="id_obatalkes">Obat</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="id_obatalkes" name="id_obatalkes" value="" placeholder="Obat" autocomplete="off" readonly disabled>
                  <span class="input-group-append">
                    <button type="button" class="btn btn-info" onclick="add_obat();"><i class="fas fa-plus"></i></button>
                  </span>
                </div>
                <span id="error-id_obatalkes" class="error invalid-feedback"></span>
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
            </div>
            <div class="card-footer">
              <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-secondary btn-sm" style="font-weight: bold;"><i class="fas fa-angle-double-left"></i> Batal</a>
              <button type="button" id="btn-save" class="btn btn-success btn-sm float-right" style="font-weight: bold;"><i class="fas fa-save"></i> Simpan</button>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card card-info card-outline">
            <div class="card-header">
              <h3 class="card-title">Daftar Obat</h3>
              <div class="card-tools">
                <!-- <a href="<?= site_url('racikan') ?>" class="btn btn-tool"><i class="fas fa-times"></i></a> -->
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="tb-racikan" class="table table-bordered" style="width: 100%;">
                  <thead>
                    <tr>
                      <?php

                      $thead1 = array(
                        '<th style="width: 5%; text-align: center;">No</th>',
                        '<th>Obat</th>',
                        '<th>Qty</th>',
                        '<th style="width: 5%; text-align: center;">Aksi</th>',
                      );

                      $targets1 = array();
                      for ($i=0; $i < count($thead1); $i++) { 
                        if ($i == count($thead1) - 1) {
                          $targets1[] = $i;
                        }
                        echo $thead1[$i];
                      }

                      ?>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>

<div class="modal fade" id="modal-form">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="tb-obat" class="table table-bordered" style="width: 100%;">
            <thead>
              <tr>
                <?php

                $thead2 = array(
                  '<th style="width: 5%; text-align: center;">No</th>',
                  '<th>Kode<span style="color: white;">_</span>Obat</th>',
                  '<th>Nama<span style="color: white;">_</span>Obat</th>',
                  '<th>Stok</th>',
                  '<th>Qty</th>',
                  '<th style="width: 5%; text-align: center;">Aksi</th>',
                );

                $targets2 = array();
                for ($i=0; $i < count($thead2); $i++) { 
                  if ($i >= 4) {
                    $targets2[] = $i;
                  }
                  echo $thead2[$i];
                }

                ?>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  var tb_racikan;
  var tb_obat;
  var obatalkes_id = [];
  var id_racikan   = [];

  $(function() {

    $('#btn-save').click(function() {
      var form_data = new FormData($('#form-data')[0]);
      var list_data = {'id_obatalkes': $('[name="id_obatalkes').val(), 'qty': 1};
      $.each(list_data, function(index, val) {
         form_data.append(index, val);
      });

      $.ajax({
        url: '<?= base_url('save/resepobat') ?>',
        type: 'POST',
        dataType: 'json',
        data: form_data,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function(response) {
          if (response.status) {
            window.location.href = '<?= base_url('racikan') ?>';
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

    tb_racikan = $('#tb-racikan').DataTable({
      "processing": false,
      "serverSide": true,
      "searching": true,
      "info": true,
      "ordering": true,
      "lengthChange": true,
      "paging": true,
      "autoWidth": false,
      "responsive": false,
      "language": { 
        "infoFiltered": "",
        "sZeroRecords": "Obat tidak ditemukan!",
        "sEmptyTable": "",
        "sSearch": "Cari:"
      },
      "order": [],
      "ajax": {
        "url": "<?= site_url('list/racikanobat') ?>",
        "type": "POST",
        "data": function(data) {
          data.obatalkes_id = obatalkes_id;
          data.id_racikan = $('[name="id_resep_obat').val();
        },
      },
      "drawCallback": function(settings) {
        if (settings.json.recordsFiltered > 0) {
          $('[name="id_obatalkes').val(settings.json.recordsFiltered).change();
        } else {
          $('[name="id_obatalkes').val('').change();
        }
      },
      "columnDefs": [{ 
        "targets": <?= json_encode($targets1) ?>,
        "orderable": false,
      }],
    });

    tb_obat = $('#tb-obat').DataTable({
      "processing": false,
      "serverSide": true,
      "searching": true,
      "info": true,
      "ordering": true,
      "lengthChange": true,
      "autoWidth": false,
      "responsive": false,
      "language": { 
        "infoFiltered": "",
        "sZeroRecords": "<?= $title ?> tidak ditemukan!",
        "sEmptyTable": "",
        "sSearch": "Cari:"
      },
      "order": [],
      "ajax": {
        "url": "<?= site_url('list/addobat') ?>",
        "type": "POST",
        "data": function(data) {

        },
      },
      "columnDefs": [{ 
        "targets": <?= json_encode($targets2) ?>,
        "orderable": false,
      }],
    });


  });

  function add_obat() {
    $('.qty').removeClass('is-invalid');
    $('.modal-title').text('Tambah Obat');
    $('#modal-form').modal('show');
  }

  function add_racikan(id) {
    var qty = $('[name="qty_'+ id +'"]').val();
    if ($.isNumeric(qty)) {
      if ($('[name="id_resep_obat').val()) {
        $.ajax({
          url: '<?= site_url('edit_qty/racikan') ?>',
          type: 'POST',
          dataType: 'json',
          data: {
            id_obatalkes: id,
            id_racikan: $('[name="id_resep_obat').val(),
            qty: qty,
          },
          success: function(response) {
            tb_racikan.ajax.reload();
            tb_obat.ajax.reload();
          }
        });
      } else {
        obatalkes_id.push({id, qty});
        tb_racikan.ajax.reload();
      }
    } else {
      $('[name="qty_'+ id +'"]').addClass('is-invalid');
      $('#error-qty_'+ id +'').text('Qty harus diisi.').show();
      $('[name="qty_'+ id +'"]').change(function() {
        $('[name="qty_'+ id +'"]').removeClass('is-invalid');
        $('#error-qty_'+ id +'').text('').hide();
      });
    }
  }

  function delete_racikan(id) {
    if (confirm('Apakah anda yakin?')) {
      if ($('[name="id_resep_obat').val()) {
        $.getJSON('<?= site_url('delete/resepobat') ?>/' + id, function(response) {
          if (response.status) {
            tb_racikan.ajax.reload();
            tb_obat.ajax.reload();
          }
        });
      } else {
        for (var i = 0; i < obatalkes_id.length; i++) {
          obatalkes_id.splice($.inArray(id, obatalkes_id), obatalkes_id.length);
        }
        tb_racikan.ajax.reload();
        $('#qty_'+ id +'').val('').change();
      }
    }

  }

</script>