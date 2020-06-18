<?php
class JazzModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Method to get all the unique locations where artists perform jazz
     *
     * @return array of jazz festival locations
     */
    public function getUniqueLocations(){
        // SQL query to get the program data from the database by date
        $this->db->query('SELECT DISTINCT location FROM event WHERE category = :category');
        // Fill in the parameters for the specific category
        $this->db->bind(':category', 'JAZZ');
        try {
            // Get the result and return it
            return $this->db->getAll();
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Method to get the program data by date for the jazz category
     *
     * @param $date string date to get the program for
     * @return array of the program data
     */
    private function getProgramByDate($date){
        // SQL query to get the program data from the database by date
        $this->db->query('SELECT name, location, date, TIME_FORMAT(start_time, \'%H:%i\') as startTime, TIME_FORMAT(end_time, \'%H:%i\') as endTime FROM event JOIN event_day ON event.id = event_day.event_id WHERE category = :category AND date=:eventDate AND name NOT LIKE "All-Access%"  ORDER BY startTime');
        // Fill in the parameters for the specific category and date
        $this->db->bind(':category', 'JAZZ');
        $this->db->bind(':eventDate', $date);
        try{
            // Get the result and return it
            return $this->db->getAll();
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Method to get all the program data for jazz
     *
     * @param array $uniqueDays
     * @return array of the program data
     */
    public function getAllProgramData(array $uniqueDays){
        $programArray = [];
        foreach ($uniqueDays as $day){
            $programArray[] = $this->getProgramByDate($day->date);
        }
        return $programArray;
    }

    /**
     * Method to get tickets by date from the database
     *
     * @param $date string date to get the tickets for
     * @return array of the ticket data
     */
    private function getTicketsByDate($date){
        // SQL query to get the ticket data from the database by date and if it is a single ticket
        $this->db->query("SELECT id, name, location, date, TIME_FORMAT(start_time, '%H:%i') as startTime, TIME_FORMAT(end_time, '%H:%i') as endTime, price, availability FROM event JOIN event_day ON event.id = event_day.event_id WHERE category = :category AND date = :eventDate ORDER BY location,  startTime;");
        // Fill in the parameters for the specific category and date
        $this->db->bind(':category', 'JAZZ');
        $this->db->bind(':eventDate', $date);
        try{
            // Get the result and return it
            return $this->db->getAll();
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Method to get all the ticket data
     *
     * @param array $uniqueDays
     * @return array of the ticket data
     */
    public function getAllTicketData(array $uniqueDays){
        $ticketArray = [];
        foreach ($uniqueDays as $day){
            $ticketArray[] = $this->getTicketsByDate($day->date);
        }
        return $ticketArray;
    }

    /**
     * Method to get the jazz lineup
     *
     * @return array of jazz lineup with artist name and image
     */
    public function getLineUp(){
        // SQL query to get lineup information (artist name and image)
        $this->db->query("SELECT name, img_path AS imgPath FROM artists WHERE category = :category;");
        // Fill in the parameters for the specific category
        $this->db->bind(':category', 'JAZZ');
        try{
            // Get the result and return it
            return $this->db->getAll();
        } catch (\Throwable $th) {
            return null;
        }
    }
}
