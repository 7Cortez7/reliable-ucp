<?php

if(isset($_POST['keyword']) != true or strlen($_POST['keyword']) < 3)
{
	header("location: index.php");
}

include_once '../core/userarea.php';
include_once '../core/staffarea.php';
include_once '../core/header.php';
include_once '../core/dashboard.php';

include_once '../class/assets/dbconfig.php';
include_once '../class/User.class.php';

$keyword = $_POST['keyword'];

?>
<body>
	<div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <?php print ("<h1 class='page-header'>Ara \"$keyword\"</h1>"); ?>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<div class="row">
                <div class="col-lg-3">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
									<th>Hesaplar</th>
                                </tr>
                            </thead>
							<tbody>

							<?php

								$db = getDB();
								$stmt = $db->prepare("SELECT id, username FROM accounts WHERE username LIKE CONCAT ('%', :keyword, '%')");
								$stmt->bindParam("keyword", $keyword, PDO::PARAM_STR);
								$stmt->execute();
								$acc_count = $stmt->rowCount();

								while($data = $stmt->fetch(PDO::FETCH_ASSOC)) {

									print ("<tr class='gradeA'>");

									$username = $data['username'];
									$id = $data['id'];

									print ("<td><a href='profile.php?account_id=$id'>$username</a></td>");

									print ("</tr>");
								}

								if($acc_count < 1) print ("<tr class='gradeA'><td>Hesap bulunamadı.</td></tr>");
							?>

                            </tbody>
							</table>

							<hr>
							<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Karakterler</th>
								</tr>
							</thead>
							<tbody>

							<?php

							$db = getDB();
							$stmt = $db->prepare("SELECT id, char_name FROM characters WHERE char_name LIKE CONCAT ('%', :keyword, '%')");
							$stmt->bindParam("keyword", $keyword, PDO::PARAM_STR);
							$stmt->execute();
							$char_count = $stmt->rowCount();

							while($data = $stmt->fetch(PDO::FETCH_ASSOC)) {

								print ("<tr class='gradeA'>");

								$char_name = $data['char_name'];
								$id = $data['id'];

								$char_name = str_replace("_", " ", $char_name);

								print ("<td><a href='character.php?character_id=$id'>$char_name</a></td>");

								print ("</tr>");
							}

							if($char_count < 1) print ("<tr class='gradeA'><td>Karakter bulunamadı.</td></tr>");

							?>

						</tbody>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
            <!-- /.row -->
		</div>
	</div>

	<?php include_once '../core/footer.php'; ?>

</body>
