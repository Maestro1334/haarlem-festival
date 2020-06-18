<section>
  <div class="card border-secondary user-card">
    <div class="card-body">
      <div class="row card-heading">
        <p class="card-title">LOGIN</p>
      </div>
      <?php flash('login_message'); ?>
      <div class="row">
        <form action="<?php echo URLROOT . '/user/login'; ?>" method="POST" class="user-form">
          <div class="form-group">
            <label class="label-text" for="email">E-mail address</label>
            <input type="text" name="email" class="form-control text-box <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="">
            <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
          </div>
          <div class="form-group">
            <label class="label-text" for="password">Password</label>
            <input type="password" name="password" class="form-control form-control-md text-box <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
            <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
            <a href="<?php echo URLROOT; ?>/user/forgot_password" class="btn btn-sm mt-1 btn-forgot-password">Forgot password?</a>
          </div>
            <input type="hidden" name="location" id="location" value="<?php echo $location ?>">
          <input type="submit" class="btn btn-block btn-submit" value="LOGIN">
        </form>
      </div>
    </div>
  </div>
  <a href="<?php echo URLROOT . $redirectLink; ?>" class="btn btn-block btn-change-form">I'M A NEW CUSTOMER</a>
</section>