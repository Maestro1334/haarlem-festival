<?php require APPROOT . '/views/admin/partial/header.php'; ?>
<?php require APPROOT . '/views/admin/partial/navbar.php'; ?>

    <div class="card card-body bg-light mt-5 col-md-6 container">
        <h2>Add User</h2>
        <p>Add a user with this form</p>
        <form action="<?php echo URLROOT; ?>/admin/user/add" method="post">
            <div class="form-group">
                <label for="email">Email: <sup>*</sup></label>
                <input type="text" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" name="email" value="<?php echo $data['email']; ?>">
                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="type">Type: </label>
                <select id="my-select" class="form-control form-control-lg" name="type">
                    <option value="<?php echo UserType::VISITOR;?>"  selected >Visitor</option>
                    <option value="<?php echo UserType::ADMIN;?>">Admin</option>
                    <option value="<?php echo UserType::SUPERADMIN;?>">SuperAdmin</option>
                </select>
                <span class="invalid-feedback"><?php echo $data['type_err']; ?></span>
            </div>
            <input type="submit" class="btn btn-success" value="Add">
            <a href="<?php echo URLROOT; ?>/admin/user" class="btn btn-dark float-right"><i class="fa fa-backward"></i> Back</a>  
        </form>
    </div>

<?php require APPROOT . '/views/admin/partial/footer.php'; ?>