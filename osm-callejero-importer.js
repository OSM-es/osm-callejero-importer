$(document).ready(function () {
    ajaxFillSelect("clase_via", "clase_via");
    ajaxFillSelect("nombre_via", "nombre_via");
    ajaxFillSelect("distrito", "cod_distrito");
    ajaxFillSelect("barrio", "cod_barrio");
    ajaxFillSelect("codigo_postal", "codigo_postal");
});


function ajaxFillSelect(select_name, column_name)
{
    $.ajax("lists.php?field="+column_name, {
	format: "json",
	success: function(data) {
	    fillSelect(select_name, data);
	}
    });
}

function fillSelect(select, data) 
{
    for (var i in data) {
	$("#"+select).append($('<option>', { value: data[i] }).text(data[i]));
    }
}
