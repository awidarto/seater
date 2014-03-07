@extends('layout.front')


@section('content')
<style type="text/css">
    ul.tablelist{
        list-style-type: none;
        text-align: center;
    }

    ul.tablelist li{
        float: left;
        margin: 5px;
    }

    .tablebox{
        width:60px;
        height: 60px;
        display: inline-block;
        text-align: center;
        position: relative;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
    }

    .tablebox h3{
        width: 50%;
        height: 50%;
        overflow: auto;
        margin: auto;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
    }

    .orange{
        background-color: orange;
        color: brown;
        font-weight: bold;
    }

    .green{
        background-color: green;
        color: white;
        font-weight: bold;
    }

    .scannerbox{
        display:block;
        text-align:center;
        margin-bottom:20px;
    }

    .scannerbox h3{
        font-weight: bold;
    }

</style>
<h3>{{$title}}</h3>

<div class="row-fluid">
    <div class="span8">
        <h1>VIP</h1>
        <ul class="tablelist">
            @for($i = 0;$i < Config::get('seater.vip_table_count');$i++)
                <li>
                    <div>
                        <div class="tablebox orange" id="VIP-{{ ($i + 1 ) }}-box">
                            <h3 id="{{ 'VIP-'.($i + 1 ) }}">
                                @if(isset($tabstat[ 'VIP-'.($i + 1 ) ]))
                                    {{ $tabstat[ 'VIP-'.($i + 1 ) ] }}
                                @endif
                            </h3>
                        </div>
                        <div>
                            <h3>{{ $i + 1 }}</h3>
                        </div>
                    </div>
                </li>
            @endfor
        </ul>

        <div class="clearfix"></div>
        <h1>GUESTS</h1>
        <ul class="tablelist">
            @for($i = 0;$i < Config::get('seater.regular_table_count');$i++)
                <li>
                    <div>
                        <div class="tablebox green" id="REGULAR-{{ ($i + 1 ) }}-box" >
                            <h3 id="{{ 'REGULAR-'.($i + 1 ) }}">
                                @if(isset($tabstat[ 'REGULAR-'.($i + 1 ) ]))
                                    {{ $tabstat[ 'REGULAR-'.($i + 1 ) ] }}
                                @endif
                            </h3>
                        </div>
                        <div>
                            <h3>{{ $i + 1 }}</h3>
                        </div>
                    </div>
                </li>
            @endfor
        </ul>

    </div>
    <div class="span3">
        <div class="scannerbox">
            <img id="guest-photo" src="{{ URL::to('images/no-photo.jpg')}}">
            <h4 id="guest-name">John Travolta</h4>
            <h5 id="guest-title">Work Unit</h5>
            <h3 >Table : <span id="table-number">1</span>&nbsp;&nbsp;&nbsp; Seat : <span id="seat-number">1</span></h3>
            {{ Former::text('barcode','')->id('barcode')->class('span10') }}

            <div id="scanResult">
                Hello !
            </div>
        </div>
    </div>
</div>

{{ HTML::script('js/wysihtml5-0.3.0.min.js') }}
{{ HTML::script('js/parser_rules/advanced.js') }}

<script type="text/javascript">


$(document).ready(function() {

    $('select').select2({
      width : 'resolve'
    });

    $('#barcode').focus();

    $('#barcode').on('keyup',function(ev){
        if(ev.keyCode == '13'){
            onScanResult();
        }
        //$('#barcode').val($('#barcode').val() + event.keyCode);
    });

    function onScanResult(){
        var txtin = $("#barcode").val();

        $.post('{{ URL::to('ajax/scan') }}',
            { 'txtin':txtin },
            function(data){
                if(data.result == 'OK'){
                    $('#scanResult').html(data.html);
                }else{
                    $('#scanResult').html(data.html);
                }

                var t = data.tabstat;

                $.each(t, function(key,val){
                    $('#' + key).html(val);
                });

                clearList();
            },'json'
        );
    }

    function clearList(){
        $('#barcode').val('').focus();
    }

});

</script>

@stop