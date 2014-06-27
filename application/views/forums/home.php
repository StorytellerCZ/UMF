<div class="col-sm-12">
    <ul class="breadcrumb">
    <li>
        <a href="<?php echo site_url('thread'); ?>"><?php echo lang('website_forums'); ?></a>
    </li>
    </ul>

    <table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th></th>
            <th><?php echo lang('forums_categories'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($categories as $category): ?>
        <?php if($category['parent_id'] == 0): ?>
        <tr>
        <td>
            <span class="glyphicon glyphicon-folder-close"></span>
        </td>
        <td>
            <a href="<?php echo site_url('forums/category/'.$category['slug']); ?>"><?php echo $category['name']; ?></a>
        </td>
        </tr>
        <!-- now show all sub-categories - currently it can do only up to one sub category -->
        <?php foreach($categories as $sub_category): ?>
        <?php if($sub_category['parent_id'] == $category['id']): ?>
        <tr>
        <td>
            |--<span class="glyphicon glyphicon-folder-open"></span>
        </td>
        <td>
            <a href="<?php echo site_url('forums/category/'.$sub_category['slug']); ?>"><?php echo $sub_category['name']; ?></a>
        </td>
        </tr>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
    </table>
<div>