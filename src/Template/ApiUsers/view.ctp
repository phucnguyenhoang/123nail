<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Api User'), ['action' => 'edit', $apiUser->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Api User'), ['action' => 'delete', $apiUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $apiUser->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Api Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Api User'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="apiUsers view large-9 medium-8 columns content">
    <h3><?= h($apiUser->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Username') ?></th>
            <td><?= h($apiUser->username) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Password') ?></th>
            <td><?= h($apiUser->password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($apiUser->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($apiUser->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($apiUser->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Active') ?></th>
            <td><?= $apiUser->active ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
