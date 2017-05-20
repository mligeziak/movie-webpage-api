<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $appuser->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $appuser->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Appusers'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="appusers form large-9 medium-8 columns content">
    <?= $this->Form->create($appuser) ?>
    <fieldset>
        <legend><?= __('Edit Appuser') ?></legend>
        <?php
            echo $this->Form->control('email');
            echo $this->Form->control('name');
            echo $this->Form->control('password');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
