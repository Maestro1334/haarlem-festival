<?php require APPROOT . '/views/partial/header.php'; ?>
<?php require APPROOT . '/views/partial/navbar.php'; ?>

<main class="content-reset-password">
  <input type="hidden" id="enableNavbarBackground">
  <section>
    <div class="card border-secondary user-card">
      <div class="card-body">
        <div class="row card-heading">
          <p class="card-title">RESET PASSWORD</p>
        </div>
        <?php flash('token_message'); ?>
        <div class="row">
          <form action="<?php echo URLROOT; ?>/user/reset_password" method="POST" class="user-form">
            <input type="hidden" name="token" value="<?php echo $data['token']; ?>">
            <div class="form-group">
              <label class="label-text" for="password">New password</label>
              <input type="password" name="password" class="form-control text-box <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>" <?php echo (!empty($data['token_err'])) ? 'disabled' : '' ?>>
              <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
            </div>
            <div class="form-group">
              <label class="label-text" for="confirm_password">Confirm password</label>
              <input type="password" name="confirm_password" class="form-control text-box <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" <?php echo (!empty($data['token_err'])) ? 'disabled' : '' ?>>
              <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
            </div>
            <input type="submit" class="btn btn-block btn-submit" value="RESET PASSWORD" <?php echo (!empty($data['token_err'])) ? 'disabled' : '' ?>>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require APPROOT . '/views/partial/footer.php'; ?>