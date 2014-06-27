<div class="col-md-12">
    <ul class="breadcrumb">
    <li>
        <a href="<?php echo site_url('forums'); ?>"><?php echo lang('website_forums'); ?></a>
    </li>
    <?php $cat_total = count($cat); foreach ($cat as $key => $c): ?>
    <li>
        <a href="<?php echo site_url('forums/category/'.$c['slug']); ?>"><?php echo $c['name']; ?></a> 
        <?php if ($key+1 != $cat_total): ?>
        <?php endif; ?>
    </li>
    <?php endforeach; ?>
    <li>
        <?php echo anchor('#', $thread->title); ?>
    </li>
    </ul>
</div>

<div class="col-md-12">
    <?php if (isset($tmp_success)): ?>
    <div class="alert alert-success">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        <h4 class="alert-heading"><?php echo lang('forums_reply_posted'); ?></h4>
    </div>
    <?php endif; ?>
    <?php if (isset($tmp_success_new)): ?>
    <div class="alert alert-success">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        <h4 class="alert-heading"><?php echo lang('forums_thread_created'); ?></h4>
    </div>
    <?php endif; ?>

    <div class="page-header">
        <h1><?php echo $thread->title; ?></h1>
    </div>
    
    <?php foreach ($posts as $post): ?>
        <div class="well">
            <?php echo $post->post; ?><br/><br/>
            
        <!-- @todo rework to only put quotes into the reply box
            <ul class="nav nav-pills" style="float:left;">
            <li class="dropdown" id="menu<?php echo $post->id; ?>">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#menu<?php echo $post->id; ?>" style="border: 1px solid #d9d9d9;font-size: 11px;">
            Quote / Reply
            <b class="caret"></b>
            </a>
            <ul class="active dropdown-menu">
                <script>
                $(document).ready(function() {
                    $("#replypost<?php echo $post->id; ?>").wysiwyg("setContent", "<div style='font-size:11px; background: #e3e3e3;padding:5px;'>posted by <b>@<?php echo $post->username; ?></b><p><i><?php echo preg_replace("/&#?[a-z0-9]+;/i","", strip_tags($post->post)); ?></i></p></div><br/><br/>");
                });
                </script>
            <li><form class="well" action="" method="post" style="margin: 5px 10px;width: 600px;text-align: left;">
                    <input type="hidden" name="row[thread_id]" value="<?php echo $thread->id; ?>"/>
                    <input type="hidden" name="row[reply_to_id]" value="<?php echo $post->id; ?>"/>
                    <input type="hidden" name="row[author_id]" value="<?php echo $this->session->userdata('cibb_user_id'); ?>"/>
                    <input type="hidden" name="row[date_add]" value="<?php echo date('Y-m-d H:i:s'); ?>"/>
                    <textarea name="row[post]" id="replypost<?php echo $post->id; ?>" class="textpostreply" cols="72" style="height:180px;" class="span12">
                    </textarea>
                    <input type="submit" style="margin-top:15px;font-weight: bold;" name="btn-post" class="btn btn-primary" value="Reply Post"/>
                </form></li>
            </ul>
            </li>
            </ul>
            -->
            <span style="font-size:11px;float:right;position: relative;top:14px;">
                posted by <?php echo $post->username; ?>, <?php echo $this->cibb->time_ago($post->date_add); ?>
            </span>
            <div class="clearfix" style="height: 30px;"></div>

        </div>
    <?php endforeach; ?>


    <div class="pagination text-center">
        <ul><?php echo $page; ?></ul>
    </div>

    <div class="page-header">
        <h4><?php echo lang('forums_reply'); ?></h4>
    </div>
    
    <?php
    echo validation_errors();
    echo form_open('', array('class' => 'well', 'role' => 'form'));
    echo '<div class="form-group">';
    echo form_textarea(array('class' => 'form-control', 'id' => 'reply-post', 'name' => 'reply-post'));
    echo '</div><div class="form-group">';
    echo form_submit(array('class' => 'btn btn-primary pull-right', 'name' => 'reply'), lang('forums_reply'));
    echo '</div><div class="clearfix"></div>';
    echo form_close();
    ?>
</div>