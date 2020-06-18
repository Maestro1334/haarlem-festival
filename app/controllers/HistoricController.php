<?php
class HistoricController extends Controller{
  private $historicModel;
  private $contentModel;
  private $eventModel;


  public function __construct(){
    // Ticket popup css and javascript
    $this->addCSS('cart-popup.css');
    $this->addJs('partial/cart-popup.js');

    // Load the models for the current page
    $this->historicModel = $this->model('HistoricModel', 'historic');
    $this->contentModel = $this->model('ContentModel');
    $this->eventModel = $this->model('EventModel');
  }


  public function index(){
    // Get the historic page data from the database and send them to the view page to be displayed
    $data = [
      'program' => $this->historicModel->getHistoricProgram(),
      'languages' => $this->historicModel->getAllTourLanguages(),
      'content' => $this->contentModel->getContentForCategory(ContentCategory::HISTORIC),
      'uniqueDays' => $this->eventModel->getUniqueDaysByCategory(ContentCategory::HISTORIC)
    ];

    // Loads historic CSS into the view
    $this->addCSS('historic/historic.css');

    // Loads historic JavaScript onto page
    $this->addJs('historic/historic.js');

    // Require 'historic index' view php file and give it the $data array with the historic contents
    $this->view('historic/index', $data);
  }
}