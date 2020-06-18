<?php require APPROOT . '/views/partial/header.php'; ?>
<?php require APPROOT . '/views/partial/navbar.php'; ?>

<main>
  <header id="header" class="container-fluid text-center">
    <picture id="header-image">
      <source media="(max-width: 576px)" srcset="<?php echo URLROOT . $data['content']['FOOD_HEADER_IMAGE_576'] ?>">
      <source media="(max-width: 992px)" srcset="<?php echo URLROOT . $data['content']['FOOD_HEADER_IMAGE_992'] ?>">
      <source media="(min-width: 993px)" srcset="<?php echo URLROOT . $data['content']['FOOD_HEADER_IMAGE_1920'] ?>">
      <img src="<?php echo URLROOT . $data['content']['FOOD_HEADER_IMAGE_1920'] ?>" alt="food header">
    </picture>
    <div class="header-title">
      <?php $parts = explode('\n', $data['content']['FOOD_HEADER_TEXT']);
      foreach ($parts as $part) { ?>
        <p><?php echo $part; ?></p>
      <?php } ?>
    </div>
  </header>
  <section id="content" class="pb-5">
    <section id="info" class="container">
      <article class="text-center">
        <p><?php echo $data['content']['FOOD_CONTENT_TEXT']; ?></p>
      </article>
    </section>
    <section id="restaurants-overview" class="container">
      <?php flash('foodIndexMessage'); ?>
      <div class="row filter">
        <?php if ($data['types']) { ?>
          <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownFilterRestaurants" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Filter restaurants
            </button>
            <div class="dropdown-menu" id="type-dropdown" aria-labelledby="dropdownFilterRestaurants">
              <li class="text-center">
                <button type="button" class="btn btn-outline-secondary btn-filter-selector" value="select">Select all</button>
                <button type="button" class="btn btn-outline-secondary btn-filter-selector" value="deselect">Deselect all</button>
              </li>
              <div class="dropdown-divider"></div>
              <?php foreach ($data['types'] as $type) { ?>
                <li>
                  <label for="checkbox-<?php echo $type; ?>">
                    <input type="checkbox" name="checkbox-<?php echo $type; ?>" id="checkbox-<?php echo $type; ?>" class="type-checkbox" value="<?php echo $type;?>" checked><?php echo $type; ?>
                  </label>
                </li>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      </div>
      <div class="row align-items-stretch restaurants">
        <?php foreach ($data['restaurants'] as $restaurant) { ?>
          <article class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 restaurant">
            <div class="card h-100">
              <img src="<?php echo $restaurant->image; ?>" alt="<?php echo $restaurant->name; ?>" class="card-img-top restaurant-image">
              <div class="card-body">
                <header>
                  <p class="restaurant-name"><?php echo $restaurant->name; ?></p>
                  <p class="restaurant-type">Type: <?php echo $restaurant->type; ?></p>
                  <input type="hidden" name="restaurant-type" value="<?php echo $restaurant->type;?>">
                </header>
                <hr>
                <p><?php echo $restaurant->short_description; ?></p>
              </div>
              <div class="card-footer row">
                <div class="col-6 pr-md-0 pr-sm-0">
                  <span>Price €<?php echo $restaurant->price; ?></span>
                </div>
                <div class="col-6 pl-sm-0">
                  <a href="<?php echo URLROOT . '/food/details/' . $restaurant->restaurant_id; ?>" class="btn float-right">Get tickets</a>
                </div>
              </div>
            </div>
          </article>
        <?php } ?>
        <article id="no-restaurants">
          <p>
            Unfortunately there are no restaurants matching your filter.
          </p>
        </article>
      </div>
    </section>
  </section>
</main>

<?php require APPROOT . '/views/partial/footer.php'; ?>