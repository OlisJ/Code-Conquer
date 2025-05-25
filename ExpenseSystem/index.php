
}
?>
<?php
  include("session.php");
  $exp_category_dc = mysqli_query($con, "SELECT expensecategory FROM expenses WHERE user_id = '$userid' GROUP BY expensecategory");
  $exp_amt_dc = mysqli_query($con, "SELECT SUM(expense) FROM expenses WHERE user_id = '$userid' GROUP BY expensecategory");

  $exp_date_line = mysqli_query($con, "SELECT expensedate FROM expenses WHERE user_id = '$userid' GROUP BY expensedate");
  $exp_amt_line = mysqli_query($con, "SELECT SUM(expense) FROM expenses WHERE user_id = '$userid' GROUP BY expensedate");
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <script src="js/feather.min.js"></script>

  <title>Expense Manager - Dashboard</title>
  <style>
    body {
      background-color: #efefef;
      color: #000;
    }

    #sidebar-wrapper,
    #sidebar-wrapper .user,
    #sidebar-wrapper .sidebar-heading,
    #sidebar-wrapper .list-group,
    #sidebar-wrapper .list-group-item {
      background-color: #fff !important;
      color: #000 !important;
      border: none !important;
    }

    .container-fluid,
    .row,
    .col,
    .col-md {
      background-color: #fff !important;
      color: #000 !important;
    }

    .card,
    .card-header,
    .card-body,
    .card-title {
      background-color: #fff !important;
      color: #000 !important;
      border: 1px solid #ddd !important;
    }

    .list-group-item.sidebar-active,
    .list-group-item:hover {
      background-color: #f8f9fa !important;
      color: #28a745 !important;
    }

    .navbar,
    .navbar-light,
    .border-bottom {
      background-color: #fff !important;
      color: #000 !important;
      border-color: #ddd !important;
    }

    .navbar.border-bottom {
      border-bottom: 1px solid #ddd !important;
      box-shadow: none !important;
    }

    .dropdown-menu {
      background-color: #fff !important;
      color: #000 !important;
    }

    .dropdown-item {
      color: #000 !important;
    }

    .dropdown-item:hover,
    .dropdown-item:focus {
      background-color: #f8f9fa !important;
      color: #28a745 !important;
    }

    .user h5,
    .user p {
      color: #000 !important;
    }

    .card a {
      color: #000 !important;
      font-weight: 500;
    }

    .card a:hover {
      color: #28a745 !important;
      text-decoration: dotted;
    }

    .form-control,
    .form-control:focus,
    input,
    textarea,
    select {
      background-color: #fff !important;
      color: #000 !important;
      border: 1px solid #ccc !important;
    }

    .form-label,
    label {
      color: #000 !important;
    }

    .table,
    .table th,
    .table td {
      background-color: #fff !important;
      color: #000 !important;
      border-color: #ccc !important;
    }

    .btn,
    .btn-primary,
    .btn-success,
    .btn-danger,
    .btn-secondary {
      background-color: #fff !important;
      color: #000 !important;
      border: 1px solid #ccc !important;
    }

    .btn:hover,
    .btn:focus {
      background-color: #f8f9fa !important;
      color: #28a745 !important;
      border: 1px solid #28a745 !important;
    }

    h1, h2, h3, h4, h5, h6 {
      color: #000 !important;
    }

    a, a:visited {
      color: #000 !important;
    }

    .bg-light, .bg-white, .bg-secondary, .bg-body, .bg-transparent {
      background-color: #fff !important;
      color: #000 !important;
    }

    .border, .border-bottom, .border-top, .border-left, .border-right {
      border-color: #ddd !important;
    }

    .modal-content,
    .popover,
    .tooltip-inner,
    .input-group-text {
      background-color: #fff !important;
      color: #000 !important;
      border-color: #ddd !important;
    }

    p, span {
      color: #000 !important;
    }

    img {
      background: transparent !important;
    }

    ::-webkit-scrollbar {
      width: 8px;
      background: #efefef;
    }

    ::-webkit-scrollbar-thumb {
      background: #ddd;
    }

    canvas {
      background-color: #fff !important;
    }
  </style>

</head>

<body>

  <div class="d-flex" id="wrapper">

    <div class="border-right" id="sidebar-wrapper">
      <div class="user">
        <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="120">
        <h5><?php echo $username ?></h5>
        <p><?php echo $useremail ?></p>
      </div>
      <div class="sidebar-heading">Management</div>
      <div class="list-group list-group-flush">
        <a href="index.php" class="list-group-item list-group-item-action sidebar-active"><span data-feather="home"></span> Dashboard</a>
        <a href="add_expense.php" class="list-group-item list-group-item-action "><span data-feather="plus-square"></span> Add Expenses</a>
        <a href="manage_expense.php" class="list-group-item list-group-item-action "><span data-feather="dollar-sign"></span> Manage Expenses</a>
      </div>
      <div class="sidebar-heading">Settings </div>
      <div class="list-group list-group-flush">
        <a href="profile.php" class="list-group-item list-group-item-action "><span data-feather="user"></span> Profile</a>
        <a href="ai.php" class="list-group-item list-group-item-action "><span data-feather="info"></span>AiAssistant</a>
        <a href="logout.php" class="list-group-item list-group-item-action "><span data-feather="power"></span> Logout</a>
      </div>
    </div>

    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light  border-bottom">


        <button class="toggler" type="button" id="menu-toggle" aria-expanded="false">
          <span data-feather="menu"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="25">
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="profile.php">Your Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php">Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container-fluid">
        <h3 class="mt-4">Dashboard</h3>
        <div class="row">
          <div class="col-md">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col text-center">
                    <a href="add_expense.php"><img src="icon/addex.png" width="57px" />
                      <p>Add Expenses</p>
                    </a>
                  </div>
                  <div class="col text-center">
                    <a href="manage_expense.php"><img src="icon/maex.png" width="57px" />
                      <p>Manage Expenses</p>
                    </a>
                  </div>
                  <div class="col text-center">
                    <a href="profile.php"><img src="icon/prof.png" width="57px" />
                      <p>User Profile</p>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <h3 class="mt-4">Full-Expense Report</h3>
        <div class="row">
          <div class="col-md">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title text-center">Yearly Expenses</h5>
              </div>
              <div class="card-body">
                <canvas id="expense_line" height="150"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title text-center">Expense Category</h5>
              </div>
              <div class="card-body">
                <canvas id="expense_category_pie" height="150"></canvas>
              </div>
            </div>
          </div>
        </div>


      </div>
    </div>

  </div>

  <script src="js/jquery.slim.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/Chart.min.js"></script>

  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
  </script>
  <script>
    feather.replace()
  </script>
  <script>
    var ctx = document.getElementById('expense_category_pie').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [<?php while ($a = mysqli_fetch_array($exp_category_dc)) {
                    echo '"' . $a['expensecategory'] . '",';
                  } ?>],
        datasets: [{
          label: 'Expense by Category',
          data: [<?php while ($b = mysqli_fetch_array($exp_amt_dc)) {
                    echo '"' . $b['SUM(expense)'] . '",';
                  } ?>],
          backgroundColor: [
            '#6f42c1',
            '#dc3545',
            '#28a745',
            '#007bff',
            '#ffc107',
            '#20c997',
            '#17a2b8',
            '#fd7e14',
            '#e83e8c',
            '#6610f2'
          ],
          borderWidth: 1
        }]
      }
    });

    var line = document.getElementById('expense_line').getContext('2d');
    var myChart = new Chart(line, {
      type: 'line',
      data: {
        labels: [<?php while ($c = mysqli_fetch_array($exp_date_line)) {
                    echo '"' . $c['expensedate'] . '",';
                  } ?>],
        datasets: [{
          label: 'Expense by Month (Whole Year)',
          data: [<?php while ($d = mysqli_fetch_array($exp_amt_line)) {
                    echo '"' . $d['SUM(expense)'] . '",';
                  } ?>],
          borderColor: [
            '#adb5bd'
          ],
          backgroundColor: [
            '#6f42c1',
            '#dc3545',
            '#28a745',
            '#007bff',
            '#ffc107',
            '#20c997',
            '#17a2b8',
            '#fd7e14',
            '#e83e8c',
            '#6610f2'
          ],
          fill: false,
          borderWidth: 2
        }]
      }
    });
  </script>
</body>

</html>