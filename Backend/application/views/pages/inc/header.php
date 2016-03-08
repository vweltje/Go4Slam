<?php $user = $this->ion_auth->user()->row(); ?>

<header>
    <div id="banner">
        <div class="container">
            <a href="<?=base_url()?>analytics"><img src="<?=base_url()?>resources/svg/logo.svg" alt="go4slam logo" name="go4slam logo" width="150"></a>
            <div id="banner-actions">
                <p>Hello <?=$user->first_name.' '.($user->prefix ? $user->prefix.' ' : '').$user->last_name?></p>
                <a href="<?=base_url()?>"><i class="fa fa-cog"></i> Settings</a>
                <a href="<?=base_url()?>user/logout"><i class="fa fa-sign-out"></i> Logout</a>
            </div>
        </div>
    </div>
    <nav>
        <div class="container">
            <a href="<?=base_url('analytics')?>"><i class="fa fa-pie-chart"></i> Analytics</a>
        </div>
    </nav>
</header>