<?php require APPROOT . '/views/partial/header.php'; ?>
<?php require APPROOT . '/views/partial/navbar.php'; ?>

  <main class="account-profile">
    <input type="hidden" id="enableNavbarBackground">

    <h1 id="account-title">ACCOUNT</h1>

    <div class="account-container">
      <?php if (isset($data['invoice'][0])) {
        $counter = 0;
        foreach ($data['invoice'] as $order) {
          $counter++; ?>
          <div class="invoice-container">
            <h1>ORDER <?php echo $counter ?></h1>
            <div class="invoice-pdf-container">
              <embed class="invoice-pdf" src="<?php echo URLROOT . "/" . $order->invoice ?>" type="application/pdf"/>
            </div>
          </div>
        <?php }
      } else { ?>
        <div class="row mx-auto program-nothing-planned col-9" style="">
          <h3 class="col-12">To view your account, please create an order</h3>

          <div class="program-buttons">
            <a href="<?php echo URLROOT; ?>/historic" class="btn tag-default col-5">Immerse
              yourself in the history, view our tours</a>
            <a href="<?php echo URLROOT; ?>/jazz" class="btn tag-default col-5">Time to
              relax, view our jazz performances</a>
            <a href="<?php echo URLROOT; ?>/dance" class="btn tag-default col-5">Go wild,
              view our dance performances</a>
            <a href="<?php echo URLROOT; ?>/food" class="btn tag-default col-5">Time to eat,
              view our restaurant offers</a>
          </div>
        </div>
      <?php } ?>
    </div>
  </main>

<?php require APPROOT . '/views/partial/footer.php'; ?>