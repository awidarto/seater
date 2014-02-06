@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

{{ Former::hidden('id')->value($formdata['_id']) }}
<div class="row-fluid">
    <div class="span6">
        {{ Former::text('title','Event Title') }}
        {{ Former::text('slug','Permalink')->id('permalink') }}
        {{ Former::text('venue','Venue') }}
        {{ Former::text('location','Location') }}


        {{ Former::text('fromDate','From')->class('span7 datepicker')
            ->data_format('dd-mm-yyyy')
            ->append('<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>') }}

        {{ Former::text('toDate','Until')->class('span7 datepicker')
            ->data_format('dd-mm-yyyy')
            ->append('<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>') }}


        {{ Former::select('category')->options(Config::get('ia.eventcat'))->label('Category') }}
        {{ Former::textarea('description','Description')->class('editor') }}
        {{ Former::text('tags','Tags')->class('tag_keyword') }}
    </div>
    <div class="span6">
        @for($i = 1;$i < 6;$i++)
            <div class="row form-horizontal">
                <div class="span4">
                    {{ Former::text('code_'.$i,'Code '.$i)->class('span12')->maxlength('6') }}
                </div>
                <div class="span4">
                    {{ Former::text('val_'.$i,'Value '.$i)->class('span12')->maxlength('6') }}
                </div>
            </div>
        @endfor
        {{ Former::text('expires','Expires')->class('span7 datepicker')
            ->data_format('dd-mm-yyyy')
            ->append('<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>') }}
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        {{ Form::submit('Save',array('class'=>'btn primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}
    </div>
</div>

{{Former::close()}}

<script type="text/javascript">


$(document).ready(function() {
    $('select').select2({
      width : 'resolve'
    });

    $('#title').keyup(function(){
        var title = $('#title').val();
        var slug = string_to_slug(title);
        $('#permalink').val(slug);
    });


});

</script>

@stop