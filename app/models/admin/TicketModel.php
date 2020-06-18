<?php 

Class TicketModel {

    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getTickets($category)
    {
        $this->db->query("SELECT id, name, category, start_time, end_time, price, availability, date, SUM(quantity) AS quantity FROM event LEFT JOIN event_day ON id = event_day.event_id LEFT JOIN booking_has_event on id = booking_has_event.event_id WHERE category = :category GROUP BY event.id ORDER BY date, start_time ASC");
        if ($category == 'HISTORIC') {
            $this->db->query("SELECT id, name, category, start_time, end_time, price, availability, date, SUM(quantity) AS quantity FROM event LEFT JOIN event_day ON id = event_day.event_id LEFT JOIN booking_has_event on id = booking_has_event.event_id WHERE category = :category AND name LIKE :singleTour GROUP BY event.id ORDER BY date, start_time ASC");
            $this->db->bind(':singleTour', 'Historic Tour Single%');
        }
        $this->db->bind(':category', $category);

        try {
            $tickets = $this->db->getAll();
            return $tickets;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateTicket($data)
    {
        $this->db->query("UPDATE event SET price = :price, availability = :availability WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':availability', $data['availability']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getTicketById($id)
    {
        $this->db->query('SELECT id, name, category, start_time, end_time, price, availability, date FROM event JOIN event_day ON id = event_id WHERE id = :id');
        $this->db->bind(':id', $id);

        try {
            $ticket = $this->db->getSingle();
            return $ticket;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

?>