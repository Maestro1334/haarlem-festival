<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="navigation">
    <div class="container">
        <a class="navbar-brand" href="<?php echo URLROOT; ?>"><img src="<?php echo URLROOT . '/img/logo_white.png'; ?>"
                                                                   alt="<?php echo SITENAME; ?>" class=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>" data-linkbackup="/page/index">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/historic/index">HISTORIC</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/jazz/index">JAZZ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/dance/index">DANCE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/food/index">FOOD</a>
                </li>

                <li class="nav-item my-auto">
                    <form action="<?php echo URLROOT; ?>/search">
                        <div class="input-group">
                            <input name="query" placeholder="Search for...." type="text" class="form-control">
                            <div class="input-group-append">
                                <button class="btn btn-search" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </li>

                <?php if (isLoggedIn()) : ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="navbar-account-dropdown" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i></a>
                        <div class="dropdown-menu" aria-labelledby="navbar-account-dropdown">
                            <a href="<?php echo URLROOT; ?>/user/account" class="dropdown-item"></i> ACCOUNT</a>
                            <a href="<?php echo URLROOT; ?>/user/logout" class="dropdown-item"></i> SIGN OUT</a>
                        </div>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/user/login"><i class="fas fa-user"></i></a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/checkout/index"><i
                                class="fas fa-shopping-cart"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>