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
                          <h1 class="box-title">Empresas <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Op.</th>
                            <th>Rut</th>
                            <th>Nombre</th>
                            <th>Direccion</th>
                            <th>Ciudad</th>
                            <th>Comuna</th>
                            <th>Giro</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Tipo Empresa</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                           <th>Op.</th>
                            <th>Rut</th>
                            <th>Nombre</th>
                            <th>Direccion</th>
                            <th>Ciudad</th>
                            <th>Comuna</th>
                            <th>Giro</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>T. Empresa</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Número Documento:</label>
                            <input type="text" class="form-control" name="num_documento" id="num_documento" maxlength="20" placeholder="Rut" 
                             onBlur="javascript:return Rut(document.formulario.num_documento.value)" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Nombre:</label>
                            <input type="hidden" name="idpersona" id="idpersona">
                            <!--input type="hidden" name="tipo_persona" id="tipo_persona" value="Cliente -->
                            <input type="text" class="form-control"  style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();" name="nombre" id="nombre" maxlength="100" placeholder="Nombre del proveedor" required>
                          </div>
                           <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Dirección:</label>
                            <input type="text" class="form-control"  style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();" name="direccion" id="direccion" maxlength="70" placeholder="Dirección" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Ciudad/Lugar:</label>
                            <input type="text" class="form-control" style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();" name="ciudad" id="ciudad" maxlength="50" placeholder="Ciudad">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Comuna:</label>
                            <select id="idcomuna" name="idcomuna" class="form-control selectpicker" data-live-search="true" required></select>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Giro:</label>
                            <input type="text" class="form-control" style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();" name="giro" id="giro" maxlength="60" placeholder="Giro" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Teléfono:</label>
                            <input type="text" class="form-control" name="telefono" id="telefono" maxlength="20" placeholder="Télefono">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Email:</label>
                            <input type="email" class="form-control" name="email" id="email" maxlength="100" placeholder="Email">
                          </div>
                          
                           <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Web:</label>
                            <input type="text" class="form-control" name="web" id="web" maxlength="45" placeholder="Sitio web">
                          </div>

                           <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Tipo empresa(*):</label>
                            <select name="tipo_persona" id="tipo_persona" class="form-control selectpicker" required>
                               <option value="Cliente">Cliente</option>
                               <option value="Proveedor">Proveedor</option>
                               <option value="Cliente y proveedor">Cliente y proveedor</option>
                            </select>
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
<script type="text/javascript" src="scripts/persona.js"></script>
<script type="text/javascript" src="scripts/funciones.js"></script>

<?php 
}
ob_end_flush();
?>