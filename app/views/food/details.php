<?php require APPROOT . '/views/partial/header.php'; ?>
<?php require APPROOT . '/views/partial/navbar.php'; ?>

<?php $restaurant = $data['restaurant']; ?>
<main class="details">
  <header id="header" class="container-fluid text-center">
    <picture id="header-image">
      <source media="(max-width: 576px)" srcset="<?php echo URLROOT . $data['content']['FOOD_HEADER_IMAGE_576']?>">
      <source media="(max-width: 992px)" srcset="<?php echo URLROOT . $data['content']['FOOD_HEADER_IMAGE_922']?>">
      <source media="(min-width: 993px)" srcset="<?php echo URLROOT . $data['content']['FOOD_HEADER_IMAGE_1920']?>">
      <img src="<?php echo URLROOT . $data['content']['FOOD_HEADER_IMAGE_1920']?>" alt="food header">
    </picture>
    <div class="header-title">
      <p id="restaurant-name"><?php echo $restaurant->name; ?></p>
      <p class="restaurant-type"><?php echo $restaurant->type; ?></p>
    </div>
  </header>
  <section id="content" class="container pb-3">
    <div class="row">
      <div class="col">
        <a href="<?php echo URLROOT; ?>/food" class="btn back-btn">Go back</a>
      </div>
    </div>
    <section id="restaurant-info" class="row">
      <div class="col-5">
        <img src="<?php echo $restaurant->image; ?>" alt="<?php echo $restaurant->name; ?>" class="restaurant-image">
        <div class="row">
          <div class="col-12 restaurant-location">
            <?php foreach ($restaurant->location_split as $location_part) { ?>
              <h2><?php echo $location_part; ?></h2>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="col-7" id="description">
        <p class="short-description">
          <?php echo $restaurant->short_description; ?>
        </p>
        <p>
          <?php echo $restaurant->long_description; ?>
        </p>
      </div>
    </section>
    <section id="restaurant-tickets" class="row justify-content-between my-3">
      <table id="tickets-table" class="col-8 table">
        <thead>
          <th>Date</th>
          <th>Time</th>
          <th>Duration</th>
          <th>Availible</th>
          <th>Seats</th>
          <th>Price</th>
          <th></th>
        </thead>
        <tbody>
          <?php foreach ($data['tickets'] as $ticket) { ?>
            <tr>
              <td><span class="ticket-date"><?php echo $ticket->date; ?></span></td>
              <td><span class="ticket-time"><?php echo $ticket->time; ?></span></td>
              <td><?php echo $ticket->duration; ?></td>
              <td><?php echo $ticket->availability; ?></td>
              <td>
                <select name="seats">
                  <?php for ($i=1; $i <= 10; $i++) { 
                    if($ticket->availability >= $i)
                    {
                      if($i == 2)
                      {
                        echo '<option value="'.$i.'" selected>'.$i.'</option>';
                      } else {
                        echo '<option value="'.$i.'">'.$i.'</option>';
                      }
                    }
                  } ?>
                </select>
              </td>
              <td>€<?php echo $restaurant->price; ?>*</td>
              <td>
                <input type="hidden" name="ticket-id" value="<?php echo $ticket->id; ?>">
                <input type="hidden" name="ticket-datetime" value="<?php echo date('l d F', strtotime($ticket->date)) . ' at ' . $ticket->time; ?>">
                <input type="hidden" name="ticket-price" value="<?php echo $ticket->price; ?>">
                <button type="button" class="btn btn-default btn-buy-ticket" <?php echo ((int)$ticket->availability == 0) ? 'disabled' : '' ?>><?php echo ((int)$ticket->availability == 0) ? 'Sold out' : 'Add to cart' ?></button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="col-3 pull-right extra-information">
        <div class="row">
          <div class="col-12">
            <label for="allergen-select" class="allergies-question">Allergies that must be taken into account: </label>
            <select name="allergen-select" id="allergen-select" class="selectpicker" multiple data-selected-text-format="count > 3" data-width="100%" data-size="8">
              <?php foreach($data['allergens'] as $allergen){?>
                <option value="<?php echo $allergen->id; ?>"><?php echo $allergen->name; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-12 mt-4">
            <p class="additional-info"><?php echo $data['content']['FOOD_DETAILS_RESERVATION_INFO']; ?></p>
          </div>
        </div>
      </div>
    </section>
  </section>
  <?php require APPROOT . '/views/partial/cart-popup.php'; ?>
</main>

<?php require APPROOT . '/views/partial/footer.php'; ?>