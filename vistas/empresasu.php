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
if ($_SESSION['acceso']==1)
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
                          <h1 class="box-title">Clientes System GK <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                     <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Op.</th>
                            <th>Nombre empresa</th>
                            <th>Coumuna</th>
                            <th>Ciudad</th>
                            <th>Direccion</th>
                            <th>Contacto</th>
                            <th>Email</th>
                            <th>Logo</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Op.</th>
                            <th>Nombre empresa</th>
                            <th>Coumuna</th>
                            <th>Ciudad</th>
                             <th>Direccion</th>
                            <th>Contacto</th>
                            <th>Email</th>
                            <th>Logo</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 500px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                         <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Rut empresa:</label>
                            <input type="text" class="form-control" name="rut_empresa" id="rut_empresa" maxlength="20" placeholder="Rut" 
                             onBlur="javascript:return Rut(document.formulario.rut_empresa.value)" required>
                          </div>
                          <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Nombre legal:</label>
                            <input type="hidden" name="id_empresa" id="id_empresa">
                            <!--input type="hidden" name="tipo_persona" id="tipo_persona" value="Cliente -->
                            <input type="text" class="form-control"  style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();" name="nombre_legal_empresa" id="nombre_legal_empresa" maxlength="150" placeholder="Nombe legal" required>
                          </div>
                           <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Nombre fantasía:</label>                                                 
                            <input type="text" class="form-control"  style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();" name="nombre_fantasia_empresa" id="nombre_fantasia_empresa" maxlength="150" placeholder="Nombe de fantasía" required>
                          </div>

                           <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Slogan o frase:</label>                                                 
                            <input type="text" class="form-control" name="slogan_empresa" id="slogan_empresa" maxlength="150" placeholder="Slogan de la empresa">
                          </div>
                           <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Dirección:</label>
                            <input type="text" class="form-control"  style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();" name="direccion_empresa" id="direccion_empresa" maxlength="70" placeholder="Dirección" required>
                          </div>
                          <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Ciudad/Lugar:</label>
                            <input type="text" class="form-control" style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();" name="ciudad_empresa" id="ciudad_empresa" maxlength="50" placeholder="Ciudad">
                          </div>
                          <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Comuna:</label>
                            <select id="empresa_idcomuna" name="empresa_idcomuna" class="form-control selectpicker" data-live-search="true" required></select>
                          </div>
                          <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Giro:</label>
                            <input type="text" class="form-control" style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();" name="giro_empresa" id="giro_empresa" maxlength="60" placeholder="Giro" required>
                          </div>
                          <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Teléfono:</label>
                            <input type="text" class="form-control" name="fono_empresa" id="fono_empresa" maxlength="20" placeholder="Télefono">
                          </div>
                          <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Email:</label>
                            <input type="email" class="form-control" name="email_empresa" id="email_empresa" maxlength="100" placeholder="Email">
                          </div>
                          
                           <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Web:</label>
                            <input type="text" class="form-control" name="web_empresa" id="web_empresa" maxlength="45" placeholder="Sitio web">
                          </div>

                           <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <label>Condicción(*):</label>
                            <select name="condicion_empresa" id="condicion_empresa" class="form-control selectpicker" required>
                               <option value="1">Activa</option>
                               <option value="0">Inactiva</option>
                            </select>
                           </div>

                           <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Nombre del contacto(*)</label>
                            <input type="text" class="form-control" name="nombre_contacto" id="nombre_contacto" maxlength="100" placeholder="Nombre del contacto" required="">
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="label label-warning">¡Importante! Logo en jpg y alto=0.625*ancho)</span>
                            <input type="file" accept="image/jpeg"  class="form-control" name="logo_empresa" id="logo_empresa">
                            <input type="hidden" name="logo_actual_empresa" id="logo_actual_empresa">
                            <img src="" width="150px" height="120px" id="imagenmuestra">
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
<script type="text/javascript" src="scripts/empresa.js"></script>
<script type="text/javascript" src="scripts/funciones.js"></script>

<?php 
}
ob_end_flush();
?>