<?php require APPROOT . '/views/partial/header.php'; ?>
<?php require APPROOT . '/views/partial/navbar.php'; ?>

<main>
  <section id="header" class="container-fluid text-center">
    <img src="<?php echo URLROOT . $data['content']['HOMEPAGE_HEADER_IMAGE']; ?>" alt="header-image" id="header-image">
    <div class="header-title">
      <?php $parts = explode('\n', $data['content']['HOMEPAGE_HEADER_TITLE']);
      foreach ($parts as $part) { ?>
        <p><?php echo $part; ?></p>
      <?php } ?>
    </div>
    <a href="#" id="scroll-arrow"><i class="fas fa-chevron-down"></i></a>
  </section>
  <section id="sponsors" class="text-center">
    <div class="row justify-content-center">
      <div class="col-xl-4">
        <p class="bordered"><span>MAIN SPONSORS</span></p>
      </div>
    </div>
    <div class="container sponsors">
      <section class="sponsor-logos slider">
        <?php foreach($data['sponsors'] as $sponsor){?>
          <div class="slide"><img src="<?php echo URLROOT . $sponsor->img_path; ?>" alt="sponsor image"></div>
        <?php } ?>
      </section>
    </div>
  </section>
  <section id="info" class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4">
    <div class="col mb-4">
      <div class="card h-100 info-box">
        <div class="card-body">
          <h3 class="card-title">DANCE</h3>
          <hr>
          <p class="card-text info-text"><?php echo $data['content']['HOMEPAGE_CONTENT_DANCE_TEXT']?></p>
        </div>
        <div class="card-footer">
          <a href="<?php echo URLROOT; ?>/dance/index" class="btn view-btn">VIEW DANCE</a>
        </div>
      </div>
    </div>
    <div class="col mb-4">
      <div class="card h-100 info-box">
        <div class="card-body">
          <h3 class="card-title">HISTORIC</h3>
          <hr>
          <p class="card-text info-text"><?php echo $data['content']['HOMEPAGE_CONTENT_HISTORIC_TEXT']?></p>
        </div>
        <div class="card-footer">
          <a href="<?php echo URLROOT; ?>/historic/index" class="btn view-btn">VIEW HISTORIC</a>
        </div>
      </div>
    </div>
    <div class="col mb-4">
      <div class="card h-100 info-box">
        <div class="card-body">
          <h3 class="card-title">FOOD</h3>
          <hr>
          <p class="card-text info-text"><?php echo $data['content']['HOMEPAGE_CONTENT_FOOD_TEXT']?></p>
        </div>
        <div class="card-footer">
          <a href="<?php echo URLROOT; ?>/food/index" class="btn view-btn">VIEW FOOD</a>
        </div>
      </div>
    </div>
    <div class="col mb-4">
      <div class="card h-100 info-box">
        <div class="card-body">
          <h3 class="card-title">JAZZ</h3>
          <hr>
          <p class="card-text info-text"><?php echo $data['content']['HOMEPAGE_CONTENT_JAZZ_TEXT']?></p>
        </div>
        <div class="card-footer">
          <a href="<?php echo URLROOT; ?>/jazz/index" class="btn view-btn">VIEW JAZZ</a>
        </div>
      </div>
    </div>
  </section>
  <section id="map">
  </section>
  <div id="legend"><h4>Legend</h4></div>
  <input type="hidden" name="eventLocations" value='<?php echo $data['eventLocations'];?>'>
</main>

<?php require APPROOT . '/views/partial/footer.php'; ?>