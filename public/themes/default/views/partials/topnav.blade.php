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
        <li><a href="{{ URL::to('property') }}" {{ sa('property') }} >Property</a></li>
        <li><a href="{{ URL::to('agent') }}" {{ sa('agent') }} >Agents</a></li>
        <li><a href="{{ URL::to('buyer') }}" {{ sa('buyer') }} >Buyers</a></li>
        <li><a href="{{ URL::to('transaction') }}" {{ sa('transaction') }} >Transactions</a></li>

        <li><a href="{{ URL::to('event') }}" {{ sa('event') }} >Events</a></li>
        <li><a href="{{ URL::to('promocode') }}" {{ sa('promocode') }} >Promo Code</a></li>

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
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                FAQ
                <b class="caret"></b>
              </a>
            <ul class="dropdown-menu">
                <li><a href="{{ URL::to('faq') }}" {{ sa('faq') }} >FAQ Entries</a></li>
                <li><a href="{{ URL::to('faqcat') }}" {{ sa('faqcat') }} >Category</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Site Content
                <b class="caret"></b>
              </a>
            <ul class="dropdown-menu">
                <li><a href="{{ URL::to('content/pages') }}" {{ sa('content/pages') }} >Pages</a></li>
                <li><a href="{{ URL::to('content/posts') }}" {{ sa('content/posts') }} >Posts</a></li>
                <li><a href="{{ URL::to('content/category') }}" {{ sa('content/category') }} >Category</a></li>
                <li><a href="{{ URL::to('content/menu') }}" {{ sa('content/menu') }} >Menu</a></li>
                <li><a href="{{ URL::to('video') }}" {{ sa('video') }} >Promo Videos</a></li>
            </ul>
        </li>
    @endif
</ul>
