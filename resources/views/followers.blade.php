@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="card">
                <h4 class="card-header">
                        @include('app_elements.logout')
                        <div class="btn-group dropdown float-right font-weight-bold">
                            @php
                                $title_pieces = explode('-', \Request::route()->getName());
                                $page_title = '';
                                foreach($title_pieces as $tp){
                                    $page_title .= ucfirst($tp).' ';
                                }
                            @endphp
                            {{ config('app.name', 'Instafollowers')." - ".trim($page_title) }}
                        </div>
                </h4>
                <div class="card-body">
                        <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-bordered">
                            <tr class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Instagram ID') }}</th>
                                <th scope="col">{{ __('Username') }}</th>
                                <th scope="col">{{ __('Full Name') }}</th>
                                <th scope="col">{{ __('Follower') }}</th>
                                <th scope="col">{{ __('Following') }}</th>
                                <th scope="col">{{ __('Was Follower') }}</th>
                                <th scope="col">{{ __('Was Following') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = $followers->firstItem();
                            @endphp
                            @foreach ($followers as $follower)
                                @php
                                    $bg_color = '';
                                    if ($follower->is_follower AND !$follower->is_following) {
                                        $bg_color = "bg-success text-white";
                                    } elseif (!$follower->is_follower AND $follower->is_following) {
                                        $bg_color = "bg-danger text-white";
                                    } elseif ($follower->is_follower AND $follower->is_following) {
                                        $bg_color = "bg-info text-white";
                                    } else {
                                        $bg_color = "bg-secondary text-white";
                                    }
                                @endphp
                            <tr>
                                <th scope="row">{!! $i++ !!}</th>
                                <td style ="word-break:break-all;"><a target="_blank" rel="noopener noreferrer" href='{{ url('https://www.instagram.com/'.$follower->username.'/') }}'>{{ $follower->instagramID }}</a></td>
                                <td style ="word-break:break-all;">{{ $follower->username }}</td>
                                <td style ="word-break:break-all;">{{ $follower->full_name }}</td>
                                <td style ="word-break:break-all;" class="{{ $bg_color }}">{{ $follower->is_follower ? __('Tak') : __('Nie') }}</td>
                                <td style ="word-break:break-all;" class="{{ $bg_color }}">{{ $follower->is_following ? __('Tak') : __('Nie') }}</td>
                                <td style ="word-break:break-all;" class="{{ $bg_color }}">{{ $follower->was_follower ? __('Tak') : __('Nie') }}</td>
                                <td style ="word-break:break-all;" class="{{ $bg_color }}">{{ $follower->was_following ? __('Tak') : __('Nie') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                    {{ $followers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
