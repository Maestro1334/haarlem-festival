<?php require APPROOT . '/views/admin/partial/header.php'; ?>
<?php require APPROOT . '/views/admin/partial/navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <?php require APPROOT . '/views/admin/partial/sidebar.php'; ?>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 main">
      <?php flash('ticket_message'); ?>
      <h1>Tickets</h1>
      <?php $i = 1; foreach($data['categories'] as $catagory) : ?>
        <button id="<?php echo $i . "_btn"?>" class="btn <?php echo($i == 1) ? '' : 'btn-dark' ?> btn-info"><?php echo $catagory?></button>
      <?php $i++; endforeach ; ?>
      <?php $j = 1; foreach($data['categories'] as $catagory) : ?>
      <div id="<?php echo $j . "_div"?>">
      <table id="table_tickets_<?php echo $catagory ?>" class="table users">
        <thead class="thead-dark">
            <tr>
              <th class="col-1">Time</th>
              <th>Name</th>
              <th>Price</th>
              <th>Availability</th>
              <th>Sold</th>
              <th class="col-1">Actions</th>
              <th class = "d-none">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data[$catagory] as $ticket) : $language = ltrim($ticket->name, 'Historic Tour Single ')?>
            <tr>
              <td class="users"><?php echo date("h:i", strtotime($ticket->start_time)) . ' - ' . date("h:i", strtotime($ticket->end_time)); ?></td>
              <td class="users"><?php echo ($ticket->category == 'HISTORIC') ? rtrim($ticket->name, 'Single ' . $language) .' '. $language : $ticket->name; ?></td>
              <td class="users"><?php echo $ticket->price; ?></td>
              <td class="users"><?php echo $ticket->availability; ?></td>
              <td class="users"><?php echo $ticket->quantity; ?></td>
              <td class="users-action"><a href="<?php echo URLROOT . '/admin/ticket/editTicket/' . $ticket->id?>" class="flex-fill"><i class='fa fa-edit'></i></a></td>
              <td class="d-none"><?php echo $ticket->date ?>
            </tr>
            <?php endforeach ; ?>
        </tbody>
      </table>
    </div>
    <?php $j++; endforeach; ?>
    </main>
  </div>
</div>


<?php require APPROOT . '/views/admin/partial/footer.php'; ?>

