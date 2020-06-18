<?php
class CheckoutModel{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    /**
     * Insert a new payment into the database
     *
     * @param $data array with user payment information
     * @return bool
     */
    public function addPayment(array $data){
        $this->db->query('INSERT INTO payment (price, type, status, invoice) VALUES(:price, :type, :status, :invoice)');

        $this->db->bind(':price', $data['price']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':status', ($data['status']));
        $this->db->bind(':invoice', ($data['invoice']));

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * Insert a new booking into the database
     *
     * @param $data array with the booking information
     * @param $paymentId int the last inserted payment id
     * @return bool
     */
    public function addBooking($data, $paymentId = 0){
        if($paymentId == 0){
            $paymentId = $this->db->lastInsertId();
        }

        $this->db->query('INSERT INTO booking (total_price, payment_id, user_id) VALUES(:totalPrice, :paymentId, :userId)');

        $this->db->bind(':totalPrice', $data['price']);
        $this->db->bind(':paymentId', $paymentId);
        $this->db->bind(':userId', $_SESSION['user_id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Insert a new booking event into the database
     *
     * @param $data array with the booking event information
     * @return bool
     */
    public function addBookingEvent($data){
        // Retrieve the last inserted id, (booking_id)
        try{
            $bookingId = $this->db->lastInsertId();
        } catch (\Throwable $th){
            return false;
        }

        foreach($data['tickets'] as $ticket){
            $this->db->query('INSERT INTO booking_has_event (booking_id, event_id, comment, quantity) VALUES(:bookingId, :eventId, :comment, :quantity)');

            $this->db->bind(':bookingId', $bookingId);
            $this->db->bind(':eventId', $ticket['id']);
            $this->db->bind(':comment', $ticket['comment']);
            $this->db->bind(':quantity', $ticket['quantity']);

            if (!$this->db->execute()){
                return false;
            }
        }
        return true;
    }

    /**
     * Insert a new invoice into the database
     *
     * @param $data array with user invoice information
     * @param $paymentId int payment id from order
     * @return bool
     */
    public function addInvoice($data, $paymentId){
        $this->db->query('INSERT INTO invoice (payment_id, first_name, last_name, address, phone_number) VALUES(:id, :firstName, :lastName, :address, :phoneNumber)');

        $this->db->bind(':id', $paymentId);
        $this->db->bind(':firstName', $data['fname']);
        $this->db->bind(':lastName', $data['lname']);
        $this->db->bind(':address', ($data['address'] . ',' . $data['postcode'] . ',' . $data['city']));
        $this->db->bind(':phoneNumber', $data['phonenumber']);

        if ($this->db->execute()) {
            return $paymentId;
        } else {
            return false;
        }
    }

    /**
     * Update stock value with new stock
     *
     * @param $data array with product data
     * @return bool
     */
    public function removeStock($data){
        foreach($data['tickets'] as $ticket){
            $this->db->query('UPDATE event SET availability = availability - :quantity WHERE id = :id');

            $this->db->bind(':id', $ticket['id']);
            $this->db->bind(':quantity', $ticket['quantity']);

            if (!$this->db->execute()){
                return false;
            }
        }
        return true;
    }

    /**
     * Update stock value with new stock
     *
     * @param $tickets
     * @return bool
     */
    public function addStock($tickets){
        foreach($tickets as $ticket){
            $this->db->query('UPDATE event SET availability = availability + :quantity WHERE id = :id');

            $this->db->bind(':id', $ticket->event_id);
            $this->db->bind(':quantity', $ticket->quantity);

            if (!$this->db->execute()){
                return false;
            }
        }
        return true;
    }

    /**
     * Update payment status
     *
     * @param $payment_id
     * @param $status
     * @return bool
     */
    public function updateStatus($payment_id, $status){
        $this->db->query('UPDATE payment SET status = :status WHERE id = :id');

        $this->db->bind(':id', $payment_id);
        $this->db->bind(':status', $status);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method to get the user connected to a payment
     *
     * @param $id int id of the payment
     * @return array|null
     */
    public function getUserByPaymentId($id){
        $this->db->query('SELECT u.id, u.email FROM booking AS b
                                JOIN user AS u ON u.id = b.user_id
                                WHERE b.payment_id = :id;');
        $this->db->bind(':id', $id);

        try {
            return $this->db->getSingle();
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Method to get the tickets connected to a payment
     *
     * @param $id int id of the payment
     * @return array|null
     */
    public function getTicketsByPaymentId($id){
        $this->db->query('SELECT event_id, quantity FROM booking_has_event JOIN booking ON booking.id = booking_id WHERE booking.payment_id = :id');
        $this->db->bind(':id', $id);

        try {
            return $this->db->getAll();
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Method to get the tickets by event id
     *
     * @param $id int id of the event
     * @return array|null
     */
    public function getTicketsByEventId($id){
        $this->db->query('SELECT availability FROM event WHERE id = :id');
        $this->db->bind(':id', $id);

        try {
            return $this->db->getSingle();
        } catch (\Throwable $th) {
            return null;
        }
    }

    // Get the status of the order payment
    public function getPaymentStatus($id){
        $this->db->query('SELECT status FROM payment WHERE id = :id');
        $this->db->bind(':id', $id);

        try {
            return $this->db->getSingle();
        } catch (\Throwable $th) {
            return null;
        }
    }

    // Add the QR code to the payment table
    public function addQRCode($paymentId, $qr){
        $this->db->query('UPDATE payment SET qrcode = :qrcode WHERE id = :id');

        $this->db->bind(':id', $paymentId);
        $this->db->bind(':qrcode', $qr);

        if ($this->db->execute()) {
            return $paymentId;
        } else {
            return false;
        }
    }

    // Fetch the order payment details by payment id
    public function getOrderPayment($paymentId){
        $this->db->query('SELECT status, invoice, qrcode, type FROM payment WHERE id = :id');
        $this->db->bind(':id', $paymentId);

        try {
            return $this->db->getSingle();
        } catch (\Throwable $th) {
            return null;
        }
    }
}