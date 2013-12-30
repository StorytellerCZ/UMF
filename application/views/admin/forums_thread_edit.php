<div class="col-md-12">
    <div class="page-header">
        <h1 class="text-center"><?php echo lang('forums_thread_edit'); ?></h1>
    </div>
    <?php echo form_open('', array('role' => 'form', 'class' => 'well form-horizontal')); ?>
    <script>
    $(function() {
        $('#thread-title').change(function() {
            var title = $('#thread-title').val().toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
            $('#thread-slug').val(title);
        });
    });
    </script>
    <div class="form-group">
        <?php echo form_label(lang('forums_name'), 'thread-title', array('class' => 'col-md-2 control-label')); ?>
        <div class="col-md-10">
            <?php echo form_input(array('class' => 'form-control', 'name' => 'thread-title', 'id' => 'thread-title'), $thread->title); ?>
        </div>
    </div>
    
    <div class="form-group">
        <?php echo form_label(lang('forums_slug'), 'thread-slug', array('class' => 'col-md-2 control-label')); ?>
        <div class="col-md-10">
            <?php echo form_input(array('class' => 'form-control', 'name' => 'thread-slug', 'id' => 'thread-slug'), $thread->slug); ?>
            <p><?php echo lang('forums_slug_exp'); ?></p>
        </div>
    </div>
    
    <div class="form-group">
        <?php echo form_label(lang('forums_category'), 'thread-category', array('class' => 'col-md-2 control-label')); ?>
        <div class="col-md-10">
            <select class="form-control" name="thread-category" id="thread-category">
                <option value="0"><?php echo lang('forums_parent_none'); ?></option>  
                <?php foreach ($categories as $cat): ?>
                <?php if ($cat['id'] == $thread->category_id): ?>
                    <option selected="selected" value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                <?php endif; ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-offset-2 col-md-10">
            <?php echo form_submit(array('class' => 'btn btn-primary btn-large', 'name' => 'thread-save'), lang('website_update')); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>