{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ __('sidebar.dashboard') }}</a></li>
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-globe"></i> {{ __('sidebar.translations') }}</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('language') }}"><i class="nav-icon la la-flag-checkered"></i> {{ __('sidebar.languages') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('language/texts') }}"><i class="nav-icon la la-language"></i> {{ __('sidebar.site_texts') }}</a></li>
    </ul>
</li>
<!-- Users, Roles, Permissions -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> {{ __('sidebar.authentication') }}</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>{{ __('sidebar.users') }}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>{{ __('sidebar.roles') }}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>{{ __('sidebar.permissions') }}</span></a></li>
    </ul>
</li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cog"></i> {{ __('sidebar.setup') }}</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('country') }}">{{ __('sidebar.countries') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard-value') }}">{{ __('sidebar.dashboard_values') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('onboarding') }}">{{ __('sidebar.onboardings') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('news') }}">{{ __('sidebar.news') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('term') }}">{{ __('sidebar.terms') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('credit-status') }}">{{ __('sidebar.credit_statuses') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('order-status') }}">{{ __('sidebar.order_statuses') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('supported-account') }}">{{ __('sidebar.supported_accounts') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('issue-type') }}">{{ __('sidebar.issue_types') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('credit-type') }}">{{ __('sidebar.credit_types') }}</a></li>
    </ul>
</li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-mail-bulk"></i> {{ __('sidebar.products') }}</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('type') }}">{{ __('sidebar.types') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}">{{ __('sidebar.categories') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('subcategory') }}">{{ __('sidebar.subcategories') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('field') }}">{{ __('sidebar.fields') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('product') }}">{{ __('sidebar.products') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('prepaid-card-stock') }}">{{ __('sidebar.prepaid_card_stock') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('credit-card') }}">{{ __('sidebar.credit_cards') }}</a></li>
    </ul>
</li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('order') }}"><i class="nav-icon la la-opencart"></i> {{ __('sidebar.orders') }}</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('group') }}"><i class="nav-icon la la-user-tag"></i> {{ __('sidebar.groups') }}</a></li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-user"></i> {{ __('sidebar.users') }}</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('vendor') }}">{{ __('sidebar.vendors') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('client') }}">{{ __('sidebar.clients') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('issue') }}">{{ __('sidebar.issues') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('notification') }}">{{ __('sidebar.notifications') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('allowed-category') }}">{{ __('sidebar.allowed_categories') }}</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('credit') }}">{{ __('sidebar.credits') }}</a></li>
    </ul>
</li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('log') }}'><i class='nav-icon la la-terminal'></i> {{ __('sidebar.logs') }}</a></li>
