<?php

class FoodController extends Controller
{
  private $ticketModel;
  private $restaurantModel;
  private $contentModel;

  public function __construct()
  {
    $this->addCSS('food/style.css');
    $this->addJs('food/food.js');

    $this->ticketModel = $this->model('TicketModel', 'food');
    $this->restaurantModel = $this->model('RestaurantModel', 'food');
    $this->contentModel = $this->model('ContentModel');
  }

  public function index()
  {
    if (isset($_GET['query'])) {
      $this->redirectFromSearchQuery();
    }
    $data =
      [
        'restaurants' => $this->restaurantModel->getRestaurants(),
        'types' => $this->restaurantModel->getRestaurantTypes(),
        'content' => $this->contentModel->getContentForCategory(ContentCategory::FOOD)
      ];
    $this->view('food/index', $data);
  }

  public function details($id)
  {
    $this->addCSS('cart-popup.css');
    $this->addJs('partial/cart-popup.js');
    $this->addCSS('bootstrap-select.min.css');
    $this->addJs('bootstrap-select.min.js');
    try {
      $id = (int) $id;
      $data = [
        'restaurant' => $this->restaurantModel->getSingleRestaurant($id),
        'allergens' => $this->restaurantModel->getRestaurantAllergens($id),
        'tickets' => $this->ticketModel->getRestaurantTickets($id),
        'content' => $this->contentModel->getContentForCategory(ContentCategory::FOOD)
      ];
      $this->view('food/details', $data);
    } catch (Throwable $th) {
      redirect('food/index');
    }
  }

  private function redirectFromSearchQuery()
  {
    $restaurantName = urldecode($_GET['query']);
    try {
      $restaurantId = $this->restaurantModel->getRestaurantIdByName($restaurantName);
      if($restaurantId != null)
      {
        redirect('food/details/' . $restaurantId);
      }
    } catch (\Throwable $th) {
    }
    //No restaurant found
    flash('foodIndexMessage', 'You tried searching for: '. $restaurantName . '. There is no restaurant matching your search.<br>Try the restaurants below', 'alert alert-warning');
  }
}
