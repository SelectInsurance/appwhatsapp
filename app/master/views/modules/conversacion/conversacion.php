<div class="container-fluid">
    <div class="row text-center p-3">
        <div class="col-12 col-sm-1 col-md-2 col-lg-4 col-xl-4"></div>
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-6 text-center">
            <h3>Conversacion</h3>
        </div>
        <div class="col-12 col-sm-1 col-md-2 col-lg-2 col-xl-2">
            <form id="frmidparaconsultarDatatableConversacion">
                <input type="text" name="id" value="<?= $idAgente ?>" class="form-control" readonly>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-1 col-md-2 col-lg-4 col-xl-4"></div>
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-6 border">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre de la Sala</th>
                        <th>Nombre Agente</th>
                        <th>Numero Mensaje</th>
                        <th>Cuerpo Mensaje</th>
                    </tr>
                </thead>
                <tbody id="tablaconversacion">

                </tbody>
            </table>
        </div>
        <div class="col-12 col-sm-1 col-md-2 col-lg-2 col-xl-2"></div>
    </div>
</div>