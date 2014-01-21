<div class="col-lg-12">  
    <div class="page-header">
        <h1><?php echo lang('forums_list_categories'); ?></h1>
    </div>
    <script>
    $(function() {
        $('#modalConfirm').modal({
            keyboard: true,
            backdrop: true,
            show: false
        });
        
        var cat_id;
        
        $('.del').click(function() {
            cat_id = $(this).attr('id').replace("cat_id_", "");
            $('#modalConfirm').modal('show');
            return false;
        });
        
        $('#btn-delete').click(function() {
            window.location = '<?php echo site_url('admin/manage_forums/category_delete'); ?>/'+cat_id;
        });
    })
    </script>
    <div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="modalConfirm" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     <h4 class="modal-title" id="modalConfirmLabel"><?php echo lang('forums_category_delete'); ?></h4>
                </div>
                <div class="modal-body">
                    <p class="text-center"><?php echo lang('forums_category_delete_confirm') ?></p>
                </div>
                <div class="modal-footer" class="text-center">
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?php echo lang('website_cancel'); ?></a>
                    <a href="#" class="btn btn-primary" id="btn-delete"><?php echo lang('website_delete'); ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($tmp_success_del)): ?>
    <div class="alert alert-info">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        <h4 class="alert-heading"><?php echo lang('forums_category_deleted'); ?></h4>
    </div>
    <?php endif; ?>
    <table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th width="38%"><?php echo lang('forums_category'); ?></th>
            <th width="38%"><?php echo lang('forums_slug'); ?></th>
            <th width="12%"></th>
            <th width="12%"><a title="create" href="admin/manage_forums/category_create" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></a></th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($categories) == 0){
        echo '<tr><td colspan="4" class="text-center">'. lang('forums_category_none') . anchor('admin/manage_forums/category_create', lang('forums_category_create'), array('class' => 'btn btn-success')) .'</td></tr>';
        }else{
        foreach ($categories as $cat){ ?>
        <tr>
        <td><?php echo anchor(site_url('admin/manage_forums/thread_view/'.$cat['id']), $cat['name'], array()); ?></td>
        <td><?php echo $cat['slug']; ?></td>
        <td class="text-center"><?php echo anchor(site_url('admin/manage_forums/category_edit/'.$cat['id']), '<span class="glyphicon glyphicon-pencil"></span>');?></td>
        <td class="text-center"><a title="delete" class="del" id="cat_id_<?php echo $cat['id']; ?>" href="<?php echo site_url('admin/manage_forums/category_delete/'.$cat['id']); ?>"><span class="glyphicon glyphicon-trash"></span> </td>
        </tr>
        <?php }
        }
        ?>
    </tbody>
    </table>
</div>