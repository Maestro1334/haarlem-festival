<?php
class EventModel
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
    /**
     * Method to get an event from the DB based on the given id
     * @param int $id the id of the event
     * @return mixed the event
     */
    public function getEvent($id){
        // Query for an event based on the id
        $this->db->query("SELECT e.*, GROUP_CONCAT(ed.date) AS date FROM event e 
                                    JOIN event_day AS ed ON e.id = ed.event_id 
                                    WHERE id = :id;");
        // Fill in the parameter for the specific id
        $this->db->bind(':id', $id);
        try{
            // Get the result and return it
            return $this->db->getSingle();
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * @param $id
     * @return int
     */
    public function getAvailableTickets($id){
        // Query for an event based on the id
        $this->db->query("SELECT COUNT(*) AS amount FROM event WHERE id=:id");
        // Fill in the parameter for the specific id
        $this->db->bind(':id', $id);
        try{
            // Get the result and return it
            return $this->db->getSingle()->amount;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    /**
     * Method to strip an event to the needed data to store it in the shopping cart
     *
     * @param $item
     * @return array
     */
    public function getStrippedEvent($item) {
        return ['name' => $item->name, 'category' => $item->category, 'location' => $item->location, 'date' => $item->date, 'startTime' => $item->start_time, 'endTime' => $item->end_time, 'price' => $item->price];
    }
    /**
     * Method to get the unique event days
     * @return array with the unique days
     */
    public function getUniqueDays(){
        // Query the unique days
        $this->db->query("SELECT ed.date FROM event e 
                                    JOIN event_day AS ed ON e.id = ed.event_id
                                    GROUP BY ed.date ORDER BY ed.date;");
        try{
            // Get the result and return it
            return $this->db->getAll();
        } catch (\Throwable $th) {
            return [];
        }
    }
    /**
     * Method to get the unique event days based on the category
     *
     * @param string $category the category to search for
     * @return { array | string }
     */
    public function getUniqueDaysByCategory($category){
        // Query the unique days for a specific category
        $this->db->query("SELECT ed.date FROM event e 
                                    JOIN event_day AS ed ON e.id = ed.event_id
                                    WHERE e.category = :category
                                    GROUP BY ed.date ORDER BY ed.date;");
        // Fill in the parameters for the specific category and day
        $this->db->bind(':category', $category);
        try{
            // Get the result and return it
            return $this->db->getAll();
        } catch (\Throwable $th) {
            return [];
        }
    }
    /**
     * Option used to search in the event table based on the given value
     *
     * @param $value string the raw value of the user
     * @return array with the results or an error
     */
    public function getFilteredEvents($value){
        // Search query to find all the events related to the given search value
        $this->db->query("SELECT e.name, e.category, GROUP_CONCAT(ed.date) AS days, e.location, e.price FROM event e 
                                    LEFT JOIN event_day ed ON e.id = ed.event_id
                                    LEFT JOIN event_restaurant er ON e.id = er.event_id
                                    LEFT JOIN restaurant r ON er.restaurant_id = r.id
                                    WHERE e.name LIKE '%' :value '%' OR r.type LIKE '%' :value '%' GROUP BY e.name, ed.date ORDER BY e.category;");
        // Fill in the parameters for the specific search value
        $this->db->bind(':value', $value);
        try {
            // Get the results from the database
            $results = $this->db->getAll();
            // Check if there are any results
            if(empty($results)){
                // Return the nothing found error
                return ['error' => "No results for '$value''"];
            }
            // Return the results
            return $results;
        } catch (\Throwable $th) {
            return [];
        }
    }
    /**
     * Get the different locations for the event with their category
     *
     * @return array of locations | null
     */
    public function getEventLocations()
    {
        $this->db->query("SELECT category, lat, lon FROM `event` WHERE lat IS NOT NULL AND lon IS NOT NULL GROUP BY lat, lon");
        try {
            $results = $this->db->getAll();
            return $results;
        } catch (\Throwable $th) {
            return null;
        }
    }
}