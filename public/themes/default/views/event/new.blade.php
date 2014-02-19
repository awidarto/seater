@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files($submit,'POST',array('class'=>''))}}

<div class="row-fluid">
    <div class="span6">
        {{ Former::text('title','Event Title') }}
        {{ Former::text('slug','Permalink')->id('permalink') }}
        {{ Former::text('venue','Venue') }}
        {{ Former::text('location','Location') }}

        {{ Former::text('fromDate','From')->class('span7 eventdate')
            ->id('fromDate')
            ->append('<i class="icon-th"></i>') }}

        {{ Former::text('toDate','Until')->class('span7 eventdate')
            ->id('toDate')
            ->append('<i class="icon-th"></i>') }}


        {{ Former::select('category')->options(Config::get('ia.eventcat'))->label('Category') }}
        {{ Former::textarea('description','Description')->class('editor') }}
        {{ Former::text('tags','Tags')->class('tag_keyword') }}
    </div>
    <div class="span6">
        @for($i = 1;$i < 6;$i++)
            <div class="row form-horizontal">
                <div class="span4">
                    {{ Former::text('code_'.$i,'Code '.$i)->id('code_'.$i)->class('span12')->maxlength('6') }}

                </div>
                <div class="span4">
                    {{ Former::text('val_'.$i,'Value '.$i)->id('val_'.$i)->class('span12')->maxlength('6') }}
                </div>
            </div>
        @endfor
        {{ Former::text('expires','Expires')->class('span7 datepicker')
            //->data_format('dd-mm-yyyy')
            ->append('<i class="icon-th"></i>') }}
   </div>
</div>

<div class="row-fluid">
    <div class="span12 pull-right">
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
        getCodePrefix(title);
    });

    $('.eventdate').on('apply',function(ev,picker){
        console.log(moment( picker.endDate ,'MM/DD/YYYY'));
        $('#expires').val( picker.endDate.add('weeks',2).format('MM/DD/YYYY') );
    });

    function getCodePrefix(title){
        words = title.split(' ');
        var pre = '';
        for(i = 0;i < words.length;i++){
            pre += words[i].charAt(0);
        }

        console.log(pre);

        var cs = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        var rand1 = randomString(4, cs);
        var rand2 = randomString(4, cs);
        var rand3 = randomString(4, cs);
        var rand4 = randomString(4, cs);
        var rand5 = randomString(4, cs);

        $('#code_1').val(pre + rand1);
        $('#code_2').val(pre + rand2);
        $('#code_3').val(pre + rand3);
        $('#code_4').val(pre + rand4);
        $('#code_5').val(pre + rand5);

        $('#val_1').val(100);
        $('#val_2').val(200);
        $('#val_3').val(500);
        $('#val_4').val(750);
        $('#val_5').val(1000);

    }

    function randomString(len, charSet) {
        charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var randomString = '';
        for (var i = 0; i < len; i++) {
            var randomPoz = Math.floor(Math.random() * charSet.length);
            randomString += charSet.substring(randomPoz,randomPoz+1);
        }
        return randomString;
    }

        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

});



</script>

@stop