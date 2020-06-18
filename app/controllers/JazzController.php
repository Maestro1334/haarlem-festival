<?php
class JazzController extends Controller{
    private $jazzModel;
    private $contentModel;
    private $eventModel;

    public function __construct(){
        // Custom css and javascript for the payment method page
        $this->addCSS('jazz/jazz.css');
        $this->addJs('jazz/jazz.js');

        // ticket popup css and javascript
        $this->addCSS('cart-popup.css');
        $this->addJs('partial/cart-popup.js');

        // Load the models for the current page
        $this->jazzModel = $this->model('JazzModel', 'jazz');
        $this->contentModel = $this->model('ContentModel');
        $this->eventModel = $this->model('EventModel');
    }

    // Load jazz Page
    public function index(){
        // Get the unique dates for the Jazz events
        $uniqueDays = $this->eventModel->getUniqueDaysByCategory(ContentCategory::JAZZ);

        // Get the jazz page data from the database and send them to the view page to be displayed
        $data = [
            'uniqueLocations' => $this->jazzModel->getUniqueLocations(),
            'program' => $this->jazzModel->getAllProgramData($uniqueDays),
            'tickets' => $this->jazzModel->getAllTicketData($uniqueDays),
            'lineup' => $this->jazzModel->getLineUp(),
            'content' => $this->contentModel->getContentForCategory(ContentCategory::JAZZ)
        ];

        if(isset($_GET['query'])){
            $data['query'] = urldecode($_GET['query']);
        }

        // Require 'jazz index' view php file and give it the $data array with the jazz contents
        $this->view('jazz/index', $data);
    }
}
