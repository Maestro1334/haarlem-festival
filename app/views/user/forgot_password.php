<?php require APPROOT . '/views/partial/header.php'; ?>
<?php require APPROOT . '/views/partial/navbar.php'; ?>

<main class="content-forgot-password">
  <input type="hidden" id="enableNavbarBackground">
  <section>
    <div class="card border-secondary user-card">
      <div class="card-body">
        <div class="row card-heading">
          <p class="card-title">FORGOT PASSWORD</p>
        </div>
        <?php flash('forgot_password_message'); ?>
        <div class="row">
          <form action="<?php echo URLROOT; ?>/user/forgot_password" method="POST" class="user-form">
            <div class="form-group">
              <label class="label-text" for="email">E-mail address</label>
              <input type="text" name="email" class="form-control text-box <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
              <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
            </div>
            <input type="submit" class="btn btn-block btn-submit" value="SEND EMAIL">
          </form>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require APPROOT . '/views/partial/footer.php'; ?>