$(document).ready(function () {
    console.log('Hola desde jquery');
    IngresarAgente();
    ReadAgentes();
    CambiarContrasena();
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
                $('#RespuestaIngresoAgentes').css('color', 'Green').html(Respuesta);
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


//Funcion para Validar que las contraseñas coincidan
var CambiarContrasena = function () {
    $('#ConfirmarNuevaContrasena').keyup(function (e) {
        var pass1 = $('#NuevaContrasena').val();
        var pass2 = $('#ConfirmarNuevaContrasena').val();
        if (pass1 == pass2) {
            $('#MensajeCoincidencia').css('color', 'Green').html('Coinciden');
        } else if (pass1 != pass2) {
            $('#MensajeCoincidencia').css('color', 'Red').html('No Coinciden');
        }
    });
}





