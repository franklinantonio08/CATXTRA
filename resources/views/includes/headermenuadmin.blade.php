<div class="wrapper d-flex flex-column min-vh-100 bg-light bg-opacity-50 dark:bg-transparent">
    <header class="header header-sticky mb-4">
    <div class="container-fluid">
    <button class="header-toggler px-md-0 me-md-3 d-md-none" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
    <svg class="icon icon-lg">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
    </svg>
    </button><a class="header-brand d-md-none" href="#">
    <svg width="118" height="46" alt="CoreUI Logo">
    <use xlink:href="{{asset('brand/coreui.svg#full') }}"></use>
    </svg></a>
    <form class="d-flex" role="search">
    <div class="input-group"><span class="input-group-text bg-light border-0 px-1" id="search-addon">
    <svg class="icon icon-lg my-1 mx-2 text-disabled">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-search') }}"></use>
    </svg></span>
    <input class="form-control bg-light border-0" type="text" placeholder="Search..." aria-label="Search" aria-describedby="search-addon">
    </div>
    </form>
    <ul class="header-nav ms-auto me-3">
    <li class="nav-item dropdown d-md-down-none"><a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="d-inline-block my-1 mx-2 position-relative">
    <svg class="icon icon-lg">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-bell') }}"></use>
    </svg><span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle"><span class="visually-hidden">New alerts</span></span></span></a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg pt-0">
    <div class="dropdown-header bg-light dark:bg-white dark:bg-opacity-10"><strong>You have 5 notifications</strong></div><a class="dropdown-item" href="#">
    <svg class="icon me-2 text-success">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-user-follow') }}"></use>
    </svg> New user registered</a><a class="dropdown-item" href="#">
    <svg class="icon me-2 text-danger">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-user-unfollow') }}"></use>
    </svg> User deleted</a><a class="dropdown-item" href="#">
    <svg class="icon me-2 text-info">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-chart') }}"></use>
    </svg> Sales report is ready</a><a class="dropdown-item" href="#">
    <svg class="icon me-2 text-success">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-basket') }}"></use>
    </svg> New client</a><a class="dropdown-item" href="#">
    <svg class="icon me-2 text-warning">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
    </svg> Server overloaded</a>
    <div class="dropdown-header bg-light dark:bg-white dark:bg-opacity-10"><strong>Server</strong></div><a class="dropdown-item d-block" href="#">
    <div class="text-uppercase mb-1"><small><b>CPU Usage</b></small></div><span class="progress progress-thin">
    <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
    </span><small class="text-medium-emphasis">348 Processes. 1/4 Cores.</small>
    </a><a class="dropdown-item d-block" href="#">
    <div class="text-uppercase mb-1"><small><b>Memory Usage</b></small></div><span class="progress progress-thin">
    <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
    </span><small class="text-medium-emphasis">11444GB/16384MB</small>
    </a><a class="dropdown-item d-block" href="#">
    <div class="text-uppercase mb-1"><small><b>SSD 1 Usage</b></small></div><span class="progress progress-thin">
    <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
    </span><small class="text-medium-emphasis">243GB/256GB</small>
    </a>
    </div>
    </li>
    <li class="nav-item dropdown d-md-down-none"><a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="d-inline-block my-1 mx-2 position-relative">
    <svg class="icon icon-lg">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-list-rich') }}"></use>
    </svg><span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle"><span class="visually-hidden">New alerts</span></span></span></a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg py-0">
    <div class="dropdown-header bg-light fw-semibold dark:bg-white dark:bg-opacity-10">You have 5 pending tasks</div><a class="dropdown-item d-block" href="#">
    <div class="small mb-1">Upgrade NPM &amp; Bower<span class="float-end"><strong>0%</strong></span></div><span class="progress progress-thin">
    <div class="progress-bar bg-info" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </span>
    </a><a class="dropdown-item d-block" href="#">
    <div class="small mb-1">ReactJS Version<span class="float-end"><strong>25%</strong></span></div><span class="progress progress-thin">
    <div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
    </span>
    </a><a class="dropdown-item d-block" href="#">
    <div class="small mb-1">VueJS Version<span class="float-end"><strong>50%</strong></span></div><span class="progress progress-thin">
    <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
    </span>
    </a><a class="dropdown-item d-block" href="#">
    <div class="small mb-1">Add new layouts<span class="float-end"><strong>75%</strong></span></div><span class="progress progress-thin">
    <div class="progress-bar bg-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
    </span>
    </a><a class="dropdown-item d-block" href="#">
    <div class="small mb-1">Angular 8 Version<span class="float-end"><strong>100%</strong></span></div><span class="progress progress-thin">
    <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
    </span>
    </a>
    <div class="p-2"><a class="btn btn-outline-primary w-100" href="#">View all tasks</a></div>
    </div>
    </li>
    <li class="nav-item dropdown d-md-down-none"><a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="d-inline-block my-1 mx-2 position-relative">
    <svg class="icon icon-lg">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-envelope-open') }}"></use>
    </svg><span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle"><span class="visually-hidden">New alerts</span></span></span></a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg pt-0">
    <div class="dropdown-header bg-light dark:bg-white dark:bg-opacity-10"><strong>You have 4 messages</strong></div><a class="dropdown-item" href="#">
    <div class="message">
    <div class="py-3 me-3 float-start">
    <div class="avatar"><img class="avatar-img" src="{{ asset('images/7.jpeg') }}" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
    </div>
    <div><small class="text-medium-emphasis">John Doe</small><small class="text-medium-emphasis float-end mt-1">Just now</small></div>
    <div class="text-truncate font-weight-bold"><span class="text-danger">!</span> Important message</div>
    <div class="small text-medium-emphasis text-truncate">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</div>
    </div>
    </a><a class="dropdown-item" href="#">
    <div class="message">
    <div class="py-3 me-3 float-start">
    <div class="avatar"><img class="avatar-img" src="{{ asset('images/6.jpeg') }}" alt="user@email.com"><span class="avatar-status bg-warning"></span></div>
    </div>
    <div><small class="text-medium-emphasis">John Doe</small><small class="text-medium-emphasis float-end mt-1">5 minutes ago</small></div>
    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
    <div class="small text-medium-emphasis text-truncate">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</div>
    </div>
    </a><a class="dropdown-item" href="#">
    <div class="message">
    <div class="py-3 me-3 float-start">
    <div class="avatar"><img class="avatar-img" src="{{ asset('images/5.jpeg') }}" alt="user@email.com"><span class="avatar-status bg-danger"></span></div>
    </div>
    <div><small class="text-medium-emphasis">John Doe</small><small class="text-medium-emphasis float-end mt-1">1:52 PM</small></div>
    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
    <div class="small text-medium-emphasis text-truncate">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</div>
    </div>
    </a><a class="dropdown-item" href="#">
    <div class="message">
    <div class="py-3 me-3 float-start">
    <div class="avatar"><img class="avatar-img" src="{{ asset('images/4.jpeg') }}" alt="user@email.com"><span class="avatar-status bg-info"></span></div>
    </div>
    <div><small class="text-medium-emphasis">John Doe</small><small class="text-medium-emphasis float-end mt-1">4:03 PM</small></div>
    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
    <div class="small text-medium-emphasis text-truncate">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</div>
    </div>
    </a><a class="dropdown-item text-center border-top" href="#"><strong>View all messages</strong></a>
    </div>
    </li>
    </ul>
    <ul class="header-nav me-4">
    <li class="nav-item dropdown d-flex align-items-center"><a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
    <div class="avatar avatar-md"><img class="avatar-img" src="{{ asset('images/8.jpeg') }}" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
    </a>
    <div class="dropdown-menu dropdown-menu-end pt-0">
    <div class="dropdown-header bg-light py-2 dark:bg-white dark:bg-opacity-10">
    <div class="fw-semibold">Account</div>
    </div><a class="dropdown-item" href="#">
    <svg class="icon me-2">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-bell') }}"></use>
    </svg> Updates<span class="badge badge-sm bg-info-gradient ms-2">42</span></a><a class="dropdown-item" href="#">
    <svg class="icon me-2">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-envelope-open') }}"></use>
    </svg> Messages<span class="badge badge-sm badge-sm bg-success ms-2">42</span></a><a class="dropdown-item" href="#">
    <svg class="icon me-2">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-task') }}"></use>
    </svg> Tasks<span class="badge badge-sm bg-danger-gradient ms-2">42</span></a><a class="dropdown-item" href="#">
    <svg class="icon me-2">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-comment-square') }}"></use>
    </svg> Comments<span class="badge badge-sm bg-warning-gradient ms-2">42</span></a>
    <div class="dropdown-header bg-light py-2 dark:bg-white dark:bg-opacity-10">
    <div class="fw-semibold">Settings</div>
    </div><a class="dropdown-item" href="#">
    <svg class="icon me-2">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
    </svg> Profile</a><a class="dropdown-item" href="#">
    <svg class="icon me-2">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-settings') }}"></use>
    </svg> Settings</a><a class="dropdown-item" href="#">
    <svg class="icon me-2">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-credit-card') }}"></use>
    </svg> Payments<span class="badge badge-sm bg-secondary-gradient text-dark ms-2">42</span></a><a class="dropdown-item" href="#">
    <svg class="icon me-2">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-file') }}"></use>
     </svg> Projects<span class="badge badge-sm bg-primary-gradient ms-2">42</span></a>
    <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
    <svg class="icon me-2">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use>
    </svg> Lock Account</a><a class="dropdown-item" href="#">
    <svg class="icon me-2">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}"></use>
    </svg> Logout</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();">
            {{ __('Log Out') }}
        </x-dropdown-link>
    </form>
    </div>
    </li>
    </ul>
    <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#aside')).show()">
    <svg class="icon icon-lg">
    <use xlink:href="{{ asset('../vendors/@coreui/icons/svg/free.svg#cil-applications-settings') }}"></use>
    </svg>
    </button>
    </div>
    </header>
