$(document).ready(function () {
    console.log('Hola desde jquery');
    CambiarContrasena();
    IngresarAgente();
    ReadAgentes();
});

//Ingresar Agente por ajax en el boton de dicho formulario
let IngresarAgente = function () {
    $('#btnRegistrarAgente').click(function (e) {
        e.preventDefault();


        let Formulario = $('#frmIngresarAgente').serialize();
        $.ajax({
            type: "POST",
            url: "AgregarAgente",
            data: Formulario,
            success: function (Respuesta) {
                $('#RespuestaIngresoAgentes').html(Respuesta);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });

        //Limpiando vasillas y desmarcando checkbox
        $('#user').val('');
        $('#nombre').val('');
        $('#apellido').val('');
        $('#documento').val('');
        $('#admin').prop('checked', false);
        $('#master').prop('checked', false);
        $('#telefono').val('');
        $('#direccion').val('');
        $('#correo').val('');
        $('#password').val('');
        $('#ConfirmacionPassword').val('');
    });


}


//Funcion para mostrar Datatable por Ajax
var ReadAgentes = function () {
    var table = $('#TablaAgentes').DataTable({
        "ajax": {
            "method": "POST",
            "url": "Datatable"
        },
        "columns": [
            { "data": "usuario" },
            { "data": "nombre" },
            { "data": "apellido" },
            { "data": "password" },
            { "data": "admin" }
        ]
    });
}


//Funcion para Cambiar Contraseña por Ajax
var CambiarContrasena = function () {
    $('#btnCambiarContrasenaAgente').click(function (e) {
        e.preventDefault();

        var Formulario = $('#frmCambioContrasena').serialize();
        $.ajax({
            type: "post",
            url: "CambiarContrasena",
            data: Formulario,
            success: function (Respuesta) {
                console.log(Respuesta);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    });
}





