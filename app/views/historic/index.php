<?php require APPROOT . '/views/partial/header.php'; ?>
<?php require APPROOT . '/views/partial/navbar.php'; ?>
<!-- Everything body from header to last section (no footer) -->
<main class="historic">
  <!-- This hidden input stores the user search query value -->
  <input type="hidden" id="user-searched" value="<?php if (isset($_GET['query'])) { echo $_GET['query']; } ?>">
  <!-- Header section -->
  <div class="container-fluid header-container">
    <img id="header-image" class="" src="<?php echo URLROOT . $data['content']['HISTORIC_HEADER_IMAGE']?>" alt="header image">
    <div id="black-rectangle"></div>
    <div class="header-title">
      <p><?php echo $data['content']['HISTORIC_HEADER_TEXT']?></p>
      <button type="button" class="btn btn-info btn-tickets" id="schedule-scroll"><?php echo $data['content']['HISTORIC_HEADER_BUTTON']?></button>
    </div>
  </div>

  <!-- General section -->
  <div class="container-fluid general-container">
    <hr class="horizontal-line">
    <div class="general-title">
      <p><?php echo $data['content']['HISTORIC_GENERAL_TITLE']?></p>
    </div>
    <div class="general-text">
      <p><?php echo $data['content']['HISTORIC_GENERAL_TEXT']?></p>
    </div>
    <div class="container-fluid pictures-section">
        <div class="tour-pictures">
          <div class="row">
            <div class="col-md">
              <img src="<?php echo URLROOT . $data['content']['HISTORIC_GENERAL_IMAGE_1']?>" alt="Historic tour picture 1">
            </div>
            <div class="col-md">
              <img src="<?php echo URLROOT . $data['content']['HISTORIC_GENERAL_IMAGE_2']?>" alt="Historic tour picture 2">
            </div>
            <div class="col-md">
              <img src="<?php echo URLROOT . $data['content']['HISTORIC_GENERAL_IMAGE_3']?>" alt="Historic tour picture 3">
            </div>
          </div>
        </div>
    </div>
  </div>

  <!-- Tickets & schedule section -->
  <div class="container-fluid tickets-container">
    <hr class="horizontal-line information-line">
    <div class="tickets-title">
      <p><?php echo $data['content']['HISTORIC_TICKETS_TITLE']?></p>
    </div>
    <div class="tickets-text">
      <p><?php echo $data['content']['HISTORIC_TICKETS_TEXT']?></p>
    </div>
    <div class="schedule-title">
      <p class="schedule-underline"><?php echo $data['content']['HISTORIC_SCHEDULE_TITLE']?></p>
    </div>

    <!-- Interactive schedule -->
    <div class="tickets-section">
      <!-- Language filter select -->
      <div class="language-filter">
        <label for="language-filter">Filter:</label>
        <select id="language-filter">
          <?php $languages = explode(',', $data['languages']->languages); ?>
          <?php foreach($languages as $lang) {?>
            <option value="<?php echo $lang ?>"><?php echo $lang ?></option>
          <?php } ?>
        </select>
      </div>
      <ul class="nav nav-pills nav-justified col-sm-11 col-md-6 mx-auto tickets-day-toggle" id="myTabJust" role="tablist">
        <?php foreach ($data['uniqueDays'] as $uniqueDay) { ?>
          <li class="nav-item">
            <a class="nav-link <?php echo $data['uniqueDays'][0] == $uniqueDay ? "active" : ""; ?>"
               href="#date-<?php echo $uniqueDay->date; ?>" data-toggle="tab" role="tab"
               aria-controls="date-<?php echo $uniqueDay->date; ?>"
               aria-selected="true"><?php echo date('l d F', strtotime($uniqueDay->date)) ?></a>
          </li>
        <?php } ?>
      </ul>

      <div class="tab-content mx-auto">
        <div class="row mx-auto ticket-row contents">
          <div class="custom-col-2 align-self-center">
            <p>Departure time</p>
          </div>
          <div class="custom-col-2 align-self-center">
            <p>Language</p>
          </div>
          <div class="custom-col-1 align-self-center">
            <p>Availability</p>
          </div>
          <div class="custom-col-3 align-self-center">
            <p>Start location</p>
          </div>
          <div class="custom-col-3 ticket-col align-self-center">
            <p>Single ticket (1 pers.)</p>
          </div>
          <div class="custom-col-3 ticket-col align-self-center">
            <p>Family ticket (4 pers.)</p>
          </div>
        </div>

        <!-- For each unique day, create day in schedule -->
        <?php foreach ($data['uniqueDays'] as $uniqueDay) { ?>
          <div class="tab-pane fade <?php echo $data['uniqueDays'][0] == $uniqueDay ? "show active" : ""; ?>" id="date-<?php echo $uniqueDay->date; ?>" role="tabpanel" aria-labelledby="date-<?php echo $uniqueDay->date; ?>">
            <div class="ticket-container">
              <!-- For each tour departure time on specified date, -->
              <!-- create div product-group that contains available tickets for each time -->
              <?php foreach($data['program'][$uniqueDay->date] as $time => $value) { ?>
                <div class="product-group">
                  <!-- For each language in departure time, create row -->
                  <?php foreach($data['program'][$uniqueDay->date][$time] as $item) { ?>
                    <div class="row mx-auto ticket-row <?php echo $item->language ?>">
                      <input type="hidden" id="product-id" value="<?php echo $item->id ?>">
                      <input type="hidden" id="product-date" value="<?php if($item->date != null) { echo date('l d F', strtotime($item->date)); } ?>">
                      <input type="hidden" id="product-name" value="<?php echo $item->name ?>">
                      <input type="hidden" id="product-availability" value="<?php echo $item->availability ?>">
                      <input type="hidden" id="product-family-id" value="<?php echo $item->familyId ?>">

                      <div class="custom-col-2 ticket-time align-self-center">
                        <p><?php echo $item->departureTime ; ?></p>
                      </div>
                      <div class="custom-col-2 ticket-language">
                        <select class="language-select">
                          <?php $languages = explode(',', $item->languages); ?>
                          <?php foreach($languages as $lang) {?>
                            <option value="<?php echo $lang ?>" <?php if($lang == $item->language) { echo 'selected'; } ?>><?php echo $lang ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="custom-col-1 ticket-availability align-self-center">
                        <p><?php
                          if ($item->availability <= 0) { echo "Sold out"; }
                          elseif ($item->availability == 1) { echo $item->availability . " ticket"; }
                          else { echo $item->availability ." tickets"; } ?></p>
                      </div>
                      <div class="custom-col-3 ticket-location align-self-center">
                        <p><?php echo $item->location ?></p>
                      </div>
                      <div class="custom-col-3 ticket-price">
                        €<?php echo number_format($item->singlePrice, 2); ?>
                        <button class="btn button-buy single" <?php if( $item->availability <= 0){ echo 'disabled'; }?> data-toggle="modal" data-target="#cart-modal"><?php echo ($item->availability <= 0 ? 'Sold Out' : 'Add to Cart');?></button>
                      </div>
                      <div class="custom-col-3 ticket-price">
                        €<?php echo number_format($item->familyPrice, 2); ?>
                        <button class="btn button-buy family" <?php if( $item->availability <= 4){ echo 'disabled'; }?> data-toggle="modal" data-target="#cart-modal"><?php echo ($item->availability <= 4 ? 'Sold Out' : 'Add to Cart');?></button>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="interactive-schedule">
      <button type="button" class="btn btn-info btn-tickets" id="information-scroll"><?php echo $data['content']['HISTORIC_INFORMATION_BUTTON']?></button>
    </div>
  </div>

  <!-- More information section -->
  <div class="container-fluid information-container">
    <hr class="horizontal-line">
    <div class="information-title">
      <h1 class="information-underline"><?php echo $data['content']['HISTORIC_INFORMATION_TITLE']?></h1>
    </div>
    <div class="information-text">
      <?php echo $data['content']['HISTORIC_MORE_INFORMATION']?>
    </div>
  </div>

  <!-- Unguided tour section -->
  <div class="container-fluid unguided-tour-container">
    <hr class="horizontal-line information-line">
    <div class="unguided-text">
      <h5><?php echo $data['content']['HISTORIC_UNGUIDED_TITLE']?></h5>
      <p><?php echo $data['content']['HISTORIC_UNGUIDED_TEXT']?></p>
    </div>
    <div class="unguided-link">
      <a href="<?php echo $data['content']['HISTORIC_UNGUIDED_LINK']?>"><?php echo $data['content']['HISTORIC_UNGUIDED_LINK_TEXT']?></a>
    </div>
    <div class="row unguided-tour-pictures">
      <div class="col-sm unguided-img-1">
        <img src="<?php echo URLROOT . $data['content']['HISTORIC_UNGUIDED_IMAGE_1']?>" alt="Unguided image 1">
      </div>
      <div class="col-sm unguided-img-2">
        <img src="<?php echo URLROOT . $data['content']['HISTORIC_UNGUIDED_IMAGE_2']?>" alt="Unguided image 2">
      </div>
    </div>
  </div>
  <?php require APPROOT . '/views/partial/cart-popup.php'; ?>
</main>
<?php require APPROOT . '/views/partial/footer.php'; ?>