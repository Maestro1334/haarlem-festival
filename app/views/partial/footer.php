<!-- Cookie modal -->
<div class="modal fade" id="cookieModal" data-backdrop="static" tabindex="-1" role="dialog"aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">This website uses cookies</h5> 
      </div>
      <div class="modal-body">
        We use only essential first party cookies to make the site functional. By continuing to the website, you accept the usage of these cookies.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="accept-cookies">Understood</button>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="row h-100 align-items-center">
    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 text-center">
      <p id="footer-follow-us">FOLLOW US ON</p>
      <p id="footer-social-media">SOCIAL MEDIA</p>
      <div class="d-flex justify-content-center">
        <a href="https://www.instagram.com/haarlemfestival/" class="social-link mx-2" target="_blank">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="https://www.facebook.com/Haarlem-Festival-100428948092059" class="social-link mx-2" target="_blank">
          <i class="fab fa-facebook-square"></i>
        </a>
        <a href="https://twitter.com/FestivalHaarlem" class="social-link mx-2" target="_blank">
          <i class="fab fa-twitter"></i>
        </a>
      </div>
    </div>
  </div>
</footer>
<script src="<?php echo URLROOT; ?>/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo URLROOT; ?>/js/popper.min.js"></script>
<script src="<?php echo URLROOT; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo URLROOT; ?>/js/partial/script.js"></script>

<!-- Custom page scripts -->
<?php 
if(isset($scripts)){
  foreach ($scripts as $key => $script) {
    if($script['internal'])
    {
      echo '<script src="' . URLROOT . '/js/' . $script['path'] . '"></script>';
    } else {
      echo '<script src="' . $script['path'] . '"></script>';
    }
  }
}
?>
</body>
</html>