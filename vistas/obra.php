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
                          <h1 class="box-title">Obra<button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>N°Ctzn</th>
                            <th>N°O</th>
                            <th>Nombre Obra</th>
                            <th>Título Cotización</th>
                            <th>Ciudad/Lugar</th>
                            <th>Dirección</th>
                            <th>Cliente</th>
                            <th>Jefe Obra</th>
                            <th>F. Inicio</th>
                            <th>F. Término</th>
                            <th>Avance (%)</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>N° Ctzn</th>
                            <th>Título Cotización</th>
                            <th>Ciudad/Lugar</th>
                            <th>Dirección</th>
                            <th>Cliente</th>
                            <th>Jefe Obra</th>
                            <th>Descripción</th>
                            <th>F. Inicio</th>
                            <th>F. Término</th>
                            <th>Avance (%)</th>
                            <th>N° Trab.</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label>Cotización(*):</label>
                            <input type="hidden" name="idObra" id="idObra">
                            <select id="numCotizacion" name="numCotizacion" class="form-control selectpicker" data-live-search="true" required>
                            </select>
                          </div>
                          
                           <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label>Jefe de Obra(*):</label>
                            <select id="idEncaragadoObra" name="idEncaragadoObra" class="form-control selectpicker" data-live-search="true" required>
                          </select>
                          </div>
                           <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label>Nombre de la obra(*):</label>
                            <input  type="text" class="form-control"  name="nomObra" id="nomObra" maxlength="100" placeholder="Nombre de la obra" required="true" style="text-transform:uppercase;"   onkeyup="javascript:this.value=this.value.toUpperCase();">
                          </div>


                           
                          <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <label>Valor estimado:</label>
                            <input type="number" class="form-control" name="valEstimadoObra" id="valEstimadoObra" placeholder="Al finalizar reemplace por el v. real" >
                          </div>
                            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <label>N° Trabajadores:</label>
                            <input type="number" class="form-control" name="numTrabajadores" id="numTrabajadores" required="true">
                          </div>

                           <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <label>Fecha inicio(*):</label>
                            <input type="date" class="form-control" name="fechaInicio" id="fechaInicio" >
                          </div>

                           <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <label>Fecha término estimada:</label>
                            <input type="date" class="form-control" name="fechaTermino" id="fechaTermino">
                          </div>


                           <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <label>Descripción de la obra: </label>
                            <textarea class="form-control" rows="5" placeholder="Aquí puedes incluir más información de la obra." name="desObra" id="desObra" maxlength="250"></textarea>
                          </div>

                           <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <label>Porcentaje de avance:</label>
                            <input type="number" class="form-control" name="porAvanceObra" id="porAvanceObra"  min="0" max="100">
                          </div>


                           <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <label>Estado de la obra:</label>
                            <select name="estadoObra" id="estadoObra" class="form-control selectpicker">
                               <option value="Ejecutando">Ejecutando</option>
                               <option value="Terminada">Terminada</option>
                               <option value="Abortada">Abortada</option>
                            </select>
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
  <!-- Fin modal -->
<?php
}
else
{
  require 'noacceso.php';
}

require 'footer.php';
?>
<script type="text/javascript" src="scripts/obra.js"></script>
<?php 
}
ob_end_flush();
?>
