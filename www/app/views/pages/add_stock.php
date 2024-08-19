<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- On Submit Save-Stock-Form Loader -->
  <div id="pageloader">
    <img src="assets/img/ProcessingIcon.gif" alt="processing...">
  </div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add Stock(Drugs)</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="?url=home">Home</a></li>
            <li class="breadcrumb-item active">Stock Management</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="modal fade" id="alert-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-danger">
          <div class="modal-header">
            <h4 class="modal-title">Invalid Input!!</h4>
          </div>
          <div id="modal-body-data" class="modal-body"></div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Ok</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="container-fluid">
      <!-- MULTIPLE DRUG SELECTOR -->
      <?php if (isset($data['cart_details']) &&
                is_array($data['cart_details']) &&
                !empty($data['cart_details'])): ?>
        <div class="row">
          <div class="col-12">
            <div class="card card-info card-outline elevation-2">
<form id="add-stock" method="POST" enctype="application/x-www-form-urlencoded" action="?url=home/add_stock/save">
              <div class="card-header py-3">
                <h3 class="card-title">Enter Details of the Added Stock</h3>

                <div class="card-tools">
                  <h6>Grand Total: <span class="badge badge-info stock-grand-total-price">Tsh 0/=</span></h6>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" style="height: 350px;">
                <table class="table table-head-fixed text-nowrap">
                  <thead>
                    <tr>
                      <th>Brand Name</th>
                      <th>Generic Name</th>
                      <th>Stock Expiry Date</th>
                      <th>Qty Bought</th>
                      <th>Cost Per Unit(Tsh)</th>
                      <th>Total Cost(Tsh)</th>
                    </tr>
                  </thead>
                  <tbody class="stock-items">
                    <?php foreach ($data['cart_details'] as $row): ?>
                      <tr class="stock-item">
                        <input type="hidden" id="inputID" name="id[]"
                                value="<?php echo $row['drug_id']; ?>" required />
                        <input type="hidden" class="selling_price" value="<?php echo $row['unit_s_price']; ?>" disabled />
                        <td><?php echo $row['brand_name']; ?></td>
                        <td><?php echo $row['generic_name']; ?></td>
                        <td>
                          <input type="date" id="inputExpiry" name="expiry_date[]" class="form-control stock-expiry-date" required />
                        </td>
                        <td>
                          <input type="number" id="inputQtyBought" name="qty_bought[]" class="form-control stock-qty" placeholder="Unit Qty" required />
                        </td>
                        <td>
                          <input type="number" id="inputUnitCost" name="unit_cost[]" class="form-control stock-price" placeholder="in Tshs" required />
                        </td>
                        <td>
                          <input type="number" id="inputTotalCost" name="total_cost[]" class="form-control stock-total-price" placeholder="0" required />
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <a href="?url=home/add_stock">
                  <button type="button" class="btn btn-default">Cancel</button>
                </a>
                <button id="form-submit-btn" type="submit" value="submit" class="btn btn-primary float-right">
                  Submit
                </button>
              </div>
</form>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      <?php else: ?>
        <div class="card card-info card-outline elevation-2">
          <div class="card-header py-3">
            <h3 class="card-title">Multiple Drug Selector</h3>
          </div>
          <!-- /.card-header -->
<form id="add-stock" method="POST" enctype="application/x-www-form-urlencoded" action="?url=home/add_stock/run">
          <div class="card-body">
            <h5>Choose the Drugs You Want to Add Stock Quantity</h5>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Drug Name:</label>
                  <select class="select2"
                    name="multiple_drug_selector[]"
                    multiple="multiple"
                    data-placeholder="Select Multiple Drugs"
                    style="width: 100%;"
                    required>
                    <?php if (isset($data['available_stock']) && is_array($data['available_stock'])): ?>
                      <?php foreach ($data['available_stock'] as $row): ?>
                        <option value="<?php echo $row['drug_id']; ?>">
                          <?php echo ucfirst($row['brand_name'])."(".ucfirst($row['generic_name']).")"; ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <button type="submit" value="submit" class="btn btn-primary">Submit</button>
          </div>
</form>
        </div>
        <!-- /.card -->
      <?php endif; ?>
    </div>
  </section>
</div>
