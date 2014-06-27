<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    
    <title><?php echo isset($title) ? $title.' - '.lang('website_title') : lang('website_title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Account Authentication and Authorization">
    <meta name="author" content="A3M">
    
    <base href="<?php echo base_url(); ?>"/>
    
    <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico"/>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">window.jQuery || document.write('<script type="text/javascript" src="<?php echo base_url(RES_DIR .'js/jquery.min.js'); ?>"><\/script>')</script>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url().RES_DIR; ?>/bootstrap/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url().RES_DIR; ?>/css/style.css"/>
    <script src="<?php echo base_url().RES_DIR; ?>/bootstrap/js/bootstrap.min.js"></script>
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
                <?php echo anchor('', lang('website_title'), 'class="navbar-brand"'); ?>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="divider-vertical"></li>
                    <li><?php echo anchor('forums', lang('website_forums')); ?></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php if ($this->authentication->is_signed_in()) : ?>
                            <i class="glyphicon glyphicon-user"></i> <?php echo $account->username; ?> <b class="caret"></b></a>
                        <?php else : ?>
                            <i class="glyphicon glyphicon-user"></i> <b class="caret"></b></a>
                        <?php endif; ?>
                        <ul class="dropdown-menu">
                                <?php if ($this->authentication->is_signed_in()) : ?>
                            <li class="dropdown-header"><?php echo lang('website_account_info'); ?></li>
                                <li><?php echo anchor('account/settings', lang('website_account')); ?></li>
                                <?php if ($account->password) : ?>
                                        <li><?php echo anchor('account/password', lang('website_password')); ?></li>
                                <?php endif; ?>
                                <li><?php echo anchor('account/linked_accounts', lang('website_linked')); ?></li>
                                <?php if($this->authorization->is_permitted('msg_use')): ?>
                                <li><?php echo anchor('pm', lang('website_pm')); ?></li>
                                <?php endif; ?>
                            <?php if ($this->authorization->is_admin()) : ?>
                                <li class="divider"></li>
                                <li class="dropdown-header"><?php echo lang('website_admin_panel'); ?></li>
                                <?php if ($this->authorization->is_permitted(array('forums_categories_create', 'forums_categories_edit', 'forums_categories_delete'))) : ?>
                                    <li><?php echo anchor('admin/manage_forums/category_view', lang('website_manage_forums')); ?></li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted('retrieve_users')) : ?>
                                    <li><?php echo anchor('admin/manage_users', lang('website_manage_users')); ?></li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted('retrieve_roles')) : ?>
                                    <li><?php echo anchor('admin/manage_roles', lang('website_manage_roles')); ?></li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted('retrieve_permissions')) : ?>
                                    <li><?php echo anchor('admin/manage_permissions', lang('website_manage_permissions')); ?></li>
                                <?php endif; ?>
                            <?php endif; ?>
                                    <li class="divider"></li>
                                    <li><?php echo anchor('account/sign_out', lang('website_sign_out')); ?></li>
                            <?php else : ?>
                                    <li><?php echo anchor('account/sign_in', lang('website_sign_in')); ?></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>

            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>
    
    <div class="container">
        <div class="row">
            <!-- Any alert messages you want to display show up here -->
            <?php if($this->session->flashdata('message')): ?>
                <div class="alert
                <?php if($this->session->flashdata('message_type'))
                {
                    switch($this->session->flashdata('message_type'))
                    {
                        case 'danger':
                            echo 'alert-danger';
                            break;
                        case 'success':
                            echo 'alert-success';
                            break;
                        case 'info':
                            echo 'alert-info';
                            break;
                        case 'warning':
                            echo 'alert-warning';
                            break;
                        default:
                            echo 'alert-warning';
                            break;
                    }
                }
                else
                {
                    echo 'alert-warning';
                } ?>">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $this->session->flashdata('message'); ?>
                </div>
            <?php endif; ?>
            <div class="col-sm-12">
                <?php echo $content; ?>
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="well well-small">
                    <strong>
                        <small>Copyright &copy; <?php echo date('Y'); ?> <?php echo lang('website_title'); ?></small>
                    </strong>
                    <div class="pull-right">
                        <small>
                            <?php echo sprintf(lang('website_page_rendered_in_x_seconds'), $this->benchmark->elapsed_time()); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
