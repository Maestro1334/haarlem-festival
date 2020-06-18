<?php

class PageController extends Controller{

  private $contentModel;
  private $sponsorModel;
  private $eventModel;
  
  public function __construct(){
    $mapsKey = '';

    $this->contentModel = $this->model('ContentModel');
    $this->sponsorModel = $this->model('SponsorModel');
    $this->eventModel = $this->model('EventModel');

    $this->addCSS('page/style.css');
    $this->addJs('slick.js');
    $this->addJs('page/page.js');
    $this->addJs('https://maps.googleapis.com/maps/api/js?key='. $mapsKey .'&callback=initMap', false);
  }

  // Load Homepage
  public function index(){

    //Set Data
    $data = [
      'content' => $this->contentModel->getContentForCategory(ContentCategory::HOMEPAGE),
      'sponsors' => $this->sponsorModel->getSponsors(),
      'eventLocations' => json_encode($this->eventModel->getEventLocations())
    ];
    // Load homepage/index view
    $this->view('page/index', $data);
  }
}
