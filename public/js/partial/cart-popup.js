let baseUrl = window.location.origin + '/haarlem_festival/';

$(document).ready(function() {
  let resetAmountInput = $("#reset-amount");
  $("#cart-modal").on("show.bs.modal", function() {
    if (resetAmountInput.val() == "true") {
      $("#cart-modal .product-amount-field").val(1);
    }

    //Hiding one of the cross selling blocks
    let ticketType = $('#cart-modal #ticket-type').text();
    switch (ticketType) {
      case 'DANCE':
        $('#cart-modal #cross-selling-dance').hide();
        break;
      case 'JAZZ':
        $('#cart-modal #cross-selling-jazz').hide();
        break;
      case 'FOOD':
        $('#cart-modal #cross-selling-food').hide();
        break;
      case 'HISTORIC':
        $('#cart-modal #cross-selling-historic').hide();
        break;
      default:
        $('#cart-modal .cross-selling-blocks .card').first().hide();
        break;
    }
  });
  $("#cart-modal").on("hide.bs.modal", function() {
    resetAmountInput.val("true");
    $('#cart-modal #product-comment').val(null);
    $('#cart-modal .cross-selling-blocks').children().show();
  });
});

$('#cart-modal #button-remove').click(function() {
  let inputField = $('#cart-modal .product-amount-field');
  let inputValue = parseInt(inputField.val());

  if (inputValue - 1 > 0) {
    inputField.val(inputValue - 1);
  }
});

$('#cart-modal #button-add').click(function() {
  let inputField = $('#cart-modal .product-amount-field');
  let inputValue = parseInt(inputField.val());

  inputField.val(inputValue + 1);
});

$('#cart-modal .btn-add-product').click(function() {
  let inputField = $('#cart-modal .product-amount-field');
  let inputValue = parseInt(inputField.val());

  let commentInputField = $('#cart-modal #product-comment');
  let commentValue = commentInputField.val();
  let sendingData = (commentValue != '') ? (inputValue + '/' + commentValue) : (inputValue);


  let productId = parseInt($('#cart-modal #product-id').val());
  let isCheckout = $(this).hasClass('btn-checkout');

  $.post(baseUrl + "cart/add/" + productId + "/" + (sendingData), function(data) {
    if (isCheckout) {
      window.location.href = baseUrl + 'checkout';
    }
  })
});