<nav id="sidebar" class="col-md-2 d-none d-md-block sidebar">
  <div class="sidebar-sticky">
    <ul class="nav flex-column list-unstyled components">
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo URLROOT; ?>/admin/dashbaord">
          <i class="fas fa-home"></i>
          Dashboard 
        </a>
      </li>
      <li class="nav-link">
        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"><i class="fas fa-edit"></i> Pages</a>
        <ul class="collapse list-unstyled" id="homeSubmenu">
          <li class="nav-item sub">
            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/page/home">Home</a>
          </li>
          <li class="nav-item sub">
            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/page/historic">Historic</a>
          </li>
          <li class="nav-item sub">
            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/page/jazz">Jazz</a>
          </li>
          <li class="nav-item sub">
            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/page/dance">Dance</a>
          </li>
          <li class="nav-item sub">
            <a class="nav-link" href="<?php echo URLROOT; ?>/admin/page/food">Food</a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo URLROOT; ?>/admin/ticket">
          <i class="fas fa-shopping-cart"></i>
          Tickets
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo URLROOT; ?>/admin/user">
          <i class="fas fa-users"></i>
          Users
        </a>
      </li>
    </ul>
  </div>
</nav>



<script>
var toggler = document.getElementsByClassName("caret");
var i;

for (i = 0; i < toggler.length; i++) {
  toggler[i].addEventListener("click", function() {
    this.parentElement.querySelector(".nested").classList.toggle("active");
    this.classList.toggle("caret-down");
  });
};
</script>