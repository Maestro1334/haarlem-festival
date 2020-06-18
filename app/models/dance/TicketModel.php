<?php
class TicketModel {
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }
    /**
     * Method to get the tickets for all the unique dance days
     *
     * @param $uniqueDays array of the unique days
     * @return array the full ordered available tickets
     */
    public function getAllTickets($uniqueDays){
        $sortedItems = [];
        // Insert all the unique dates into the sorted items array
        foreach ($uniqueDays as $item) {
            $sortedItems[$item->date] = $this->getDayTickets($item->date);
        }
        return $sortedItems;
    }
    /**
     * Base method to get the tickets for a specific day in the dance category
     *
     * @param $date string date to get the tickets for
     * @return array of the tickets
     */
    private function getDayTickets($date){
        // Query the dance program for the given day if it is a single event ticket
        $this->db->query("SELECT e.id, e.name, ed.date, e.availability, d.event_type AS eventType, e.price FROM event e 
                                    JOIN event_day AS ed ON e.id = ed.event_id
                                    LEFT JOIN dance AS d ON e.id = d.event_id
                                    WHERE category = :category AND ed.date = :eventDate;");
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
    /**
     * Method to retrieve all the notes for each day
     * @return array
     */
    public function getDaySpecificNotes(){
        // Query the dance notes
        $this->db->query("SELECT date, GROUP_CONCAT(note) AS notes FROM dance_day_notes GROUP BY date;");
        try{
            // Get the results from the database
            $results = $this->db->getAll();

            $sortedNotes = [];
            foreach($results as $result){
                $sortedNotes[$result->date] = explode(',', $result->notes);
            }
            // Return the sorted notes
            return $sortedNotes;
        } catch (\Throwable $th) {
            return [];
        }
    }
}