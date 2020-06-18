<?php require APPROOT . '/views/partial/header.php'; ?>
<?php require APPROOT . '/views/partial/navbar.php'; ?>

<main class="content-login">
  <input type="hidden" id="enableNavbarBackground">
  <?php $formLink = '/user/login'; $redirectLink = '/user/register'; require APPROOT . '/views/partial/login.php' ?>
</main>

<?php require APPROOT . '/views/partial/footer.php'; ?>