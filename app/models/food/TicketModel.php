<?php
class TicketModel
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  /**
   * Gett all tickets for a single restaurant
   * @param int $restaurantId The id of the restaurant
   * @return array of tickets for the restaurant
   */
  public function getRestaurantTickets(int $restaurantId)
  {
    $this->db->query("SELECT e.id, e.name, e.category, e.start_time, e.end_time, e.location, e.price, e.availability, ed.date
                        FROM event AS e 
                        INNER JOIN event_day AS ed ON e.id = ed.event_id
                        WHERE id IN( 
                        SELECT event_id FROM event_restaurant 
                        WHERE restaurant_id = :restaurantId)");
    $this->db->bind(':restaurantId', $restaurantId);
    try {
      $tickets = $this->db->getAll();
      $tickets = ($tickets != null) ? $this->formatTickets($tickets) : $tickets;
      return $tickets;
    } catch (\Throwable $th) {
      //throw $th;
      return null;
    }
  }

  /**
   * Format an array of tickets
   * @param $tickets The array of tickets to filter
   * @return array of formatted tickets
   */
  private function formatTickets($tickets)
  {
    foreach($tickets as $ticket)
    {
      // Ticket date
      $date = new DateTime($ticket->date);
      $ticket->date = $date->format('d-m-Y');
      // Ticket start time
      $time = new DateTime($ticket->start_time);
      $ticket->time = $time->format('H:i');
      // Ticket duration
      $difference = $time->diff(new DateTime($ticket->end_time));
      $minuts = (int)$difference->format('%I');
      if($minuts != 0)
      {
        $ticket->duration = $difference->format('%h:%I'). ' hours';
      } else {
        $ticket->duration = $difference->format('%h'). ' hours';
      }
      // Ticket price
      $ticket->price = number_format($ticket->price, 2, ',', ' ');
    }
    return $tickets;
  }
}
