<?php require APPROOT . '/views/admin/partial/header.php'; ?>
<?php require APPROOT . '/views/admin/partial/navbar.php'; ?>

    <div class="card card-body bg-light mt-5 col-md-6 container">
        <h2>Edit ticket </h2>
        <form action="<?php echo URLROOT . '/admin/page/editTicket/' . $data['id']?>" method="post">
            <div class="form-group">
                <label for="name">Name: <sup>*</sup></label>
                <input type="text" class="form-control form-control-lg" name="name" value="<?php echo $data['name']; ?>" readonly>
            </div>
            <div class="d-flex">
                <div class="form-group mr-3">
                    <label for="date">Date: <sup>*</sup></label>
                    <input type="date" class="form-control form-control-lg" name="date" value="<?php echo $data['date'] ?>" min="<?php echo date("Y-m-d")?>"readonly>
                </div>
                <div class="form-group mr-3">
                    <label for="start_time">Starting Time: <sup>*</sup></label>
                    <input type="time" class="form-control form-control-lg" name="start_time" value="<?php echo date("h:i", strtotime($data['start_time'])) ?>"readonly>
                </div>
                <div class="form-group">
                    <label for="end_time">Ending Time: <sup>*</sup></label>
                    <input type="time" class="form-control form-control-lg" name="end_time" value="<?php echo date("h:i", strtotime($data['end_time'])); ?>"readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="price">Price(in EUR): <sup>*</sup></label>
                <input type="number" class="form-control form-control-lg <?php echo (!empty($data['price_err'])) ? 'is-invalid' : '';?>" name="price" value="<?php echo $data['price']; ?>">
                <span class="invalid-feedback"><?php echo $data['price_err']; ?></span> 
            </div>
            <div class="form-group">
                <label for="availability">Availability: <sup>*</sup></label>
                <input type="number" class="form-control form-control-lg <?php echo (!empty($data['availability_err'])) ? 'is-invalid' : '';?>" name="availability" value="<?php echo $data['availability']; ?>">
                <span class="invalid-feedback"><?php echo $data['availability_err']; ?></span> 
            </div>
            
            <input type="submit" class="btn btn-success" value="Edit">
            <a href="<?php echo URLROOT . '/admin/ticket'?>" class="btn btn-dark float-right"><i class="fa fa-backward"></i> Back</a>  
        </form>
    </div>

<?php require APPROOT . '/views/admin/partial/footer.php'; ?>