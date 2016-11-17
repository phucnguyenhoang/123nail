<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Api Users'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="apiUsers form large-9 medium-8 columns content">
    <?= $this->Form->create($apiUser) ?>
    <fieldset>
        <legend><?= __('Add Api User') ?></legend>
        <?php
            echo $this->Form->input('username');
            echo $this->Form->input('password');
            echo $this->Form->input('active');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
