<?php
    function sa($item){
        if(URL::to($item) == URL::full() ){
            return  'class="active"';
        }else{
            return '';
        }
    }
?>
<ul class="nav">
    @if(Auth::check())

        @if(Auth::user()->role == 'root' || Auth::user()->role == 'admin')
        <li><a href="{{ URL::to('attendee') }}" {{ sa('attendee') }} >Attendee</a></li>
        <li><a href="{{ URL::to('attending') }}" {{ sa('attending') }} >Attendance</a></li>

        <li><a href="{{ URL::to('user') }}" {{ sa('user') }} >Admins</a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Reports
                <b class="caret"></b>
              </a>
            <ul class="dropdown-menu">
                <li><a href="{{ URL::to('activity') }}" {{ sa('activity') }} >Activity Log</a></li>
                <li><a href="{{ URL::to('access') }}" {{ sa('access') }} >Site Access</a></li>
            </ul>
        </li>
        @endif
    @endif
</ul>
