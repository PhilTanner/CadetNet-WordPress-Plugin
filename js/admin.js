	/*			
		Plugin Name: NZCF Cadet Net 
		Plugin URI:  https://github.com/PhilTanner/CadetNet-WordPress-Plugin.git
        
        Copyright (C) 2016 Phil Tanner

    	This program is free software: you can redistribute it and/or modify
    	it under the terms of the GNU General Public License as published by
    	the Free Software Foundation, either version 3 of the License, or
    	(at your option) any later version.

    	This program is distributed in the hope that it will be useful,
    	but WITHOUT ANY WARRANTY; without even the implied warranty of
    	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    	GNU General Public License for more details.

    	You should have received a copy of the GNU General Public License
    	along with this program.  If not, see <http://www.gnu.org/licenses/>.
	*/
	
	jQuery(document).ready( function($){
		
		$('button.eoi_applications').click(function(){
			$.ajax({
				url: site_url+'/wp-admin/admin-ajax.php?action=eoi_application_list&vacancy_id='+$(this).data('vacancy'),
				dataType: 'json'
			}).done( function(json, text) { 
				var html = "";
				
				if( json['submitted'].length ) {
					html += "<h2> Awaiting CUCDR comments </h2>";
					html += "<table>";
					html += "	<thead>";
					html += "		<tr>";
					html += "			<th> Submitted </th>";
					html += "			<th> Name </th>";
					html += "			<th> Reasons for applying </th>";	
					html += "		</tr>";
					html += "	</thead>";
					html += "	<tbody>";
					for( var i=0; i< json['submitted'].length; i++ ) {
						html += "		<tr>";
						html += "			<td>"+json['submitted'][i].created+"</td>";
						html += "			<td>"+json['submitted'][i].rank_shortname+" "+json['submitted'][i].name+"</td>";
						html += "			<td>"+json['submitted'][i].reasons_for_applying+"&hellip;</td>";
						html += "			<td><button type='button' class='external link' data-appid='"+json['submitted'][i].application_id+"'>View</button></td>";
						html += "		</tr>";
					}
					html += "	</tbody>";
					html += "</table>";
				}
				
				if( json['cucdr_reviewed'].length ) {
					html += "<h2> Awaiting ASO comments </h2>";
					html += "<table>";
					html += "	<thead>";
					html += "		<tr>";
					html += "			<th> Submitted </th>";
					html += "			<th> Name </th>";
					html += "			<th> Reasons for applying </th>";
					html += "		</tr>";
					html += "	</thead>";
					html += "	<tbody>";
					for( var i=0; i< json['cucdr_reviewed'].length; i++ ) {	
						html += "		<tr>";
						html += "			<td>"+json['cucdr_reviewed'][i].created+"</td>";
						html += "			<td>"+json['cucdr_reviewed'][i].name+"</td>";
						html += "			<td>"+json['cucdr_reviewed'][i].reasons_for_applying+"</td>";
						html += "			<td><button type='button' class='external link' data-appid='"+json['cucdr_reviewed'][i].application_id+"'>View</button></td>";
						html += "		</tr>";
					}
					html += "	</tbody>";
					html += "</table>";
				}
				
				if( json['aso_reviewed'].length ) {
					html += "<h2> Awaiting AC comments </h2>";
					html += "<table>";
					html += "	<thead>";
					html += "		<tr>";
					html += "			<th> Submitted </th>";
					html += "			<th> Name </th>";
					html += "			<th> Reasons for applying </th>";
					html += "		</tr>";
					html += "	</thead>";
					html += "	<tbody>";
					for( var i=0; i< json['aso_reviewed'].length; i++ ) {	
						html += "		<tr>";
						html += "			<td>"+json['aso_reviewed'][i].created+"</td>";
						html += "			<td>"+json['aso_reviewed'][i].name+"</td>";
						html += "			<td>"+json['aso_reviewed'][i].reasons_for_applying+"</td>";
						html += "			<td><button type='button' class='external link' data-appid='"+json['aso_reviewed'][i].application_id+"'>View</button></td>";
						html += "		</tr>";
					}
					html += "	</tbody>";
					html += "</table>";
				}
				
				if( json['ac_reviewed'].length ) {
					html += "<h2> Awaiting COMDT comments </h2>";
					html += "<table>";
					html += "	<thead>";
					html += "		<tr>";
					html += "			<th> Submitted </th>";
					html += "			<th> Name </th>";
					html += "			<th> Reasons for applying </th>";
					html += "		</tr>";
					html += "	</thead>";
					html += "	<tbody>";
					for( var i=0; i< json['ac_reviewed'].length; i++ ) {
						html += "		<tr>";
						html += "			<td>"+json['ac_reviewed'][i].created+"</td>";
						html += "			<td>"+json['ac_reviewed'][i].name+"</td>";
						html += "			<td>"+json['ac_reviewed'][i].reasons_for_applying+"</td>";
						html += "			<td><button type='button' class='external link' data-appid='"+json['ac_reviewed'][i].application_id+"'>View</button></td>";
						html += "		</tr>";
					}
					html += "	</tbody>";
					html += "</table>";	
				}
				
				if( json['completed'].length ) {
					html += "<h2> Complete </h2>";
					html += "<table>";
					html += "	<thead>";
					html += "		<tr>";
					html += "			<th> Submitted </th>";
					html += "			<th> Name </th>";
					html += "			<th> Reasons for applying </th>";
					html += "		</tr>";
					html += "	</thead>";
					html += "	<tbody>";
					for( var i=0; i< json['completed'].length; i++ ) {
						html += "		<tr>";
						html += "			<td>"+json['completed'][i].created+"</td>";
						html += "			<td>"+json['completed'][i].name+"</td>";
						html += "			<td>"+json['completed'][i].reasons_for_applying+"</td>";
						html += "			<td><button type='button' class='external link' data-appid='"+json['completed'][i].application_id+"'>View</button></td>";
						html += "		</tr>";
					}
					html += "	</tbody>";
					html += "</table>";
				}
				
				html += "<script>";
				html += "	jQuery('button.external.link').button({ text:false, icons: { primary: 'ui-icon-extlink' } }).css({padding:'0px'}).click(function(){";
				html += "		window.open('"+URLs.eoi_address+"&eoi_id='+jQuery(this).data('appid'), '_blank');";
				html += "	});";
				html += "</script>";
				
				$('<div id="applicationlist"></div>').empty().html(html).dialog({
					title: "Applications",
					modal: false,
					width: "80%",
					close: function(){ $(this).dialog("destroy"); },
					buttons: [{
						text: 'OK',
						click: function(){ $(this).dialog("destroy"); }
					}] 
				}).parent().css({zIndex:10000});
				
				
			});
		});
	});