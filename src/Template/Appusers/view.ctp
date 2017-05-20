<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Appuser'), ['action' => 'edit', $appuser->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Appuser'), ['action' => 'delete', $appuser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $appuser->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Appusers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Appuser'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="appusers view large-9 medium-8 columns content">
    <h3><?= h($appuser->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($appuser->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($appuser->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Password') ?></th>
            <td><?= h($appuser->password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($appuser->id) ?></td>
        </tr>
    </table>
</div>
