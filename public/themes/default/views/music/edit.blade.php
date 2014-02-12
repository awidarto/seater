@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

{{ Former::hidden('id')->value($formdata['_id']) }}
<div class="row">

    <div class="span6">

        {{ Former::text('songTitle','Song Title') }}
        {{ Former::text('artist','Artist') }}
        {{ Former::text('album','Album') }}
        {{ Former::text('genre','Genre')->class('tag_keyword') }}
        {{ Former::select('countryOfOrigin')->options(Config::get('country.countries'))->label('Country of Origin') }}
        {{ Former::text('slug','Permalink')->id('permalink') }}

        {{ Former::text('price','Price (IDR)') }}
        {{ Former::text('tags','Tags')->class('tag_keyword') }}

        <div class="control-group">
            <label class="control-label" for="userfile">Upload Music</label>
            <div class="controls">
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span>Add file...</span>
                    <!-- The file input field used as target for the file upload widget -->
                    <input id="musicupload" type="file" name="files[]">
                </span>
                <br />
                <br />
                <div id="mprogress" class="progress progress-success progress-striped">
                    <div class="bar"></div>
                </div>
                <br />
                <div id="mfiles" class="files">
                    <ul>
                        <?php
                            $allin = Input::old();
                            $showold = false;

                            if( count($allin) > 0){
                                $showold = true;
                            }

                            if($showold && isset( $allin['thumbnail_url'])){

                                $filename = $allin['filename'];
                                $thumbnail_url = $allin['thumbnail_url'];

                                $thumb = '<li><img src="%s"><br /><input type="radio" name="defaultpic" value="%s" %s > Default<br />';
                                $thumb .= '<span class="img-title">%s</span>';
                                /*
                                $thumb .= '<label for="colour">Colour</label><input type="text" name="colour[]" value="%s"  />';
                                $thumb .= '<label for="material">Material & Finish</label><input type="text" name="material[]" value="%s"  />';
                                $thumb .= '<label for="tags">Tags</label><input type="text" name="tag[]" value="%s"  /></li>';
                                */

                                for($t = 0; $t < count($filename);$t++){
                                    if($allin['defaultpic'] == $filename[$t]){
                                        $isdef = 'checked="checked"';
                                    }else{
                                        $isdef = ' ';
                                    }

                                    printf($thumb,$thumbnail_url[$t],
                                        $filename[$t],
                                        $isdef,
                                        $filename[$t]
                                        );
                                }

                            }
                        ?>
                    </ul>
                </div>
                <div id="muploadedform">
                    <?php

                        if($showold && isset( $allin['filename'] )){

                            $count = 0;
                            $upcount = count($allin['filename']);

                            $upl = '';
                            for($u = 0; $u < $upcount; $u++){
                                $upl .= '<input type="hidden" name="mdelete_type[]" value="' . $allin['delete_type'][$u] . '">';
                                $upl .= '<input type="hidden" name="mdelete_url[]" value="' . $allin['delete_url'][$u] . '">';
                                $upl .= '<input type="hidden" name="mfilename[]" value="' . $allin['filename'][$u]  . '">';
                                $upl .= '<input type="hidden" name="mfilesize[]" value="' . $allin['filesize'][$u]  . '">';
                                $upl .= '<input type="hidden" name="mtemp_dir[]" value="' . $allin['temp_dir'][$u]  . '">';
                                $upl .= '<input type="hidden" name="mthumbnail_url[]" value="' . $allin['thumbnail_url'][$u] . '">';
                                $upl .= '<input type="hidden" name="mfiletype[]" value="' . $allin['filetype'][$u] . '">';
                                $upl .= '<input type="hidden" name="mfileurl[]" value="' . $allin['fileurl'][$u] . '">';
                            }

                            print $upl;
                        }

                    ?>
                </div>
            </div>
        </div>


        <div class="control-group">
            <label class="control-label" for="userfile">Upload Images</label>
            <div class="controls">
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span>Add files...</span>
                    <!-- The file input field used as target for the file upload widget -->
                    <input id="fileupload" type="file" name="files[]" multiple>
                </span>
                <br />
                <br />
                <div id="progress" class="progress progress-success progress-striped">
                    <div class="bar"></div>
                </div>
                <br />
                <div id="files" class="files">
                    <ul>
                        <?php
                            $allin = Input::old();
                            $showold = false;

                            if( count($allin) > 0){
                                $showold = true;
                            }

                            if($showold && isset( $allin['thumbnail_url'])){

                                $filename = $allin['filename'];
                                $thumbnail_url = $allin['thumbnail_url'];

                                $thumb = '<li><img src="%s"><br /><input type="radio" name="defaultpic" value="%s" %s > Default<br />';
                                $thumb .= '<span class="img-title">%s</span>';
                                $thumb .= '<label for="colour">Colour</label><input type="text" name="colour[]" value="%s"  />';
                                $thumb .= '<label for="material">Material & Finish</label><input type="text" name="material[]" value="%s"  />';
                                $thumb .= '<label for="tags">Tags</label><input type="text" name="tag[]" value="%s"  /></li>';

                                for($t = 0; $t < count($filename);$t++){
                                    if($allin['defaultpic'] == $filename[$t]){
                                        $isdef = 'checked="checked"';
                                    }else{
                                        $isdef = ' ';
                                    }

                                    printf($thumb,$thumbnail_url[$t],
                                        $filename[$t],
                                        $isdef,
                                        $filename[$t],
                                        $allin['colour'][$t],$allin['material'][$t],$allin['tag'][$t]);
                                }

                            }
                        ?>
                    </ul>
                </div>
                <div id="uploadedform">
                    <?php

                        if($showold && isset( $allin['filename'] )){

                            $count = 0;
                            $upcount = count($allin['filename']);

                            $upl = '';
                            for($u = 0; $u < $upcount; $u++){
                                $upl .= '<input type="hidden" name="delete_type[]" value="' . $allin['delete_type'][$u] . '">';
                                $upl .= '<input type="hidden" name="delete_url[]" value="' . $allin['delete_url'][$u] . '">';
                                $upl .= '<input type="hidden" name="filename[]" value="' . $allin['filename'][$u]  . '">';
                                $upl .= '<input type="hidden" name="filesize[]" value="' . $allin['filesize'][$u]  . '">';
                                $upl .= '<input type="hidden" name="temp_dir[]" value="' . $allin['temp_dir'][$u]  . '">';
                                $upl .= '<input type="hidden" name="thumbnail_url[]" value="' . $allin['thumbnail_url'][$u] . '">';
                                $upl .= '<input type="hidden" name="filetype[]" value="' . $allin['filetype'][$u] . '">';
                                $upl .= '<input type="hidden" name="fileurl[]" value="' . $allin['fileurl'][$u] . '">';
                            }

                            print $upl;
                        }

                    ?>
                </div>
            </div>
        </div>

    </div>
    <div class="span6">
        @include('partials.editortoolbar')
        {{ Former::textarea('lyric','Lyric') }}


    </div>
</div>

<div class="row">
    <div class="span12">
        {{ Form::submit('Save',array('class'=>'btn primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}
    </div>
</div>

{{Former::close()}}

<style type="text/css">
#lyric{
    min-height: 350px;
    height: 400px;
}
</style>

{{ HTML::script('js/wysihtml5-0.3.0.min.js') }}
{{ HTML::script('js/parser_rules/advanced.js') }}

<script type="text/javascript">


$(document).ready(function() {
    /*
    $('select').select2({
      width : 'resolve'
    });
    */
    var editor = new wysihtml5.Editor("lyric", { // id of textarea element
      toolbar:      "wysihtml5-toolbar", // id of toolbar element
      parserRules:  wysihtml5ParserRules // defined in parser rules set
    });

    var url = '{{ URL::to('upload/cover') }}';
    var murl = '{{ URL::to('upload/music') }}';

    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            $('#progress .bar').css(
                'width',
                '0%'
            );

            $.each(data.result.files, function (index, file) {
                var thumb = '<li><img src="' + file.thumbnail_url + '" /><br /><input type="radio" name="defaultpic" value="' + file.name + '"> Default<br /><span class="img-title">' + file.name + '</span>' +
                '<label for="colour">Colour</label><input type="text" name="colour[]" />' +
                '<label for="material">Material & Finish</label><input type="text" name="material[]" />' +
                '<label for="tags">Tags</label><input type="text" name="tag[]" />' +
                '</li>';
                $(thumb).appendTo('#files ul');

                var upl = '<input type="hidden" name="delete_type[]" value="' + file.delete_type + '">';
                upl += '<input type="hidden" name="delete_url[]" value="' + file.delete_url + '">';
                upl += '<input type="hidden" name="filename[]" value="' + file.name  + '">';
                upl += '<input type="hidden" name="filesize[]" value="' + file.size  + '">';
                upl += '<input type="hidden" name="temp_dir[]" value="' + file.temp_dir  + '">';
                upl += '<input type="hidden" name="thumbnail_url[]" value="' + file.thumbnail_url + '">';
                upl += '<input type="hidden" name="filetype[]" value="' + file.type + '">';
                upl += '<input type="hidden" name="fileurl[]" value="' + file.url + '">';

                $(upl).appendTo('#uploadedform');

            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        }
    })
    .prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

    $('#musicupload').fileupload({
        url: murl,
        dataType: 'json',
        done: function (e, data) {
            $('#mprogress .bar').css(
                'width',
                '0%'
            );

            $.each(data.result.files, function (index, file) {
                var thumb = '<li><img src="' + file.thumbnail_url + '" /><br /><input type="radio" name="defaultpic" value="' + file.name + '"> Default<br /><span class="img-title">' + file.name + '</span>' +
                '<label for="colour">Colour</label><input type="text" name="colour[]" />' +
                '<label for="material">Material & Finish</label><input type="text" name="material[]" />' +
                '<label for="tags">Tags</label><input type="text" name="tag[]" />' +
                '</li>';
                $(thumb).appendTo('#mfiles ul');

                var upl = '<input type="hidden" name="delete_type[]" value="' + file.delete_type + '">';
                upl += '<input type="hidden" name="delete_url[]" value="' + file.delete_url + '">';
                upl += '<input type="hidden" name="filename[]" value="' + file.name  + '">';
                upl += '<input type="hidden" name="filesize[]" value="' + file.size  + '">';
                upl += '<input type="hidden" name="temp_dir[]" value="' + file.temp_dir  + '">';
                upl += '<input type="hidden" name="thumbnail_url[]" value="' + file.thumbnail_url + '">';
                upl += '<input type="hidden" name="filetype[]" value="' + file.type + '">';
                upl += '<input type="hidden" name="fileurl[]" value="' + file.url + '">';

                $(upl).appendTo('#muploadedform');

            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#mprogress .bar').css(
                'width',
                progress + '%'
            );
        }
    })
    .prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

    $('#songTitle').keyup(function(){
        var title = $('#songTitle').val();
        var slug = string_to_slug(title);
        $('#permalink').val(slug);
    });


});

</script>

@stop