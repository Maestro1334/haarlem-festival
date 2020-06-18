<div class="modal fade" id="cart-modal" tabindex="-1" role="dialog" aria-labelledby="cart-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="cart-modalLabel">The following ticket has been added to your shopping cart</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="reset-amount" value="true">
                <input type="hidden" id="product-id">
                <input type="hidden" id="product-comment" name="product-comment">
                <div class="container-fluid">
                    <div class="row first-row">
                        <div id="ticket-type" class="col-2 my-auto"></div>
                        <div class="ticket-info col-6">
                            <span id="ticket-title"></span><br>
                            <span id="ticket-date"></span>
                        </div>
                        <div class="ticket-checkout col-4"><button type="button" class="btn btn-add-product btn-checkout col-12">Checkout</button></div>
                    </div>
                    <div class="row second-row">
                        <div class="col-md-2 product-amount-col offset-2">
                            <button class="product-amount-button float-left" id="button-remove">-</button>
                            <input class="product-amount-field float-none" type="number" min="1" value="1">
                            <button class="product-amount-button float-right" id="button-add">+</button>
                        </div>
                        <div class="ticket-continue col-4 ml-auto"><button type="button" class="btn btn-add-product col-12" data-dismiss="modal">Continue Shopping</button></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row cross-selling-blocks">
                        <div class="col-12">
                            <p id="cross-selling-text">These events are an amazing combination with your ticket:</p>
                        </div>
                        <div class="col-4 cross-selling-block" id="cross-selling-historic">
                            <div class="card h-100">
                                <img src="<?php echo URLROOT; ?>/img/cart-popup/historic.png" alt="cross selling historic image">
                                <div class="card-body">
                                    <h3 class="card-title">Historic</h3>
                                    <p class="card-text">Enjoy the beautiful history of Haarlem with a guide.</p>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="<?php echo URLROOT; ?>/historic" class="btn btn-default">View event</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 cross-selling-block" id="cross-selling-dance">
                            <div class="card h-100">
                                <img src="<?php echo URLROOT; ?>/img/cart-popup/dance.png" alt="cross selling dance image">
                                <div class="card-body">
                                    <h3 class="card-title">Dance</h3>
                                    <p class="card-text">Break free with with some of the best DJs of the world.</p>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="<?php echo URLROOT; ?>/dance" class="btn btn-default">View event</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 cross-selling-block" id="cross-selling-food">
                            <div class="card h-100">
                                <img src="<?php echo URLROOT; ?>/img/cart-popup/food.png" alt="cross selling food image">
                                <div class="card-body">
                                    <h3 class="card-title">Food</h3>
                                    <p class="card-text">Treat those tastebuds with an amazing variety of specials.</p>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="<?php echo URLROOT; ?>/food" class="btn btn-default">View event</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 cross-selling-block" id="cross-selling-jazz">
                            <div class="card h-100">
                                <img src="<?php echo URLROOT; ?>/img/cart-popup/jazz.png" alt="cross selling jazz image">
                                <div class="card-body">
                                    <h3 class="card-title">Jazz</h3>
                                    <p class="card-text">Lose your mind while enjoying with everyone else.</p>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="<?php echo URLROOT; ?>/jazz" class="btn btn-default">View event</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>