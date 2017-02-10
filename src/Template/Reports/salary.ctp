<div class="page-header">
  <h1><?= __('Employee Salaries') ?></h1>
</div>

<form class="form-horizontal" method="get" action="<?= $this->Url->build('/reports/salary', true) ?>">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><?= __('Conditions') ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="cboShop" class="col-sm-2 control-label"><?= __('Shop') ?></label>
                        <div class="col-sm-9">
                            <select class="form-control" id="cboShop" name="shop">
                                <option value="0">All</option>
                                <?php foreach ($shops as $shop) : ?>
                                    <?php echo '<option value="'.$shop->id.'" '.($shop->id == $conditions['shopId'] ? 'selected' : '').'>'.$shop->account.'</option>'; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cboEmployee" class="col-sm-2 control-label"><?= __('Employee') ?></label>
                        <div class="col-sm-9">
                            <select class="form-control" id="cboEmployee" name="employee">
                                <option value="0">All</option>
                                <?php foreach ($employees as $employee) : ?>
                                    <?php echo '<option value="'.$employee->id.'" '.($employee->id == $conditions['employeeId'] ? 'selected' : '').'>'.$employee->first_name.' '.$employee->last_name.'</option>'; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fromDate" class="col-sm-2 control-label"><?= __('Date') ?></label>
                        <div class="col-sm-9">
                            <div class="input-group input-daterange">
                                <input id="fromDate" name="fromDate" type="text" class="form-control" value="<?= $conditions['fromDate']->format('Y-m-d') ?>" readonly>
                                <div class="input-group-addon">to</div>
                                <input id="toDate" name="toDate" type="text" class="form-control" value="<?= $conditions['toDate']->format('Y-m-d') ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button type="reset" class="btn btn-default"><?= __('Clear') ?></button>
                    <button type="submit" class="btn btn-primary"><?= __('Submit') ?></button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Services</th>
                        <th class="text-center">Tips</th>
                        <th class="text-center">Shop Fee</th>
                        <th class="text-center">Total <span class="glyphicon glyphicon-usd"></span></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data->count() <= 0) : ?>
                        <tr class="warning"><td class="text-center" colspan="7">No data result</td></tr>
                    <?php endif; ?>
                    <?php foreach ($data as $n => $row) : ?>
                        <tr>
                            <th class="text-center"><?= $n+1 ?></th>
                            <td><?= $row->full_name ?></td>
                            <td class="text-right"><?= $row->tPrice ?></td>
                            <td class="text-right"><?= (float)$row->tTips ?></td>
                            <td class="text-right"><?= (float)$row->tShopFee ?></td>
                            <th class="text-right"><?= $row->tPrice + (float)$row->tTips - (float)$row->tShopFee ?></th>
                            <td class="text-center w30">
                                <button type="button" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-eye-open"></span></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>        
    </div>
</div>
<input type="hidden" id="hidEmployeeUrl" value="<?= $this->Url->build('/reports/employee-list/', true) ?>">