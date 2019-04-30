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
                          <h1 class="box-title">Gastos <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Op.</th>
                            <th>Gasto</th>
                            <th>Obra</th>
                            <th>Monto gasto</th>
                            <th>N° Documento</th>
                          
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                          <th>Op.</th>
                            <th>Gasto</th>
                            <th>Obra</th>
                            <th>Monto gasto</th>
                            <th>N° Documento</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Obra(*)</label>
                            <select id="idobra" name="idobra" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                             <input type="hidden" class="form-control"  name="ido" id="ido" >
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Descripción gasto (*)</label>
                            <input type="hidden"  class="form-control"  name="id_gastos_obra" id="id_gastos_obra">
                            <input type="text" class="form-control" style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();" name="des_gl_gasto" id="des_gl_gasto" maxlength="150" placeholder="Descripcion gasto" required>
                          </div>
                           <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>N° Factura/Boleta (0= Sin documento)</label>
                            <input type="number" class="form-control" name="num_documento" id="num_documento" >
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Monto (C/Impuesto) (*)</label>
                            <input type="number" class="form-control"  name="monto_gasto" id="monto_gasto"  required>
                          </div>
                          
                         <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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
  
<?php
}
else
{
  require 'noacceso.php';
}
require 'footer.php';

?>
<script type="text/javascript" src="scripts/gasto.js"></script>


<?php 
}
ob_end_flush();
?>