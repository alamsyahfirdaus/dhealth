<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>D'Health | Login</title>
        <link rel="icon" type="image/x-icon" href="<?= base_url('assets/dist/img/dhealth.jpg') ?>">
        <link href="<?= base_url('assets/') ?>css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="" style="font-family: Arial; background-image: url('<?= site_url('assets/dist/img/auth-mage.png') ?>'); background-repeat: no-repeat; background-size: 100% 100%;">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div style="padding-top: 20%;">
                                    <div class="card shadow-lg border-0 rounded-lg mt-5 card-info card-outline">
                                        <div class="card-header">
                                            <h3 class="text-center font-weight-light my-4" style="font-weight: bold;">D'HEALTH</h3>
                                        </div>
                                        <div class="card-body">
                                            <form action="" method="post" id="form-data">
                                                <div class="form-group form-floating mb-3">
                                                    <input class="form-control" id="email" name="email" type="text" placeholder="Email" autocomplete="off" />
                                                    <label for="email">Email</label>
                                                    <span id="error-email" class="error invalid-feedback"></span>
                                                </div>
                                                <div class="form-group form-floating mb-3">
                                                    <input class="form-control" id="password" name="password" type="password" placeholder="Password" autocomplete="off" />
                                                    <label for="password">Password</label>
                                                    <span id="error-password" class="error invalid-feedback"></span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                    <button type="submit" class="btn btn-info" style="width: 100%; font-weight: bold; background-color: ##17a2b8; color: white;">Login</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="card-footer text-center py-3">
                                            <!-- <div class="small"><a href="javascript:void(0)" style="text-decoration: none;">Lupa Password?</a></div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="<?= base_url('assets') ?>/js/scripts.js"></script>
        <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
        <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url('assets') ?>/plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="<?= base_url('assets') ?>/plugins/jquery-validation/additional-methods.min.js"></script>

        <script type="text/javascript">

            $(function () {
              $.validator.setDefaults({
                submitHandler: function () {
                  save_data();
                }
              });
              $('#form-data').validate({
                rules: {
                  email: {
                    required: true,
                    email: true,
                  },
                  password: {
                    required: true,
                  },
                },
                messages: {
                  email: {
                    required: "Email harus diisi.",
                    email: "Email tidak valid.",
                  },
                  password: {
                    required: "Password harus diisi.",
                  },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                  error.addClass('invalid-feedback');
                  element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                  $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                  $(element).removeClass('is-invalid');
                }
              });
            });

            function save_data() {
                $.ajax({
                  url: '<?= base_url('auth/login') ?>',
                  type: 'POST',
                  dataType: 'json',
                  data: new FormData($('#form-data')[0]),
                  processData: false,
                  contentType: false,
                  cache: false,
                  async: false,
                  success: function(response) {

                    if (response.status) {
                      window.location.href = '<?= base_url('home') ?>';
                    } else {
                      $.each(response.errors, function (key, val) {

                          $('[name="' + key + '"]').addClass('is-invalid');
                          $('#error-'+ key +'').text(val).show();

                          if (val === '') {
                              $('[name="' + key + '"]').removeClass('is-invalid');
                              $('#error-'+ key +'').text('').hide();
                          }

                          $('[name="' + key + '"]').keyup(function() {
                              $('[name="' + key + '"]').removeClass('is-invalid');
                              $('#error-'+ key +'').text('').hide();
                          });

                      });
                    }

                  }

                });
            }

        </script>
    </body>
</html>
