<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo row text-center mt-3">
      <div class="col-12" style="margin-left: 41px;margin-top :-5px;">
        @php
        $settings=App\Models\SiteSetting::pluck('value','name');
        @endphp
        <a href="{{url('admin/dashboard')}}" class="app-brand-link">
            <span class="app-brand-logo demo text-center pl-5">
            @if(!empty($settings['business_logo1']))
           <img width="80" height="40" src="{{ url('/images/logo').'/'.$settings['business_logo1'] ?? "" }}" alt="logo">
           @endif
            </span>
            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">Unify</span> -->
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>

      </div>

       <div class="col-12" style="margin-bottom :-68px;">
        <div class="app-sidebar__user">
            <div class="dropdown user-pro-body text-center">
                <div class="user-pic mb-4">
                    @php
                    $userprofile_image = Auth::user()->profileimage;
                    @endphp
                    @if(isset($userprofile_image) && $userprofile_image != null)
                        <img width="50px" src="{{ url('/profileimage').'/'.$userprofile_image ?? '' }}" alt="user-img" class="avatar-xl rounded-circle ">
                    @else
                        <img width="50px" src="{{ URL::asset('admin/assets/img/avatars/1.png') }}" alt="user-img" class="avatar-xl rounded-circle ">
                    @endif
                    
                </div>
                <div class="user-info >
                    <h6 class="mb-0">{{ ucfirst(Auth::user()->name) }}</h6>
                    
                </div>
                <hr class="mt-2">
            </div>
        </div>
       </div>
        
    </div>

    

    

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->is('admin/dashboard') || request()->is('admin/dashboard/*') ? 'active' : '' }}">
            <a href="{{url('admin/dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>


        <!-- Layouts -->
        @can('project_access')
        <li class="menu-item {{ request()->is('admin/projects*') ? 'open' : '' }} {{ request()->is('admin/jobs*') ? 'open' : '' }}{{ request()->is('admin/proposal*') ? 'open' : '' }}{{ request()->is('admin/project-category*') ? 'open' : '' }} {{ request()->is('admin/project-skill*') ? 'open' : '' }} {{ request()->is('admin/project-listing-type*') ? 'open' : '' }} ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon fa  fa-briefcase "></i>
                <div data-i18n="Account Settings">Projects</div>
            </a>

            <ul class="menu-sub">
                @can('project_access')
                <li class="menu-item {{ request()->is('admin/projects') || request()->is('admin/projects/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.projects.index") }}" class="menu-link">
                        <div data-i18n="Account">All Projects</div>
                    </a>
                </li>
                @endcan
                @can('project_access')
                <li class="menu-item {{ request()->is('admin/jobs') || request()->is('admin/jobs/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.jobs.index") }}" class="menu-link">
                        <div data-i18n="Account">Contracts</div>
                    </a>
                </li>
                @endcan
                @can('project_category_access')
                <li class="menu-item {{ request()->is('admin/project-category') || request()->is('admin/project-category/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.project-category.index") }}" class="menu-link">
                        <div data-i18n="Notifications">Categories</div>
                    </a>
                </li>
                @endcan
               

                @can('project_skills_access')
                <li class="menu-item  {{ request()->is('admin/project-skill') || request()->is('admin/project-skill/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.project-skill.index") }}" class="menu-link">
                        <div data-i18n="Connections">Skills</div>
                    </a>
                </li>
                @endcan

                @can('project_listing_type_access')
                <li class="menu-item {{ request()->is('admin/project-listing-type') || request()->is('admin/project-listing-type/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.project-listing-type.index") }}" class="menu-link">
                        <div data-i18n="Connections">Listing Types</div>
                    </a>
                </li>
                @endcan

               

            </ul>
        </li>
        @endcan
    
        <li class="menu-item {{ request()->is('admin/service*') ? 'open' : '' }}{{ request()->is('admin/plan*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon fa  fa-money "></i>
                <div data-i18n="Account Settings">Subscriptions </div>
            </a>

            <ul class="menu-sub">
              
                <li class="menu-item {{ request()->is('admin/service') || request()->is('admin/service/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.service.index") }}" class="menu-link">
                        <div data-i18n="Account">Services</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('admin/plan') || request()->is('admin/plan/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.plan.index") }}" class="menu-link">
                        <div data-i18n="Account">Plans</div>
                         </a>
                </li>
             
                
              

            </ul>
        </li>
        @can('notification_access')
        @php
        $notification= App\Models\Notification::where('status','0')->count();
        @endphp
        <li class="menu-item {{ request()->is('admin/notification') || request()->is('admin/notification/*') ? 'active' : '' }}">
            <a href="{{ url("admin/notification") }}" class="menu-link">
                <i class="menu-icon fa fa-bell-o"></i>
                <div data-i18n="Analytics notification">Notifications  @if($notification>0) ({{$notification}}) @endif</div>
              
            </a>
        </li>
        @endcan
        
        @can('document_access')
        <li class="menu-item {{ request()->is('admin/documents') || request()->is('admin/documents/*') ? 'active' : '' }}">
            <a href="{{ route("admin.documents.index") }}" class="menu-link">
                <i class="menu-icon fa fa-file"></i>
                <div data-i18n="Analytics">Documents</div>
            </a>
        </li>
        @endcan
        @can('transaction_access')
        <li class="menu-item {{ request()->is('admin/transactions') || request()->is('admin/transactions/*') ? 'active' : '' }}">
            <a href="{{ route("admin.transactions.index") }}" class="menu-link">
                <i class="menu-icon fa fa-usd"></i>
                <div data-i18n="Analytics">Transactions</div>
            </a>
        </li>
        @endcan
        @can('client_report_access')
        <!-- <li class="menu-item {{ request()->is('admin/client-reports') || request()->is('admin/client-reports/*') ? 'active' : '' }}">
            <a href="{{ route("admin.client-reports.index") }}" class="menu-link">
                <i class="menu-icon fa fa-line-chart"></i>
                <div data-i18n="Analytics">Reports</div>
            </a>
        </li> -->
        @endcan
        @can('user_management_access')
        <li class="menu-item {{ request()->is('admin/clients') || request()->is('admin/clients/*') ? 'active' : '' }}">
            <a href="{{ route("admin.clients.index") }}" class="menu-link">
                <i class="menu-icon fa fa-user"></i>
                <div data-i18n="Analytics">Clients</div>
            </a>
        </li>
        @endcan
        @can('user_management_access')
        <li class="menu-item {{ request()->is('admin/freelancer') || request()->is('admin/freelancer/*') ? 'active' : '' }}">
            <a href="{{ route("admin.freelancer.index") }}" class="menu-link">
                <i class="menu-icon fa fa-user-circle"></i>
                <div data-i18n="Analytics">Freelancers</div>
            </a>
        </li>
        @endcan
        @can('user_management_access')
        <li class="menu-item {{ request()->is('admin/users*') ? 'open' : '' }} {{ request()->is('admin/permissions*') ? 'open' : '' }} {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon fa fa-users" aria-hidden="true"></i>
                <div data-i18n="Layouts">User Management</div>
            </a>

            <ul class="menu-sub ">
                @can('permission_access')
                <li class="menu-item {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.permissions.index") }}" class="menu-link">
                        <div data-i18n="Without menu">Permissions</div>
                    </a>
                </li>
                @endcan
            </ul>
            @can('role_access')
            <ul class="menu-sub {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                <li class="menu-item {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.roles.index") }}" class="menu-link ">
                        <div data-i18n="Without menu">Roles</div>
                    </a>
                </li>
              @endcan
            </ul>
            <ul class="menu-sub ">
                @can('user_access')
                <li class="menu-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <a href="{{ route("admin.users.index") }}" class="menu-link">
                        <div data-i18n="Without menu">All Users</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan
        @can('support')
        <li class="menu-item {{ request()->is('admin/support') || request()->is('admin/support/*') ? 'active' : '' }}">
            <a href="{{ route("admin.support.index") }}" class="menu-link">
            <i class="menu-icon fa  fa-mobile "></i>
                <div data-i18n="Analytics">Support</div>
            </a>
        </li>
        @endcan
        @can('client_management_setting_access')
        <li class="menu-item {{ request()->is('admin/project-statuses*') ? 'open' : '' }} {{ request()->is('admin/business_size') ? 'open' : '' }} {{ request()->is('admin/mail*') ? 'open' : '' }} {{ request()->is('admin/site-settings') ? 'open' : '' }}" >
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon fa  fa-cog "></i>
                <div data-i18n="Account Settings">Settings</div>
            </a>
            <ul class="menu-sub">

                @can('site_setting_access')
                <li class="menu-item {{ request()->is('admin/site-setting') || request()->is('admin/site-setting/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.site-setting.index") }}" class="menu-link">
                        <div data-i18n="Account">Site Settings</div>
                    </a>
                </li>
                @endcan
                @can('mail_setting_acccess')
                <li class="menu-item {{ request()->is('admin/mail') || request()->is('admin/mail/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.mail.index") }}" class="menu-link">
                        <div data-i18n="Notifications">Mail Settings</div>
                    </a>
                </li>
                @endcan
                @can('business_acccess')
                <li class="menu-item {{ request()->is('admin/business_size') || request()->is('admin/business_size/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.business_size.index") }}" class="menu-link">
                        <div data-i18n="Notifications">Business Size</div>
                    </a>
                </li>
                @endcan
                @can('project_status_access')
                <li class="menu-item {{ request()->is('admin/project-statuses') || request()->is('admin/project-statuses/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.project-statuses.index") }}" class="menu-link">
                        <div data-i18n="Connections">Edit Status</div>
                    </a>
                </li>
                @endcan
                <li class="menu-item {{ request()->is('admin/close-reason') || request()->is('admin/close-reason/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.close-reason.index") }}" class="menu-link">
                        <div data-i18n="Connections">Account Close Reason</div>
                    </a>
                </li>
            </ul>
        </li>
        @endcan
        <li class="menu-item ">
            <a  class="menu-link"   href="{{url('/admin/logout')}}">
                <i class="menu-icon fa fa-sign-out"></i>
                <div data-i18n="Analytics">Logout</div>
            </a>
        </li>
        


    </ul>
</aside>
<style>
    span.unseen_notification {
    border: 1px solid #d1c2c2;
    border-radius: 24px;
    font-size: 12px;
    padding: 0px 5px 0px 5px;
    margin: -3px -4px 6px -6px;
    color: #000;
    background-color: #696cff;
}
</style>
