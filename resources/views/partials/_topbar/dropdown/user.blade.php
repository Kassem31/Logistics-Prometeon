
<!--begin: Head -->
<div class="kt-user-card kt-user-card--skin-light kt-notification-item-padding-x">
	<div class="kt-user-card__avatar">
		<img class="kt-hidden-" alt="Pic" src="{{ Auth::user()->avatar }}" />

		<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
		<span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold kt-hidden"></span>
	</div>
	<div class="kt-user-card__name">
		{{ Auth::user()->full_name }}
	</div>
	<div class="kt-user-card__badge">
		<span class="btn btn-label-primary btn-sm btn-bold btn-font-md">0 messages</span>
	</div>
</div>

<!--end: Head -->

<!--begin: Navigation -->
<div class="kt-notification">
	<a href="{{ route('user.resetpassword') }}" class="kt-notification__item">
		<div class="kt-notification__item-icon">
			<i class="flaticon2-calendar-3 kt-font-success"></i>
		</div>
		<div class="kt-notification__item-details">
			<div class="kt-notification__item-title kt-font-bold">
				Change Password
			</div>
		</div>
	</a>
	<div class="kt-notification__custom kt-start">
        <form action="{{ route('logout') }}" method="POST" id="logout">@csrf</form>
		<a href="javascript:void(0)" class="btn btn-label btn-label-brand btn-sm btn-bold" onclick="document.getElementById('logout').submit()">Sign Out</a>
	</div>
</div>

<!--end: Navigation -->
