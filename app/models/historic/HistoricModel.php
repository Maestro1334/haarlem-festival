<?php
  class HistoricModel {
    private $db;

    public function __construct()
    {
      $this->db = new Database();
    }

    public function getHistoricProgram() {
      // SQL query to get the program data from the database
      $this->db->query('SELECT e.id, e.name, d.date, TIME_FORMAT(e.start_time, \'%H:%i\') AS departureTime, substring(name, 22) AS language, e.availability, e.location, min(e.price) AS singlePrice, (SELECT max(price) FROM event WHERE category = :category) AS familyPrice
                              FROM event e JOIN event_day d ON e.id=d.event_id
                              WHERE category = :category AND name NOT LIKE \'%Family%\'
                              GROUP BY id');
      // Fill in the parameters for the specific category and date
      $this->db->bind(':category', 'HISTORIC');

      try{
        // Get the result and return it
        $program = $this->db->getAll();

        foreach($program as $ticket){
          $ticket->languages = $this->getLanguagesByDateAndTime($ticket->date, $ticket->departureTime)->languages;
        }

        foreach($program as $ticket){
          $ticket->familyId = $this->getFamilyIdsByDateTimeAndLanguage($ticket->date, $ticket->departureTime, $ticket->language)->familyId;
        }

//        print_r($this->getTicketsSorted($program));
        return $this->getTicketsSorted($program);


      } catch (\Throwable $th) {
        return null;
      }
    }

    private function getTicketsSorted($program)
    {
      $sortedArray = [];

      foreach ($program as $row) {
        if (isset($sortedArray[$row->date][$row->departureTime])) {
          array_push($sortedArray[$row->date][$row->departureTime], $row);
        }
        else {
          $sortedArray[$row->date][$row->departureTime] = [$row];
        }
      }
      return $sortedArray;
    }


    private function getLanguagesByDateAndTime($date, $time) {
      $this->db->query('SELECT GROUP_CONCAT(DISTINCT SUBSTRING(name,22) SEPARATOR \',\') AS languages 
                              FROM event e JOIN event_day d ON e.id=d.event_id
                              WHERE category = :category AND date = :eventDate AND start_time = :departureTime');
      // Fill in the parameters for the specific category and date
      $this->db->bind(':category', 'HISTORIC');
      $this->db->bind(':eventDate', $date);
      $this->db->bind(':departureTime', $time);
      try{
        // Get the result and return it

        return $this->db->getSingle();
      } catch (\Throwable $th) {
        return null;
      }
    }

    private function getFamilyIdsByDateTimeAndLanguage($date, $time, $language) {
      $this->db->query('SELECT e.id AS familyId FROM event e JOIN event_day d ON e.id=d.event_id
                              WHERE category = :category AND date = :eventDate AND start_time = :departureTime AND SUBSTRING(e.name,22) = :lang AND name LIKE \'%Family%\'');
      // Fill in the parameters for the specific category and date
      $this->db->bind(':category', 'HISTORIC');
      $this->db->bind(':eventDate', $date);
      $this->db->bind(':departureTime', $time);
      $this->db->bind(':lang', $language);
      try{
        // Get the result and return it

        return $this->db->getSingle();
      } catch (\Throwable $th) {
        return null;
      }
    }

    public function getAllTourLanguages(){
      $this->db->query('SELECT GROUP_CONCAT(DISTINCT SUBSTRING(name,22) SEPARATOR \',\') AS languages 
                              FROM event
                              WHERE category = :category');

      $this->db->bind(':category', 'HISTORIC');
      try{
        // Get the result and return it

        return $this->db->getSingle();
      } catch (\Throwable $th) {
        return null;
      }
    }
  }

