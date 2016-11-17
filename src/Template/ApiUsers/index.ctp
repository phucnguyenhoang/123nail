<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Api User'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="apiUsers index large-9 medium-8 columns content">
    <h3><?= __('Api Users') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('username') ?></th>
                <th scope="col"><?= $this->Paginator->sort('password') ?></th>
                <th scope="col"><?= $this->Paginator->sort('active') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($apiUsers as $apiUser): ?>
            <tr>
                <td><?= $this->Number->format($apiUser->id) ?></td>
                <td><?= h($apiUser->username) ?></td>
                <td><?= h($apiUser->password) ?></td>
                <td><?= h($apiUser->active) ?></td>
                <td><?= h($apiUser->created) ?></td>
                <td><?= h($apiUser->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $apiUser->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $apiUser->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $apiUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $apiUser->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
