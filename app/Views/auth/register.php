<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block" style="background: #4e73df; display: flex; align-items: center; justify-content: center;">
                        <div style="text-align: center; color: white; padding: 40px;">
                            <i class="fas fa-user-plus fa-5x mb-4" style="opacity: 0.9;"></i>
                            <h2 style="font-weight: 700; margin-bottom: 15px;">Daftar Akun Baru</h2>
                            <p style="font-size: 18px; opacity: 0.85;">Buat akun untuk mengakses sistem pendataan SPP</p>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger">
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" action="<?= base_url('auth/register') ?>" class="user">
                                <?= csrf_field() ?>
                                
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control form-control-user" 
                                           placeholder="Username" required>
                                </div>
                                
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-user" 
                                           placeholder="Email Address" required>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" name="password" class="form-control form-control-user" 
                                               placeholder="Password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" name="confirm_password" class="form-control form-control-user" 
                                               placeholder="Repeat Password" required>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>
                            
                            <hr>
                            <div class="text-center">
                                <a class="small" href="<?= base_url('login') ?>">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/sbadmin2/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/sbadmin2/js/sb-admin-2.min.js') ?>"></script>

</body>

</html>