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
                    <h1 class="page-header">Storico Applications</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			
			<div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>	
								<th>Personaggio</th>
								<th>Valutato da</th>
								<th>Esito</th>
								<th>Azioni</th>
                            </tr>
                            </thead>
                            <tbody>
							
							<?php
									
								$db = getDB();
								$stmt = $db->prepare("SELECT id FROM character_applications WHERE is_accepted IS NOT NULL AND created_at > date_sub(NOW(), INTERVAL 2 DAY) ORDER BY created_at DESC;");
								$stmt->execute();
										
								while($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
										
									print ("<tr class='gradeA'>");
											
									$app = new Application($data['id']);
									$id = $app->getID();
									$name = $app->getName();
									$handler_id = $app->getHandlerId();
									$handler_name = $app->getHandlerName();
									$status = $app->getStatus();
									$date = $app->getDate();
										
									$status = ($status) ? "Accettata" : "Rifiutata";	
									$name = str_replace("_", " ", $name);	
											
									print ("<td>$name</td>");
									print ("<td><a href='profile.php?account_id=$handler_id'>$handler_name</a></td>");
									print ("<td>$status</td>");
									print ("<td><a href='whitelist_details.php?app_id=$id'>Visualizza</a></td>");
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