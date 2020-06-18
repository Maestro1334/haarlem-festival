<?php require APPROOT . '/views/admin/partial/header.php'; ?>
<?php require APPROOT . '/views/admin/partial/navbar.php'; ?>

    <div class="card card-body bg-light mt-5 col-md-6 container">
        <h2>View Artist</h2>
        <form action="<?php echo URLROOT . '/admin/page/editArtist/' . $data['artist']->category ?>" method="post">
            <div class="form-group artist-img <?php echo ($data['artist']->category == 'HISTORIC') ? 'd-none' : '' ?>" > 
                <label>Image:</label>
                <span class="img-div">
                    <img src="<?php echo URLROOT . $data['artist']->img_path ;?>" id="imageDisplay">
                </span>
            </div>
            <div class="form-group">
                <label for="name">Name: <sup>*</sup></label>
                <input type="text" class="form-control form-control-lg" name="name" value="<?php echo $data['artist']->name; ?>" readonly>
            </div>
            <?php if($data['artist']->category == 'FOOD') :?>
                <div class="form-group">
                    <label for="short_des">Short description: <sup>*</sup></label>
                    <textarea type="text" class="form-control form-control-lg" name="short_des" readonly><?php echo $data['artist']->short_description; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="long_des">Long description: <sup>*</sup></label>
                    <textarea type="text" class="form-control form-control-lg long_des" name="long_des" readonly><?php echo $data['artist']->long_description; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="res_type">Type:</label>
                    <input type="text" class="form-control form-control-lg" name="long_des" value="<?php echo $data['artist']->type; ?>" readonly >
                </div>
                <p>Allergies:</p>
                <div class="d-flex flex-wrap">
                    <div class="custom-control custom-checkbox col-3">
                        <input id="gluten" class="custom-control-input" type="checkbox" name="gluten" value="Gluten" disabled <?php echo ($data['gluten']) ? 'checked' : '' ?>>
                        <label for="gluten" class="custom-control-label">Gluten</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="crustaceans" class="custom-control-input" type="checkbox" name="crustaceans" value="Crustaceans" disabled <?php echo ($data['crustaceans']) ? 'checked' : '' ?>>
                        <label for="crustaceans" class="custom-control-label">Crustaceans</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="egg" class="custom-control-input" type="checkbox" name="egg" value="Egg" disabled <?php echo ($data['egg']) ? 'checked' : '' ?>>
                        <label for="egg" class="custom-control-label">Egg</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="fish" class="custom-control-input" type="checkbox" name="fish" value="Fish" disabled <?php echo ($data['fish']) ? 'checked' : '' ?>>
                        <label for="fish" class="custom-control-label">Fish</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="peanut" class="custom-control-input" type="checkbox" name="peanut" value="Peanut" disabled <?php echo ($data['peanut']) ? 'checked' : '' ?>>
                        <label for="peanut" class="custom-control-label">Peanut</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="soybeans" class="custom-control-input" type="checkbox" name="soybeans" value="Soybeans" disabled <?php echo ($data['soybeans']) ? 'checked' : '' ?>>
                        <label for="soybeans" class="custom-control-label">Soybeans</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="milk" class="custom-control-input" type="checkbox" name="milk" value="Milk" disabled <?php echo ($data['milk']) ? 'checked' : '' ?>>
                        <label for="milk" class="custom-control-label">Milk</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="nuts" class="custom-control-input" type="checkbox" name="nuts" value="Nuts" disabled <?php echo ($data['nuts']) ? 'checked' : '' ?>>
                        <label for="nuts" class="custom-control-label">Nuts</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="celery" class="custom-control-input" type="checkbox" name="celery" value="Celery" disabled <?php echo ($data['celery']) ? 'checked' : '' ?>>
                        <label for="celery" class="custom-control-label">Celery</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="mustard" class="custom-control-input" type="checkbox" name="mustard" value="Mustard" disabled <?php echo ($data['mustard']) ? 'checked' : '' ?>>
                        <label for="mustard" class="custom-control-label">Mustard</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="sesame" class="custom-control-input" type="checkbox" name="sesame" value="Sesame" disabled <?php echo ($data['sesame']) ? 'checked' : '' ?>>
                        <label for="sesame" class="custom-control-label">Sesame</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="sulphur_dioxide" class="custom-control-input" type="checkbox" name="Sulphur dioxide" value="sulphur_dioxide" disabled <?php echo ($data['sulphur_dioxide']) ? 'checked' : '' ?>>
                        <label for="sulphur_dioxide" class="custom-control-label">Sulphur dioxide</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="lupin" class="custom-control-input" type="checkbox" name="lupin" value="Lupin" disabled <?php echo ($data['lupin']) ? 'checked' : '' ?>>
                        <label for="lupin" class="custom-control-label">Lupin</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="mollucs" class="custom-control-input" type="checkbox" name="mollucs" value="Mollucs" disabled <?php echo ($data['mollucs']) ? 'checked' : '' ?>>
                        <label for="mollucs" class="custom-control-label">Mollucs</label>
                    </div>
                </div>
            <?php elseif($data['artist']->category == 'HISTORIC') :?>
                <div class="form-group">
                    <label for="language">Language: </label>
                    <select id="my-select" class="form-control form-control-lg" name="type" readonly>>
                        <option value="Dutch">Dutch</option>
                        <option value="English">English</option>
                        <option value="Chinese">Chinese</option>
                    </select>
                </div>
            <?php endif;?>
            <div class="form-group">
                <label for="name">Category:</label>
                <input type="text" class="form-control form-control-lg" name="category" value="<?php echo $data['artist']->category; ?>" readonly>
            </div>
            <a href="<?php echo URLROOT . '/admin/page/getArtists/' . $data['artist']->category?>" class="btn btn-dark float-right"><i class="fa fa-backward"></i> Back</a>  
        </form>
    </div>

<?php require APPROOT . '/views/admin/partial/footer.php'; ?>