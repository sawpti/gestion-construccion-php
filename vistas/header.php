<?php 
if (strlen(session_id())<1)
{
  session_start();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $_SESSION['nombre_fantasia'] ?> | System GK</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="../public/img/favicon.ico">

    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">    
    <link href="../public/datatables/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="../public/datatables/responsive.dataTables.min.css" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">

  </head>
  <body class="hold-transition skin-green-light sidebar-mini">
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="index2.html" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>System</b>GK</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>System GK</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="../files/usuarios/usuario.png" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../files/usuarios/usuario.png" class="img-circle" alt="User Image">
                    <p>
                      <?php echo $_SESSION['cargo'] ?>
                      <small><?php echo $_SESSION['nombre_fantasia'] ?></small>
                    </p>
                  </li>
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    
                    <div class="pull-right">
                      <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat">Cerrar</a>
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">       
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header"></li>
           
            <?php 
             if ($_SESSION ['escritorio']==1)
              {
               echo '<li>
              <a href="escritorio.php">
                <i class="fa fa-tasks"></i> <span>Escritorio</span>
              </a>
            </li>';
              }
            ?>

            <?php 
             if ($_SESSION ['empresas']==1)
              {
               echo '<li class="treeview">
              <a href="#">
                <i  class="fa fa-briefcase" aria-hidden="true" ></i>
                <span>Gestión empresas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="empresas.php"><i class="fa fa-users"></i> Empresas</a></li>
                <li><a href="contacto.php"><i  class="fa fa-users"></i> Contactos</a></li>
                
              </ul>
            </li>';
              }
            ?>

            <?php 
             if ($_SESSION ['almacen']==1)
              {
               echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Productos y servicios</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="categoria.php"><i class="fa fa-circle-o"></i> Categorías</a></li>
                <li><a href="productos.php"><i class="fa fa-circle-o"></i> Productos</a></li>
                <li><a href="servicios.php"><i class="fa fa-circle-o"></i> Servicios</a></li>
                <li><a href="productoservicio.php"><i class="fa fa-circle-o"></i> Productos y servicios</a></li>
                <li><a target="_blank" href="../reportes/rptproductos.php"><i class="fa fa-circle-o"></i> Productos y Servicios PDF</a></li>
              </ul>
            </li>';
              }
            ?>
             <?php 
             if ($_SESSION ['compras']==1)
              {
               echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-th"></i>
                <span>Compras</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="ingreso.php"><i class="fa fa-circle-o"></i> Ingresos</a></li>
               
              </ul>
            </li>';
              }
            ?>
             <?php 
             if ($_SESSION ['ventas']==1)
              {
               echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-shopping-cart"></i>
                <span>Ventas</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="venta.php"><i class="fa fa-circle-o"></i> Ventas</a></li>
               
              </ul>
            </li> ';
              }
            ?>

            <?php 
             if ($_SESSION ['cotizaciones']==1)
              {
               echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-calculator" aria-hidden="true"></i>
                <span>Cotizaciones</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="cotizacion.php"><i class="fa fa-circle-o"></i> Cotizaciones</a></li>
                
              </ul>
            </li>';
              }
            ?>
             <?php 
             if ($_SESSION ['escritorio']==1)
              {
               echo '';
              }
            ?>

             <?php 
             if ($_SESSION ['epagos']==1)
              {
               echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-list-ol" aria-hidden="true"></i>
                <span>Administrición obras</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="obra.php"><i class="fa fa-building-o"></i> Obras</a></li>
                <li><a href="epagos.php"><i class="fa fa-money"></i> Estados de pago</a></li>
                <li><a href="gastos.php"><i class="fa fa-plus-square"></i> Agregar gasto</a></li>
                </ul>
            </li>';
              }
            ?>

             <?php 
             if ($_SESSION ['acceso']==1)
              {
               echo '<li class="treeview">
              <a href="#">
                <!--i class="fa fa-folder" ></i--> <i class="fa fa-users" aria-hidden="true"></i><span>Acceso</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="empresasu.php"><i class="fa fa-circle-o"></i> Empresas </a></li>
                <li><a href="usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                <li><a href="permiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
              </ul>
            </li>';
              }
            ?>

             <?php 
             if ($_SESSION ['consultam']==1)
              {
               echo ' <li class="treeview">
              <a href="#">
                <i class="fa fa-cubes" aria-hidden="true"></i> <span>Consultas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="productosser.php"><i class="fa fa-circle-o"></i> Productos y Servicios</a></li>  
                <li><a href="comprasfecha.php"><i class="fa fa-circle-o"></i> Compras</a></li> 
                <li><a href="ventasfechacliente.php"><i class="fa fa-circle-o"></i> Ventas por cliente</a></li>
                 <li><a href="ventasxserpro.php"><i class="fa fa-circle-o"></i> Ventas por producto o servicio</a></li> 
                <li><a href="gastosobra.php"><i class="fa fa-circle-o"></i> Gastos por obra</a></li> 
                <li><a href="renxtrabajador.php"><i class="fa fa-chevron-right fa-xs"></i>Rendimiento por trabajador </a></li>    
              </ul>
            </li>';
              }
            ?>

           <?php 
             if ($_SESSION ['epagos']==1)
              {
               echo '<li class="treeview">
              <a href="#">
                <i class="fa fa-cube" aria-hidden="true"></i>
                <span>Medidas construcción</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                 <li><a href="medida.php"><i class="fa fa-building-o  fa-xs"></i>Agregar medidas </a></li>
                 <li><a href="renxobra.php"><i class="fa fa-building-o fa-xs"></i>Informes de rendimiento </a></li>
              </ul>
            </li>';
              }
            ?>


            <li>
              <a href="#">
                <i class="fa fa-plus-square"></i> <span>Ayuda</span>
                <small class="label pull-right bg-red">PDF</small>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-info-circle"></i> <span>Acerca De...</span>
                <small class="label pull-right bg-yellow">System GK</small>
                             </a>
            </li>
                        
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
