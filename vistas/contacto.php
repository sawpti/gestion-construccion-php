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
if ($_SESSION['empresas']==1)
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
                          <h1 class="box-title">Contacto <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Op.</th>
                            <th>Nombre</th>
                            <th>Fono</th>
                            <th>E-mail</th>
                            <th>Cargo</th>
                            <th>Empresa</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                           <th>Op.</th>
                            <th>Nombre</th>
                            <th>Fono</th>
                            <th>E-mail</th>
                            <th>Cargo</th>
                            <th>Empresa</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Empresa(*):</label>
                            <select id="idempresa" name="idempresa" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Nombre:</label>
                            <input type="hidden"  class="form-control"  name="idcontacto" id="idcontacto">
                            <input type="text" class="form-control" style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();" name="nombre" id="nombre" maxlength="150" placeholder="Nombre y Apellido" required>
                          </div>
                           <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Cargo:</label>
                            <input type="text" class="form-control" style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();"  name="cargo" id="cargo" maxlength="45" placeholder="Cargo" >
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Teléfono</label>
                            <input type="text" class="form-control"  name="fono" id="fono" maxlength="45" placeholder="Télefono" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>E-mail</label>
                            <input type="email" class="form-control"   name="email" id="email" maxlength="150" placeholder=" E-mail">
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
<script type="text/javascript" src="scripts/contacto.js"></script>
<script type="text/javascript" src="scripts/funciones.js"></script>

<?php 
}
ob_end_flush();
?>