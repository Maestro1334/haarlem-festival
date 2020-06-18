<?php
class DanceController extends Controller{

    private $contentModel;
    private $eventModel;
    private $programModel;
    private $ticketModel;
    private $lineupModel;

    public function __construct()
    {
        // Load page related CSS/JS
        $this->addCSS('dance/style.css');
        $this->addJs('dance/dance.js');
        $this->addCSS('cart-popup.css');
        $this->addJs('partial/cart-popup.js');
        // Load the models for the current page
        $this->contentModel = $this->model('ContentModel');
        $this->eventModel = $this->model('EventModel');
        $this->programModel = $this->model('ProgramModel', 'dance');
        $this->ticketModel = $this->model('TicketModel', 'dance');
        $this->lineupModel = $this->model('LineupModel', 'dance');
    }

    // Load Dance Page
    public function index(){
        // Get the unique dates for the dance events
        $uniqueDays = $this->eventModel->getUniqueDaysByCategory(ContentCategory::DANCE);

        // Load the friday program items and send them to the view
        $data = [
            'content' => $this->contentModel->getContentForCategory(ContentCategory::DANCE),
            'uniqueDays' => $uniqueDays,
            'program' => $this->programModel->getFullProgram($uniqueDays),
            'tickets' => $this->ticketModel->getAllTickets($uniqueDays),
            'ticketDayNotes' => $this->ticketModel->getDaySpecificNotes(),
            'lineup' => $this->lineupModel->getLineUp()
        ];

        if(isset($_GET['query'])){
            $data['query'] = urldecode($_GET['query']);
        }

        // Load homepage/index view
        $this->view('dance/index', $data);
    }
}