<?php
require APPROOT . '/views/partial/header.php';
require APPROOT . '/views/partial/navbar.php';
?>
    <main id="top-of-page">
        <header class="container-fluid header-container text-center">
            <img id="header-image"
                 src="<?php echo URLROOT . $data['content']['JAZZ_HEADER_IMAGE']; ?>"
                 alt="jazz-header image">

            <div class="header-title">
                <h1>
                    <?php if (isset($data['content']['JAZZ_HEADER_TITLE'])) {
                        echo $data['content']['JAZZ_HEADER_TITLE'];
                    } ?>
                </h1>
                <h2>
                    <?php if (isset($data['content']['JAZZ_HEADER_DATE'])) {
                        echo $data['content']['JAZZ_HEADER_DATE'];
                    } ?>
                </h2>
                <div id="header-ad">
                    <?php if (isset($data['content']['JAZZ_HEADER_AD'])) {
                        $adPieces = explode(",", $data['content']['JAZZ_HEADER_AD']);
                        $floatDirection = "float-left";
                        foreach ($adPieces as $piece) {
                            echo '<div class="' . $floatDirection . '"><p>' . $piece . '</p></div>';
                            if ($floatDirection == "float-left") {
                                $floatDirection = "float-right";
                            } else {
                                $floatDirection = "float-left";
                            }
                        }
                    } ?>
                </div>
            </div>
        </header>

        <section id="jazz-buttons-container">
            <div class="jazz-content">
                <div id="jazz-text">
                    <p>
                        <?php if (isset($data['content']['JAZZ_HEADER_TEXT'])) {
                            echo $data['content']['JAZZ_HEADER_TEXT'];
                        } ?>
                    </p>
                </div>
                <hr>
                <div class="jazz-buttons-pt">
                    <button type="button" id="jazz-button-program" class="btn btn-secondary jazz-button-default jazz-button-small jazz-button-border-right col-4 no-gutters">
                        PROGRAM
                    </button><button type="button" id="jazz-button-tickets" class="btn btn-secondary jazz-button-default jazz-button-large col-4 no-gutters">
                        TICKETS
                    <button type="button" id="jazz-button-lineup" class="btn btn-secondary jazz-button-default jazz-button-small jazz-button-border-left col-4 no-gutters">
                        LINEUP
                    </button>
                </div>
                <hr>
            </div>
        </section>

        <section id="jazz-program-container">
            <div class="jazz-content">
                <h2 class="jazz-title text-center">PROGRAM</h2>
                <div class="row">
                    <?php foreach ($data['program'] as $program) { ?>
                        <div class="col-6">
                            <h3 class="jazz-program-day text-center">
                                <?php echo date("l, d F", strtotime($program[0]->date)); ?>
                            </h3>
                            <?php $counter = 0;
                            foreach ($data['uniqueLocations'] as $locations) {
                                foreach ($program as $day) {
                                    if ($day->location == $locations->location) {
                                        if ($counter == 0) { ?>
                                            <p class="jazz-program-location">
                                                <?php echo $locations->location ?>
                                            </p>
                                            <?php $counter++;
                                        } ?>

                                        <div class="row pl-3">
                                            <p class="col-3 jazz-program-event"><?php echo $day->startTime . "-" . $day->endTime; ?></p>
                                            <p class="col-auto jazz-program-event"><?php echo $day->name; ?></p>
                                        </div>
                                    <?php }
                                }
                                $counter = 0;
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>

        <section id="jazz-tickets-container">
            <div class="jazz-content">
                <hr>
                <div class="jazz-ad row">
                    <div class="col-4">
                        <a href="https://www.facebook.com/">
                            <img id="facebook-img-ad"
                                 src="<?php echo URLROOT; ?>/public/img<?php echo $data['content']['JAZZ_FACEBOOK_IMG']; ?>"
                                 alt="Facebook Link">
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="https://www.instagram.com/">
                            <img id="instagram-img-ad"
                                 src="<?php echo URLROOT; ?>/public/img<?php echo $data['content']['JAZZ_INSTAGRAM_IMG']; ?>"
                                 alt="Instagram Link">
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="https://www.twitter.com/">
                            <img id="twitter-img-ad"
                                 src="<?php echo URLROOT; ?>/public/img<?php echo $data['content']['JAZZ_TWITTER_IMG']; ?>"
                                 alt="Twitter Link">
                        </a>
                    </div>
                </div>
                <hr id="tickets-link">

                <div id="filter-block">
                    <input type="text" id="searchTicket" onkeyup="filterArtists()" value="<?php echo (!empty($data['query'])) ? $data['query'] : ''; ?>" placeholder="Search for an artist..">
                </div>

                <h2 class="text-center">TICKETS</h2>
                <div class="row">
                    <ul class="nav nav-pills nav-justified col-12 ticket-day-toggle px-0" role="tablist">
                        <?php $ariaSelected = "true";
                        $tabActive = " active";
                        foreach ($data['tickets'] as $tickets) {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tabActive; ?>" href="#t<?php echo $tickets[0]->date; ?>"
                                   data-toggle="tab" role="tab" aria-controls="day-<?php echo $tickets[0]->date; ?>"
                                   aria-selected="<?php echo $ariaSelected; ?>"><?php echo date("l", strtotime($tickets[0]->date)) . "<br>" . date("d F", strtotime($tickets[0]->date)); ?>
                                </a>
                            </li>
                            <?php $ariaSelected = "false";
                            $tabActive = "";
                        } ?>
                    </ul>

                    <div class="tab-content col-12 px-0">
                        <?php $showTab = " show active";
                        if (isset($data['tickets'])){
                            foreach ($data['tickets'] as $tickets) { ?>
                                <div class="tab-pane fade<?php echo $showTab ?>" id="<?php echo "t" . $tickets[0]->date; ?>"
                                     role="tabpanel" aria-labelledby="day-<?php echo $tickets[0]->date ?>">
                                    <table class="jazz-ticket-table">
                                        <?php foreach ($tickets as $ticket) {
                                            if ($ticket->date == $tickets[0]->date || $ticket->date == NULL) { ?>
                                                <tr>
                                                    <td class="jazz-ticket-time"><?php echo $ticket->startTime;
                                                        if ($ticket->startTime != NULL) {
                                                            echo "-";
                                                        }
                                                        echo $ticket->endTime; ?>
                                                    </td>
                                                    <td class="jazz-ticket-location"><?php echo $ticket->location; ?>
                                                        <br><?php echo date("l, d F", strtotime($ticket->date)); ?>
                                                    </td>
                                                    <td class="jazz-ticket-artist">
                                                        <?php echo $ticket->name; ?>
                                                    </td>
                                                        <?php if ($ticket->price > 0) { ?>
                                                    <td class="jazz-ticket-price">
                                                        €<?php echo number_format($ticket->price, 2, ",", "."); ?>
                                                    </td>
                                                    <td class="jazz-ticket-button">
                                                        <input type="hidden" id="product-id"
                                                               value="<?php echo $ticket->id ?>">
                                                        <input type="hidden" id="product-date"
                                                               value="<?php if ($ticket->date != null) {
                                                                   echo date('l d F', strtotime($ticket->date));
                                                               } ?>">
                                                        <?php if ($ticket->availability > 0) {
                                                            echo '<button type="button" class="jazz-button-buy jazz-button-available" data-toggle="modal" data-target="#cart-modal">Add to cart</button>';
                                                        } else {
                                                            echo '<button type="button" class="jazz-button-buy jazz-button-sold-out" disabled>Sold out</button>';
                                                        } ?>
                                                    </td>
                                                    <?php } else { ?>
                                                        <td class="jazz-ticket-price">
                                                        </td>
                                                        <td class="jazz-ticket-button">
                                                            <p class="ticket-free-text text-center">
                                                                Free<br><span id="no-reservation-text">No reservation needed</span>
                                                            </p>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php }
                                            $showTab = "";
                                        } ?>
                                    </table>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
        </section>

        <section id="jazz-lineup-container">
            <div class="jazz-content">
                <hr id="lineup-link">
                <h2 class="jazz-title text-center">
                    LINEUP
                </h2>
                <div class="row">
                    <?php if (isset($data['lineup'])){
                        foreach ($data['lineup'] as $artist) { ?>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <p class="jazz-lineup-name text-center">
                                    <?php echo $artist->name; ?>
                                </p>
                                <img class="jazz-lineup-img mb-4"
                                     src="<?php echo URLROOT; ?>/public/img<?php echo $artist->imgPath; ?>"
                                     alt="<?php echo $artist->name; ?> image">
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>
        </section>

        <section id="jazz-patronaat-container">
            <div class="jazz-content">
                <div class="row">
                    <div class="col-md-1">
                        <img class="jazz-icon-size"
                             src="<?php echo URLROOT; ?>/public/img<?php echo $data['content']['JAZZ_LOCATION_ICON']; ?>"
                             alt="Address Icon">
                    </div>
                    <div class="col-md-11">
                        <p class="jazz-patronaat-text">
                            <?php if (isset($data['content']['JAZZ_LOCATION_TEXT'])){
                                $locationPieces = explode(",", $data['content']['JAZZ_LOCATION_TEXT']);
                                foreach ($locationPieces as $piece) {
                                    echo $piece . '<br>';
                                }
                            } ?>
                        </p>
                    </div>
                    <div class="col-md-1">
                        <img class="jazz-icon-size"
                             src="<?php echo URLROOT; ?>/public/img<?php echo $data['content']['JAZZ_PHONE_ICON']; ?>"
                             alt="Phone Icon">
                    </div>
                    <div class="col-md-11">
                        <p class="jazz-patronaat-text">
                            <?php if (isset($data['content']['JAZZ_PHONE_TEXT'])){
                                $phonePieces = explode(",", $data['content']['JAZZ_PHONE_TEXT']);
                                foreach ($phonePieces as $piece) {
                                    echo $piece . '<br>';
                                }
                            } ?>
                        </p>
                    </div>
                    <div class="col-md-1">
                        <img class="jazz-icon-size"
                             src="<?php echo URLROOT; ?>/public/img<?php echo $data['content']['JAZZ_EMAIL_ICON']; ?>"
                             alt="Email Icon">
                    </div>
                    <div class="col-md-11">
                        <p class="jazz-patronaat-text">
                            <?php if (isset($data['content']['JAZZ_EMAIL_TEXT'])){
                                echo $data['content']['JAZZ_EMAIL_TEXT'];
                            } ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <?php require APPROOT . '/views/partial/cart-popup.php'; ?>
    </main>

<?php require APPROOT . '/views/partial/footer.php'; ?>