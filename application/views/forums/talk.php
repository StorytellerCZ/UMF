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
            <?php echo htmlspecialchars_decode($post->post, ENT_QUOTES); ?><br/><br/>
            
            <span class="pull-right">
                <?php echo lang('forums_posted_by') . $post->username; ?>, <?php echo $this->cibb->time_ago($post->date_add); ?>
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
    echo form_textarea(array('class' => 'form-control editable', 'id' => 'reply-post', 'name' => 'reply-post'));
    echo '</div><div class="form-group">';
    echo form_submit(array('class' => 'btn btn-primary pull-right', 'name' => 'reply'), lang('forums_reply'));
    echo '</div><div class="clearfix"></div>';
    echo form_close();
    ?>
</div>