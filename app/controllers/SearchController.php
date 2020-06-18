<?php
class SearchController extends Controller {

    private $eventModel;

    public function index(){
        // Load CSS/JS
        $this->addCSS('search/style.css');
        $this->addJs('search/script.js');
        // Load EventModel
        $this->eventModel = $this->model('EventModel');


        //Check if the url contains the GET option 'query'
        if(!isset($_GET['query'])){
          redirect();
        }

        $data = [
            'results' => $this->eventModel->getFilteredEvents(urldecode($_GET['query']))
        ];

        // Load search/index view
        $this->view('search/index', $data);
    }
}