<?php
class RestaurantModel
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  /**
   * Get all restaurants from the database
   * @return array of restaurants
   */
  public function getRestaurants()
  {
    $this->db->query("SELECT e.id as event_id, r.id as restaurant_id, e.name, r.price, r.type, r.short_description, r.img_path 
                      FROM event e JOIN event_restaurant er ON er.event_id = e.id 
                      JOIN restaurant r ON r.id = er.restaurant_id 
                      GROUP BY e.name ORDER BY e.id");
    try {
      $restaurants = $this->formatRestaurants($this->db->getAll());
      return $restaurants;
    } catch (\Throwable $th) {
      //throw $th;
      return null;
    }
  }
  
  /**
   * Get all data from a single restaurant
   * @param int $restaurantId The id of the restaurant
   * @return single restaurant object
   */
  public function getSingleRestaurant(int $restaurantId)
  {
    $this->db->query("SELECT e.id as event_id, r.id as restaurant_id, e.name, r.price, e.location, r.type, r.short_description, r.long_description, r.img_path 
                      FROM event e JOIN event_restaurant er ON er.event_id = e.id 
                      JOIN restaurant r ON r.id = er.restaurant_id WHERE r.id = :restaurantId 
                      GROUP BY e.name ORDER BY e.id");
    $this->db->bind(':restaurantId', $restaurantId);
    try {
      $restaurant = $this->db->getSingle();
      $restaurant = ($restaurant != null) ? $this->formatRestaurant($restaurant) : $restaurant;
      return $restaurant;
    } catch (\Throwable $th) {
      //throw $th;
      return null;
    }
  }

  /**
   * Get the id of a restaurant by name
   * @param string $restaurantName
   * @return int $restaurantId
   */
  public function getRestaurantIdByName(string $restaurantName)
  {
    $this->db->query("SELECT r.id FROM restaurant AS r JOIN event_restaurant AS er ON r.id = er.restaurant_id JOIN event AS e ON er.event_id = e.id WHERE e.name = :restaurantName GROUP BY r.id");
    $this->db->bind(':restaurantName', $restaurantName);
    try {
      print_r($this->db->getSingle());
      $restaurantId = $this->db->getSingle();
      $restaurantId = ($restaurantId != null) ? $restaurantId->id : null;
      return $restaurantId;
    } catch (\Throwable $th) {
      //throw $th;
      return null;
    }
  }

  /**
   * Get all restaurant types
   * @return array of restaurant types
   */
  public function getRestaurantTypes()
  {
    $this->db->query("SELECT GROUP_CONCAT(type) as types FROM (SELECT type FROM `restaurant`) AS types");
    try {
      $types = $this->db->getSingle()->types;
      $types = array_unique(array_map('trim', explode(',', $types)));
      return $types;
    } catch (\Throwable $th) {
      return null;
    }
  }

  /**
   * Get all allergens for the given restaurant
   * @param int $restaurantId The id of the restaurant to get the allergens from
   * @return array of allergens
   */
  public function getRestaurantAllergens(int $restaurantId)
  {
    $this->db->query("SELECT a.id, a.name FROM allergen AS a INNER JOIN restaurant_has_allergen as ra ON a.id = ra.allergen_id WHERE ra.restaurant_id = :restaurantId");
    $this->db->bind(':restaurantId', $restaurantId);
    try {
      $allergens = $this->db->getAll();
      return $allergens;
    } catch (\Throwable $th) {
      return null;
    }
  }

  /**
   * Format restaurant data of an array of restaurants
   * @param $restaurants Array of restaurants
   * @return array of formatted restaurants
   */
  private function formatRestaurants($restaurants)
  {
    foreach($restaurants as $restaurant)
    {
      $restaurant->price = number_format($restaurant->price, 2, ',', ' ');
      $restaurant->image = URLROOT .  $restaurant->img_path;
    }
    return $restaurants;
  }

  /**
   * Format a single restaurant
   * @param $restaurant The restaurant object
   * @return single formatted restaurant
   */
  private function formatRestaurant($restaurant)
  {
    $restaurant->image = URLROOT . $restaurant->img_path;
    $restaurant->location_split = explode(',', $restaurant->location);
    $restaurant->price = number_format($restaurant->price, 2, ',', ' ');

    return $restaurant;
  }
}