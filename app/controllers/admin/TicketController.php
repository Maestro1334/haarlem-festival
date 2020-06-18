<?php 
Class TicketController extends Controller {

    private $ticketModel;

    public function __construct() {
        $this->ticketModel = $this->model('TicketModel', 'admin');

        // custom js
        $this->addJs('admin/ticket.js');
    }

    public function index()
    {
        // initialize datatable
        $this->addJs('datatables/datatables.min.js');
        $this->addCSS('datatables/datatables.min.css');
        
        $data = [
            'categories' => array('Dance', 'Historic', 'Food', 'Jazz'),
            'Dance' => $this->ticketModel->getTickets('DANCE'),
            'Historic' => $this->ticketModel->getTickets('HISTORIC'),
            'Food' => $this->ticketModel->getTickets('FOOD'),
            'Jazz' => $this->ticketModel->getTickets('JAZZ')
        ];

        $this->view('admin/tickets', $data);
    }
    
    public function editTicket($id)
    {

        $ticket = $this->ticketModel->getTicketById($id);

        if (isPost()) {
            filterPost();

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'date' => trim($_POST['date']),
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time']),
                'price' => trim($_POST['price']),
                'availability' => trim($_POST['availability']),
                'price_err' => '',
                'availability_err' => '',
            ];

            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter a price';
            }

            if (empty($data['availability'])) {
                $data['availability_err'] = 'Please enter a avalability amount';
            }

            if (empty($data['avalability_err']) && empty($data['price_err'])) {
                if($this->ticketModel->updateTicket($data)){
                    flash('ticket_message', 'Ticket Updated');
                    $this->view('admin/ticket', $data);
                } else {
                    flash('ticket_message', 'Something Went Wrong', 'alert alert-danger');
                    $this->view('admin/ticket', $data);
                } 
            } else {
                $this->view('admin/edit_ticket', $data);
            }
        } else {
            

            $data = [
                'name' => $ticket->name,
                'date' => $ticket->date,
                'start_time' => $ticket->start_time,
                'end_time' => $ticket->end_time,
                'price' => $ticket->price,
                'availability' => $ticket->availability
            ];

            $this->view('admin/edit_ticket', $data);
        }
    }
}
?>