
<!-- begin:: Header Topbar -->
<div class="kt-header__topbar kt-grid__item">

	<!--begin: Search -->
	{{-- <div class="kt-header__topbar-item kt-header__topbar-item--search dropdown" id="kt_quick_search_toggle">
		<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
			<span class="kt-header__topbar-icon"><i class="flaticon2-search-1"></i></span>
		</div>
		<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-lg">

            <!--[html-partial:include:{"file":"partials/_topbar/dropdown/search-dropdown.html"}]/-->
            @include('partials/_topbar/dropdown/search-dropdown')
		</div>
	</div> --}}

	<!--end: Search -->

	<!--begin: Notifications -->
	<div class="kt-header__topbar-item dropdown">
		<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
			<span class="kt-header__topbar-icon"><i class="flaticon2-bell-alarm-symbol"></i></span>
			{{-- <span class="kt-badge kt-badge--danger"></span> --}}
		</div>
		<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">
			<form>

                <!--[html-partial:include:{"file":"partials/_topbar/dropdown/notifications.html"}]/-->
                {{-- @include('partials/_topbar/dropdown/notifications') --}}
			</form>
		</div>
	</div>

	<!--end: Notifications -->

	<!--begin: Quick actions -->
	{{-- <div class="kt-header__topbar-item dropdown">
		<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
			<span class="kt-header__topbar-icon"><i class="flaticon2-gear"></i></span>
		</div>
		<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">
			<form>
                <!--[html-partial:include:{"file":"partials/_topbar/dropdown/quick-actions.html"}]/-->
                @include('partials/_topbar/dropdown/quick-actions')
			</form>
		</div>
	</div> --}}

	<!--end: Quick actions -->


	<!--begin: User bar -->
	<div class="kt-header__topbar-item kt-header__topbar-item--user">
		<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
			<span class="kt-header__topbar-welcome kt-visible-desktop">Hi,</span>
			<span class="kt-header__topbar-username kt-visible-desktop">{{ Auth::user()->name }}</span>
			<img alt="Pic" src="{{ Auth::user()->avatar }}" />

			<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
			<span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold kt-hidden">S</span>
		</div>
		<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">

            <!--[html-partial:include:{"file":"partials/_topbar/dropdown/user.html"}]/-->
            @include('partials/_topbar/dropdown/user')
		</div>
	</div>

	<!--end: User bar -->

	<!--begin: Quick panel toggler -->

	<!--end: Quick panel toggler -->
</div>

<!-- end:: Header Topbar -->
