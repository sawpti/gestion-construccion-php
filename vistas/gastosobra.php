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

if ($_SESSION['consultaps']==1)
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
                          <h1 class="box-title">Consulta de gastos por obra</h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <!--div class="panel-body table-responsive" id="listadoregistros"-->
                         <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
                           <label>Obra</label>
                           <select name="idobra" id="idobra" class="form-control selectpicker" data-live-search="true" required>  
                           </select>
                           <button class="btn btn-success" onclick="listar()">Mostrar</button>            
                         </div>
                         <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label name="total_label" id="total_label">Total gastos</label>
                                  <input type="text" class="form-control" name="total" id="total" readonly="true">
                          </div>

                     <!--/div-->

                                                                           
                        <div class="form-inline col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        
                        <br></br>

                        </div>

                       <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Obra</th>
                            <th>Gasto</th>
                            <th>N° Documento</th>
                            <th>Monto</th>           
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <!--th>  </th-->
                            <!--th> </th-->
                            <!--th class="text-center"> </th-->
                            <th  bgcolor="#4CAF50" colspan="4" >Monto</th>                                 
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
<script type="text/javascript" src="scripts/gastosobra.js"></script>
<?php 
}
ob_end_flush();
?>


