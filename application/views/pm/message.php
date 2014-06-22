<div class="col-sm-12">
    <div class="page-header">
        <h1><?php echo $thread[0]['subject']; ?>
        <?php echo anchor('pm', '<span class="glyphicon glyphicon-arrow-left"></span> ' . lang('pm_back_to_overview'), array('class' => 'btn btn-default pull-right')) ?></h1>
    </div>
    <div class="col-sm-9">
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
    echo form_open(uri_string(), array('role' => 'form'), array('msg-subject' => $thread[0]['subject'], 'msg-reply-id' => $thread[0]['id']));
        echo form_label(lang('pm_text'), 'msg-text');
        echo form_error('msg-text', '<div class="error">', '</div>');
        echo form_textarea(array('name' => 'msg-text', 'id' => 'msg-text', 'class' => 'form-control'));
        echo form_submit(array('name' => 'msg-reply', 'class' => "btn btn-lg btn-success pull-right"), lang('pm_msg_send'));
    echo form_close();
    ?>
    </div>
    
    <!-- Management of participants -->
    <div class="col-sm-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo lang('pm_participants'); ?></h3>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                <?php
                foreach($participants as $participant)
                {
                    //@todo need to figure out who is going to have the priviledge to remove people from 3+ debates
                    //everyone should have the option to remove themselves from 3+ debates
                    if(count($participants) > 2)
                    {
                        if($participant['user_id'] == $account->id)
                        {
                            $badge = anchor(base_url('pm/message/remove_participant/'.$thread[0]['thread_id'].'/'.$participant['user_id']), '<span class="glyphicon glyphicon-minus"></span>', array('class' => 'badge pull-right'));
                        }
                        else
                        {
                            $badge = NULL;
                        }
                    }
                    else
                    {
                        $badge = NULL;
                    }
                    
                    echo '<li class="list-group-item" id="'.$participant['user_id'].'">' . $participant['username'] . ' ' . $badge .'</li>';
                }
                ?>
                </ul>
            </div>
            <div class="panel-footer">
                <?php
                    echo form_open(base_url('pm/message/add_participant/'.$thread[0]['id']), array('role' => 'form')) . '<div class="form-group">';
                    echo form_label(lang('pm_add_participants'), 'msg-add-participants');
                    echo form_input(array('name' => 'msg-add-participants', 'id' => 'msg-add-participants', 'class' => 'form-control')) . '</div>';
                    echo form_submit(array('class' => 'btn btn-success'), lang('pm_add_users'));
                    echo form_close();
                    //@todo
                    //echo anchor('#', lang('pm_participant_add'), array('class' => 'btn btn-success'));
                ?>
            </div>
        </div>
    </div>
</div>