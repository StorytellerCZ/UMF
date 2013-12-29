<div class="span2"></div>
<div class="span6">
    <div class="page-header">
        <h3 style="text-align:center;">Edit Thread</h3>
    </div>
    <?php if (isset($tmp_success)): ?>
    <div class="alert alert-success">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        <h4 class="alert-heading">User created!</h4>
    </div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
    <div class="alert alert-error">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        <h4 class="alert-heading">Error!</h4>
        <?php if (isset($error['title'])): ?>
            <div>- <?php echo $error['title']; ?></div>
        <?php endif; ?>
        <?php if (isset($error['slug'])): ?>
            <div>- <?php echo $error['slug']; ?></div>
        <?php endif; ?>  
        <?php if (isset($error['category'])): ?>
            <div>- <?php echo $error['category']; ?></div>
        <?php endif; ?>  
        <?php if (isset($error['post'])): ?>
            <div>- <?php echo $error['post']; ?></div>
        <?php endif; ?>  
    </div>
    <?php endif; ?>
    <form class="well" action="" method="post" style="margin: 5px 10px;">
    <input type="hidden" name="row[id]" value="<?php echo $thread->id; ?>"/>
    <script>
    $(function() {
        $('#title').change(function() {
            var title = $('#title').val().toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
            $('#slug').val(title);
        });
    });
    </script>
    <label>Title</label>
    <input type="text" id="title" name="row[title]" value="<?php echo $thread->title; ?>" class="span12" placeholder="">
    <label>Slug (url friendly)</label>
    <input type="text" id="slug" name="row[slug]" title="<?php echo site_url('thread/talk/'.$thread->slug); ?>" value="<?php echo $thread->slug; ?>" disabled="disabled" class="span12 disabled" placeholder="">
    <label>Category</label>
    <select class="span12" name="row[category_id]">
        <option value="0">-- none --</option>  
        <?php foreach ($categories as $cat): ?>
        <?php if ($cat['id'] == $thread->category_id): ?>
            <option selected="selected" value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
        <?php endif; ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" style="margin-top:15px;font-weight: bold;" name="btn-save" class="btn btn-primary btn-large" value="Save Thread"/>
    </form>
</div>
<div class="span2"></div>