<?php require APPROOT . '/views/partial/header.php';
require APPROOT . '/views/partial/navbar.php'; ?>

    <main id="checkout-main">
        <input type="hidden" id="enableNavbarBackground">

        <div class="container-fluid wizard-container col-10">
            <div class="row col-12 wizard-row">
                <div class="col-4 wizard-step">
                    <a>SHOPPING CART</a>
                </div>
                <div class="col-sm-4 wizard-step">
                    <a>YOUR INFORMATION</a>
                </div>
                <div class="col-sm-4 wizard-step">
                    <a>PAYMENT METHOD</a>
                </div>
            </div>
        </div>


        <div class="container-fluid information-container col-10">
            <div class="text-center alert-box mx-auto col-12">
                <?php flash('alert'); ?>
            </div>

            <div class="row information-row col-12">
                <div class="col-7">
                    <ul class="nav nav-pills nav-justified col-12 program-day-toggle"
                        role="tablist">
                        <?php foreach ($data['program']['uniqueDays'] as $day) { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $data['program']['uniqueDays'][0] == $day ? "active" : ""; ?>"
                                   href="#date-<?php echo $day->date; ?>" data-toggle="tab" role="tab"
                                   aria-controls="date-<?php echo $day->date; ?>"
                                   aria-selected="true"><?php echo date('l d F', strtotime($day->date)) ?></a>
                            </li>
                        <?php } ?>
                    </ul>


                    <div class="tab-content program-content col-12 mx-auto">
                        <?php foreach ($data['program']['uniqueDays'] as $day) { ?>
                            <div class="tab-pane fade program-container <?php echo $data['program']['uniqueDays'][0] == $day ? "show active" : ""; ?>"
                                 id="date-<?php echo $day->date; ?>" role="tabpanel"
                                 aria-labelledby="date-<?php echo $day->date; ?>">
                                <?php if (!empty($data['program']['items'][$day->date])) {
                                    foreach ($data['program']['items'][$day->date] as $item) { ?>
                                        <div class="row program-row">
                                            <input type="hidden" class="productId" value="<?php echo $item['id'] ?>">
                                            <div class="col-6 program-artist"><?php echo $item['name']; ?> <strong>(<?php echo $item['category']; ?>)</strong></div>
                                            <div class="col-2 program-time">
                                                <?php if (isset($item['startTime']) && isset($item['endTime'])) {
                                                    echo date('H:i', strtotime($item['startTime'])) . ' - ' . date('H:i', strtotime($item['endTime']));
                                                } ?>
                                            </div>
                                            <div class="col-4 program-location">
                                                <?php if (isset($item['location'])) {
                                                    echo $item['location'];
                                                } ?>
                                            </div>
                                        </div>
                                    <?php }
                                } ?>

                                <div class="row program-nothing-planned mx-auto col-9"
                                     style="<?php echo(!empty($data['program']['items'][$day->date]) ? 'display: none;' : ''); ?>">
                                    <h3 class="col-12">Oh no, you have nothing planned on this day!</h3>

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
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-5 product-column">
                    <div class="product-list">
                        <?php foreach ($data['shoppingCart']['items'] as $item) { ?>

                            <div class="row col-12 product-row">
                                <input type="hidden" class="productId" value="<?php echo $item['id'] ?>">
                                <div class="col-8 product-information">
                                    <strong class="product-name"><?php echo $item['attributes']['name'] . ' (' . $item['attributes']['category'] . ')' ?></strong>
                                    <br>
                                    <span>
                                        <?php

                                        if (count(explode(',', $item['attributes']['date'])) <= 1) {
                                            echo date('l d F', strtotime($item['attributes']['date']));
                                        } else {
                                            $dates = explode(',', $item['attributes']['date']);
                                            echo date('l d F', strtotime(reset($dates))) . ' to ' . date('l d F', strtotime(end($dates)));
                                        }


                                        if (isset($item['attributes']['startTime'])) {
                                            echo ' at ' . date('H:i', strtotime($item['attributes']['startTime']));
                                        }
                                        ?>

                                    </span>
                                </div>
                                <div class="col-2 product-amount my-auto">
                                    <button class="product-amount-button button-remove float-left">-</button>
                                    <strong class="product-amount-indicator float-none"><span
                                                class="product-amount"><?php echo $item['quantity'] ?></span>x</strong>
                                    <button class="product-amount-button button-add float-right ">+</button>
                                </div>
                                <div class="col-2 product-price-indicator my-auto">
                                    <strong>€<span class="product-price"><?php echo $item['totalPrice'] ?></span>,-</strong>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="product-actions">
                        <div class="row col-9 mx-auto">
                            <div class="col-12 product-vat">
                                <strong class="float-left">VAT:</strong>
                                <strong class="float-right">€<span
                                            id="vatAmount"><?php echo number_format($data['shoppingCart']['totalPrice'] * 0.09, 2, ".", "") ?></span></strong>
                            </div>
                            <div class="col-12 product-total">
                                <strong class="float-left">Total:</strong>
                                <strong class="float-right">€<span
                                            id="totalPrice"><?php echo number_format($data['shoppingCart']['totalPrice'], 2, ".", "") ?></span></strong>
                            </div>

                            <hr class="col-10">

                            <a href="<?php echo URLROOT; ?>/checkout/payment"
                               class="btn col-11 mx-auto tag-default btn-checkout" disabled>Continue to Checkout</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require APPROOT . '/views/partial/footer.php'; ?>