<ul class="sidebar-nav">
    <li class="sidebar-item active">
        <a class="sidebar-link" href="<?php echo route('admin') ?>">
            <i class="align-middle" data-feather="sliders"></i> <span class="align-middle"><?php ee('Dashboard') ?></span>
        </a>
    </li>    
    <li class="sidebar-item">
        <a class="sidebar-link collapsed" data-bs-target="#nav-urls" data-bs-toggle="collapse">
            <i class="align-middle" data-feather="link"></i> <span class="align-middle"><?php ee('Links') ?></span>
        </a>
        <ul id="nav-urls" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.links') ?>"><?php ee('All Links') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.links.expired') ?>"><?php ee('Expired Links') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.links.archived') ?>"><?php ee('Archived Links') ?></a></li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="<?php echo route('admin.links.report') ?>"><?php ee('Reported Links') ?>
                    <?php if($notifications['data']['reports']['count']): ?>
                        <span class="sidebar-badge badge bg-primary"><?php echo $notifications['data']['reports']['count'] ?></span>
                    <?php endif ?>
                </a>        
            </li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.links.pending') ?>"><?php ee('Pending Links') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.links.import') ?>"><?php ee('Import Links') ?></a></li>
        </ul>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link collapsed" data-bs-target="#nav-users" data-bs-toggle="collapse">
            <i class="align-middle" data-feather="users"></i> <span class="align-middle"><?php ee('Users') ?></span>
        </a>
        <ul id="nav-users" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.users.new') ?>"><?php ee('Add User') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.users') ?>"><?php ee('All Users') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.users.inactive') ?>"><?php ee('Inactive Users') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.users.banned') ?>"><?php ee('Banned Users') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.users.admin') ?>"><?php ee('Admin Users') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.testimonial') ?>"><?php ee('Testimonials') ?></a></li>
        </ul>
    </li>   
    <li class="sidebar-item">
        <a class="sidebar-link collapsed" data-bs-target="#nav-pages" data-bs-toggle="collapse">
            <i class="align-middle" data-feather="file-text"></i> <span class="align-middle"><?php ee('Pages') ?></span>
        </a>
        <ul id="nav-pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.page.new') ?>"><?php ee('Add Page') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.page') ?>"><?php ee('All Pages') ?></a></li>
        </ul>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link collapsed" data-bs-target="#nav-domain" data-bs-toggle="collapse">
            <i class="align-middle" data-feather="globe"></i> <span class="align-middle"><?php ee('Domains') ?></span>
        </a>
        <ul id="nav-domain" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.domains.new') ?>"><?php ee('Add Domain') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.domains') ?>"><?php ee('All Domains') ?></a></li>
        </ul>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link collapsed" href="<?php echo route('admin.ads') ?>">
            <i class="align-middle" data-feather="dollar-sign"></i> <span class="align-middle"><?php ee('Advertisement') ?></span>
        </a>
    </li>   
    <li class="sidebar-item">
        <a class="sidebar-link collapsed" data-bs-target="#nav-setting" data-bs-toggle="collapse">
            <i class="align-middle" data-feather="settings"></i> <span class="align-middle"><?php ee('Settings') ?></span>
        </a>
        <ul id="nav-setting" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.settings') ?>"><?php ee('General Settings') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.settings.config', ['app']) ?>"><?php ee('Application Settings') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.settings.config', ['link']) ?>"><?php ee('Link Settings') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.settings.config', ['advanced']) ?>"><?php ee('Advanced Settings') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.settings.config', ['theme']) ?>"><?php ee('Themes Settings') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.settings.config', ['security']) ?>"><?php ee('Security Settings') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.settings.config', ['membership']) ?>"><?php ee('Membership Settings') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.settings.config', ['payments']) ?>"><?php ee('Payment Gateway') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.settings.config', ['users']) ?>"><?php ee('Users Settings') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.settings.config', ['mail']) ?>"><?php ee('Mail Settings') ?></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="<?php echo route('admin.settings.config', ['integrations']) ?>"><?php ee('Integrations') ?></a></li>
        </ul>
    </li>    
    <li class="sidebar-item">
        <a class="sidebar-link" href="<?php echo route('admin.stats') ?>">
            <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle"><?php ee('Statistics') ?></span>
        </a>
    </li>
</ul>