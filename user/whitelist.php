<?php

include_once '../core/userarea.php';
include_once '../core/supporterarea.php';
include_once '../core/header.php';
include_once '../core/dashboard.php';

include_once '../class/Application.class.php';

include_once '../class/assets/dbconfig.php';

?>

<body>
	<div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Bekleyen başvurular</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

			<?php

				$count = Application::getCount();

				if($count < 1)
				{
					print ("<blockquote><p>Bekleyen başvuru yok.</p></blockquote>"); die();
				}
			?>

			<div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
								<th>IP</th>
								<th>Tarih</th>
								<th>Hesap</th>
								<th>İsim</th>
								<th>Başvuru</th>
								<th>Durum</th>
                            </tr>
                            </thead>
                            <tbody>

							<?php

								$db = getDB();
								$stmt = $db->prepare("SELECT id FROM character_applications WHERE is_accepted IS NULL ORDER BY created_at ASC;");
								$stmt->execute();

								while($data = $stmt->fetch(PDO::FETCH_ASSOC)) {

									print ("<tr class='gradeA'>");

									$app = new Application($data['id']);
									$id = $app->getID();
									$name = $app->getName();
									$date = $app->getDate();
									$account_id = $app->getAccountId();
									$account_name = $app->getAccountName();
									$handler_id = $app->getHandlerId();
									$ip = $app->getIP();

									$name = str_replace("_", " ", $name);

									print ("<td>$ip</td>");
									print ("<td>$date</td>");
									print ("<td><a href='profile.php?account_id=$account_id'>$account_name</a></td>");
									print ("<td>$name</td>");
									print ("<td><a href='whitelist_details.php?app_id=$id'>Görüntüle</a></td>");
									print ($handler_id != -1) ? ("<td>Değerlendirme aşamasında</td>") : ("<td>Değerlendirilecek</td>");
									print ("</tr>");
								}
							?>

                            </tbody>
							</table>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>

	<?php include_once '../core/footer.php'; ?>

</body>
