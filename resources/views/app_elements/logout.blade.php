<div class="btn-group dropdown float-left">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ Auth::user()->name ?? ""}}
        </button>
        <div class="dropdown-menu">
                <a class="dropdown-item {{ \Request::route()->getName() == 'all-followers' ? 'active' : ' ' }}" href="{{ route('all-followers') }}">All</a>
                <a class="dropdown-item {{ \Request::route()->getName() == 'followers-and-following' ? 'active' : ' ' }}" href="{{ route('followers-and-following') }}">Followers & Following</a>
                <a class="dropdown-item {{ \Request::route()->getName() == 'followers' ? 'active' : ' ' }}" href="{{ route('followers') }}">Only Followers</a>
                <a class="dropdown-item {{ \Request::route()->getName() == 'following' ? 'active' : ' ' }}" href="{{ route('following') }}">Only Following</a>
                <a class="dropdown-item {{ \Request::route()->getName() == 'past-followers' ? 'active' : ' ' }}" href="{{ route('past-followers') }}">Past Followers</a>
                <a class="dropdown-item {{ \Request::route()->getName() == 'past-following' ? 'active' : ' ' }}" href="{{ route('past-following') }}">Past Following</a>
                <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
            </form>
        </div>
</div>

