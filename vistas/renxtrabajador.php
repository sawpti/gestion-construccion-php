
<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

/*if (strlen(session_id()) < 1) 
  session_start();*/
//$idempresa_u=$_SESSION['id_empresa'];

if (!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
require 'header.php';

if ($_SESSION['consultav']==1)
{

  $fecha = date('Y-m-d');
  $fechainicio = strtotime ( '-30 day' , strtotime ( $fecha ) ) ;
  $fechainicio = date ( 'Y-m-d' , $fechainicio );

  
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
                          <h1 class="box-title">Consulta de Ventas por producto o servicio</h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                          <label>Fecha inicio periodo</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fechainicio; ?>">
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                          <label>Fecha fin periodo</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-inline col-lg-4 col-md-4 col-sm-6 col-xs-12">
                          <label>Selecciona una obra</label>
                          <select name="idobra" id="idobra" class="form-control selectpicker" data-live-search="true" required>                         	
                          </select>                          
                        </div>
                        <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Selecciona un material</label>
                          <select name="idmateral" id="idmaterial" class="form-control selectpicker" data-live-search="true" required>                         	
                          </select>                          
                        </div>
                        <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label>Selecciona un maestro</label>
                          <select name="idmaestro" id="idmaestro" class="form-control selectpicker" data-live-search="true" required>                         	
                          </select>                          
                         
                        </div>
                         <div class="form-inline col-lg-12 col-md-12 col-sm-12 col-xs-12">
                         <hr style="color: #0056b2;" />
                          
                                             
                          <button  class="btn btn-success btn-block" onclick="listar()">Mostrar consulta</button>
                          <hr style="color: #0056b2;" />

                        </div>
                        
                        
                    
                         
                        </table>
                        
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                            
                          <thead>
                            <th>Fecha</th>
                            <th>Largo</th>
                            <th>Ancho</th>
                            <th>Cantidad</th>
                            <th>TOTAL  M2</th>
                            <th>Fecha</th>
                            <th>Largo</th>
                            <th>Cantidad</th>
                            <th>TOTAL  M</th>
                          </thead>
                          
                          
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th  colspan="9">Opciones</th>
                          </tfoot>
                        </table>
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
<script type="text/javascript" src="scripts/ventasxserpro.js"></script>
<?php 
}
ob_end_flush();
?>


