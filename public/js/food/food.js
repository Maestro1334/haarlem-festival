$(function() {
  $('[data-toggle="tooltip"]').tooltip();
});

/** Method sending data to shopping cart modal **/
$('.btn-buy-ticket').click(function() {
  let parent = $(this).parent().parent();

  // Get the input fields of the modal
  let resetAmountInputField = $('#cart-modal #reset-amount');
  let amountInputField = $('#cart-modal #product-id');
  let eventTypeField = $('#cart-modal #ticket-type');
  let eventTitleField = $('#cart-modal #ticket-title');
  let eventDateField = $('#cart-modal #ticket-date');
  let quantityInputField = $('#cart-modal .product-amount-field');
  let commentInputField = $('#cart-modal #product-comment');

  // Getting data from the ticket row
  let productId = parseInt(parent.find('input[name=ticket-id]').val());
  let type = 'FOOD';
  let title = $(document).find('#restaurant-name').text();
  let dateTime = parent.find('input[name=ticket-datetime]').val();
  let quantity = parent.find('select[name=seats]').val();
  let combinedAllergens = [];
  let selectedAllergens = $('#allergen-select').prop('selectedOptions');
  for (const allergen of selectedAllergens) {
    combinedAllergens.push($(allergen).text());
  }

  // Filling all fields with data
  resetAmountInputField.val('false');
  amountInputField.val(productId);
  eventTypeField.text(type);
  eventTitleField.text(title);
  eventDateField.text(dateTime);
  quantityInputField.val(quantity);
  if (combinedAllergens.length > 0) {
    commentInputField.val('Allergen: ' + combinedAllergens.join());
  }

  // Showing the modal
  $('#cart-modal').modal('show');
});


// PREVENT INSIDE CLICK DROPDOWN
$('.dropdown-menu').on("click.bs.dropdown", function(e) {
  e.stopPropagation();
});

let typeCheckboxes = $('#type-dropdown').find('input.type-checkbox');
let restaurants = $('.restaurants .restaurant');

function checkedTypeValues() {
  let checkedBoxes = [];
  typeCheckboxes.each(function() {
    if (this.checked) {
      checkedBoxes.push($(this).val());
    }
  });
  return checkedBoxes;
}

function arrangeRestaurants() {
  let vissibleRestaurants = [];
  // Check for each restaurant if its types are still checked
  restaurants.each(function() {
    // Getting all types of the restaurant and making it an array
    let types = $(this).find('input[name=restaurant-type]').val();
    types = types.split(',');
    types.forEach(function(item, index) {
      types[index] = item.trim();
    });
    // Checking if one of the restaurant types is checked. Show/hide
    if (types.some(r => checkedTypeValues().indexOf(r) >= 0)) {
      $(this).show('100');
      vissibleRestaurants.push(true);
    } else {
      $(this).hide('100');
      vissibleRestaurants.push(false);
    }
  });
  // Show placeholder if no restaurants are shown
  if (vissibleRestaurants.indexOf(true) == -1) {
    $('#no-restaurants').show();
  } else {
    $('#no-restaurants').hide();
  }
}

$('.type-checkbox').change(function() {
  arrangeRestaurants();
});

$('.btn-filter-selector').on('click', function() {
  // Set all checkboxes to (select) true or (deselect) false
  let value = $(this).val() == 'select';
  typeCheckboxes.each(function() {
    $(this).prop('checked', value);
  });
  arrangeRestaurants();
});