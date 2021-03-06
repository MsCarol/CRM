<?php
require "../Include/Config.php";
require "../Include/Functions.php";

use ChurchCRM\Service\SundaySchoolService;
use ChurchCRM\Service\DashboardService;

$dashboardService = new DashboardService();
$sundaySchoolService = new SundaySchoolService();

$groupStats = $dashboardService->getGroupStats();


$kidsWithoutClasses = $sundaySchoolService->getKidsWithoutClasses();
$classStats = $sundaySchoolService->getClassStats();
$classes = $groupStats['sundaySchoolClasses'];
$teachers = 0;
$kids = 0;
$families = 0;
$maleKids = 0;
$femaleKids = 0;
$familyIds = array();
foreach ($classStats as $class) {
  $kids = $kids + $class['kids'];
  $teachers = $teachers + $class['teachers'];
  $classKids = $sundaySchoolService->getKidsFullDetails($class["id"]);
  foreach ($classKids as $kid) {
    array_push($familyIds, $kid["fam_id"]);
    if ($kid["kidGender"] == "1") {
      $maleKids++;
    } else if ($kid["kidGender"] == "2") {
      $femaleKids++;
    }
  }
}


// Set the page title and include HTML header
$sPageTitle = gettext("Sunday School Dashboard");
require "../Include/Header.php";

?>
<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-aqua"><i class="fa fa-gg"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><?= gettext("Classes") ?></span>
        <span class="info-box-number"> <?= $classes ?> <br/></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-olive"><i class="fa fa-group"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><?= gettext("Teachers") ?></span>
        <span class="info-box-number"> <?= $teachers ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-orange"><i class="fa fa-child"></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><?= gettext("Students") ?></span>
        <span class="info-box-number"> <?= $kids ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-gray"><i class="fa fa-user"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><?= gettext("Families") ?></span>
        <span class="info-box-number"> <?= count(array_unique($familyIds)) ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-blue"><i class="fa fa-male"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><?= gettext("Boys") ?></span>
        <span class="info-box-number"> <?= $maleKids ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-fuchsia"><i class="fa fa-female"></i></span>

      <div class="info-box-content">
        <span class="info-box-text"><?= gettext("Girls") ?></span>
        <span class="info-box-number"> <?= $femaleKids ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
</div><!-- /.row -->
<div class="row">
  <div class="col-lg-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><?= gettext("Sunday School Reports") ?></h3>
      </div>
      <div class="box-body">
        <p>
          <a href="SundaySchoolReports.php"><?= gettext("Sunday School Reports"); ?></a><br/>
          <?= gettext("Generate class lists and attendance sheets"); ?>
        </p>
        <p>
          <a href="SundaySchoolClassListExport.php"><?= gettext("Export Sunday School to CSV") ?></a><br/>
          <?= gettext("Export All Classes, Kids, and Parent to CSV file"); ?>
        </p>
      </div>
    </div>
  </div>
  <!-- ./col -->
</div>
<!-- on continue -->
<div class="box box-info">
  <div class="box-header">
    <h3 class="box-title"><?= gettext("Sunday School Classes") ?></h3>
  </div>
  <div class="box-body">
    <table id="sundayschoolMissing" class="table table-striped table-bordered data-table" cellspacing="0" width="100%">
      <thead>
      <tr>
        <th></th>
        <th><?= gettext("Class") ?></th>
        <th><?= gettext("Teachers") ?></th>
        <th><?= gettext("Students") ?></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($classStats as $class) { ?>
        <tr>
          <td><a href='SundaySchoolClassView.php?groupId=<?= $class['id'] ?>'>
            <span class="fa-stack">
            <i class="fa fa-square fa-stack-2x"></i>
            <i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
          </td>
          <td><?= $class['name'] ?></td>
          <td><?= $class['teachers'] ?></td>
          <td><?= $class['kids'] ?></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</div>


<div class="box box-danger">
  <div class="box-header">
    <h3 class="box-title"><?= gettext("Students not in a Sunday School Class") ?></h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body table-responsive">
    <table id="sundayschoolMissing" class="table table-striped table-bordered data-table" cellspacing="0" width="100%">
      <thead>
      <tr>
        <th></th>
        <th><?= gettext("First Name")?></th>
        <th><?= gettext("Last Name")?></th>
        <th><?= gettext("Birth Date")?></th>
        <th><?= gettext("Age")?></th>
        <th><?= gettext("Home Address")?></th>
      </tr>
      </thead>
      <tbody>
      <?php

      foreach ($kidsWithoutClasses as $child) {
        extract($child);
        $birthDate = "";
        if ($birthYear != "") {
          $birthDate = $birthDay . "/" . $birthMonth . "/" . $birthYear;
        }

        echo "<tr>";
        echo "<td><a href='../PersonView.php?PersonID=" . $kidId . "'>";
        echo "	<span class=\"fa-stack\">";
        echo "	<i class=\"fa fa-square fa-stack-2x\"></i>";
        echo "	<i class=\"fa fa-search-plus fa-stack-1x fa-inverse\"></i>";
        echo "	</span></a></td>";
        echo "<td>" . $firstName . "</td>";
        echo "<td>" . $LastName . "</td>";
        echo "<td>" . $birthDate . "</td>";
        echo "<td>" . FormatAge($birthMonth, $birthDay, $birthYear, "") . "</td>";
        echo "<td>" . $Address1 . " " . $Address2 . " " . $city . " " . $state . " " . $zip . "</td>";
        echo "</tr>";
      }

      ?>
      </tbody>
    </table>
  </div>
</div>

<?php require "../Include/Footer.php" ?>
