<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Snowker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
</head>
<style>

table {
   border-collapse: collapse;
   width: 50%;
   padding: 5px;
   color: Cyan;
   background-color: Black;
   font-family: monospace;
   font-size: 22px;
   text-align: center;
}

tr {
   background-color: Black;
   color: Cyan;
   text-align: center;
}

th {
   background-color: #Black;
   color: Cyan;
   text-align: center;
}

tr:nth-child(even) {background-color: #Black}

.title_lines {
    position: relative;
    font-size: 30px;
    z-index: 1;
    overflow: hidden;
    text-align: center;
    color: white;
}

.title_lines:before, .title_lines:after {
    position: absolute;
    top: 51%;
    overflow: hidden;
    width: 48%;
    height: 1px;
    content: '\a0';
    background-color: white;
    margin-left: 2%;
}
.title_lines:before {
    margin-left: -50%;
    text-align: right;
}

body {
  background-color: black;
}

h1   {
  color: white;
  text-align: center;
  font-family: 'Roboto Mono', monospace;
}

p {
  margin: 20px auto;
  border: 2px solid Cyan;
  background-color: black;
  padding: 5px;
  color: white;
  text-align: center;
  height: 150px;
  width: 200px;
}

a    {
  border: 2px solid Cyan;
  background-color: black;
  padding: 5px;
  color: white;
}

</style>
</head>
<body>

  <h1 class="title_lines">Snowker</h1>

  <div style="text-align:center">
    <a href="http://snowker.xyz/phpmyadmin">Database</a>
  </div>

  <div class="container">
      <div class="row">
          <div class="col-md-12">
              <div class="card mt-4">
                  <div class="card-header">
                      <h4> Ingrese un valor a buscar </h4>
                  </div>
                  <div class="card-body">
                      <div class="row">
                          <div class="col-md-7">

                              <form action="" method="GET">
                                  <div class="input-group mb-3">
                                      <input type="text" name="search" required value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control" placeholder="Search data">
                                      <button type="submit" class="btn btn-primary">Buscar</button>
                                  </div>
                              </form>

                          </div>
                      </div>
                  </div>
              </div>
          </div>

          <div class="col-md-12">
              <div class="card mt-4">
                  <div class="card-body">
                      <table class="table table-bordered">
                          <tbody>
                              <?php
                                  $con = mysqli_connect("localhost","root","password","Mediciones_IoT");

                                  if(isset($_GET['search']))
                                  {
                                      $filtervalues = $_GET['search'];
                                      //$query = "SELECT * FROM Mediciones WHERE CONCAT(Temperatura,Humedad,CO2,Alcohol,Fecha,Hora,Num_Equipo,ID_Lugar) LIKE '%$filtervalues%' ";
				      $query = "SELECT Temperatura,Humedad,CO2,Alcohol,Fecha,Hora,Num_Equipo,ID_Lugar FROM Mediciones";
                                      $query_run = mysqli_query($con, $query);
				      ?>
				      <thead>
                                         <tr>
                                            <th><?= $filtervalues ?></th>
                                         </tr>
                                      </thead>
				      <?php
                                      if(mysqli_num_rows($query_run) > 0)
                                      {
                                          foreach($query_run as $items)
                                          {
					      if($filtervalues == 'Toda') {
						?>
					         <tr>
						    <td><?= $items['Temperatura']; ?></td>
						    <td><?= $items['Humedad']; ?></td>
						    <td><?= $items['CO2']; ?></td>
						    <td><?= $items['Alcohol']; ?></td>
						    <td><?= $items['Fecha']; ?></td>
						    <td><?= $items['Hora']; ?></td>
						    <td><?= $items['Num_Equipo']; ?></td>
						    <td><?= $items['ID_Lugar']; ?></td>
						 </tr>
					     <?php
					      }
					      else {
					      ?>
                                                 <tr>
                                                     <td><?= $items[$filtervalues]; ?></td>
                                                 </tr>
                                              <?php
					      }
                                          }
                                      }
                                      else
                                      {
                                          ?>
                                              <tr>
                                                  <td colspan="4">No Record Found</td>
                                              </tr>
                                          <?php
                                      }
                                  }
                              ?>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
