<?php
class ProgramModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Method to get the full program for all the unique dance days
     *
     * @param $uniqueDays array of the unique days
     * @return array the full ordered program
     */
    public function getFullProgram($uniqueDays){
        $sortedItems = [];

        // Insert all the unique dates into the sorted items array
        foreach ($uniqueDays as $item) {
            $sortedItems[$item->date] = $this->getDayProgram($item->date);
        }

        return $sortedItems;
    }

    /**
     * Base method to get the program for a specific day in the dance category
     *
     * @param $date string date to get the program for
     * @return array of the program items
     */
    private function getDayProgram($date){
        // Query the dance program for the given day if it is a single event ticket
        $this->db->query('SELECT e.name, e.location, TIME_FORMAT(e.start_time, \'%H:%i\') as startTime FROM event AS e
                                    JOIN event_day AS ed ON e.id = ed.event_id
                                    WHERE e.category = :category AND start_time IS NOT NULL AND ed.date= :eventDate');

        // Fill in the parameters for the specific category and day
        $this->db->bind(':category', 'DANCE');
        $this->db->bind(':eventDate', $date);

        try{
            // Get the result and return it
            return $this->db->getAll();
        } catch (\Throwable $th) {
            return [];
        }
    }
}