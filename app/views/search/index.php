<?php require APPROOT . '/views/partial/header.php'; ?>
<?php require APPROOT . '/views/partial/navbar.php'; ?>

    <main id="search-main">
        <input type="hidden" id="enableNavbarBackground">

        <div class="container">
            <?php if (isset($data['results']['error'])) { ?>
                <div class="col-8 mx-auto" id="search-nothing-found-row">
                    <h3>No results for: <span id="search-value"><?php echo urldecode($_GET['query']); ?></span></h3>

                    <div id="search-new-term">
                        <form action="<?php echo URLROOT; ?>/search">
                            <label for="search">Try again with a new search term</label>
                            <div class="input-group">
                                <input name="query" placeholder="Search for...." type="text" class="form-control">
                                <div class="input-group-append">
                                    <button class="btn btn-search" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            <?php } else { ?>
                <div class="row" id="search-value-row">
                    <h3>Search results for: <span id="search-value"><?php echo urldecode($_GET['query']); ?></span></h3>
                </div>
                <div class="row search-result-row-headers">
                    <div class="col-2 header-category">CATEGORY</div>
                    <div class="col-5 header-name">TICKET</div>
                    <div class="col-2 float-right header-date">DATE</div>
                    <div class="col-1 float-right header-price">PRICE</div>
                </div>
                <?php foreach ($data['results'] as $result) { ?>
                    <div class="row search-result-row">
                        <div class="col-2 my-auto category"><strong><?php echo $result->category ?></strong></div>
                        <div class="col-5 my-auto name"><?php echo $result->name ?></div>
                        <div class="col-2 my-auto float-right date"><?php

                            if (count(explode(',', $result->days)) <= 1) {
                                echo date('l d F', strtotime($result->days));
                            } else {
                                $dates = explode(',', $result->days);
                                echo date('l d F', strtotime(reset($dates))) . '<br> to ' . date('l d F', strtotime(end($dates)));
                            }
                            ?>
                        </div>
                        <div class="col-1 my-auto float-right price">€<?php echo number_format($result->price, 2); ?></div>
                        <div class="col-2"><button class="btn btn-default btn-view-event col-12">View Event</button></div>
                    </div>
                <?php }
            } ?>
        </div>
    </main>

<?php require APPROOT . '/views/partial/footer.php'; ?>