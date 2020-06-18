<?php require APPROOT . '/views/partial/header.php';
require APPROOT . '/views/partial/navbar.php'; ?>

    <main id="checkout-main">
        <input type="hidden" id="enableNavbarBackground">

        <div class="container-fluid wizard-container col-10">
            <div class="row col-12 wizard-row">
                <div class="col-sm-4 wizard-step">
                    <a class="finished">
                        SHOPPING CART
                    </a>
                </div>
                <div class="col-sm-4 wizard-step">
                    <a class="finished">
                        YOUR INFORMATION
                    </a>
                </div>
                <div class="col-sm-4 wizard-step">
                    <a class="finished">
                        PAYMENT METHOD
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid completed-container">
            <?php if (isset($data['order'])) {
                if ($data['order']->type == 4) { ?>
                    <span><h1>Thank you for your order!</h1></span>
                    <div class="checkmark-container">
                        <img src="<?php echo URLROOT; ?>/img/checkout/completed/completed.png" alt="Order completed">
                    </div>
                    <span><h2>We've sent an email with your order confirmation and your invoice. Payment can be done at the counter or with one of our provided payment methods</h2></span>
                    <?php
                } else {
                    if ($data['order']->status == 'paid') { ?>
                        <span><h1>Thank you for your order!</h1></span>
                        <div class="checkmark-container">
                            <img src="<?php echo URLROOT; ?>/img/checkout/completed/completed.png"
                                 alt="Order completed">
                        </div>
                        <span><h2>We've sent an email with your order confirmation and your ticket.</h2></span>
                    <?php } else { ?>
                        <span><h1>Your order could not be completed!</h1></span>
                        <div class="checkmark-container">
                            <img src="<?php echo URLROOT; ?>/img/checkout/completed/failed.png"
                                 alt="Order failed">
                        </div>
                        <span><h2>Payment for the tickets has failed, please try again.</h2></span>
                    <?php }
                }
            } ?>
        </div>
    </main>

<?php require APPROOT . '/views/partial/footer.php'; ?>