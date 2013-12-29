<div class="col-lg-12">  
    <div class="page-header">
        <h1><?php echo lang('forums_category_edit'); ?></h1>
    </div>
    <?php echo form_open('', array('class' => 'form-horizontal', 'role' => 'form'));
        echo form_fieldset();
    ?>
    <div class="form-group">
        <?php echo form_label(lang('forums_name'), 'category_name', array('class' => 'col-md-2 control-label')); ?>
        <div class="col-md-10">
            <?php echo form_input(array('class' => 'form-control', 'name' => 'category_name', 'id' => 'category_name'), $category->name); ?>
        </div>
    </div>
    
    <div class="form-group">
        <?php echo form_label(lang('forums_slug'), 'category_slug', array('class' => 'col-md-2 control-label')); ?>
        <div class="col-md-10">
            <?php echo form_input(array('class' => 'form-control', 'name' => 'category_slug', 'id' => 'category_slug'), $category->slug); ?>
            <p class="help-block"><?php echo lang('slug_exp'); ?></p>
        </div>
    </div>
    
          <div class="form-group">
            <?php echo form_label(lang('forums_parent'), 'category_parent', array('class' => 'col-md-2 control-label')); ?>
            <div class="col-md-10">
              <select id="category_parent" name="category_parent">
                <option <?php if ($category->id == 0): ?>selected="selected"<?php endif; ?> value="0"><?php echo lang('forums_parent_none'); ?></option>  
                <?php foreach ($categories as $cat): ?>
                <?php if ($category->id != $cat['id']): ?>
                <option <?php if ($category->parent_id == $cat['id']): ?>selected="selected"<?php endif; ?> value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                <?php else: ?>
                <option disabled value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          
        <div class="form-group">
          <div class="col-md-offset-2 col-md-10">
              <?php echo form_submit(array('name' => 'category-edit', 'class' => 'btn btn-primary'), lang('forums_category_edit')); ?>
          </div>
        </div>
    <?php
    echo form_fieldset_close() . form_close();
    ?>
</div>