<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <?php if ($this->session->flashdata('success')) {
          echo '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">'. $this->session->flashdata('success') .'</div>';
        } ?>
        <div id="response-data"></div>
        <div class="card card-info card-outline">
          <div class="card-header">
            <h3 class="card-title">Profile Pengguna</h3>
            <div class="card-tools">
              <div class="btn-group">
                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                  <i class="fas fa-cogs"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                  <a href="javascript:void(0)" onclick="ubah_profile();" class="dropdown-item">Ubah Profile</a>
                  <div class="dropdown-divider"></div>
                  <a href="javascript:void(0)" onclick="ubah_foto();" class="dropdown-item">Ubah Foto</a>
                  <?php if ($pengguna->foto_profile): ?>
                    <div class="dropdown-divider"></div>
                    <a href="<?= base_url('delete_foto/pengguna/'. base64_encode($pengguna->id_pengguna)) ?>" onclick="return confirm('Apakah anda yakin?');" class="dropdown-item">Hapus Foto</a>
                  <?php endif ?>
                  <div class="dropdown-divider"></div>
                  <a href="javascript:void(0)" onclick="ubah_password();" class="dropdown-item">Ubah Password</a>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                <div class="card-body box-profile">
                  <div class="text-center">
                    <?php $foto_profile = $pengguna->foto_profile ? 'assets/img/'. $pengguna->foto_profile : 'assets/dist/img/default-150x150.png'; ?>
                    <img class="img-fluid" src="<?= site_url($foto_profile) ?>" alt="" style="width: 150px; height: 150px;">
                  </div>
                  <ul class="list-group list-group-unbordered mt-3">
                    <?php 

                    $profile_pengguna = array(
                      'Nama<span style="color: white;">_</span>Lengkap' => $pengguna->nama_pengguna,
                      'Email' => $pengguna->email ? $pengguna->email : '-',
                    );

                    foreach ($profile_pengguna as $key => $value) {
                      $list = '<li class="list-group-item">';
                      $list .= '<b>'. $key .'</b><span class="float-right">'. $value .'</span>';
                      $list .= '</li>';
                      echo $list;
                    }

                    ?>
                  </ul>
                </div>
              </div>
              <div class="col-md-3"></div>
            </div>
          </div>
        </div>
        <div class="card card-info card-outline">
          <div class="card-header">
            <h3 class="card-title">Daftar Pengguna</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" onclick="tambah_pengguna();"><i class="fas fa-plus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="table-pengguna" class="table table-bordered" style="width: 100%;">
                <thead>
                  <tr>
                    <?php

                    $thead1 = array(
                      '<th style="width: 5%; text-align: center;">No</th>',
                      '<th>Nama<span style="color: white;">_</span>Pengguna</th>',
                      '<th>Email</th>',
                      '<th style="text-align: center;">Foto</th>',
                      '<th style="width: 5%; text-align: center;">Aksi</th>',
                    );

                    $targets1 = array();
                    for ($i=0; $i < count($thead1); $i++) { 
                      if ($i >= 1) {
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
  </div>
</section>

<form action="<?= base_url('update_foto/pengguna/'. base64_encode($pengguna->id_pengguna)) ?>" method="post" id="form-foto_profile" enctype="multipart/form-data" style="display: none;">
  <input type="file" name="foto_profile" accept=".jpg, .png, .gif, .jpeg">
</form>

<div class="modal fade" id="modal-pengguna">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="form-pengguna">
          <input type="text" name="id_pengguna" class="form-control" value="" style="display: none;">
          <div class="form-group">
            <label for="nama_pengguna">Nama Pengguna</label>
            <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" value="" placeholder="Nama Pengguna" autocomplete="off">
            <span id="error-nama_pengguna" class="error invalid-feedback"></span>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" class="form-control" value="" placeholder="Email" autocomplete="off">
            <span id="error-email" class="error invalid-feedback"></span>
          </div>
          <div class="form-group" id="input-password">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="" placeholder="Password" autocomplete="off">
            <span id="error-password" class="error invalid-feedback"></span>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="font-weight: bold;"><i class="fas fa-angle-double-left"></i> Batal</button>
        <button type="button" onclick="save_pengguna();" class="btn btn-success btn-sm" style="font-weight: bold;"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-password">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="form-password">
          <div class="form-group">
            <label for="password1">Password Sekarang</label>
            <input type="password" name="password1" id="password1" class="form-control" value="" placeholder="Password Sekarang" autocomplete="off">
            <span id="error-password1" class="error invalid-feedback"></span>
          </div>
          <div class="form-group" id="input-password">
            <label for="password2">Password Baru</label>
            <input type="password" name="password2" id="password2" class="form-control" value="" placeholder="Password Baru" autocomplete="off">
            <span id="error-password2" class="error invalid-feedback"></span>
          </div>
          <div class="form-group" id="input-password">
            <label for="password3">Konfirmasi Password</label>
            <input type="password" name="password3" id="password3" class="form-control" value="" placeholder="Konfirmasi Password" autocomplete="off">
            <span id="error-password3" class="error invalid-feedback"></span>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="font-weight: bold;"><i class="fas fa-angle-double-left"></i> Batal</button>
        <button type="button" onclick="save_password();" class="btn btn-success btn-sm" style="font-weight: bold;"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {

    tb_pengguna = $('#table-pengguna').DataTable({
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
        "url": "<?= site_url('list/pengguna') ?>",
        "type": "POST",
        "data": function(data) {

        },
      },
      "columnDefs": [{ 
        "targets": <?= json_encode($targets1) ?>,
        "orderable": false,
      }],
    });

    $('[name="foto_profile"]').change(function() {
      if ($(this).val()) {
        if ($('[name="id_pengguna"]').val()) {
          save_foto($('[name="id_pengguna"]').val());
        } else {
          $('#form-foto_profile').submit();
        }

      }
    });

  });

  function ubah_profile() {
    $('#input-password').hide();
    $('#form-pengguna .form-control').val('').change();
    $('#form-pengguna .form-control').removeClass('is-invalid');
    $('[name="id_pengguna"]').val('<?= $pengguna->id_pengguna ?>').change();
    $('[name="nama_pengguna"]').val('<?= $pengguna->nama_pengguna ?>').change();
    $('[name="email"]').val('<?= $pengguna->email ?>').change();
    $('.modal-title').text('Ubah pengguna');
    $('#modal-pengguna').modal('show');
  }

  function ubah_foto(id_pengguna = null) {
    if (id_pengguna != null) {
      $('[name="id_pengguna"]').val(id_pengguna).change();
    } else {
      $('[name="id_pengguna"]').val('').change();
    }
    $('[name="foto_profile"]').click();
  }

  function save_foto(id_pengguna) {
    $.ajax({
      url: '<?= base_url('update_foto/pengguna') ?>/' + id_pengguna,
      type: 'POST',
      dataType: 'json',
      data: new FormData($('#form-foto_profile')[0]),
      processData: false,
      contentType: false,
      cache: false,
      async: false,
      success: function(response) {
        if (response.status) {
          tb_pengguna.ajax.reload();
          $('#response-data').html('');
          $(window).scrollTop(0);
          $('<div class="alert alert-success alert-dismissible" id="alert-data" style="font-weight: bold;">'+ response.message +'</div>').show().appendTo('#response-data');
          $('#alert-data').delay(2750).slideUp('slow', function(){
              $(this).remove();
          });
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
  }

  function ubah_password() {
    $('#form-password .form-control').val('').change();
    $('#form-password .form-control').removeClass('is-invalid');
    $('.modal-title').text('Ubah Password');
    $('#modal-password').modal('show');
  }

  function save_password() {
    $.ajax({
      url: '<?= base_url('password/pengguna/'. base64_encode($pengguna->id_pengguna)) ?>',
      type: 'POST',
      dataType: 'json',
      data: new FormData($('#form-password')[0]),
      processData: false,
      contentType: false,
      cache: false,
      async: false,
      success: function(response) {
        if (response.status) {
          $('#modal-password').modal('hide');
          $('#response-data').html('');
          $(window).scrollTop(0);
          $('<div class="alert alert-'+ response.type +' alert-dismissible" id="alert-data" style="font-weight: bold;">'+ response.message +'</div>').show().appendTo('#response-data');
          $('#alert-data').delay(2750).slideUp('slow', function(){
              $(this).remove();
          });
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
  }

  function tambah_pengguna() {
    $('#input-password').show();
    $('#form-pengguna .form-control').val('').change();
    $('#form-pengguna .form-control').removeClass('is-invalid');
    $('.modal-title').text('Tambah Pengguna');
    $('#modal-pengguna').modal('show');
  }

  function edit_pengguna(id) {
    var nama_pengguna   = $('[name="nama_pengguna_'+ id +'"]').val();
    var email = $('[name="email_'+ id +'"]').val();

    $('#input-password').show();
    $('#form-pengguna .form-control').val('').change();
    $('#form-pengguna .form-control').removeClass('is-invalid');

    $('[name="id_pengguna"]').val(id).change();
    $('[name="nama_pengguna"]').val(nama_pengguna).change();
    $('[name="email"]').val(email).change();
    $('.modal-title').text('Ubah Pengguna');
    $('#modal-pengguna').modal('show');
  }

  function save_pengguna() {
    $.ajax({
      url: '<?= base_url('save/pengguna') ?>',
      type: 'POST',
      dataType: 'json',
      data: new FormData($('#form-pengguna')[0]),
      processData: false,
      contentType: false,
      cache: false,
      async: false,
      success: function(response) {
        if (response.status) {
          $('#modal-pengguna').modal('hide');
          if (response.id_pengguna) {
            setTimeout(function() {
              window.location.reload();
            }, 375);
          } else {
            tb_pengguna.ajax.reload();
            $('#response-data').html('');
            $(window).scrollTop(0);
            $('<div class="alert alert-success alert-dismissible" id="alert-data" style="font-weight: bold;">'+ response.message +'</div>').show().appendTo('#response-data');
            $('#alert-data').delay(2750).slideUp('slow', function(){
                $(this).remove();
            });
          }
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
  }

  function delete_pengguna(id) {
    if (confirm('Apakah anda yakin?')) {
      $.getJSON('<?= site_url('delete/pengguna') ?>/' + id, function(response) {
        if (response.status) {
          tb_pengguna.ajax.reload();
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