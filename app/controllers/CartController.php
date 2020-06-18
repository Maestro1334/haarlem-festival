<?php

class CartController extends Controller {

    private $eventModel;
    private $shoppingCart;

    public function __construct()
    {
        $this->eventModel = $this->model('EventModel');
        $this->shoppingCart = new ShoppingCart();
    }

    public function index(){
        redirect();
    }

    /**
     * Action to add a product to the shopping cart
     *
     * @param null $id the id of the product
     * @param int $quantity the quantity
     * @param null $comment the optional comment
     */
    public function add($id = null, $quantity = 1, $comment = null){
        $result = $this->eventModel->getEvent($id);

        // Check if the result is not null
        if($result != null){
            $this->shoppingCart->add($result->id, $result->availability, $quantity, $comment, $this->eventModel->getStrippedEvent($result));
        }

        redirect();
    }

    /**
     * Action to update a product in the shopping cart
     *
     * @param null $id the id of the product
     * @param int $quantity the quantity
     */
    public function update($id = null, $quantity = 1){
        $result = $this->eventModel->getEvent($id);

        if($result != null){
            $this->shoppingCart->update($result->id, $result->availability, $quantity);

            header('Content-Type: application/json');
            echo json_encode(['productAmount' => $this->shoppingCart->getProductAmount($id), 'productTotalPrice' => $this->shoppingCart->getProductTotalPrice($id), 'totalPrice' => $this->shoppingCart->getTotalPrice()]);
            return;
        }

        redirect();
    }
}