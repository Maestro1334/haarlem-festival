<?php require APPROOT . '/views/partial/header.php';
require APPROOT . '/views/partial/navbar.php'; ?>

    <main id="checkout-main">
        <input type="hidden" id="enableNavbarBackground">

        <div class="container-fluid wizard-container col-10">
            <div class="row col-12 wizard-row">
                <div class="col-sm-4 wizard-step">
                    <a class="finished" href="<?php echo URLROOT; ?>/checkout/shoppingcart">
                        SHOPPING CART
                    </a>
                </div>
                <div class="col-sm-4 wizard-step">
                    <a>
                        YOUR INFORMATION
                    </a>
                </div>
                <div class="col-sm-4 wizard-step">
                    <a>
                        PAYMENT METHOD
                    </a>
                </div>
            </div>
        </div>

        <div class="pb-5">
            <?php $redirectLink = '/checkout/login';
            $location = 'checkout';
            require APPROOT . '/views/partial/register.php' ?>
        </div>
    </main>

<?php require APPROOT . '/views/partial/footer.php'; ?>