$(document).ready(function () {
    console.log('Hola desde jquery');
    IngresarAgente();
    ReadAgentes();
    CambiarContrasena();
    ReadAccesWebToken();
    IngresoAccessWebToken();
    ActivarEmotes();
    ReadTransferenciaChat();
    ReadSalasChatTransferencia();
});

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


//Read AccesWebToken
var ReadAccesWebToken = function () {
    $.ajax({
        type: "GET",
        url: "ReadAccesWebToken",
        success: function (Respuesta) {
            let json = JSON.parse(Respuesta);
            let tbody = '';
            json.forEach(
                Consulta => {
                    tbody += `
                        <tr>
                            <td>${Consulta.idToken}</td>
                            <td>${Consulta.Instance}</td>
                            <td>${Consulta.Token}</td>
                        </tr>
                        `
                });
            $('#TablaTokenChatApi').html(tbody);
        },
        error: function (xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
        }
    });
}


//Ingreso de AccesWebToken
var IngresoAccessWebToken = function () {
    $('#btnIngresoAccesWebToken').click(function (e) {
        e.preventDefault();
        var form = $('#frmAccesWebToken').serialize();

        $.ajax({
            type: "POST",
            url: "InsertAccesWebToken",
            data: form,
            success: function (Respuesta) {
                $('#RespuestaIngresoToken').html(Respuesta)
                console.log(Respuesta);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
        $('#instancia').val('');
        $('#token').val('');
        ReadAccesWebToken();
    });
}


//Activar Emotes
var ActivarEmotes = function () {
    $(function () {
        window.emojiPicker = new EmojiPicker({
            emojiable_selector: '[data-emojiable=true]',
            assetsPath: 'app/master/views/assets/Emoji/img',
            popupButtonClasses: 'icon-smile'
        });
        window.emojiPicker.discover();
    });
}

//Consultar tabla para transferir chat
var ReadTransferenciaChat = function () {
    $.ajax({
        type: "GET",
        url: "ConsultandoUsuarioATransferir",
        success: function (Respuesta) {
            var json = JSON.parse(Respuesta);
            var tbody = '';
            json.forEach(
                consulta => {
                    tbody += `
                    <tr>
                        <td><input type="checkbox" name="idAgenteTransferir[]" value="${consulta.id}" class="form-check-input"></td>
                        <td>${consulta.nombre}</td>
                        <td>${consulta.apellido}</td>
                        <td>${consulta.usuario}</td>
                    </tr>
                    `;
                }
            );
            $('#TablaTransferirChat').html(tbody);
        }
    });
};


//Consultar salas de chat para transferir
var ReadSalasChatTransferencia = function () {
    $.ajax({
        type: "GET",
        url: "ConsultandoSalasChatSelector",
        success: function (Respuesta) {
            var json = JSON.parse(Respuesta);
            var select = '';
            json.forEach(
                consulta =>{
                    select +=`
                    <option value="${consulta.name}">${consulta.name}</option>
                    `
                }
            );
            $('#SeleccionSalaChat').html(select);
        }
    });
}