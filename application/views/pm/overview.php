<div class="col-lg-12">
    <div class="page-header">
        <h1><?php echo lang('pm_welcome'); ?>
        <?php echo anchor('#msg_new', lang('pm_new'), array('class' => 'btn btn-success pull-right', 'data-toggle' => 'modal', 'data-target' => '#new-msg')) ?></h1>
    </div>
    <?php
        if(isset($response)) print_r($response);
        if(empty($threads))
        {
            echo lang('pm_no_msg');
        }
        else
        {
            //print_r($threads);
            //print_r($participants);
            foreach($threads as $thread)
            {
                //figure out who is involved in the thread
                $from ="";
                if(count($participants[$thread['thread_id']]) < 1)
                {
                    foreach($participants[$thread['thread_id']] as $participant)
                    {
                        $from .= $participant['username'] . ", ";
                    }
                }
                else
                {
                    $from .= $participants[$thread['thread_id']][0]['username'];
                }
                
                //put the array index to the last message
                $messages = $thread['messages'];
                $last_message = end($messages);
            ?>
            <div class="list-group">
                <a href="pm/message/<?php echo $thread['thread_id']; ?>" class="list-group-item">
                    <h4 class="list-group-item-heading"><?php echo $from . " - " . $last_message['subject']; ?></h4>
                    <p class="list-group-item-text"><?php echo $last_message['body']; ?></p>
                </a>
            </div><?php }
        }
    ?>
</div>

<!-- modal to create a new message -->
<div class="modal fade" id="new-msg" tabindex="-1" role="dialog" aria-labelledby="newMessageModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="new-msg-label"><?php echo lang('pm_msg_create'); ?></h4>
      </div>
      <?php echo form_open('', array('role' => 'form')); ?>
      <div class="modal-body">
        <!-- Reciever -->
        <div class="form-group">
            <?php
                echo form_label(lang('pm_recipients'), 'msg-recipients');
                //@todo add jQuery function that will find and confirm users before the form is send
                echo form_input(array('name' => 'msg-recipients', 'id' => 'msg-recipients', 'class' => 'form-control'));
                echo form_error('msg-recipients', '<div class="error">', '</div>');
            ?>
        </div>
        
        <!-- Message title -->
        <div class="form-group">
            <?php
                echo form_label(lang('pm_subject'), 'msg-subject');
                echo form_input(array('name' => 'msg-subject', 'id' => 'msg-subject', 'class' => 'form-control'));
                echo form_error('msg-subject', '<div class="error">', '</div>');
            ?>
        </div>
        
        <!-- Message text -->
        <div class="form-group">
            <?php
                echo form_label(lang('pm_text'), 'msg-text');
                echo form_error('msg-text', '<div class="error">', '</div>');
                echo form_textarea(array('name' => 'msg-text', 'id' => 'msg-text', 'class' => 'form-control'));
            ?>
        </div>
      </div>
      <div class="modal-footer">
        <?php echo form_reset(array('class' => "btn btn-default", 'data-dismiss' => 'modal'), lang('website_cancel')); ?>
        <?php echo form_submit(array('name' => 'msg-new', 'class' => "btn btn-success"), lang('pm_msg_send')); ?>
      </div>
      <?php echo form_close(); ?>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
