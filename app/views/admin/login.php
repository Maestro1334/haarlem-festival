<?php require APPROOT . '/views/admin/partial/header.php'; ?>

<body class="bg-dark">
  <div class="container py-5">
    <div class="row">
      <div class="col-md-12">
        <h2 class="text-center text-white mb-4">Admin login</h2>
        <div class="row">
          <div class="col-md-6 mx-auto">
            <div class="card rounded-0">
              <div class="card-header">
                <h3 class="mb-0">Login</h3>
              </div>
              <?php flash('login_message');?>
              <div class="card-body">
                <form action="<?php echo URLROOT; ?>/admin/login" method="post" class="form" autocomplete="off">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control form-control-lg rounded-0 <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" name="username" placeholder="Email" value="<?php echo $data['username']; ?>">
                    <span class="invalid-feedback"><?php echo $data['username_err']; ?></span>
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control form-control-lg rounded-0 <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" name="password" placeholder="Password" value="<?php echo $data['password']; ?>">
                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                  </div>
                  <button class="btn login" type="submit" value="Login">Login</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>