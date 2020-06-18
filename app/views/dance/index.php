<?php require APPROOT . '/views/partial/header.php'; ?>
<?php require APPROOT . '/views/partial/navbar.php'; ?>

    <main class="">
        <div class="container-fluid header-container">
            <img id="header-image" src="<?php echo URLROOT . $data['content']['DANCE_HEADER_IMAGE'] ?>"
                 alt="Header Image">
            <div class="header-title">
                <p class="mx-auto"><?php echo $data['content']['DANCE_HEADER_TITLE'] ?></p>

                <button type="button" class="btn"
                        id="btn-tickets"><?php echo $data['content']['DANCE_HEADER_BUTTON'] ?></button>
            </div>
        </div>

        <div class="container-fluid information-container">
            <div class="row mx-auto">
                <div class="col-sm-12 col-md-6 information-column d-none d-lg-block d-xl-block">
                    <h2 id="header-lineup">LINE UP</h2>

                    <div class="row align-items-center artist-row">
                        <?php foreach ($data['lineup'] as $item) { ?>
                            <div class="col-xl-4 col-lg-6 artist-column">
                                <div class="artist-img-cover">
                                    <img class="artist-img"
                                         src="<?php echo URLROOT; ?>/img<?php echo $item->imgPath; ?>"
                                         alt="<?php echo $item->name; ?> Image">
                                </div>
                                <h4 class="artist-name"><?php echo $item->name; ?></h4>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 information-column">
                    <h2 id="header-program">PROGRAM 2020</h2>

                    <div class="row mx-auto">
                        <?php foreach ($data['uniqueDays'] as $uniqueDay) { ?>
                            <div class="container col-sm-12 col-md-11 program-container">
                                <h4 class="program-title"><?php echo date('l d, F', strtotime($uniqueDay->date)) ?></h4>
                                <?php foreach ($data['program'][$uniqueDay->date] as $item) { ?>
                                    <div class="row mx-auto program-row">
                                        <div class="col-6 program-artist align-self-center">
                                            <?php echo $item->name; ?>
                                        </div>
                                        <div class="col-4 program-location align-self-center">
                                            <?php echo $item->location; ?>
                                        </div>
                                        <div class="col-2 program-time align-self-center">
                                            <?php echo $item->startTime; ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid tickets-container" id="tickets">
            <h2 class="col-11 col-lg-3 mx-auto" id="header-tickets">TICKETS</h2>

            <div class="col-11 col-lg-3 mx-auto ticket-search-container">
                <div class="input-group">
                    <input type="text" class="col-12 form-control" id="ticket-search"
                           value="<?php echo (!empty($data['query'])) ? $data['query'] : ''; ?>"
                           placeholder="Search for a ticket...">
                    <div class="input-group-append">
                        <button class="btn btn-default" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>

            </div>


            <ul class="nav nav-pills nav-justified col-11 col-lg-4 mx-auto tickets-day-toggle"
                role="tablist">
                <?php foreach ($data['uniqueDays'] as $uniqueDay) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $data['uniqueDays'][0] == $uniqueDay ? "active" : ""; ?>"
                           href="#date-<?php echo $uniqueDay->date; ?>" data-toggle="tab" role="tab"
                           aria-controls="date-<?php echo $uniqueDay->date; ?>"
                           aria-selected="true"><?php echo date('l d F', strtotime($uniqueDay->date)) ?></a>
                    </li>
                <?php } ?>
            </ul>

            <div class="tab-content col-11 mx-auto">
                <?php foreach ($data['uniqueDays'] as $uniqueDay) { ?>
                    <div class="tab-pane fade  <?php echo $data['uniqueDays'][0] == $uniqueDay ? "show active" : ""; ?>" id="date-<?php echo $uniqueDay->date; ?>" role="tabpanel"
                         aria-labelledby="date-<?php echo $uniqueDay->date; ?>">
                        <div class="container col-11 ticket-container">
                            <?php foreach ($data['tickets'][$uniqueDay->date] as $item) { ?>
                                <div class="row mx-auto ticket-row">
                                    <input type="hidden" id="product-id" value="<?php echo $item->id ?>">
                                    <input type="hidden" id="product-date" value="<?php if ($item->date != null) {
                                        echo date('l d F', strtotime($item->date));
                                    } ?>">
                                    <div class="col-5 ticket-title align-self-center">
                                        <?php echo $item->name; ?>
                                    </div>
                                    <div class="col-4 ticket-type align-self-center">
                                        <?php echo $item->eventType; ?>
                                    </div>
                                    <div class="col-1 ticket-price align-self-center">
                                        €<?php echo number_format($item->price, 2); ?>
                                    </div>
                                    <div class="col-2 ticket-button align-self-center">
                                        <button type="button" class="btn btn-default btn-buy-ticket col-12" <?php echo $item->availability <= 0 ? 'disabled' : '' ?> data-toggle="modal" data-target="#cart-modal"><?php echo($item->availability <= 0 ? 'Sold Out' : 'Add to Cart'); ?></button>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="row mx-auto ticket-no-result">
                                <h2 class="col-12 mx-auto">No tickets found</h2>
                            </div>
                        </div>

                        <ul class="ticket-extras mx-auto col-11">
                            <?php foreach ($data['ticketDayNotes'][$uniqueDay->date] as $item) {?>
                                <li><?php echo $item; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>

        <?php require APPROOT . '/views/partial/cart-popup.php'; ?>
    </main>
<?php require APPROOT . '/views/partial/footer.php'; ?>