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
                                <option value="all">All</option>
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
                                <option value="all">All</option>
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
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Name</th>
                        <th>Services</th>
                        <th>Tips</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="text-center">1</th>
                        <td>Sores Kanl</td>
                        <td>200</td>
                        <td>15</td>
                        <td class="text-center w30">
                            <button type="button" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-eye-open"></span></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>