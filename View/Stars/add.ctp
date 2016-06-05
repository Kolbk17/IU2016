<div class="users form">
<?php echo $this->Form->create('Star'); ?>
    <fieldset>
        <legend><?php echo __('Add Star'); ?></legend>
        <?php echo $this->Form->input('name');
        echo $this->Form->input('description');
        echo $this->Form->input('galaxy_id', array('options'=>$options));
        ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>