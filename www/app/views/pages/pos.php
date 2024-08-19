<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- On Submit Select-Drug-Form Loader -->
  <div id="pageloader">
    <img src="assets/img/ProcessingIcon.gif" alt="processing...">
  </div>
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Point of Sales</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="?url=home">Home</a></li>
            <li class="breadcrumb-item"><a>POS</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary card-outline elevation-2">
            <div class="card-body">
              <div class="row">
                <div class="col-12 border-bottom mb-3 px-3">
                  <h5 class="text-left py2">Customer&rsquo;s Cart</h5>
                </div>
                <div class="col-12">
                  <div class="row px-5">
                    <div class="col-9">
                      <div class="form-group">
                        <select id="select2-drug-list" 
                                class="form-control select2 select2-info select-item" 
                                data-dropdown-css-class="select2-info" 
                                style="width: 100%;"
                                name="drug_id" required>
                          <option value="" selected="selected" disabled>Search by Drug Name</option>
                        </select>
                      </div>
                      <!-- /.form-group -->
                    </div>
                    <div class="col-3">
                      <button type="button" class="btn btn-block btn-info btn-md add-to-cart-button">
                        Select
                      </button>
                    </div>
                  </div>
                </div>
                <div class="col-12 border-bottom">
                  <div class="table-responsive">
                    <table class="table m-0">
                      <thead>
                        <tr>
                          <th>Brand Name</th>
                          <th>Generic Name</th>
                          <th>Unit-Price</th>
                          <th>Available Qty</th>
                          <th>Qty to Sell</th>
                          <th>Sub-Total</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody class="cart-items"></tbody>
                      <tfoot>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><h6>Grand-Total: </h6></td>
                        <td><h6 class="cart-total-price">Tshs 0/=</h6></td>
                        <td></td>
                      </tfoot>
                    </table>
                  </div>
                  <!-- /.table-responsive -->
                </div>
              </div>
            </div>
            <div class="card-footer px-5">
              <button type="button" class="btn btn-primary btn-md float-sm-right btn-process-transaction">
                Process Transaction
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>
