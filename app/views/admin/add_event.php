<?php require APPROOT . '/views/admin/partial/header.php'; ?>
<?php require APPROOT . '/views/admin/partial/navbar.php'; ?>

    <div class="card card-body bg-light mt-5 col-md-6 container">
        <h2><?php echo ($data['edit']) ? 'Edit' : 'Add' ?> event </h2>
        <form action="<?php echo URLROOT . '/admin/page/' ?><?php echo ($data['edit']) ? 'editEvent/' . $data['id'] . '-' : 'addEvent/' ?><?php echo $data['title']; ?>" method="post">
            <div class="form-group">
                <label for="name">Name: <sup>*</sup></label>
                <input type="text" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : '';?>" name="name" value="<?php echo ($data['category'] == 'historic') ? 'Historic Tour ' : $data['name']; ?>">
                <span class="invalid-feedback"><?php echo $data['name_err']; ?></span> 
            </div>
            <div class="d-flex">
                <div class="form-group mr-3">
                    <label for="date">Date: <sup>*</sup></label>
                    <input type="date" class="form-control form-control-lg <?php echo (!empty($data['date_err'])) ? 'is-invalid' : '';?>" name="date" value="<?php echo $data['date'] ?>" min="<?php echo date("Y-m-d")?>">
                    <span class="invalid-feedback"><?php echo $data['date_err']; ?></span> 
                </div>
                <div class="form-group mr-3">
                    <label for="start_time">Starting Time: <sup>*</sup></label>
                    <input type="time" class="form-control form-control-lg <?php echo (!empty($data['start_time_err'])) ? 'is-invalid' : '';?>" name="start_time" value="<?php echo date("h:i", strtotime($data['start_time'])) ?>">
                    <span class="invalid-feedback"><?php echo $data['start_time_err']; ?></span> 
                </div>
                <div class="form-group">
                    <label for="end_time">Ending Time: <sup>*</sup></label>
                    <input type="time" class="form-control form-control-lg <?php echo (!empty($data['end_time_err'])) ? 'is-invalid' : '';?>" name="end_time" value="<?php echo date("h:i", strtotime($data['end_time'])); ?>">
                    <span class="invalid-feedback"><?php echo $data['end_time_err']; ?></span> 
                </div>
            </div>
            <?php if($data['category'] == 'historic') :?>
                <div class="form-group">
                    <label for="language">Language: </label>
                    <select class="form-control form-control-lg" name="language">
                        <option value="Dutch">Dutch</option>
                        <option value="English">English</option>
                        <option value="Chinese">Chinese</option>
                    </select>
                </div>
            <?php endif;?>
            <div class="form-group">
                <label for="location">Location: <sup>*</sup></label>
                <input type="text" class="form-control form-control-lg <?php echo (!empty($data['location_err'])) ? 'is-invalid' : '';?>" name="location" value="<?php echo ($data['category'] == 'historic') ? 'Church of St. Bavo' : $data['location']; ?>">
                <span class="invalid-feedback"><?php echo $data['location_err']; ?></span> 
            </div>
            
            <input type="submit" class="btn btn-success" value="<?php echo ($data['edit']) ? 'Edit' : 'Add' ?>">
            <a href="<?php echo URLROOT . '/admin/page/getProgram/' . $data['title'] ?>" class="btn btn-dark float-right"><i class="fa fa-backward"></i> Back</a>  
        </form>
    </div>

<?php require APPROOT . '/views/admin/partial/footer.php'; ?>