<?php require APPROOT . '/views/partial/header.php';
require APPROOT . '/views/partial/navbar.php'; ?>

<main id="checkout-main">
    <input type="hidden" id="enableNavbarBackground">

    <div class="container-fluid wizard-container col-10">
        <div class="row col-12 wizard-row">
            <div class="col-4 wizard-step">
                <a class="finished" href="<?php echo URLROOT; ?>/checkout/shoppingcart">
                    SHOPPING CART
                </a>
            </div>
            <div class="col-sm-4 wizard-step">
                <a class="finished" href="<?php echo URLROOT; ?>/checkout/login">
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

    <div class="payment-container">
        <div class="row">
            <div class="col-auto">
                <div class="row" id="payment-block">
                    <ul class="nav flex-column nav-pills nav-justified col-3 purple-block" id="v-pills-tab"
                        role="tablist" aria-orientation="vertical">
                        <li class="nav-item">
                            <a class="nav-link active" href="#ideal" data-toggle="tab" role="tab"
                               aria-controls="choice-ideal" aria-selected="true">
                                <img id="button-ideal"
                                     src="<?php echo URLROOT; ?>/public/img<?php echo $data['content']['CHECKOUT_IDEAL']; ?>"
                                     alt="ideal-image"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#mastercard" data-toggle="tab" role="tab"
                               aria-controls="choice-mastercard" aria-selected="false">
                                <img id="button-mastercard"
                                     src="<?php echo URLROOT; ?>/public/img<?php echo $data['content']['CHECKOUT_CREDITCARD']; ?>"
                                     alt="mastercard-image"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#paypal" data-toggle="tab" role="tab"
                               aria-controls="choice-paypal" aria-selected="false">
                                <img id="button-paypal"
                                     src="<?php echo URLROOT; ?>/public/img<?php echo $data['content']['CHECKOUT_PAYPAL']; ?>"
                                     alt="paypal-image"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#invoice" data-toggle="tab" role="tab"
                               aria-controls="choice-invoice" aria-selected="false">
                                <p id="button-invoice">
                                    INVOICE
                                </p>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content col-9">
                        <div class="tab-pane fade show active" id="ideal" role="tabpanel"
                             aria-labelledby="choice-ideal">
                            <form action="<?php echo URLROOT; ?>/checkout/ideal" method="post">
                                <div id="ideal-block">
                                    <b>Choose your bank: </b><br>
                                    <?php try {
                                        $mollie = new \Mollie\Api\MollieApiClient();
                                        $mollie->setApiKey("");
                                        $method = $mollie->methods->get(\Mollie\Api\Types\PaymentMethod::IDEAL, ["include" => "issuers"]);
                                        echo '<select name="issuer">';
                                        foreach ($method->issuers() as $issuer) {
                                            echo '<option value=' . htmlspecialchars($issuer->id) . '>' . htmlspecialchars($issuer->name) . '</option>';
                                        }
                                        echo '</select>';
                                    } catch (\Mollie\Api\Exceptions\ApiException $e) {
                                        echo "API call failed: " . htmlspecialchars($e->getMessage());
                                    } ?>
                                </div>
                                <?php if (empty($data['tickets'])) {
                                    echo '<button type="submit" class="btn button-pay" id="button-pay-ideal" disabled>PAY</button>';
                                } else {
                                    echo '<button type="submit" class="btn button-pay" id="button-pay-ideal">PAY</button>';
                                } ?>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="mastercard" role="tabpanel"
                             aria-labelledby="choice-mastercard">
                            <div id="creditcard-block">
                                <p>
                                    <b>Credit card selected</b>
                                </p>
                                <?php if ($data['totalPrice'] > 2000) {
                                    echo '<div class="text-danger text-center">Maximum of €2.000,00 via credit card</div>';
                                } ?>
                                <!--
                                <label for="cardnumber">Card number</label><br>
                                <input class="payment-dateB" type="text" name="cardnumber" maxlength="20"><br>
                                <label for="namecard">Name on card</label><br>
                                <input class="payment-dateB" type="text" name="namecard" maxlength="100"><br>
                                <label for="validthruMM">Valid Thru</label><br>
                                <input class="payment-date" type="text" name="validthruMM" placeholder="MM"
                                       maxlength="2"> / <input class="payment-date" type="text" name="validthruDD"
                                                               placeholder="DD" maxlength="2"><br>
                                <label for="cvc-cid">CVC/CID</label><br>
                                <input class="payment-dateB mb-5" type="text" name="cvc-cid" maxlength="20"><br>
                                -->
                            </div>
                            <form action="<?php echo URLROOT; ?>/checkout/creditcard" method=post>
                                <?php if ($data['totalPrice'] > 2000 || empty($data['tickets'])) {
                                    echo '<button type="submit" class="btn button-pay" id="button-pay-mastercard" disabled>PAY</button>';
                                } else {
                                    echo '<button type="submit" class="btn button-pay" id="button-pay-mastercard">PAY</button>';
                                } ?>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="paypal" role="tabpanel" aria-labelledby="choice-paypal">
                            <div id="paypal-block">
                                <p>
                                    <b>Paypal selected</b>
                                </p>
                            </div>
                            <form action="<?php echo URLROOT; ?>/checkout/paypal" method=post>
                                <?php if (empty($data['tickets'])) {
                                    echo '<button type="submit" class="btn button-pay" id="button-pay-paypal" disabled>PAY</button>';
                                } else {
                                    echo '<button type="submit" class="btn button-pay" id="button-pay-paypal">PAY</button>';
                                } ?>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="invoice" role="tabpanel" aria-labelledby="choice-invoice">
                            <form action="<?php echo URLROOT; ?>/checkout/invoice" method=post>
                                <div id="invoice-block">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="fname">First Name</label><br>
                                            <input type="text" name="fname" maxlength="100"
                                                   value="<?php if (isset($data['error']['fname'])) {
                                                       echo $data['error']['fname'];
                                                   } ?>"><br>
                                            <p class="text-danger">
                                                <?php if (isset($data['error']['fnameError'])) {
                                                    echo $data['error']['fnameError'];
                                                } ?>
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <label for="lname">Last Name</label><br>
                                            <input type="text" name="lname" maxlength="100"
                                                   value="<?php if (isset($data['error']['lname'])) {
                                                       echo $data['error']['lname'];
                                                   } ?>"><br>
                                            <p class="text-danger">
                                                <?php if (isset($data['error']['lnameError'])) {
                                                    echo $data['error']['lnameError'];
                                                } ?>
                                            </p>
                                        </div>
                                        <div class="col-12">
                                            <label for="address">Address</label><br>
                                            <input type="text" name="address" maxlength="100"
                                                   value="<?php if (isset($data['error']['address'])) {
                                                       echo $data['error']['address'];
                                                   } ?>"><br>
                                            <p class="text-danger">
                                                <?php if (isset($data['error']['addressError'])) {
                                                    echo $data['error']['addressError'];
                                                } ?>
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <label for="postcode">Postcode</label><br>
                                            <input type="text" name="postcode" maxlength="20"
                                                   value="<?php if (isset($data['error']['postcode'])) {
                                                       echo $data['error']['postcode'];
                                                   } ?>"><br>
                                            <p class="text-danger">
                                                <?php if (isset($data['error']['postcodeError'])) {
                                                    echo $data['error']['postcodeError'];
                                                } ?>
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <label for="city">City</label><br>
                                            <input type="text" name="city" maxlength="100"
                                                   value="<?php if (isset($data['error']['city'])) {
                                                       echo $data['error']['city'];
                                                   } ?>"><br>
                                            <p class="text-danger">
                                                <?php if (isset($data['error']['cityError'])) {
                                                    echo $data['error']['cityError'];
                                                } ?>
                                            </p>
                                        </div>
                                        <div class="col-12">
                                            <label for="phonenumber">Phone number</label><br>
                                            <input type="text" name="phonenumber" maxlength="20"
                                                   value="<?php if (isset($data['error']['phonenumber'])) {
                                                       echo $data['error']['phonenumber'];
                                                   } ?>"><br>
                                            <p class="text-danger">
                                                <?php if (isset($data['error']['phonenumberError'])) {
                                                    echo $data['error']['phonenumberError'];
                                                } ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php if (empty($data['tickets'])) {
                                    echo '<button type="submit" class="btn button-pay" id="button-pay-invoice" disabled>PAY</button>';
                                } else {
                                    echo '<button type="submit" class="btn button-pay" id="button-pay-invoice">PAY</button>';
                                } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div id="shopping-cart">
                    <div id="shopping-cart-block" class="text-center">
                        <p><b>Shopping Cart</b></p>
                    </div>
                    <div class="ticket-container">
                        <?php foreach ($data['tickets'] as $ticket) { ?>
                            <div class="shopping-cart-ticket">
                                <div class="row no-gutters">
                                    <div class="col-1">
                                        <p class="ticket-amount">
                                            <?php echo $ticket['quantity'] . 'x'; ?>
                                        </p>
                                    </div>
                                    <div class="col-8">
                                        <p class="ticket-text">
                                            Haarlem <?php echo $ticket['attributes']['category'] . ' - ' . $ticket['attributes']['name'] ?>
                                            <br>
                                            <?php
                                            if (isset($ticket['attributes']['date'])) {
                                                echo date('l d F', strtotime($ticket['attributes']['date']));
                                            }
                                            if (isset($ticket['attributes']['startTime'])) {
                                                echo ' at ' . date('H:i', strtotime($ticket['attributes']['startTime']));
                                            } ?>
                                        </p>
                                    </div>
                                    <div class="col-3">
                                        <p class="ticket-price">
                                            <b>€<?php echo number_format($ticket['totalPrice'], 2, ",", ".") ?></b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="cart-total">
                        <p class="total-p">
                            Total
                        </p>
                        <p class="price-total">
                            €<?php echo number_format($data['totalPrice'], 2, ",", ".") ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/partial/footer.php'; ?>
