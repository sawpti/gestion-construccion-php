<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
require 'header.php';

if ($_SESSION['epagos']==1)
{
?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Estado de Pago<button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Nombre Obra</th>
                            <th>N°EP O</th>
                            <th>N° Factura</th>
                            <th>Encargado Obra</th>
                            <th>Impuesto</th>
                            <th>Total Neto</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Nombre Obra</th>
                            <th>N°EP O</th>
                            <th>N° Factura</th>
                            <th>Encargado Obra</th>
                            <th>Impuesto</th>
                            <th>Total Neto</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Obra(*):</label>
                            <input type="hidden" name="idestadopago" id="idestadopago">
                            <select id="obra_id" name="obra_id" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div>
                          
                           <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>N° EP de la Obra(*):</label>
                            <input type="number" class="form-control" name="numEPObra" id="numEPObra" require>
                          </select>
                          </div>
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fecha(*):</label>
                            <input type="date" class="form-control" name="fechaEP" id="fechaEP" required="">
                          </div>


                          <div class="form-group col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <label>N° Factura:</label>
                                <input type="number" class="form-control" name="numFactura" id="numFactura" require>
                             </div>

                              <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            <label>Impuesto(*):</label>
                            <select name="tipo_impuesto" id="tipo_impuesto" class="form-control selectpicker" required="">
                               <option value="IVA">IVA</option>
                               <option value="NETO">NETO</option>
                              
                            </select>
                          </div>

                          <div class="form-group col-lg-2 col-md-2 col-sm-8 col-xs-12">
                            <label>Impuesto:</label>
                            <input type="text" class="form-control" name="impuesto" id="impuesto" required="">
                          </div>

                          
                          <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label>Nombre anexo:</label>
                            <input type="text" class="form-control" name="anexo" id="anexo" maxlength="100" placeholder=" Nombre del anexo, Ej: detallemedidas.pdf" >
                          </div>
                           <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12">
                            <label>Estado(*):</label>
                            <select name="estadoEP" id="estadoEP" class="form-control selectpicker" required="">
                               <option value="Enviado">Enviado</option>
                               <option value="Pagado">Pagado</option>
                               <option value="Anulado">Anulado</option>
                              
                            </select>
                          </div>       

                           <div class="form-group col-lg-6 col-md-6 col-sm-8 col-xs-12">
                            <label>EETT: </label>
                            <textarea class="form-control" rows="3" placeholder="Aquí puedes incluir generalidades de las Especificaciones Técnicas. Para abordar aspectos más puntuales la recomendación es crear un anexo en Word u otro editor de texto." name="obns1EP" id="obns1EP" maxlength="250"></textarea>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-8 col-xs-12">
                            <label>Observaciones: </label>
                            <textarea class="form-control" rows="3" placeholder="Aquí puedes incluir condiciones de pago, plazos de entrega y otras generalidades" name="obns2EP" id="obns2EP" maxlength="250"></textarea>
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <a data-toggle="modal" href="#myModal">           
                              <button id="btnAgregarArt" type="button" class="btn btn-primary"> <span class="fa fa-plus"></span> Agregar Detalle</button>
                            </a>
                          </div>

                          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Producto o Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Precio Venta</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                </thead>
                                <tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">$ 0.00</h4><input type="hidden" name="total_estadopago" id="total_estadopago"></th> 
                                </tfoot>
                                <tbody>
                                  
                                </tbody>
                            </table>
                          </div>

                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                            <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                        </form>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width: 55% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione un Producto o Servicio</h4>
        </div>
        <div class="modal-body table-responsive">
          <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Código</th>
                <th>Stock</th>
                <th>Precio Venta</th>
              
            </thead>
            <tbody>
              
            </tbody>
            <tfoot>
              <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Código</th>
                <th>Stock</th>
                <th>Precio Venta</th>
                
            </tfoot>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>        
      </div>
    </div>
  </div>  
  <!-- Fin modal -->
<?php
}
else
{
  require 'noacceso.php';
}

require 'footer.php';
?>
<script type="text/javascript" src="scripts/epago.js"></script>
<?php 
}
ob_end_flush();
?>
