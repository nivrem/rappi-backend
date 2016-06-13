String.prototype.trim = function () {
    return this.replace(/^\s+|\s+$/g, "");
}
String.prototype.ltrim = function () {
    return this.replace(/^\s+/g, "");
}
String.prototype.rtrim = function () {
    return this.replace(/\s+$/g, "");
}

$.ajaxSetup({
    headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
});

function enviarCampos(tarea, clase, capa, php, method) {
    $.ajax({
        type: method,
        url: php,
        data: $(clase).serializeArray(),
        beforeSend: function (jqXHR, settings) {
            antesDeEnviar(jqXHR, settings, tarea, capa);
        },
        success: function (msg, textStatus, jqXHR) {
            envioExitoso(msg, textStatus, jqXHR, tarea, capa);
        }
    });
}

function enviarFormulario(tarea, formulario, capa, php, method) {
    console.log(formulario);
    console.log($("#" + formulario).serialize());
    $.ajax({
        type: method,
        url: php,
        data: $("#" + formulario).serialize(),
        beforeSend: function (jqXHR, settings) {
            antesDeEnviar(jqXHR, settings, tarea, capa);
        },
        success: function (msg, textStatus, jqXHR) {
            envioExitoso(msg, textStatus, jqXHR, tarea, capa);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error: " + textStatus + " - " + errorThrown);
        }
    });
}

function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
//alert(charCode);
    if (charCode != 45 && charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 96 || charCode > 105))
        return false;

    return true;
}

