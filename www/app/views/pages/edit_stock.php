<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- On Submit Move-To-Trash Loader -->
	<div id="pageloader">
		<img src="assets/img/ProcessingIcon.gif" alt="processing...">
	</div>
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Edit Purchase Record</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="?url=home">Home</a></li>
            <li class="breadcrumb-item"><a>Edit Details</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <?php if (isset($data['list_of_purchases']) && is_array($data['list_of_purchases'])): ?>
      <?php  $i=1; foreach ($data['list_of_purchases'] as $row): ?>
      <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Purchase Details for "<?php echo ucfirst($row['brand_name'])." (".ucfirst($row['generic_name']).")"; ?>"</h3>
            </div>
<form id="edit-stock" method="POST" enctype="application/x-www-form-urlencoded" action="?url=home/edit_stock_record">
              <input type="hidden" id="inputBatchID" name="Bid" class="form-control" value="<?php echo ucfirst($row['batch_id']); ?>" required>
              <input type="hidden" id="inputUnitSellingPriceID" name="selling_price" class="form-control" value="<?php echo ucfirst($row['unit_s_price']); ?>" required>
              <div class="card-body">
                <div class="form-group">
                  <label for="inputExpiry">Stock Expiry Date</label>
                  <input type="date" id="inputExpiry" name="expiry_date" class="form-control stock-expiry-date" value="<?php echo ucfirst($row['expiry_date']); ?>" required>
                </div>
                <div class="form-group">
                  <label for="inputQtyBought">Qty Bought</label>
                  <input type="number" id="inputQtyBought" name="qty_bought" class="form-control stock-qty" value="<?php echo ucfirst($row['qty_bought']); ?>" required>
                </div>
                <div class="form-group">
                  <label for="inputUnitCost">Cost Per Unit(Tsh)</label>
                  <input type="number" id="inputUnitCost" name="unit_cost" class="form-control stock-price" value="<?php echo $row['unit_b_price']; ?>" required>
                </div>
                <div class="form-group">
                  <label for="inputTotalCost">Total Cost(Tsh)</label>
                  <input type="number" id="inputTotalCost" name="total_cost" class="form-control stock-total-price" value="<?php echo $row['total_cost']; ?>" required>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" value="submit" class="btn btn-success btn-block btn-round">
                  Update
                </button>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div class="col-md-3"></div>
</form>
      </div>
      <?php $i++;endforeach; ?>
    <?php endif; ?>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
