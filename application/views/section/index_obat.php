<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div id="response-data"></div>
        <div class="card card-info card-outline">
          <div class="card-header">
            <h3 class="card-title">Daftar <?= $title ?></h3>
            <div class="card-tools">
              <a href="<?= site_url('add/obat') ?>" class="btn btn-tool"><i class="fas fa-plus"></i></a>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="table" class="table table-bordered" style="width: 100%;">
                <thead>
                  <tr>
                    <?php

                    $thead = array(
                      '<th style="width: 5%; text-align: center;">No</th>',
                      '<th>Kode<span style="color: white;">_</span>Obat</th>',
                      '<th>Nama<span style="color: white;">_</span>Obat</th>',
                      '<th>Stok<span style="color: white;">_</span>Awal</th>',
                      '<th>Stok<span style="color: white;">_</span>Tersedia</th>',
                      '<th style="width: 5%; text-align: center;">Aksi</th>',
                    );

                    $targets = array();
                    for ($i=0; $i < count($thead); $i++) { 
                      if ($i >= 4) {
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
        "sZeroRecords": "<?= $title ?> tidak ditemukan!",
        "sEmptyTable": "",
        "sSearch": "Cari:"
      },
      "order": [],
      "ajax": {
        "url": "<?= site_url('list/obat') ?>",
        "type": "POST",
        "data": function(data) {

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
      $.getJSON('<?= site_url('delete/obat') ?>/' + id, function(response) {
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