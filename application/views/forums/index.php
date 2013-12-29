<div class="col-md-12">
    <ul class="breadcrumb">
    <?php if ($type == 'category'): ?>
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
    <?php else: ?>
    <li>
        <a href="<?php echo site_url('forums'); ?>"><?php echo lang('website_forums'); ?></a>
    </li>
    <?php endif; ?>
    </ul>
    <?php
    function time_ago($date) {

        if(empty($date)) {
            return "No date provided";
        }

        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");
        $now = time();
        $unix_date = strtotime($date);

        // check validity of date

        if(empty($unix_date)) {
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "ago";
        } else {
            $difference = $unix_date - $now;
            $tense = "from now";
        }
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        if($difference != 1) {
            $periods[$j].= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }
    ?>

    <table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th width="85%"><?php echo lang('forums_threads_all'); ?></th>
            <th width="15%"><?php echo lang('forums_last_update'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($threads as $thread): ?>
        <tr>
        <td>
            <a href="<?php echo site_url('forums/thread/'.$thread->slug); ?>"><?php echo $thread->title; ?></a>
        </td>
        <td style="font-size:12px;color:#999;vertical-align: middle;">
            <!-- <?php echo date("m/d/y g:i A", strtotime($thread->date_add)); ?> -->
            <?php echo time_ago($thread->date_add); ?>
        </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
    
    <?php echo anchor(base_url('forums/thread/create/'.$category->id), lang('forums_thread_start'), array('class' => 'btn btn-primary pull-right')); ?>
    
    <div class="pagination text-center">
        <ul><?php echo $page; ?></ul>
    </div>
<div>