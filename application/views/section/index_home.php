<section class="content">
  <div class="container-fluid">
    <div class="row">
      <?php foreach ($list_info as $key1 => $value1): ?>
        <div class="col-12 col-sm-6 col-md-6">
          <div class="info-box">
            <?php foreach ($value1 as $key2 => $value2): ?>
              <span class="info-box-icon bg-info elevation-1" style="color: white;"><?= $key2 ?></span>
              <div class="info-box-content">
                <span class="info-box-text"><?= $key1 ?></span>
                <span class="info-box-number"><?= $value2 ?></span>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      <?php endforeach ?>
    </div>
    <div class="row">
      <div class="col-12">
        <?php if ($this->session->flashdata('success')) {
          echo '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">'. $this->session->flashdata('success') .'</div>';
        } ?>
        <div id="response-data"></div>
        <div class="card card-info card-outline">
          <div class="card-header">
            <h3 class="card-title">Daftar Resep Obat</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="table" class="table table-bordered" style="width: 100%;">
                <thead>
                  <tr>
                    <?php

                    $thead = array(
                      '<th style="width: 5%; text-align: center;">No</th>',
                      '<th>Kode</th>',
                      '<th>Obat</th>',
                      '<th>Qty</th>',
                      '<th>Signa</th>',
                      '<th>Keterangan</th>',
                      '<th style="width: 5%; text-align: center;">Aksi</th>',
                    );

                    $targets = array();
                    for ($i=0; $i < count($thead); $i++) { 
                      if ($i >= 5) {
                        $targets[] = $i;
                      }
                      echo $thead[$i];
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
  </div>
</section>

<script type="text/javascript">
  $(function() {

    table = $('#table').DataTable({
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
        "sZeroRecords": "",
        "sEmptyTable": "",
        "sSearch": "Cari:"
      },
      "order": [],
      "ajax": {
        "url": "<?= site_url('list/racikan') ?>",
        "type": "POST",
        "data": function(data) {
          data.id_signa = true;
        },
      },
      "columnDefs": [{ 
        "targets": <?= json_encode($targets) ?>,
        "orderable": false,
      }],
    });

  });

  function delete_data(id) {
    if (confirm('Apakah anda yakin?')) {
      $.getJSON('<?= site_url('delete/resepobat') ?>/' + id, function(response) {
        if (response.status) {
          table.ajax.reload();
          $('#response-data').html('');
          $(window).scrollTop(0);
          $('<div class="alert alert-success alert-dismissible" id="alert-data" style="font-weight: bold;">'+ response.message +'</div>').show().appendTo('#response-data');
          $('#alert-data').delay(2750).slideUp('slow', function(){
              $(this).remove();
          });
        }
      });
    }
  }

</script>