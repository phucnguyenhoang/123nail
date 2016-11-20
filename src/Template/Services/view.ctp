<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Service'), ['action' => 'edit', $service->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Service'), ['action' => 'delete', $service->id], ['confirm' => __('Are you sure you want to delete # {0}?', $service->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Services'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Service'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="services view large-9 medium-8 columns content">
    <h3><?= h($service->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Category') ?></th>
            <td><?= $service->has('category') ? $this->Html->link($service->category->name, ['controller' => 'Categories', 'action' => 'view', $service->category->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($service->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($service->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Price') ?></th>
            <td><?= $this->Number->format($service->price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Shop Fee') ?></th>
            <td><?= $this->Number->format($service->shop_fee) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($service->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($service->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Active') ?></th>
            <td><?= $service->active ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
