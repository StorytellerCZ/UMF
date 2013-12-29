<div class="col-md-12">  
    <div class="page-header">
        <h1><?php echo lang('forums_threads_in_cat') . $category->name; ?></h1>
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
            window.location = '<?php echo site_url('admin/thread_delete'); ?>/'+thread_id;
        });
    })
    </script>
    <div class="modal hide" id="modalConfirm">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3><?php echo lang('forums_threads_delete'); ?></h3>
        </div>
        <div class="modal-body text-center">
        <p>
            <?php echo lang('forums_threads_delete_confirm'); ?>
            <br/>
            <span style="font-weight: bold;color:#ff0000;font-size: 14px;"><?php echo lang('forums_threads_delete_warning'); ?><span>
        </p>
        </div>
        <div class="modal-footer text-center">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancel</a>
            <a href="#" class="btn btn-primary" id="btn-delete">Delete</a>
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
            <th width="5%" class="text-center">No</th>
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
        <td class="text-center"><a title="edit" href="<?php echo site_url('admin/manage_forums/thread_edit').'/'.$thread->id; ?>"><img src="<?php echo base_url(); ?>resources/icons/pencil.png"/></a> </td>
        <td class="text-center"><a title="delete" class="del" id="thread_id<?php echo $thread->id; ?>" href="<?php echo site_url('admin/thread_delete').'/'.$thread->id; ?>"><img src="<?php echo base_url(); ?>resources/icons/delete.png"/></a> </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
       
    <div class="pagination text-center">
        <ul><?php echo $page; ?></ul>
    </div>
</div>