<div class="col-lg-12">
    <div class="page-header">
        <h1><?php echo $thread[0]['subject']; ?>
        <?php echo anchor('pm', '<span class="glyphicon glyphicon-arrow-left"></span> ' . lang('pm_back_to_overview'), array('class' => 'btn btn-default pull-right')) ?></h1>
    </div>
    <div class="col-lg-10">
    <?php //print_r($thread);
    foreach($thread as $message)
    {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $message['username'] . " - " . $message['subject']; ?>
                <span class="pull-right"><?php echo $message['cdate']; ?></span></h3>
            </div>
            <div class="panel-body">
                <?php echo $message['body']; ?>
            </div>
        </div>
        <?php
    }
    echo "<h4>" . lang('pm_reply') . "</h4>";
    echo form_open('', array('role' => 'form'), array('msg-subject' => $thread[0]['subject'], 'msg-reply-id' => $thread[0]['id']));
        echo form_label(lang('pm_text'), 'msg-text');
        echo form_error('msg-text', '<div class="error">', '</div>');
        echo form_textarea(array('name' => 'msg-text', 'id' => 'msg-text', 'class' => 'form-control'));
        echo form_submit(array('name' => 'msg-reply', 'class' => "btn btn-success pull-right"), lang('pm_msg_send'));
    echo form_close();
    ?>
    </div>
    
    <!-- Management of participants -->
    <div class="col-lg-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo lang('pm_participants'); ?></h3>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                <?php
                foreach($participants as $participant)
                {
                    //@todo add functionality to remove participants
                    //first need to figure out who is going to have the priviledge to remove people from 3+ debates
                    //also everyone should have the option to remove themselves from 3+ debates
                    if($participant['user_id'] != $account->id)
                    {
                        $badge = NULL;
                    }
                    else
                    {
                        $badge = '<span class="badge glyphicon glyphicon-minus"></span>';
                    }
                    
                    echo '<li class="list-group-item" id="'.$participant['user_id'].'">'. $badge . $participant['username'].'</li>';
                }
                ?>
                </ul>
            </div>
            <div class="panel-footer">
                <?php
                    //@todo
                    //echo anchor('#', lang('pm_participant_add'), array('class' => 'btn btn-success'));
                ?>
            </div>
        </div>
    </div>
</div>