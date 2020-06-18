<?php require APPROOT . '/views/admin/partial/header.php'; ?>
<?php require APPROOT . '/views/admin/partial/navbar.php'; ?>

    <div class="card card-body bg-light mt-5 col-md-6 container">
        <h2><?php echo ($data['edit']) ? 'Edit ' : 'Add ' ?>sponsor</h2>
        <form action="<?php echo URLROOT . '/admin/page/' ?><?php echo ($data['edit']) ? 'editSponsor/' . $data['id'] : 'addSponsor'?>" method="post" enctype="multipart/form-data">
            <div class="form-group artist-img">
                <label>Image:</label>
                <span class="img-div">
                    <img src="<?php echo URLROOT . $data['img_path'] ;?>" id="imageDisplay">
                </span>
                <input type="file" name="sponsorImage" onChange="displayImage(this)" id="sponsorImage" class="form-control-file <?php echo (!empty($data['img_path_err'])) ? 'is-invalid' : ''; ?>"<?php echo ($data['view']) ? 'disabled': ''?>>
                <span class="invalid-feedback"><?php echo $data['img_path_err']; ?></span>
            </div>
            <div class="d-flex">
            <div class="form-group col-8 p-2">
                <label for="name">Name: <sup>*</sup></label>
                <input type="text" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" name="name" value="<?php echo $data['name']; ?>" <?php echo ($data['view']) ? 'disabled': ''?>>
                <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
            </div>
            <div class="form-group col-2 p-2 ml-5">
                <label for="priority">Priority: </label>
                <select id="my-select" class="form-control form-control-lg" name="priority"<?php echo ($data['view']) ? 'disabled': ''?>>
                    
                    <?php for ($i=1; $i <= 10; $i++) : ?>
                    <option value="<?php echo $i ?>"<?php echo ($i==$data['priority']) ? 'selected' : '' ?>><?php echo $i ?></option>
                    <?php endfor ; ?>
                </select>
            </div>
            </div>
            
            <input type="submit" class="btn btn-success <?php echo ($data['view']) ? 'd-none': ''?>" value="<?php echo ($data['edit']) ? 'Edit ' : 'Add ' ?>">
            <a href="<?php echo URLROOT . '/admin/page/getSponsors' ?>" class="btn btn-dark float-right"><i class="fa fa-backward"></i> Back</a>  
        </form>
    </div>

<?php require APPROOT . '/views/admin/partial/footer.php'; ?>