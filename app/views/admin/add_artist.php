<?php require APPROOT . '/views/admin/partial/header.php'; ?>
<?php require APPROOT . '/views/admin/partial/navbar.php'; ?>

    <div class="card card-body bg-light mt-5 col-md-6 container">
        <h2><?php echo ($data['edit']) ? 'Edit ' : 'Add ' ?><?php echo $data['name_line-up']?></h2>
        <form action="<?php echo URLROOT . '/admin/page/' ?><?php echo ($data['edit']) ? 'editArtist/' . $data['id'] . '-' : 'addArtist/'?><?php echo $data['title'] ?>" method="post" enctype="multipart/form-data">
            <div class="form-group artist-img <?php echo ($data['title'] == 'Historic') ? 'd-none' : '' ?>">
                <label>Image:</label>
                <span class="img-div">
                    <img src="<?php echo URLROOT . $data['img_path'] ;?>" id="imageDisplay">
                </span>
                <input type="file" name="artistImage" onChange="displayImage(this)" id="artistImage" class="form-control-file <?php echo (!empty($data['img_path_err'])) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $data['img_path_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="name">Name: <sup>*</sup></label>
                <input type="text" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" name="name" value="<?php echo $data['name']; ?>">
                <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
            </div>
            <?php if($data['title'] == 'Food') :?>
                <div class="form-group">
                    <label for="short_des">Short description: <sup>*</sup></label>
                    <textarea type="text" class="form-control form-control-lg <?php echo (!empty($data['short_des_err'])) ? 'is-invalid' : ''; ?>" name="short_des"><?php echo $data['short_des']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['short_des_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="long_des">Long description: <sup>*</sup></label>
                    <textarea type="text" class="form-control form-control-lg long_des <?php echo (!empty($data['long_des_err'])) ? 'is-invalid' : ''; ?>" name="long_des"><?php echo $data['long_des']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['long_des_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="res_type">Type:</label>
                    <input type="text" class="form-control form-control-lg" name="res_type" value="<?php echo $data["res_type"]; ?>">
                </div>
                <p>Allergies:</p>
                <div class="d-flex flex-wrap">
                    <div class="custom-control custom-checkbox col-3">
                        <input id="gluten" class="custom-control-input" type="checkbox" name="allergen[]" value="Gluten" <?php echo ($data['gluten']) ? 'checked' : '' ?>>
                        <label for="gluten" class="custom-control-label">Gluten</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="crustaceans" class="custom-control-input" type="checkbox" name="allergen[]" value="Crustaceans" <?php echo ($data['crustaceans']) ? 'checked' : '' ?>>
                        <label for="crustaceans" class="custom-control-label">Crustaceans</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="egg" class="custom-control-input" type="checkbox" name="allergen[]" value="Egg" <?php echo ($data['egg']) ? 'checked' : '' ?>>
                        <label for="egg" class="custom-control-label">Egg</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="fish" class="custom-control-input" type="checkbox" name="allergen[]" value="Fish" <?php echo ($data['fish']) ? 'checked' : '' ?>>
                        <label for="fish" class="custom-control-label">Fish</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="peanut" class="custom-control-input" type="checkbox" name="allergen[]" value="Peanut" <?php echo ($data['peanut']) ? 'checked' : '' ?>>
                        <label for="peanut" class="custom-control-label">Peanut</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="soybeans" class="custom-control-input" type="checkbox" name="allergen[]" value="Soybeans" <?php echo ($data['soybeans']) ? 'checked' : '' ?>>
                        <label for="soybeans" class="custom-control-label">Soybeans</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="milk" class="custom-control-input" type="checkbox" name="allergen[]" value="Milk" <?php echo ($data['milk']) ? 'checked' : '' ?>>
                        <label for="milk" class="custom-control-label">Milk</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="nuts" class="custom-control-input" type="checkbox" name="allergen[]" value="Nuts" <?php echo ($data['nuts']) ? 'checked' : '' ?>>
                        <label for="nuts" class="custom-control-label">Nuts</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="celery" class="custom-control-input" type="checkbox" name="allergen[]" value="Celery" <?php echo ($data['celery']) ? 'checked' : '' ?>>
                        <label for="celery" class="custom-control-label">Celery</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="mustard" class="custom-control-input" type="checkbox" name="allergen[]" value="Mustard" <?php echo ($data['mustard']) ? 'checked' : '' ?>>
                        <label for="mustard" class="custom-control-label">Mustard</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="sesame" class="custom-control-input" type="checkbox" name="allergen[]" value="Sesame" <?php echo ($data['sesame']) ? 'checked' : '' ?>>
                        <label for="sesame" class="custom-control-label">Sesame</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="sulphur_dioxide" class="custom-control-input" type="checkbox" name="allergen[]" value="Sulphur dioxide" <?php echo ($data['sulphur_dioxide']) ? 'checked' : '' ?>>
                        <label for="sulphur_dioxide" class="custom-control-label">Sulphur dioxide</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="lupin" class="custom-control-input" type="checkbox" name="allergen[]" value="Lupin" <?php echo ($data['lupin']) ? 'checked' : '' ?>>
                        <label for="lupin" class="custom-control-label">Lupin</label>
                    </div>
                    <div class="custom-control custom-checkbox col-3">
                        <input id="mollucs" class="custom-control-input" type="checkbox" name="allergen[]" value="Mollucs" <?php echo ($data['mollucs']) ? 'checked' : '' ?>>
                        <label for="mollucs" class="custom-control-label">Mollucs</label>
                    </div>
                </div>
            <?php elseif($data['title'] == 'Historic') :?>
                <div class="form-group">
                    <label for="language">Language: </label>
                    <select id="my-select" class="form-control form-control-lg" name="type">
                        <option value="Dutch">Dutch</option>
                        <option value="English">English</option>
                        <option value="Chinese">Chinese</option>
                    </select>
                </div>
            <?php endif;?>
            <div class="form-group">
                <label for="name">Category:</label>
                <input type="text" class="form-control form-control-lg" name="category" value="<?php echo $data['title']; ?>" readonly>
            </div>
            <input type="submit" class="btn btn-success" value="<?php echo ($data['edit']) ? 'Edit ' : 'Add ' ?>">
            <a href="<?php echo URLROOT . '/admin/page/getArtists/' . $data['title']?>" class="btn btn-dark float-right"><i class="fa fa-backward"></i> Back</a>  
        </form>
    </div>

<?php require APPROOT . '/views/admin/partial/footer.php'; ?>