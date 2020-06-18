<?php require APPROOT . '/views/admin/partial/header.php'; ?>
<?php require APPROOT . '/views/admin/partial/navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <?php require APPROOT . '/views/admin/partial/sidebar.php'; ?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 main">
      <?php flash('user_message'); ?>
      <h1>Users</h1>
      <?php if($_SESSION['user_type'] == UserType::SUPERADMIN)  :?><div class="d-flex justify-content-end"><a href="<?php echo URLROOT;?> /admin/user/add" class="btn btn-primary btn-md users">Add user</a></div> <?php endif; ?>
      <table id="table_users" class = 'table users'>
        <thead class = 'thead-dark'>
          <tr>
            <th>Email</th>
            <th>Type</th>
            <?php if($_SESSION['user_type'] == UserType::SUPERADMIN)  :?><th class="col-1">Actions</th> <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['users'] as $user) : ?>
          <tr>
            <td class="users"><?php echo $user->email ?></td>
            <td class="users">
            <?php 
            switch ($user->type) {
              case 1:
                echo 'Visitor';
              break;
              case 2:
                echo 'Admin';
              break;
              case 3:
                echo 'Superadmin';
              break;
            }
            ?></td> 
            <?php if($_SESSION['user_type'] == UserType::SUPERADMIN)  :?>
            <td class="d-flex justify-content-around users-action pl-4"> 
              <a href="<?php echo URLROOT . '/admin/user/edit/' . $user->id;?>" class="flex-fill"><i class='fa fa-edit'></i></a>
              <a href="<?php echo URLROOT . '/admin/user/delete/' . $user->id;?>" class="flex-fill"><i class='fa fa-trash-alt trash'></i></a>
            </td>
            <?php endif; ?>
          </tr>
          <?php endforeach ; ?>
        </tbody>
      </table>
    </main>
  </div>
</div>


<?php require APPROOT . '/views/admin/partial/footer.php'; ?>

