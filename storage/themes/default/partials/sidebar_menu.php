<ul class="sidebar-nav">
    <li class="sidebar-item active">
        <a class="sidebar-link" href="<?php echo route('dashboard') ?>">
            <i class="align-middle" data-feather="sliders"></i> <span class="align-middle"><?php ee('Dashboard') ?></span>
        </a>
    </li>
    <?php plug('usermenu.top') ?>

    <li class="sidebar-item">
        <a class="sidebar-link" href="<?php echo route('user.stats') ?>">
            <i class="align-middle" data-feather="bar-chart"></i> <span class="align-middle"><?php ee('Statistics') ?></span>
        </a>
    </li>
    <li class="sidebar-header"><?php ee('Link Management') ?></li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="<?php echo route('links') ?>">
            <i class="align-middle" data-feather="link"></i> <span class="align-middle"><?php ee('Links') ?> </span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="<?php echo route('expired') ?>">
            <i class="align-middle" data-feather="calendar"></i> <span class="align-middle"><?php ee('Expired Links') ?></span>
        </a>
    </li>    
    <?php plug('usermenu.medium') ?>      
    <?php if($user->has('domain')): ?>
    <li class="sidebar-item">
        <a class="sidebar-link" href="<?php echo route('domain') ?>">
            <i class="align-middle" data-feather="globe"></i> <span class="align-middle"><?php ee('Branded Domains') ?></span>
        </a>
    </li>      
    <?php endif ?>     
</ul>