<?php
class PageController extends Controller
{

    private $pageModel;

    public function __construct()
    {
      $this->pageModel = $this->model('PageModel', 'admin');
      $this->contentModel = $this->model('ContentModel');

      // custom js
      $this->addJs('admin/page.js');

    }

  public function index()
  {
      
  }

  public function home()
  {
    $data = $this->getData('homepage');

    $this->view('admin/pages', $data);
  }

  public function historic()
  {
    $data = $this->getData('historic');

    $this->view('admin/pages', $data);
  }

  public function jazz()
  {
    $data = $this->getData('jazz');

    $this->view('admin/pages', $data);
  }

  public function dance()
  {
    $data = $this->getData('dance');

    $this->view('admin/pages', $data);
  }

  public function food()
  {
    $data = $this->getData('food');

    $this->view('admin/pages', $data);
  }

  public function getBanner($category)
  {
    $category = strtolower($category);
    $data = $this->getData($category);

    $data += [
      'banner_img' => '',
      'banner_title' => '',
      'view_banner' => true,
      'view_text' => false,
      'view_program' => false,
      'view_line-up' => false
    ];

    switch ($category) {
      case 'food':
        $banner_img = strtoupper($category . '_HEADER_IMAGE_1920');
        $data['banner_img'] = $this->contentModel->getContentByReference($banner_img);
        $banner_title = strtoupper($category . '_HEADER_TEXT');
        $data['banner_title'] = $this->contentModel->getContentByReference($banner_title);
        break;
      case 'jazz':
        $banner_img = strtoupper($category . '_HEADER_IMAGE');
        $data['banner_img'] = $this->contentModel->getContentByReference($banner_img);
        $banner_title = strtoupper($category . '_HEADER_TITLE');
        $data['banner_title'] = $this->contentModel->getContentByReference($banner_title);
        $banner_date = strtoupper($category . '_HEADER_DATE');
        $data += ['banner_date' => $this->contentModel->getContentByReference($banner_date)];
        $banner_ad = strtoupper($category . '_HEADER_AD');
        $data['banner_ad'] = $this->contentModel->getContentByReference($banner_ad);
        break;
      case 'historic':
        $banner_img = strtoupper($category . '_HEADER_IMAGE');
        $data['banner_img'] = $this->contentModel->getContentByReference($banner_img);
        $banner_title = strtoupper($category . '_HEADER_TEXT');
        $data['banner_title'] = $this->contentModel->getContentByReference($banner_title);
        break;
      default:
        $banner_img = strtoupper($category . '_HEADER_IMAGE');
        $data['banner_img'] = $this->contentModel->getContentByReference($banner_img);
        $banner_title = strtoupper($category . '_HEADER_TITLE');
        $data['banner_title'] = $this->contentModel->getContentByReference($banner_title);
        break;
    }

    $data['title_parts'] = explode('\n', $data['banner_title']);
    
    $this->view('admin/pages', $data);
  }

  public function updateBannerImage($category)
  {
    $category = strtolower($category);

    if (isPost()) {
      filterPost();

      if (empty(basename($_FILES["bannerImage"]['name']))) {
        flash('page_message', 'No Image Uploaded', 'alert alert-danger');
        redirect('admin/page/getBanner/' . $category);
      }

      $reference = strtoupper($category) . '_HEADER_IMAGE';
      if ($category == 'food') {
        $reference .= '_1920';
        $file_path = 'img/' . $category . '/header/' . $category . '_header.' . strtolower(pathinfo(basename($_FILES["bannerImage"]['name']), PATHINFO_EXTENSION));
      }
      $img_path = $this->contentModel->getContentByReference($reference);
      $img_path = substr($img_path, 1);

      $file_path = 'img/' . $category . '/' . $category . '_header.' . strtolower(pathinfo(basename($_FILES["bannerImage"]['name']), PATHINFO_EXTENSION));
      $new_img = saveImage($_FILES["bannerImage"], $img_upload_err);
        
      $data['img_path'] = '/' . $file_path;
      
      // overwrite existing image and deletes it from uploads
      if (copy(FILE_UPLOAD . $new_img, $file_path)) {
        if ($this->pageModel->updateContent($reference, $file_path)) {
          deleteImage($new_img);
          unlink($img_path);
          flash('page_message', 'Banner Image Updated');
          redirect('admin/page/getBanner/' . $category);
        } else {
          flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
          redirect('admin/page/getBanner/' . $category);
        }
      } else {
        flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
        redirect('admin/page/getBanner/' . $category);
      }
    }
  }

  public function updateBannerTitle($category)
  {
    $category = strtolower($category);

    if(isPost()){
      filterPost();

      $bannerTitle = implode( '\n', $_POST['bannerTitle']);

      if (empty($bannerTitle)) {
        flash('page_message', 'Please Enter A Title', 'alert alert-danger');
        redirect('admin/page/getBanner/' . $category);
      }

      $reference = strtoupper($category) . '_HEADER_TITLE';
      if ($category == 'food' || $category == 'historic') {
        $reference = strtoupper($category) . '_HEADER_TEXT';
      }

      if($this->pageModel->updateContent($reference, $bannerTitle)){
        flash('page_message', 'Banner Title Updated');
        redirect('admin/page/getBanner/' . $category);
      } else {
        flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
        redirect('admin/page/getBanner/' . $category);
      }
    }
  }

  public function updateBannerDate($category)
  {
    $category = strtolower($category);
    $reference = strtoupper($category) . '_HEADER_DATE';

    if(isPost()){
      filterPost();

      $bannerDate = trim($_POST['bannerDate']);

      if($this->pageModel->updateContent($reference, $bannerDate)){
        flash('page_message', 'Banner Date Updated');
        redirect('admin/page/getBanner/' . $category);
      } else {
        flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
        redirect('admin/page/getBanner/' . $category);
      }
    }
  }

  public function updateBannerAd($category)
  {
    $category = strtolower($category);

    if(isPost()){
      filterPost();

      $bannerAd = implode( ',', $_POST['bannerAd']);

      if (empty($bannerAd)) {
        flash('page_message', 'Please Enter An Ad Message', 'alert alert-danger');
        redirect('admin/page/getBanner/' . $category);
      }

      $reference = strtoupper($category) . '_HEADER_AD';

      if($this->pageModel->updateContent($reference, $bannerAd)){
        flash('page_message', 'Banner Ad Updated');
        redirect('admin/page/getBanner/' . $category);
      } else {
        flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
        redirect('admin/page/getBanner/' . $category);
      }
    }
  }

  public function getText($category)
  {
    $this->addCSS('summernote-bs4.css');
    $this->addJs('summernote-bs4.min.js');

    $category = strtolower($category);
    $data = $this->getData($category);

    $data += [
      'text' => [],
      'view_banner' => false,
      'view_text' => true,
      'view_program' => false,
      'view_line-up' => false,
      'text_cat' => [],
      'title_text' => ''
    ];

    switch($category) {
      case 'homepage':
        $reference = strtoupper($category);
        $data['text_cat'] = ['Dance', 'Historic', 'Food', 'Jazz'];
        $content = $this->contentModel->getContentForCategory($reference);
        $data['text'] = [
          'Dance' => $content['HOMEPAGE_CONTENT_DANCE_TEXT'],
          'Historic' => $content['HOMEPAGE_CONTENT_HISTORIC_TEXT'],
          'Food' => $content['HOMEPAGE_CONTENT_FOOD_TEXT'],
          'Jazz' => $content['HOMEPAGE_CONTENT_JAZZ_TEXT']
        ];
        break;
      case 'food':
        $reference = strtoupper($category) . '_CONTENT_TEXT';
        $data['text_cat'] = ['Content'];
        $data['text'] = ['Content' => $this->contentModel->getContentByReference($reference)];
        break;
      case 'jazz':
        $reference = strtoupper($category) . '_HEADER_TEXT';
        $data['text_cat'] = ['Header'];
        $data['text'] = ['Header' => $this->contentModel->getContentByReference($reference)];
        break;
      case 'historic':
        $reference = strtoupper($category);
        $content = $this->contentModel->getContentForCategory($reference);
        $data['text_cat'] = ['General', 'Information', 'Tickets', 'Unguided'];
        $data['text'] = [
          'General' => $content['HISTORIC_GENERAL_TEXT'],
          'Information' => $content['HISTORIC_MORE_INFORMATION'],
          'Tickets' => $content['HISTORIC_TICKETS_TEXT'],
          'Unguided' => $content['HISTORIC_UNGUIDED_TEXT']
        ];
        $data['title_text'] = [
          'General' => explode('<br>', $content['HISTORIC_GENERAL_TITLE']),
          'Information' => explode('<br>', $content['HISTORIC_INFORMATION_TITLE']),
          'Tickets' => explode('<br>', $content['HISTORIC_TICKETS_TITLE']),
          'Unguided' => explode('<br>', $content['HISTORIC_UNGUIDED_TITLE'])
        ];
        default:
        break;
    }

    $this->view('admin/pages', $data);
  }

  public function updateText($input)
  {
    list($category, $text_cat) = explode('-', $input);
    $category = strtoupper($category);
    $text_cat = strtoupper($text_cat);

    if(isPost()){
      filterPost();

      $text = $_POST['text'];
      $title_text = implode('<br>', $_POST['title_text']);

      switch($category) {
        case 'HOMEPAGE':
          $reference = $category . '_CONTENT_' . $text_cat . '_TEXT';
          break;
        case 'FOOD':
          $reference = $category . '_' . $text_cat . '_TEXT';
          break;
        case 'JAZZ':
          $reference = $category . '_' . $text_cat . '_TEXT';
          break;
        case 'HISTORIC':
          $reference = $category . '_' . $text_cat . '_TEXT';
          if ($text_cat == 'INFORMATION') {
            $reference = $category . '_MORE_' . $text_cat;
          }
          $reference2 = $category . '_' . $text_cat . '_TITLE';
          break;
      }

      if($this->pageModel->updateContent($reference, $text)){
        if (!empty($reference2)) {
          if($this->pageModel->updateContent($reference2, $title_text)){
            flash('page_message', 'Text Updated');
            redirect('admin/page/getText/' . $category);
          } else {
            flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
            redirect('admin/page/getText/' . $category);
          }
        }
        flash('page_message', 'Text Updated');
        redirect('admin/page/getText/' . $category);
      } else {
        flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
        redirect('admin/page/getText/' . $category);
      }
    }
  }

  public function cancelText($category)
  {
    redirect('admin/page/getText/' . $category);
  }

  public function getProgram($category)
  {
    // initialize datatable
    $this->addJs('datatables/datatables.min.js');
    $this->addCSS('datatables/datatables.min.css');

    $category = strtolower($category);
    $data = $this->getData($category);

    $program = $this->pageModel->getProgram($category);
    
    $data += [
      'program' => $program,
      'view_banner' => false,
      'view_text' => false,
      'view_program' => true,
      'view_line-up' => false
    ];

    $this->view('admin/pages', $data);
  }

  public function addEvent($category)
  {
    $category = strtolower($category);
    $data =$this->getData($category);

    if (isPost()) {
      
      filterPost();

      $data += [
        'name' => trim($_POST['name']),
        'date' => trim($_POST['date']),
        'start_time' => trim($_POST['start_time']),
        'end_time' => trim($_POST['end_time']),
        'location' => trim($_POST['location']),
        'name_err' => '',
        'date_err' => '',
        'start_time_err' => '',
        'end_time_err' => '',
        'location_err' => '',
        'edit' => false,
        'category' => $category
      ];

      if(empty($data['name'])){
        $data['name_err'] = 'Please enter a name';
      }
      if(empty($data['date'])){
        $data['date_err'] = 'Please enter a date';
      }
      if(empty($data['start_time'])){
        $data['start_time_err'] = 'Please enter a starting time';
      }
      if(empty($data['end_time'])){
        $data['end_time_err'] = 'Please enter a ending time';
      }
      if(empty($data['location'])){
        $data['location_err'] = 'Please enter a location';
      }
      if($data['start_time'] == $data['end_time']) {
        $data['start_time_err'] = 'Ending time is the';
        $data['end_time_err'] = 'same as the starting time';
      }

      if($category == 'historic') {
        $data['language'] = trim($_POST['language']);
      }

      if (empty($data['name_err']) && empty($data['date_err']) && empty($data['start_time_err']) && empty($data['end_time_err']) && empty($data['location_err'])) {
        
        if ($this->pageModel->addEvent($data)) {
          flash('page_message', 'Event Added');
          redirect('admin/page/getProgram/' . $data['title']);
        } else {
          flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
          redirect('admin/page/getProgram/' . $data['title']);
        }
      } else {
        $this->view('admin/add_event', $data);
      }
    } else {

      $data += [
        'name' => '',
        'date' => '',
        'start_time' => '',
        'end_time' => '',
        'location' => '',
        'edit' => false,
        'category' => $category
      ];

      $this->view('admin/add_event', $data);
    }
  }

  public function editEvent($input)
  {
    list($id, $category) = explode('-', $input);
    $category = strtolower($category);

    $data =$this->getData($category);

    if (isPost()) {
      
      filterPost();

      $data += [
        'id' => $id,
        'name' => trim($_POST['name']),
        'date' => trim($_POST['date']),
        'start_time' => trim($_POST['start_time']),
        'end_time' => trim($_POST['end_time']),
        'location' => trim($_POST['location']),
        'name_err' => '',
        'date_err' => '',
        'start_time_err' => '',
        'end_time_err' => '',
        'location_err' => '',
        'edit' => true,
        'category' => $category
      ];

      if(empty($data['name'])){
        $data['name_err'] = 'Please enter a name';
      }
      if(empty($data['date'])){
        $data['date_err'] = 'Please enter a date';
      }
      if(empty($data['start_time'])){
        $data['start_time_err'] = 'Please enter a starting time';
      }
      if(empty($data['end_time'])){
        $data['end_time_err'] = 'Please enter a ending time';
      }
      if(empty($data['location'])){
        $data['location_err'] = 'Please enter a location';
      }
      if($data['start_time'] == $data['end_time']) {
        $data['start_time_err'] = 'Ending time is the';
        $data['end_time_err'] = 'same as the starting time';
      }

      if($category == 'historic') {
        $data['language'] = trim($_POST['language']);
      }

      if (empty($data['name_err']) && empty($data['date_err']) && empty($data['start_time_err']) && empty($data['end_time_err']) && empty($data['location_err'])) {
        
        if ($this->pageModel->updateEvent($data)) {
          flash('page_message', 'Event Updated');
          redirect('admin/page/getProgram/' . $category);
        } else {
          flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
          redirect('admin/page/getProgram/' .  $category);
        }
      } else {
        $this->view('admin/add_event', $data);
      }
    } else {

      $program = $this->pageModel->getEventById($id);
      $data += [
        'id' => $id,
        'name' => $program->name,
        'date' => $program->date,
        'start_time' => $program->start_time,
        'end_time' => $program->end_time,
        'location' => $program->location,
        'edit' => true,
        'category' => $category
      ];

      $this->view('admin/add_event', $data);
    }
  }

  public function deleteEvent($input)
  {
    list($id, $category) = explode('-', $input);
    $category = strtolower($category);

    if ($this->pageModel->deleteEvent($id, $category)) {
      flash('page_message', 'Event deleted');
      redirect('admin/page/getProgram/'. $category);
    } else {
      flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
      redirect('admin/page/getProgram/' .  $category);
    }
  }

  public function getArtists($category)
  {
    // initialize datatable
    $this->addJs('datatables/datatables.min.js');
    $this->addCSS('datatables/datatables.min.css');
    $this->addJs('datatables/datatables.fixedHeader.min.js');
    $this->addCSS('datatables/fixedHeader.bootstrap.min.css');

    $category = strtolower($category);
    $data = $this->getData($category);

    $artists = $this->pageModel->getLineUp($category);
    
    $data += [
      'artists' => $artists,
      'view_banner' => false,
      'view_text' => false,
      'view_program' => false,
      'view_line-up' => true
    ];

    $this->view('admin/pages', $data);
  }

  public function addArtist($category)
  {
    $category = strtolower($category);
    $data = $this->getData($category);

    if(isPost()){
      
      filterPost();

      $data += [
        'name' => trim($_POST['name']),
        'img_path' => '',
        'name_err' => '',
        'img_path_err' => '',
        'edit' => false
      ];

      $lineup = 'lineup';
      
      // errorchecks
      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter a name';
      }

      if ($data['title'] == 'Historic') {
        $data['img_path'] = 0;
      }

      if (empty(basename($_FILES["artistImage"]['name']))) {
        $data['img_path_err'] = 'Please upload an image';
      }

      if ($category == 'food') {
        $lineup = 'restaurants';

        $data += [
          'short_des' => trim($_POST['short_des']),
          'long_des' => trim($_POST['long_des']),
          'res_type' => trim($_POST['res_type']),
          'short_des_err' => '',
          'long_des_err' => '',
          'res_type' => '',
          'allergen' => $_POST['allergen'],
          'gluten' => false,
          'crustaceans' => false,
          'egg' => false,
          'fish' => false,
          'peanut' => false,
          'soybeans' => false,
          'milk' => false,
          'nuts' => false,
          'celery' => false,
          'mustard' => false,
          'sesame' => false,
          'sulphur_dioxide' => false,
          'lupin' => false,
          'mollucs' => false,
        ];

        if (empty($data['short_des'])) {
          $data['short_des_err'] = 'Please enter an short description of the restaurant';
        }

        if (empty($data['long_des'])) {
          $data['long_des_err'] = 'Please enter an long description of the restaurant';
        }
      }

      // check if the errors are empty
      if (empty($data['name_err']) && empty($data['img_path']) && empty($data['short_des_err']) && empty($data['long_des_err'])) {

        $file_path = 'img/' . $category . '/' . $lineup . '/' . $data['name'] . '.' . strtolower(pathinfo(basename($_FILES["artistImage"]['name']), PATHINFO_EXTENSION));

        $new_img = saveImage($_FILES["artistImage"], $data['img_path_err']);
        $data['img_path'] = '/' . $file_path;

        // copy the image to the right directory and deletes it from uploads
        if (copy(FILE_UPLOAD . $new_img, $file_path)) {
          deleteImage($new_img);
        } else {
          flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
          redirect('admin/page/getArtists/' . $data['title']);
        }

        // add artist
        if($this->pageModel->addArtist($data)){
          flash('page_message', 'Artist Added');
          redirect('admin/page/getArtists/' . $data['title']);
        } else {
          flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
          redirect('admin/page/getArtists/' . $data['title']);
        }
      } else {
        // load view with errors
        $this->view('admin/add_artist', $data);
      }

    } else {

      $data += [
        'name' => '',
        'img_path' => '',
        'edit' => false
      ];

      if ($category == 'food') {

        $data += [
          'short_des' => '',
          'long_des' => '',
          'res_type' => '',
          'gluten' => false,
          'crustaceans' => false,
          'egg' => false,
          'fish' => false,
          'peanut' => false,
          'soybeans' => false,
          'milk' => false,
          'nuts' => false,
          'celery' => false,
          'mustard' => false,
          'sesame' => false,
          'sulphur_dioxide' => false,
          'lupin' => false,
          'mollucs' => false
        ];
      }

      $this->view('admin/add_artist', $data);
    }
  }

  public function editArtist($input)
  {

    list($id, $category) = explode('-', $input);
    $category = strtolower($category);

    $data = $this->getData($category);

    $artist = $this->pageModel->getArtistById($id, $category);

    if(ISPOST()){

      filterPost();

      $data += [
        'id' => $id,
        'name' => trim($_POST['name']),
        'img_path' => $artist->img_path,
        'name_err' => '',
        'img_path_err' => '',
        'edit' => true
      ];

      $lineup = 'lineup';

      // error checks
      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter a name';
      }

      if ($data['title'] == 'Historic') {
        $data['img_path'] = 0;
      }

      if (empty(basename($_FILES["artistImage"]['name']))) {
        $data['img_path_err'] = 'Please upload an image';
      }

      if ($category == 'food') {
        $lineup = 'restaurants';

        $data += [
          'short_des' => trim($_POST['short_des']),
          'long_des' => trim($_POST['long_des']),
          'res_type' => trim($_POST['res_type']),
          'short_des_err' => '',
          'long_des_err' => '',
          'allergen' => $_POST['allergen'],
          'gluten' => false,
          'crustaceans' => false,
          'egg' => false,
          'fish' => false,
          'peanut' => false,
          'soybeans' => false,
          'milk' => false,
          'nuts' => false,
          'celery' => false,
          'mustard' => false,
          'sesame' => false,
          'sulphur_dioxide' => false,
          'lupin' => false,
          'mollucs' => false,
        ];
        
        if (empty($data['short_des'])) {
          $data['short_des_err'] = 'Please enter an short description of the restaurant';
        }

        if (empty($data['long_des'])) {
          $data['long_des_err'] = 'Please enter an long description of the restaurant';
        }

        $allergies = $this->pageModel->getAllergenFromRestaurant($id);
        foreach ($allergies as $allergy) {
          switch ($allergy->name) {
            case 'Gluten':
              $data['gluten'] = true;
              break;
            case 'Crustaceans':
              $data['crustaceans'] = true;
              break;
            case 'Egg':
              $data['egg'] = true;
              break;
            case 'Fish':
              $data['fish'] = true;
              break;
            case 'Peanut':
              $data['peanut'] = true;
              break;
            case 'Soybeans':
              $data['soybeans'] = true;
              break;
            case 'Milk':
              $data['milk'] = true;
              break;
            case 'Nuts':
              $data['nuts'] = true;
              break;
            case 'Celery':
              $data['celery'] = true;
              break;
            case 'Mustard':
              $data['mustard'] = true;
              break;
            case 'Sesame':
              $data['sesame'] = true;
              break;
            case 'Sulphur dioxide':
              $data['sulphur_dioxide'] = true;
              break;
            case 'Lupin':
              $data['lupin'] = true;
              break;
            case 'Mollucs':
              $data['mollucs'] = true;
              break;
            default:
              break;
          }
        }
      }

      if (empty($data['name_err']) && empty($data['short_des_err']) && empty($data['lon_des_err'])) {
        
        if (!empty(basename($_FILES["artistImage"]['name']))) {
          $file_path = 'img/' . $category . '/' . $lineup . '/' . $data['name'] . '.' . strtolower(pathinfo(basename($_FILES["artistImage"]['name']), PATHINFO_EXTENSION));
          $new_img = saveImage($_FILES["artistImage"], $data['img_path_err']);
        }
        
        $data['img_path'] = '/' . $file_path;

        // overwrite existing image and deletes it from uploads
        if (copy(FILE_UPLOAD . $new_img, $file_path)) {
          deleteImage($new_img);
        } else {
          flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
          redirect('admin/page/getArtists/' . $category);
        }
        
        if ($this->pageModel->updateArtist($data)) {
          flash('page_message', 'Artist Updated');
          redirect('admin/page/getArtists/' . $category);
        } else {
          flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
          redirect('admin/page/getArtists/' . $category);
        }
      } else {
        $this->view('admin/add_artist', $data);
      }

    } else {

      
      $data += [
        'id' => $id,
        'name' => $artist->name,
        'img_path' => $artist->img_path,
        'edit' => true
      ];

      if ($category == 'food') {

        $data += [
          'short_des' => $artist->short_description,
          'long_des' => $artist->long_description,
          'res_type' => $artist->type,
          'gluten' => false,
          'crustaceans' => false,
          'egg' => false,
          'fish' => false,
          'peanut' => false,
          'soybeans' => false,
          'milk' => false,
          'nuts' => false,
          'celery' => false,
          'mustard' => false,
          'sesame' => false,
          'sulphur_dioxide' => false,
          'lupin' => false,
          'mollucs' => false,
        ];

        $allergies = $this->pageModel->getAllergenFromRestaurant($id);
          
        foreach ($allergies as $allergy) {
          switch ($allergy->name) {
            case 'Gluten':
              $data['gluten'] = true;
              break;
            case 'Crustaceans':
              $data['crustaceans'] = true;
              break;
            case 'Egg':
              $data['egg'] = true;
              break;
            case 'Fish':
              $data['fish'] = true;
              break;
            case 'Peanut':
              $data['peanut'] = true;
              break;
            case 'Soybeans':
              $data['soybeans'] = true;
              break;
            case 'Milk':
              $data['milk'] = true;
              break;
            case 'Nuts':
              $data['nuts'] = true;
              break;
            case 'Celery':
              $data['celery'] = true;
              break;
            case 'Mustard':
              $data['mustard'] = true;
              break;
            case 'Sesame':
              $data['sesame'] = true;
              break;
            case 'Sulphur dioxide':
              $data['sulphur_dioxide'] = true;
              break;
            case 'Lupin':
              $data['lupin'] = true;
              break;
            case 'Mollucs':
              $data['mollucs'] = true;
              break;
            default:
              break;
          }
        }
      }
      $this->view('admin/add_artist', $data);
    }
  }

  public function viewArtist($input)
  {
    list($id, $category) = explode('-', $input);
    $category = strtolower($category);

    $data = [
      'artist' => $this->pageModel->getArtistById($id, $category),
      'category' => $category,
      'gluten' => false,
      'crustaceans' => false,
      'egg' => false,
      'fish' => false,
      'peanut' => false,
      'soybeans' => false,
      'milk' => false,
      'nuts' => false,
      'celery' => false,
      'mustard' => false,
      'sesame' => false,
      'sulphur_dioxide' => false,
      'lupin' => false,
      'mollucs' => false,
      
    ];

    if ($category == 'food') {

      $allergies = $this->pageModel->getAllergenFromRestaurant($id);

      foreach ($allergies as $allergy) {
        switch ($allergy->name) {
          case 'Gluten':
            $data['gluten'] = true;
            break;
          case 'Crustaceans':
            $data['crustaceans'] = true;
            break;
          case 'Egg':
            $data['egg'] = true;
            break;
          case 'Fish':
            $data['fish'] = true;
            break;
          case 'Peanut':
            $data['peanut'] = true;
            break;
          case 'Soybeans':
            $data['soybeans'] = true;
            break;
          case 'Milk':
            $data['milk'] = true;
            break;
          case 'Nuts':
            $data['nuts'] = true;
            break;
          case 'Celery':
            $data['celery'] = true;
            break;
          case 'Mustard':
            $data['mustard'] = true;
            break;
          case 'Sesame':
            $data['sesame'] = true;
            break;
          case 'Sulphur dioxide':
            $data['sulphur_dioxide'] = true;
            break;
          case 'Lupin':
            $data['lupin'] = true;
            break;
          case 'Mollucs':
            $data['mollucs'] = true;
            break;
          
          default:
            # code...
            break;
        }
      }
    }

    $this->view('admin/view_artist', $data);
  }

  public function deleteArtist($input)
  {

    list($id, $category) = explode('-', $input);
    $category = strtolower($category);
    $res = $this->pageModel->getArtistById($id, $category);
    $img_path = substr($sponsor->img_path, 1);
    unlink($img_path);

    // delete artist
    if ($this->pageModel->deleteArtist($id)) {
      flash('page_message', 'Artist Removed');
      redirect('admin/page/getArtists/' . $category);
    } else {
      flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
      redirect('admin/page/getArtists/' . $category);
    }
  }

  public function getSponsors()
  {
    // initialize datatable
    $this->addJs('datatables/datatables.min.js');
    $this->addCSS('datatables/datatables.min.css');
    $this->addJs('datatables/datatables.fixedHeader.min.js');
    $this->addCSS('datatables/fixedHeader.bootstrap.min.css');

    $data = $this->getData('homepage');

    $sponsors = $this->pageModel->getSponsors();
    
    $data += [
      'sponsors' => $sponsors,
      'view_banner' => false,
      'view_text' => false,
      'view_program' => false,
      'view_line-up' => false,
      'view_sponsor' => true
    ];

    $this->view('admin/pages', $data);
  }

  public function addSponsor()
  {
    $data = $this->getData('homepage');

    if (ISPOST()) {
      filterPost();

      $data += [
        'name' => trim($_POST['name']),
        'img_path' => '',
        'priority' => trim($_POST['priority']),
        'name_err' => '',
        'img_path_err' => '',
        'priority_err' => '',
        'edit' => false,
        'view' => false
      ];

      

      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter a name';
      }

      if (empty($data['priority'])) {
        $data['priority_err'] = 'Please enter a priority';
      }

      if (empty(basename($_FILES["sponsorImage"]['name']))) {
        $data['img_path_err'] = 'Please upload an image';
      }

      if (empty($data['name_err']) && empty($data['img_path_err']) && empty($data['priority_err'])) {

        $file_path = 'img/sponsors/' . $data['name'] . '_logo.' . strtolower(pathinfo(basename($_FILES["sponsorImage"]['name']), PATHINFO_EXTENSION));
        $new_img = saveImage($_FILES["sponsorImage"], $data['img_path_err']);
        
        $data['img_path'] = '/' . $file_path;
      
        // overwrite existing image and deletes it from uploads
        if (copy(FILE_UPLOAD . $new_img, $file_path)) {
          deleteImage($new_img);
        } else {
          die('something went wrong');
        }
      
        if ($this->pageModel->addSponsor($data)) {
          flash('page_message', 'Sponsor Aded');
          redirect('admin/page/getSponsors');
        } else {
          flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
          redirect('admin/page/getSponsors');
        }
      } else {
        $this->view('admin/add_sponsor', $data);
      }

    } else {
      $data += [
        'name' => '',
        'img_path' => '',
        'priority' => '',
        'edit' => false,
        'view' => false
      ];

      $this->view('admin/add_sponsor', $data);
    }
  }

  public function editSponsor($id)
  {

    $sponsor = $this->pageModel->getSponsorById($id);
    $data = $this->getData('homepage');

    if (ISPOST()) {
      filterPost();

      $data += [
        'id' => $id,
        'name' => trim($_POST['name']),
        'img_path' => $sponsor->img_path,
        'priority' => trim($_POST['priority']),
        'name_err' => '',
        'img_path_err' => '',
        'priority_err' => '',
        'edit' => true,
        'view' => false
      ];

      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter a name';
      }

      if (empty($data['priority'])) {
        $data['priority_err'] = 'Please enter a priority';
      }

      
      $data['img_path'] = substr($data['img_path'], 1);
      if(!empty(basename($_FILES["sponsorImage"]['name']))){
        $file_path = 'img/sponsors/' . $data['name'] . '_logo.' . strtolower(pathinfo(basename($_FILES["sponsorImage"]['name']), PATHINFO_EXTENSION));
        $new_img = saveImage($_FILES["sponsorImage"], $data['img_path_err']);
        // copy the image to the right directory and deletes it from uploads
        if (copy(FILE_UPLOAD . $new_img, $file_path)) {
          deleteImage($new_img);
          unlink($data['img_path']);
        } else {
          flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
          redirect('admin/page/getSponsors');
        }
      }
      
      if($data['name'] != $sponsor->name){
        $file_path = 'img/sponsors/' . $data['name'] . '_logo.' . strtolower(pathinfo(basename($data["img_path"]['name']), PATHINFO_EXTENSION));
        // overwrite existing image and deletes it from uploads
        if (copy($data['img_path'], $file_path)) {
          unlink($data['img_path']);
        } else {
          flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
          redirect('admin/page/getSponsors');
        }
      }
      
      if (!empty($file_path)) {
        $data['img_path'] = '/' . $file_path;
      } else {
        $data['img_path'] = '/' . $data['img_path'];
      }

      if (empty($data['name_err']) && empty($data['priority_err'])) {
        if ($this->pageModel->updateSponsor($data)) {
          flash('page_message', 'Sponsor Updated');
          redirect('admin/page/getSponsors');
        } else {
          flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
          redirect('admin/page/getSponsors');
        }
      } else {
        $this->view('admin/add_sponsor', $data);
      }

    } else {
      $sponsor = $this->pageModel->getSponsorById($id);

      $data += [
        'id' => $id,
        'name' => $sponsor->name,
        'img_path' => $sponsor->img_path,
        'priority' => $sponsor->priority,
        'edit' => true,
        'view' => false
      ];

      $this->view('admin/add_sponsor', $data);
    }
  }

  public function viewSponsor($id)
  {
    $data = $this->getData('homepage');

    $sponsor = $this->pageModel->getSponsorById($id);

    $data += [
      'name' => $sponsor->name,
      'img_path' => $sponsor->img_path,
      'priority' => $sponsor->priority,
      'edit' => false,
      'view' => true
    ];

    $this->view('admin/add_sponsor', $data);
  }

  public function deleteSponsor($id)
  {
    $sponsor = $this->pageModel->getSponsorById($id);
    $img_path = substr($sponsor->img_path, 1);
    unlink($img_path);
    if ($this->pageModel->deleteSponsor($id)) {
      flash('page_message', 'Sponsor Deleted');
      redirect('admin/page/getSponsors');
    } else {
      flash('page_message', 'Something Went Wrong Please Try Again', 'alert alert-danger');
      redirect('admin/page/getSponsors');
    }
    
  }

  // Gets the standard data for the category (ex buttons and title)
  public function getData($category)
  {
    $category = strtolower($category);

    switch ($category) {
      case 'homepage':
        $data = [
          'title' => 'Homepage',
          'btn_banner' => true,
          'btn_text' => true,
          'btn_program' => false,
          'btn_line-up' => false,
          'btn_sponsor' => true,
          'name_line-up' => ''
        ];
        break;
      case 'historic':
        $data = [
          'title' => 'Historic',
          'btn_banner' => true,
          'btn_text' => true,
          'btn_program' => true,
          'btn_line-up' => false,
          'btn_sponsor' => false,
          'name_line-up' => '',
          'view_sponsor' => false
        ];
        break;
      case 'jazz':
        $data = [
          'title' => 'Jazz',
          'btn_banner' => true,
          'btn_text' => true,
          'btn_program' => true,
          'btn_line-up' => true,
          'btn_sponsor' => false,
          'name_line-up' => 'Line-up',
          'view_sponsor' => false
        ];
        break;
      case 'dance':
        $data = [
          'title' => 'Dance',
          'btn_banner' => true,
          'btn_text' => false,
          'btn_program' => true,
          'btn_line-up' => true,
          'btn_sponsor' => false,
          'name_line-up' => 'Line-up',
          'view_sponsor' => false
        ];
        break;
      case 'food':
        $data = [
          'title' => 'Food',
          'btn_banner' => true,
          'btn_text' => true,
          'btn_program' => true,
          'btn_line-up' => true,
          'btn_sponsor' => false,
          'name_line-up' => 'Restaurants',
          'view_sponsor' => false
        ];
        break;
      default:
        break;
    }
    return $data;
  }
}

?>