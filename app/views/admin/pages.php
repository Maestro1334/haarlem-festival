<?php require APPROOT . '/views/admin/partial/header.php'; ?>
<?php require APPROOT . '/views/admin/partial/navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <?php require APPROOT . '/views/admin/partial/sidebar.php'; ?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 main">
    <?php flash('page_message'); ?>
      <h1><?php echo $data['title']?></h1>
      <div class="d-flex justify-content-around">
        <a href="<?php echo URLROOT . '/admin/page/getBanner/' . $data['title'];?>" id="btn_banner" class="flex-fill mr-4 btn btn-dark btn-lg <?php echo (!$data['btn_banner']) ? 'd-none' : '' ?>">Banner</a>
        <a href="<?php echo URLROOT . '/admin/page/getText/' . $data['title'];?>" id="btn_text" class="flex-fill mr-4 btn btn-dark btn-lg <?php echo (!$data['btn_text']) ? 'd-none' : '' ?>">Text</a>
        <a href="<?php echo URLROOT . '/admin/page/getProgram/' . $data['title'];?>" id="btn_program" class="flex-fill mr-4 btn btn-dark btn-lg <?php echo (!$data['btn_program']) ? 'd-none' : '' ?>">Program</a>
        <a href="<?php echo URLROOT . '/admin/page/getArtists/' . $data['title'];?>" id="btn_line-up" class="flex-fill mr-4 btn btn-dark btn-lg btn-line-up <?php echo (!$data['btn_line-up']) ? 'd-none' : '' ?>"><?php echo $data['name_line-up'] ?></a>
        <a href="<?php echo URLROOT . '/admin/page/getSponsors'?>" id="btn_sponsor" class="flex-fill mr-4 btn btn-dark btn-lg <?php echo (!$data['btn_sponsor']) ? 'd-none' : '' ?>">Sponsors</a>
      </div>

      <!-- Banner view -->
      <div class="mt-5  <?php echo (!$data['view_banner']) ? 'd-none' : '' ?>">
        <h2> Banner: </h2>
        <div class="header-container">
          <img src="<?php echo URLROOT . $data['banner_img'] ;?>" alt="<?php echo $data['title']?>_BANNER" class="banner">
          <div class="header-title">
            <?php foreach ($data['title_parts'] as $part) : ?>
              <h3><?php echo $part; ?></h3>
            <?php endforeach; ?>
            <h3>
              <?php echo (isset($data['banner_date'])) ? $data['banner_date'] : ''?>
            </h3>
            <div>
              <p class="text-right">
              <?php if(isset($data['banner_ad'])) {
                $adPieces = explode(",", $data['banner_ad']);
                $floatDirection = "float-left";
                $i = 0;
                 foreach ($adPieces as $piece) {
                  if ($i == 0) {
                    echo $piece;
                    $i = 1;
                  } else {
                    echo $piece . '<br>';
                    $i = 0;
                  }
                  
                }
              }?>
              </p>
           </div>
          </div>
        </div>
        <div class="d-flex mt-3">
          <button data-toggle="modal" data-target="#imageModal" class="btn btn-primary mr-4">Change photo</button>
          <button data-toggle="modal" data-target="#titleModal" class="btn btn-primary mr-4">Change title</button>
          <?php  if(isset($data['banner_date'])) : ?><button data-toggle="modal" data-target="#dateModal" class="btn btn-primary mr-4">Change date</button> <?php endif; ?>
          <?php  if(isset($data['banner_ad'])) : ?><button data-toggle="modal" data-target="#adModal" class="btn btn-primary">Change ad</button> <?php endif; ?>
        </div>

        <!-- modals for the banner changes -->
        <!-- Change Image Modal -->
        <div id="imageModal" class="modal" role="dialog" tabindex="-1" aria-labbeledby="changeImage" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="changeTitle">Change the image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="<?php echo URLROOT . '/admin/page/updateBannerImage/' . $data['title'] ?>" method="post" enctype="multipart/form-data">
                  <div class="form-group artist-img">
                    <label>Image:</label>
                    <span class="img-div">
                      <img src="<?php echo URLROOT . $data['banner_img'] ;?>" id="imageDisplay">
                    </span>
                    <input type="file" name="bannerImage" onChange="displayImage(this)" id="bannerImage" class="form-control-file mb-3">
                  </div>
                  <input type="submit" class="btn btn-success" value="Upload">
                  <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Change Title Modal -->
        <div id="titleModal" class="modal" role="dialog" tabindex="-1" aria-labbeledby="changeTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="changeTitle">Change the title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="<?php echo URLROOT . '/admin/page/updateBannerTitle/' . $data['title'] ?>" method="post">
                  <div class="form-group">
                    <label for="bannerTitle">Title: </label>
                    <?php foreach ($data['title_parts'] as $part) : ?>
                    <input type="text" class="form-control" name="bannerTitle[]" value="<?php echo $part?>">
                    <?php endforeach; ?>
                  </div>
                  <input type="submit" class="btn btn-success" value="Change">
                  <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Change Date Modal -->
        <div id="dateModal" class="modal" role="dialog" tabindex="-1" aria-labbeledby="changeDate" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="changeDate">Change the date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="<?php echo URLROOT . '/admin/page/updateBannerDate/' . $data['title'] ?>" method="post">
                  <div class="form-group">
                    <label for="bannerDate">Date: </label>
                    <input type="text" class="form-control" name="bannerDate" value="<?php echo $data['banner_date']?>">
                  </div>
                  <input type="submit" class="btn btn-success" value="Change">
                  <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Change Ad Modal -->
      <div id="adModal" class="modal" role="dialog" tabindex="-1" aria-labbeledby="changeAd" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="changeTitle">Change the title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="<?php echo URLROOT . '/admin/page/updateBannerAd/' . $data['title'] ?>" method="post">
                  <div class="form-group">
                    <label for="bannerAd">Ad: </label>
                    <?php $adParts = explode(',', $data['banner_ad']);foreach ($adParts as $part) : ?>
                    <input type="text" class="form-control" name="bannerAd[]" value="<?php echo $part?>">
                    <?php endforeach; ?>
                  </div>
                  <input type="submit" class="btn btn-success" value="Change">
                  <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>
                </form>
              </div>
            </div>
          </div>
        </div>

      <!-- Text view -->
      <div class="mt-5 <?php echo (!$data['view_text']) ? 'd-none' : '' ?>">
        <?php $i = 1; foreach ($data['text_cat'] as $cat) : ?>
          <button id="<?php echo $i . "_btn"?>" class="btn <?php echo($i == 1) ? '' : 'btn-dark' ?> btn-info"><?php echo $cat?></button>
        <?php $i++; endforeach;?>
        <?php $j = 1; foreach ($data['text_cat'] as $cat) :?>
          <div id="<?php echo $j . "_div"?>" class="<?php echo($j == 1) ? '' : 'd-none' ?>">
            <form action="<?php echo URLROOT;?> /admin/page/updateText/ <?php echo $data['title'] . '-' . $cat; ?>" method="post" enctype="multipart/form-data" onsubmit="return postSumNote_1()">
              <?php if (!empty($data['title_text'][$cat])) : ?>
                <label for="title_text">Title: </label>
                <?php foreach($data['title_text'][$cat] as $part) : ?>
                <input class="form-control" type="text" name="title_text[]" value="<?php echo $part?>">
                <?php endforeach; ?>
              <?php endif;?>
              <label for="text">Text: </label>
              <textarea id="summernote_<?php echo $j?>" name="text"><?php echo $data['text'][$cat]; ?></textarea>
              <input type="submit" class="btn btn-success mt-2 save-text" value="Save">
              <a href="<?php echo URLROOT . '/admin/page/cancelText/' . $data['title']; ?>" class="btn btn-danger float-right mt-2 cancel-text">Cancel</a>
            </form>
          </div>
        <?php $j++; endforeach;?>
      </div>

      <!-- Program view -->
      <div class="mt-5 <?php echo (!$data['view_program']) ? 'd-none' : '' ?>">
        <div class="d-flex justify-content-between">
          <h2> Program: </h2>
          <?php if($_SESSION['user_type'] == UserType::SUPERADMIN)  :?><a href="<?php echo URLROOT . '/admin/page/addEvent/' . $data['title']?>" class="btn btn-primary btn-md float-right users">Add</a> <?php endif; ?>
        </div>
        <table id="table_program" class="table users">
          <thead class="thead-dark">
            <tr>
              <th class="col-1">Time</th>
              <th>Name</th>
              <th><?php echo ($data['title'] == 'Historic') ? 'Languages' : 'Venue' ?></th>
              <th class="col-1">Actions</th>
              <th class = "d-none">Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data['program'] as $program) : $language = ltrim($program->name, 'Historic Tour Single ')?>
            <tr>
              <td class="users"><?php echo date("h:i", strtotime($program->start_time)) . ' - ' . date("h:i", strtotime($program->end_time)); ?></td>
              <td class="users"><?php echo ($program->category == 'HISTORIC') ? rtrim($program->name, 'Single ' . $language) : $program->name; ?></td>
              <td class="users"><?php echo ($program->category == 'HISTORIC') ? $language : $program->location; ?></td>
              <td class="d-flex justify-content-around users-action pl-4"> 
              <a href="<?php echo URLROOT . '/admin/page/editEvent/' . $program->id . '-' . $program->category?>" class="flex-fill"><i class='fa fa-edit'></i></a>
              <a href="<?php echo URLROOT . '/admin/page/deleteEvent/' . $program->id . '-' . $program->category?>" class="flex-fill"><i class='fa fa-trash-alt trash'></i></a>
              </td>
              <td class="d-none"><?php echo $program->date ?>
            </tr>
            <?php endforeach ; ?>
          </tbody>
        </table>
      </div>

      <!-- Line-up view -->
      <div id="view_line-up" class="mt-5 <?php echo (!$data['view_line-up']) ? 'd-none' : '' ?>">
        <div class="d-flex justify-content-between">
          <h2> <?php echo $data['name_line-up'] ?>: </h2>
          <?php if($_SESSION['user_type'] == UserType::SUPERADMIN)  :?><a href="<?php echo URLROOT . '/admin/page/addArtist/' . $data['title']?>" class="btn btn-primary btn-md float-right users">Add</a> <?php endif; ?>
        </div>
        <table id="table_line-up" class="table users">
          <thead class="thead-dark">
            <tr>
              <th>Name</th>
              <?php echo($data['title'] == 'Historic') ? '<th>Language</th>' : '' ?>
              <th class="col-1">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data['artists'] as $artist) : ?>
            <tr>
              <td class="users"><?php echo $artist->name ?></td>
              <?php if($data['title'] == 'Historic')  :?>
                <td class="users"><?php echo $artist->h.language; ?></td>
              <?php endif; ?>
              <td class="d-flex justify-content-around users-action pl-4"> 
                <a href="<?php echo URLROOT . '/admin/page/editArtist/' . $artist->id . '-' . $data['title'];?>" class="flex-fill"><i class='fa fa-edit'></i></a>
                <a href="<?php echo URLROOT . '/admin/page/deleteArtist/' . $artist->id .  '-' . $data['title'];?>" class="flex-fill"><i class='fa fa-trash-alt trash'></i></a>
                <a href="<?php echo URLROOT . '/admin/page/viewArtist/' . $artist->id . '-' . $data['title'];?>" class="flex-fill"><i class='fa fa-eye'></i></a>
              </td>
            </tr>
            <?php endforeach ; ?>
          </tbody>
        </table>
      </div>

      <!-- Sponsor view -->
      <div class="mt-5 <?php echo (!$data['view_sponsor']) ? 'd-none' : '' ?>">
        <div class="d-flex justify-content-between">
          <h2> Sponsors: </h2>
          <?php if($_SESSION['user_type'] == UserType::SUPERADMIN)  :?><a href="<?php echo URLROOT . '/admin/page/addSponsor'?>" class="btn btn-primary btn-md float-right users">Add</a> <?php endif; ?>
        </div>
        <table id="table_sponsors" class="table users">
          <thead class="thead-dark">
            <tr>
              <th>Name</th>
              <th class="col-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data['sponsors'] as $sponsor) : ?>
            <tr>
              <td class="users"><?php echo $sponsor->name ?></td>
              <td class="d-flex justify-content-around users-action pl-4">
                <a href="<?php echo URLROOT . '/admin/page/editSponsor/' . $sponsor->id;?>" class="flex-fill"><i class='fa fa-edit'></i></a>
                <a href="<?php echo URLROOT . '/admin/page/deleteSponsor/' . $sponsor->id;?>" class="flex-fill"><i class='fa fa-trash-alt trash'></i></a>
                <a href="<?php echo URLROOT . '/admin/page/viewSponsor/' . $sponsor->id;?>" class="flex-fill"><i class='fa fa-eye'></i></a>
              </td>
            </tr>
            <?php endforeach ; ?>
          </tbody>
        </table>
      </div>

    </main>
  </div>
</div>



<?php require APPROOT . '/views/admin/partial/footer.php'; ?>
