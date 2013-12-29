<div class="col-md-12">  
    <div class="page-header">
        <h1><?php echo lang('forums_category_create'); ?></h1>
    </div>
    <?php echo validation_errors();
    echo form_open('', array('class'=>'form-horizontal', 'role' => 'form'));
        if (isset($error)): ?>
        <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">&times;</a>
            <h4 class="alert-heading">Error!</h4>
            <?php if (isset($error['name'])): ?>
                <div>- <?php echo $error['name']; ?></div>
            <?php endif; ?>
            <?php if (isset($error['slug'])): ?>
                <div>- <?php echo $error['slug']; ?></div>
            <?php endif; ?>  
        </div>
        <?php endif; ?>
        <script>
        $(function() {
            $('#category_name').change(function() {
                var name = $('#category_name').val().toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
                $('#category_slug').val(name);
            });
        });
        </script>
        <?php echo form_fieldset(); ?>
          <div class="form-group">
            <?php echo form_label(lang('forums_name'), 'category_name', array('class' => 'col-md-2 control-label')); ?>
            <div class="col-md-10">
                <?php echo form_input(array('class' => 'form-control', 'name' => 'category_name', 'id' => 'category_name'), set_value('category_name')); ?>
            </div>
          </div>
          <div class="form-group">
            <?php echo form_label(lang('forums_slug'), 'category_slug', array('class' => 'col-md-2 control-label')); ?>
            <div class="col-md-10">
                <?php echo form_input(array('class' => 'form-control', 'name' => 'category_slug', 'id' => 'category_slug'), set_value('category_slug')); ?>
              <p class="help-block"><?php echo lang('slug_exp'); ?></p>
            </div>
          </div>
          
          <div class="form-group">
            <?php echo form_label(lang('forums_parent'), 'category_parent', array('class' => 'col-md-2 control-label')); ?>
            <div class="col-md-10">
              <select id="category_parent" name="category_parent">
                <option value="0"><?php echo lang('forums_parent_none'); ?></option>  
                <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <?php echo form_submit(array('name' => 'category-create', 'class' => 'btn btn-primary'), lang('forums_category_create')); ?>
            </div>
          </div>
        <?php echo form_fieldset_close();
        echo form_close(); ?>
</div>