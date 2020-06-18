<script src="<?php echo URLROOT; ?>/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo URLROOT; ?>/js/popper.min.js"></script>
<script src="<?php echo URLROOT; ?>/js/bootstrap.min.js"></script>
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