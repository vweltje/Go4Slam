<?php $user = $this->ion_auth->user()->row(); ?>

<header>
    <div id="banner">
        <div class="container">
            <a href="<?=base_url('analytics')?>"><img src="<?=base_url('resources/svg/logo.svg')?>" alt="go4slam logo" name="go4slam logo" width="150"></a>
            <div id="banner-actions">
                <p>Hello <?=$user->first_name.' '.($user->prefix ? $user->prefix.' ' : '').$user->last_name?></p>
                <a href="<?=base_url()?>"><i class="fa fa-cog"></i> Settings</a>
                <a href="<?=base_url('user/logout')?>"><i class="fa fa-sign-out"></i> Logout</a>
            </div>
        </div>
    </div>
    <nav>
        <div class="container">
            <a href="<?=base_url('analytics')?>"><i class="fa fa-pie-chart"></i> Analytics</a>
            <a href="<?=base_url('content')?>"><i class="fa fa-newspaper-o"></i> Content Items</a> <!--<i class="fa fa-inbox"></i><i class="fa fa-newspaper-o"></i>-->
            <a href="<?=base_url('sponsors')?>"><i class="fa fa-money"></i> Sponsors</a> <!--<i class="fa fa-credit-card-alt"></i>-->
            <a href="<?=base_url('about')?>"><i class="fa fa-info"></i> About Go4Slam</a>
            <a href="<?=base_url('app_users')?>"><i class="fa fa-mobile"></i> App Users</a>
            <a href="<?=base_url('cms_users')?>"><i class="fa fa-user"></i> CMS Users</a>
        </div>
    </nav>
</header>