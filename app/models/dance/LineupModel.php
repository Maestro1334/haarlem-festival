<?php
class LineupModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Method to get the lineup
     * @return array of the lineup items
     */
    public function getLineUp(){
        // Query the dance program for the given day if it is a single event ticket
        $this->db->query("SELECT name, img_path AS imgPath FROM artists WHERE category = :category LIMIT 6;");

        // Fill in the parameters for the specific category
        $this->db->bind(':category', 'DANCE');

        try{
            // Get the result and return it
            return $this->db->getAll();
        } catch (\Throwable $th) {
            return [];
        }
    }
}