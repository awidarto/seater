@extends('layout.front')

@section('content')

<style type="text/css">
.act{
	cursor: pointer;
}

.pending{
	padding: 4px;
	background-color: yellow;
}

.canceled{
	padding: 4px;
	background-color: red;
	color:white;
}

.sold{
	padding: 4px;
	background-color: green;
	color:white;
}

</style>

<div class="row-fluid">
	<div class="span12 command-bar">

        <h3>{{ $title }}</h3>

        @if(isset($can_add) && $can_add == true)
	       	<a href="{{ URL::to($addurl) }}" class="btn btn-primary">Add</a>
       	@endif
	 </div>
</div>

<div class="row-fluid">
   <div class="span12">

      <table class="table table-condensed dataTable">

		    <thead>

		        <tr>
		        	@foreach($heads as $head)
		        		@if(is_array($head))
		        			<th
		        				@foreach($head[1] as $key=>$val)
		        					@if(!is_array($val))
		        						{{ $key }}="{{ $val }}"
		        					@endif
		        				@endforeach
		        			>
		        			{{ $head[0] }}
		        			</th>
		        		@else
		        		<th>
		        			{{ $head }}
		        		</th>
		        		@endif
		        	@endforeach
		        </tr>
		        @if(isset($secondheads) && !is_null($secondheads))
		        	<tr>
		        	@foreach($secondheads as $head)
		        		@if(is_array($head))
		        			<th
		        				@foreach($head[1] as $key=>$val)
		        					@if($key != 'search')
			        					{{ $key }}="{{ $val }}"
		        					@endif
		        				@endforeach
		        			>
		        			{{ $head[0] }}
		        			</th>
		        		@else
		        		<th>
		        			{{ $head }}
		        		</th>
		        		@endif
		        	@endforeach
		        	</tr>
		        @endif
		    </thead>

			<?php
				$form = new Former();
			?>

		    <thead id="searchinput">
			    <tr>
			    <?php $index = -1 ;?>
		    	@foreach($heads as $in)
		    		@if( $in[0] != 'select_all' && $in[0] != '')
			    		@if(isset($in[1]['search']) && $in[1]['search'] == true)
			    			@if(isset($in[1]['date']) && $in[1]['date'])
				        		<td>
									<div class="input-append date datepickersearch" id="{{ $index }}" data-date="" data-date-format="dd-mm-yyyy">
									    <input class="span8 search_init dateinput" size="16" type="text" value="" placeholder="{{$in[0]}}" >
									    <span class="add-on"><i class="icon-th"></i></span>
									</div>
									{{--
									<div id="{{ $index }}" class="input-append datepickersearch">
									    <input id="{{ $index }}" name="search_{{$in[0]}}" data-format="dd-MM-yyyy" class="search_init dateinput" type="text" placeholder="{{$in[0]}}" ></input>
									    <span class="add-on">
											<i data-time-icon="icon-clock" data-date-icon="icon-calendar">
											</i>
									    </span>
									</div>

									--}}

				        		</td>
			    			@elseif(isset($in[1]['datetime']) && $in[1]['datetime'])
				        		<td>
									<div class="input-append date datetimepickersearch" id="{{ $index }}" data-date="" data-date-format="dd-mm-yyyy">
									    <input class="span8 search_init datetimeinput" size="16" type="text" value="" placeholder="{{$in[0]}}" >
									    <span class="add-on"><i class="icon-th"></i></span>
									</div>
									{{--
									<div id="{{ $index }}" class="input-append datetimepickersearch">
									    <input id="{{ $index }}" name="search_{{$in[0]}}" data-format="dd-MM-yyyy hh:mm:ss" class="search_init datetimeinput" type="text" placeholder="{{$in[0]}}" ></input>
									    <span class="add-on">
											<i data-time-icon="icon-clock" data-date-icon="icon-calendar">
											</i>
									    </span>
									</div>
									--}}
				        		</td>
			    			@elseif(isset($in[1]['select']) && is_array($in[1]['select']))
			    				<td>
			    					<input id="{{ $index }}" type="text" name="search_{{$in[0]}}" id="search_{{$in[0]}}" placeholder="{{$in[0]}}" value="" style="display:none;" class="search_init {{ (isset($in[1]['class']))?$in[1]['class']:'filter'}}" />
			    					<div class="styled-select">
				    					{{ Form::select('select_'.$in[0],$in[1]['select'],null,array('class'=>'selector input-small','id'=>$index ))}}
			    					</div>
			    				</td>
			    			@else
				        		<td>
				        			<input id="{{ $index }}" type="text" name="search_{{$in[0]}}" id="search_{{$in[0]}}" placeholder="{{$in[0]}}" value="" class="search_init {{ (isset($in[1]['class']))?$in[1]['class']:'filter'}}" />
				        		</td>
			    			@endif
		    			@else
			    			@if(isset($in[1]['clear']) && $in[1]['clear'] == true)
			    				<td><span id="clearsearch" style="cursor:pointer;">Clear Search</span></td>
			    			@else
				        		<td>&nbsp;</td>
			    			@endif
		    			@endif

			    		<?php $index++; ?>

		    		@elseif($in[0] == 'select_all')
	    				<td>{{ Former::checkbox('select_all') }}</td>
		    		@elseif($in[0] == '')
		        		<td>&nbsp;</td>
		    		@endif


		    	@endforeach
			    </tr>
		    </thead>

         <tbody>
         	<!-- will be replaced by ajax content -->
         </tbody>

      </table>

   </div>
</div>

<div id="prop-chg-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Change Property Status</h3>
  </div>
  <div class="modal-body">
  	<h4 id="prop-trx-order"></h4>
  	{{ Former::hidden('prop_id')->id('prop-trx-chg') }}
  	{{ Former::select('status', 'Status')->options(Config::get('ia.publishing'))->id('prop-stat-chg')}}
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary" id="prop-save-chg">Save changes</button>
  </div>
</div>


<div id="chg-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Change Transaction Status</h3>
  </div>
  <div class="modal-body">
  	<h4 id="trx-order"></h4>
  	{{ Former::hidden('trx_id')->id('trx-chg') }}
  	{{ Former::select('status', 'Status')->options(Config::get('ia.trx_status'))->id('stat-chg')}}
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary" id="save-chg">Save changes</button>
  </div>
</div>

<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<script type="text/javascript">

	var oTable;

	var current_pay_id = 0;
	var current_del_id = 0;
	var current_print_id = 0;



	function toggle_visibility(id) {
		$('#' + id).toggle();
	}

	/* Formating function for row details */
	function fnFormatDetails ( nTr )
	{
	    var aData = oTable.fnGetData( nTr );

	    //console.log(aData);

	    var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';

	    @include($row)

	    sOut += '</table>';

	    return sOut;
	}

    $(document).ready(function(){

    	$.fn.dataTableExt.oApi.fnStandingRedraw = function(oSettings) {
		    if(oSettings.oFeatures.bServerSide === false){
		        var before = oSettings._iDisplayStart;

		        oSettings.oApi._fnReDraw(oSettings);

		        // iDisplayStart has been reset to zero - so lets change it back
		        oSettings._iDisplayStart = before;
		        oSettings.oApi._fnCalculateEnd(oSettings);
		    }

		    // draw the 'current' page
		    oSettings.oApi._fnDraw(oSettings);
		};

		$.fn.dataTableExt.oApi.fnFilterClear  = function ( oSettings )
		{
		    /* Remove global filter */
		    oSettings.oPreviousSearch.sSearch = "";

		    /* Remove the text of the global filter in the input boxes */
		    if ( typeof oSettings.aanFeatures.f != 'undefined' )
		    {
		        var n = oSettings.aanFeatures.f;
		        for ( var i=0, iLen=n.length ; i<iLen ; i++ )
		        {
		            $('input', n[i]).val( '' );
		        }
		    }

		    /* Remove the search text for the column filters - NOTE - if you have input boxes for these
		     * filters, these will need to be reset
		     */
		    for ( var i=0, iLen=oSettings.aoPreSearchCols.length ; i<iLen ; i++ )
		    {
		        oSettings.aoPreSearchCols[i].sSearch = "";
		    }

		    /* Redraw */
		    oSettings.oApi._fnReDraw( oSettings );
		};


		$('.activity-list').tooltip();

		asInitVals = new Array();

        oTable = $('.dataTable').DataTable(
			{
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{$ajaxsource}}",
				"oLanguage": { "sSearch": "Search "},
				"sPaginationType": "full_numbers",
				"sDom": 'Tlrpit',
				"iDisplayLength":50,

				@if(isset($excludecol) && $excludecol != '')
				"oColVis": {
					"aiExclude": [ {{ $excludecol }} ]
				},
				@endif

				"oTableTools": {
					"sSwfPath": "{{ URL::to('/')  }}/swf/copy_csv_xls_pdf.swf"
				},

				"aoColumnDefs": [
				    { "bSortable": false, "aTargets": [ {{ $disablesort }} ] }
				 ],
			    "fnServerData": function ( sSource, aoData, fnCallback ) {
		            $.ajax( {
		                "dataType": 'json',
		                "type": "POST",
		                "url": sSource,
		                "data": aoData,
		                "success": fnCallback
		            } );
		        }

			}
        );

    	$('div.dataTables_length select').wrap('<div class="ingrid styled-select" />');


		$('.dataTable tbody tr td span.expander').on( 'click', function () {

			//console.log('expand !');

		    var nTr = $(this).parents('tr')[0];

		    if ( oTable.fnIsOpen(nTr) )
		    {
		        oTable.fnClose( nTr );
		    }
		    else
		    {
		        oTable.fnOpen( nTr, fnFormatDetails(nTr), 'details-expand' );
		    }
		} );


		//header search

		$('thead input.filter').keyup( function () {
			//console.log($('thead input').index(this));
			//console.log(this.id);
			/* Filter on the column (the index) of this element */
			//var search_index = $('thead input').index(this);
			var search_index = this.id;
			oTable.fnFilter( this.value, search_index );
		} );

		eldatetime = $('.datetimepickersearch').datetimepicker({
			minView:2,
			maxView:2
		});

		eldate = $('.datepickersearch').datetimepicker({
			minView:2,
			maxView:2
		});

		eldate.on('changeDate', function(e) {

			if(e.date.valueOf() != null){
				var dateval = e.date.valueOf();
			}else{
				var dateval = '';
			}
			var search_index = e.currentTarget.id;

			oTable.fnFilter( dateval, search_index );
		});

		eldatetime.on('changeDate', function(e) {

			if(e.date.valueOf() != null){
				var dateval = e.date.valueOf();
			}else{
				var dateval = '';
			}
			var search_index = e.target.id;

			oTable.fnFilter( dateval, search_index );
		});

		$('thead select.selector').change( function () {
			/* Filter on the column (the index) of this element */
			//var prev = $(this).parent().prev('input');

			//var search_index = $('thead input').index(prev);
			var search_index = this.id;

			//console.log(search_index);

			oTable.fnFilter( this.value,  search_index  );
		} );

		$('#clearsearch').click(function(){

			console.log($('thead td input').val());
			$('thead td input').val('');

			console.log($('thead td input').val());

			console.log('reloading table');
			//oTable.fnClearTable(1);
			/*
			$('thead td input').each(function(){
				console.log(this.id);
				var index = this.id;
				oTable.fnFilter('',index);
			});
			oTable.fnFilter('',1);

			oTable.fnFilter('');
			*/
			oTable.fnFilterClear();
			oTable.fnDraw();
		});
		/*
		 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
		 * the footer
		 */
		/*
		$('thead input').each( function (i) {
			asInitVals[i] = this.value;
		} );

		$('thead input.filter').focus( function () {

			console.log(this);

			if ( this.className == 'search_init' )
			{
				this.className = '';
				this.value = '';
			}
		} );

		$('thead input.filter').blur( function (i) {
			console.log(this);
			if ( this.value == '' )
			{
				this.className = 'search_init';
				this.value = asInitVals[$('thead input').index(this)];
			}
		} );

		*/

		$('#select_all').click(function(){
			if($('#select_all').is(':checked')){
				$('.selector').attr('checked', true);
			}else{
				$('.selector').attr('checked', false);
			}
		});

		$(".selectorAll").on("click", function(){
			var id = $(this).attr("id");
			if($(this).is(':checked')){
				$('.selector_'+id).attr('checked', true);
			}else{
				$('.selector_'+id).attr('checked', false);
			}
		});


		$('#confirmdelete').click(function(){

			$.post('{{ URL::to($ajaxdel) }}',{'id':current_del_id}, function(data) {
				if(data.status == 'OK'){
					//redraw table


					oTable.fnStandingRedraw();

					$('#delstatusindicator').html('Payment status updated');

					$('#deleteWarning').modal('toggle');

				}
			},'json');
		});

		$('#printstart').click(function(){

			var pframe = document.getElementById('print_frame');
			var pframeWindow = pframe.contentWindow;
			pframeWindow.print();

		});

		$('table.dataTable').click(function(e){

			if ($(e.target).is('.del')) {
				var _id = e.target.id;
				var answer = confirm("Are you sure you want to delete this item ?");

				console.log(answer);

				if (answer == true){

					$.post('{{ URL::to($ajaxdel) }}',{'id':_id}, function(data) {
						if(data.status == 'OK'){
							//redraw table

							oTable.fnStandingRedraw();
							alert("Item id : " + _id + " deleted");
						}
					},'json');

				}else{
					alert("Deletion cancelled");
				}
		   	}

			if ($(e.target).is('.pbadge')) {
				var _id = e.target.id;

				current_print_id = _id;

				$('#print_id').val(_id);

				<?php

					$printsource = (isset($printsource))?$printsource.'/': '/';

				?>

				var src = '{{ $printsource }}' + _id;

				$('#print_frame').attr('src',src);

				$('#printBadge').modal();
		   	}



		   	if ($(e.target).is('.viewform')) {

				var _id = e.target.id;
				var _rel = $(e.target).attr('rel');
				var url = '{{ URL::to('/')  }}' + '/exhibitor/' + _rel + '/' + _id;


				//var url = $(this).attr('url');
			    //var modal_id = $(this).attr('data-controls-modal');
			    $("#viewformModal .modal-body").load(url);


				$('#viewformModal').modal();

		   	}

		   	if ($(e.target).is('.editform')) {

				var _id = e.target.id;
				var _rel = $(e.target).attr('rel');
				var url = '{{ URL::to('/')  }}' + '/exhibitor/' + _rel + '/' + _id;


				//var url = $(this).attr('url');
			    //var modal_id = $(this).attr('data-controls-modal');
			    setTimeout(function() {
				    $("#editformModal .modal-body").load(url);
				}, 1000);



				$('#editformModal').modal();

		   	}

			if ($(e.target).is('.thumbnail')) {
				var _id = e.target.id;
				var links = [];

				var g = $('.g_' + _id);

				g.each(function(){
					links.push({
						href:$(this).val(),
						title:$(this).data('caption')
					});
				})
				var options = {
					carousel: false
				};
				blueimp.Gallery(links, options);
				console.log(links);

		   	}


			if ($(e.target).is('.pop')) {
				var _id = e.target.id;
				var _rel = $(e.target).attr('rel');

				$.fancybox({
					type:'iframe',
					href: '{{ URL::to('/')  }}' + '/' + _rel + '/' + _id,
					autosize: true
				});

		   	}

			if ($(e.target).is('.chg')) {
				var _id = e.target.id;
				var _rel = $(e.target).attr('rel');
				var _status = $(e.target).data('status');

				$('#chg-modal').modal();

				$('#trx-chg').val(_id);
				$('#stat-chg').val(_status);

				$('#trx-order').html('Order # : ' + _rel);

		   	}

			if ($(e.target).is('.propchg')) {
				var _id = e.target.id;
				var _rel = $(e.target).attr('rel');
				var _status = $(e.target).data('status');

				console.log(_status);

				$('#prop-chg-modal').modal();
				$('#prop-trx-chg').val(_id);
				$('#prop-stat-chg').val(_status);
				$('#prop-trx-order').html('Property ID : ' + _rel);

		   	}

		});


		$('#save-chg').on('click',function(){
            $.post('{{ URL::to('ajax/changestatus')}}',
            {
                trx_id:$('#trx-chg').val(),
                status:$('#stat-chg').val()
            },
            function(data){
				$('#chg-modal').modal('hide');
            },
            'json');
		});

		$('#chg-modal').on('hidden', function () {
			oTable.fnDraw();
		})


		$('#prop-save-chg').on('click',function(){
            $.post('{{ URL::to('ajax/propchangestatus')}}',
            {
                trx_id:$('#prop-trx-chg').val(),
                status:$('#prop-stat-chg').val()
            },
            function(data){
				$('#prop-chg-modal').modal('hide');
            },
            'json');
		});

		$('#prop-chg-modal').on('hidden', function () {
			oTable.fnDraw();
		});

		function dateFormat(indate) {
	        var yyyy = indate.getFullYear().toString();
	        var mm = (indate.getMonth()+1).toString(); // getMonth() is zero-based
	        var dd  = indate.getDate().toString();

	        return (dd[1]?dd:"0"+dd[0]) + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + yyyy;
   		}


    });
  </script>

@stop