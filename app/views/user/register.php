<?php require APPROOT . '/views/partial/header.php'; ?>
<?php require APPROOT . '/views/partial/navbar.php'; ?>

<main class="content-register">
  <input type="hidden" id="enableNavbarBackground">
  <?php $formLink = '/user/register'; $redirectLink = '/user/login'; require APPROOT . '/views/partial/register.php' ?>
</main>

<?php require APPROOT . '/views/partial/footer.php'; ?>