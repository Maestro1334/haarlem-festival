<?php
class ShoppingCart
{
    protected $cartId;
    private $useCookie = false;
    private $items = [];

    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }

        // Check if we can use cookies or not
        if(isset($_COOKIE['HaarlemFestival_Use_Cookies'])){
            $this->useCookie = $_COOKIE['HaarlemFestival_Use_Cookies'];
        }

        // Set the unique cartId to store all the data
        $this->cartId = md5((isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : 'HaarlemFestival') . '_cart';

        // Read all the available data in the cache
        $this->read();
    }
    /**
     * Add item to cart.
     *
     * @param string $id
     * @param int $availability
     * @param int $quantity
     * @param string $comment
     * @param array $attributes
     *
     * @return bool
     */
    public function add($id, $availability, $quantity = 1, $comment = null, $attributes = [])
    {
        $quantity = (preg_match('/^\d+$/', $quantity)) ? $quantity : 1;
        $attributes = (is_array($attributes)) ? array_filter($attributes) : [$attributes];
        if (isset($this->items[$id])) {
            self::update($id, $availability, $this->items[$id]['quantity'] += $quantity);
        } else {
            // Check if there are enough tickets available
            if($availability < $quantity) {
                $quantity = $availability;
                flash('alert', "Sorry, there are not enough tickets to complete your order. We added the maximum of $availability " . $attributes['name'] . " tickets.", 'alert alert-warning');
            }

            $this->items[$id] = [
                'id' => $id,
                'quantity' => $quantity,
                'comment' => $comment,
                'totalPrice' => $attributes['price'] * $quantity,
                'attributes' => $attributes
            ];
        }
        $this->write();
        return true;
    }
    /**
     * Update item quantity
     *
     * @param string $id
     * @param int $availability
     * @param int $quantity
     *
     * @return bool
     */
    public function update($id, $availability, $quantity = 1)
    {
        $quantity = (preg_match('/^\d+$/', $quantity)) ? $quantity : 1;
        if ($quantity == 0) {
            $this->remove($id);
            return true;
        }

        if (isset($this->items[$id])) {
            if($availability < $quantity) {
                flash('alert', "Sorry, there are not enough tickets to complete your order. We added the maximum of $availability " . $this->items[$id]['attributes']['name'] . " tickets.", 'alert alert-warning');
                $this->items[$id]['quantity'] = $availability;
            } else {
                $this->items[$id]['quantity'] = $quantity;
            }

            $this->items[$id]['totalPrice'] = $this->items[$id]['attributes']['price'] * $this->items[$id]['quantity'];
            $this->write();
            return true;
        }
        return false;
    }
    /**
     * Remove item from cart.
     *
     * @param string $id
     * @param array $attributes
     *
     * @return bool
     */
    public function remove($id)
    {
        // Check if the item is part of the shopping cart
        if (!isset($this->items[$id])) {
            return false;
        }
        unset($this->items[$id]);
        $this->write();
        return false;
    }
    /**
     * Method to get all the items in the cart
     * @return array the items available in the cart
     */
    public function getItems()
    {
        return $this->items;
    }
    /**
     * Method to get all the products per day
     *
     * @param $uniqueDates array the unique ticket dates
     * @return array with the items per day
     */
    public function getItemsSorted($uniqueDates)
    {
        $sortedItems = [];
        // Insert all the unique dates into the sorted items array
        foreach ($uniqueDates as $date) {
            $sortedItems[$date->date] = [];
        }
        // Loop trough all the items
        foreach ($this->items as $item) {
            foreach(explode(',', $item['attributes']['date']) as $date) {
                $item['attributes']['id'] = $item['id'];
                // Add the item to the sortedItemsArray
                array_push($sortedItems[$date], $item['attributes']);
            }
        }
        // Loop trough all the sorted items for order them by start time
        foreach ($sortedItems as $key => $value) {
            usort($sortedItems[$key], function ($a, $b) {
                if (!isset($a['startTime'])) {
                    return null;
                }
                return $a['startTime'] <=> $b['startTime'];
            });
        }
        return $sortedItems;
    }
    /**
     * Method to check if the array of items is empty
     * @return bool true if empty else false
     */
    public function isEmpty()
    {
        return empty(array_filter($this->items));
    }

    /**
     * Method to get the total of the unique items in the shopping cart
     * @return int the total of unique items in the cart
     */
    public function getTotalUniqueItems()
    {
        return count($this->items);
    }
    /**
     * Method to get the total quantity of all the items in the shopping cart
     * @return int the total quantity of items in the cart
     */
    public function getTotalItemQuantity()
    {
        $totalQty = 0;
        foreach ($this->items as $item) {
            $totalQty += $item['quantity'];
        }
        return $totalQty;
    }

    /**
     * Method to get the total amount of a specific product in the shopping cart
     *
     * @param int id of the product
     * @return float the total amount of the product
     */
    public function getProductAmount($id){
        if (isset($this->items[$id])) {
            return $this->items[$id]['quantity'];
        }
        return 0;
    }

    /**
     * Method to get the total price of a specific product in the shopping cart
     *
     * @param int id of the product
     * @return float the total price of the product
     */
    public function getProductTotalPrice($id)
    {
        if (isset($this->items[$id])) {
            return $this->items[$id]['totalPrice'];
        }
        return 0.00;
    }
    /**
     * Method to get the total price of all the products in the shopping cart
     * @return float the total price of all the products
     */
    public function getTotalPrice()
    {
        $totalPrice = 0.00;
        foreach ($this->items as $item) {
            $totalPrice += $item['attributes']['price'] * $item['quantity'];
        }
        return $totalPrice;
    }
    /**
     * Write changes into cart session.
     */
    private function write()
    {
        if ($this->useCookie) {
            setcookie($this->cartId, json_encode(array_filter($this->items)), time() + 604800, '/');
        } else {
            $_SESSION[$this->cartId] = json_encode(array_filter($this->items));
        }

    }
    /**
     * Read all the data from the cart session
     */
    private function read()
    {
        $this->items = ($this->useCookie) ? json_decode((isset($_COOKIE[$this->cartId])) ? $_COOKIE[$this->cartId] : '[]', true) : json_decode((isset($_SESSION[$this->cartId])) ? $_SESSION[$this->cartId] : '[]', true);
    }
    /**
     * Destroy cart session.
     */
    public function destroy()
    {
        $this->items = [];
        if ($this->useCookie) {
            setcookie($this->cartId, '', -1);
        } else {
            unset($_SESSION[$this->cartId]);
        }
    }
    /**
     * Clear the shopping cart
     */
    public function clear()
    {
        $this->items = [];
        $this->write();
    }
}