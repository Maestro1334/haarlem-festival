let baseUrl = window.location.origin + '/haarlem_festival/';

$('.button-remove').click(function () {
    let parent = $(this).parent().parent();

    updateProductAmount(parent, getProductAmount(parent) - 1);

});

$('.button-add').click(function () {
    let parent = $(this).parent().parent();

    updateProductAmount(parent, getProductAmount(parent) + 1);

});

function getProductAmount(parent) {
    return parseInt(parent.children().find('.product-amount-indicator').find('.product-amount').text());
}

/**
 * Method to update a product amount and remove it when needed
 *
 * @param parent
 * @param newAmount
 */
function updateProductAmount(parent, newAmount) {
    let child = parent.children();

    let productId = parent.find('.productId').val();
    let productAmountField = child.find('.product-amount-indicator').find('.product-amount');

    let productTotalPriceField = parent.find('.product-price-indicator').find('.product-price');
    let totalPriceField = document.getElementById("totalPrice");
    let vatPriceField = document.getElementById("vatAmount");

    if (newAmount <= 0) {
        parent.remove();

        // Delete the item from the program
        deleteItemFromProgram(productId);
    }

    $.post(baseUrl + "cart/update/" + productId + "/" + (newAmount), function (data) {
        productAmountField.text(data['productAmount']);
        productTotalPriceField.text(data['productTotalPrice']);
        totalPriceField.innerHTML = data['totalPrice'].toFixed(2);
        vatPriceField.innerHTML = (data['totalPrice'] * 0.09).toFixed(2);

        if(parseInt(data['productAmount']) !== newAmount){
            window.location.reload();
        }
    })
        .fail(function (response) {

        })
}

/**
 * Method to delete an item from the custom program when it is removed from the shopping cart
 * @param id the id of the product to search for
 */
function deleteItemFromProgram(id){
    let items = $('.program-content .program-artist');
    let programContainers = $('.program-container');

    items.each(function() {
        let productId = $(this).parent().find('.productId').val();

        if(productId === id) {
            $(this).parent().remove();
        }
    });

    programContainers.each(function() {
        let parent = $(this);
        let visibleCount = $(this).find('.program-row').filter(function(){
            if($(this).css('display') !== 'none')
                return $(this);
        }).length;

        if(visibleCount <= 0) {
            parent.find('.program-nothing-planned').show();
        } else {
            parent.find('.program-nothing-planned').hide();
        }
    });
}

