<div class="col-md-12">  
    <div class="page-header">
        <h1><?php echo lang('forums_threads_in_cat') . $category->name . anchor('admin/manage_forums/category_view', lang('forums_category_list_back'), array('class' => 'btn btn-default pull-right')); ?></h1>
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
            thread_id = $(this).attr('id').replace("thread_id", "");
            $('#modalConfirm').modal('show');
            return false;
        });
        
        $('#btn-delete').click(function() {
            window.location = '<?php echo site_url('admin/manage_forums/thread_delete'); ?>/'+thread_id;
        });
    })
    </script>
    <div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="modalConfirmLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="modalConfirmLabel"><?php echo lang('forums_threads_delete'); ?></h3>
                </div>
                <div class="modal-body text-center">
                    <p class="lead"><?php echo lang('forums_threads_delete_confirm'); ?></p>
                    <br/>
                    <p class="text-warning"><?php echo lang('forums_threads_delete_warning'); ?></p>
                </div>
                <div class="modal-footer text-center">
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?php echo lang('website_cancel'); ?></a>
                    <a href="#" class="btn btn-primary" id="btn-delete"><?php echo lang('forums_threads_delete'); ?></a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    $(function() {
        $('.linkviewtip').tooltip();
    });
    </script>
    <table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th width="5%" class="text-center">#</th>
            <th width="75%"><?php echo lang('forums_threads'); ?></th>
            <th width="10%"></th>
            <th width="10%"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($threads as $key => $thread): ?>
        <tr>
        <td class="text-center"><?php echo $key + 1 + $start; ?></td>
        <td>
            <a class="linkviewtip" title="Go to: <?php echo $thread->title; ?>" href="<?php echo site_url('forums/thread/'.$thread->slug); ?>"><?php echo $thread->title; ?></a>
        </td>
        <td class="text-center"><a title="edit" href="<?php echo site_url('admin/manage_forums/thread_edit/'.$thread->id); ?>"><span class="glyphicon glyphicon-pencil"></span></a> </td>
        <td class="text-center"><a title="delete" class="del" id="thread_id<?php echo $thread->id; ?>" href="<?php echo site_url('admin/manage_forums/thread_delete/'.$thread->id); ?>"><span class="glyphicon glyphicon-remove-sign"></span></a> </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
       
    <div class="pagination text-center">
        <ul><?php echo $page; ?></ul>
    </div>
</div>