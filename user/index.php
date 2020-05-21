<?php

include_once '../core/userarea.php';
include_once '../core/header.php';
include_once '../core/dashboard.php';

include_once '../class/User.class.php';
include_once '../class/assets/dbconfig.php';

$user = new User($_COOKIE['account']);
$id = $user->getID();

?>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Anasayfa</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"> <?php print $user->getCharactersCount(); ?></div>
                                    <div>Karakterler</div>
                                </div>
                            </div>
                        </div>
                        <?php print ("<a href='profile.php?account_id=$id'>"); ?>
                            <div class="panel-footer">
                                <span class="pull-left">Tüm karakterler</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-exclamation fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"> <?php print $user->getUnreadAlerts(); ?></div>
                                    <div>Yeni Bildirimler</div>
                                </div>
                            </div>
                        </div>
                        <a href="alerts.php">
                            <div class="panel-footer">
                                <span class="pull-left">Bildirimler</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-share fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"> <?php print $user->getApplicationsCount(); ?></div>
                                    <div>Başvurular</div>
                                </div>
                            </div>
                        </div>
                        <a href="application.php">
                            <div class="panel-footer">
                                <span class="pull-left">Başvuru Gönder!</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php print $user->getCoynPoints(); ?></div>
                                    <div>rCoin</div>
                                </div>
                            </div>
                        </div>
                        <a href="forum bağış linki">
                            <div class="panel-footer">
                                <span class="pull-left">rCoin satın al!</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                    <!-- /.panel -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Haber
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <ul class="timeline">
									<?php
										$db = getDB();
										$stmt = $db->prepare("SELECT * FROM news ORDER BY created_at DESC;");
										$stmt->execute();

										while($data = $stmt->fetch(PDO::FETCH_ASSOC)) {

											$title = $data['title'];
											$text = $data['text'];
											$date = $data['created_at'];
											$admin = (int)$data['admin'];
											$id = (int)$data['id'];

											$admin_data = new User($admin);
											$admin_name = $admin_data->getUsername();

											print ($id % 2 != 0) ? ("<li class='timeline-inverted'>") :
																   ("<li><div class='timeline-inverted'></div>");

											print("<div class='timeline-panel'>");

												print("<div class='timeline-heading'>");
													print ("<h4 class='timeline-title'><b>$title</b></h4>");
													print ("<p><small class='text-muted'><i class='fa fa-clock-o'></i> $date tarihinde $admin_name</small></p>");
												print("</div>");

												print("<div class='timeline-body'>");
													print("<p>$text</p>");
												print("</div>");

											print("</div>");
											print("</li>");
										}
									?>
                            </ul>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> İstatistik
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                                <i class="list-group-item">
                                    <i class="fa fa-user fa-fw"></i> Kayıtlı hesaplar
                                    <?php
										$db = getDB();
										$stmt = $db->prepare("SELECT COUNT(id) AS registered_account FROM accounts");
										$stmt->execute();
										$data = $stmt->fetch(PDO::FETCH_ASSOC);
										$count = $data['registered_account'];
										print ("<span class='pull-right text-muted small'><em>$count</em></span>");
									?>
                                </i>
                                <i class="list-group-item">
                                    <i class="fa fa-male fa-fw"></i> Kayıtlı karakterler
                                    <?php
										$db = getDB();
										$stmt = $db->prepare("SELECT COUNT(id) AS registered_characters FROM characters WHERE deleted = 0");
										$stmt->execute();
										$data = $stmt->fetch(PDO::FETCH_ASSOC);
										$count = $data['registered_characters'];
										print ("<span class='pull-right text-muted small'><em>$count</em></span>");
									?>
                                </i>
                                <i class="list-group-item">
                                    <i class="fa fa-spinner fa-fw"></i> Bekleyen başvurular
                                    <?php
										$db = getDB();
										$stmt = $db->prepare("SELECT COUNT(id) AS applications FROM character_applications WHERE is_accepted IS NULL");
										$stmt->execute();
										$data = $stmt->fetch(PDO::FETCH_ASSOC);
										$count = $data['applications'];
										print ("<span class='pull-right text-muted small'><em>$count</em></span>");
									?>
                                </i>
                                <i class="list-group-item">
                                    <i class="fa fa-archive fa-fw"></i> Geçmiş başvurular
                                    <?php
										$db = getDB();
										$stmt = $db->prepare("SELECT COUNT(id) AS applications FROM character_applications WHERE created_at > date_sub(NOW(), INTERVAL 1 WEEK);");
										$stmt->execute();
										$data = $stmt->fetch(PDO::FETCH_ASSOC);
										$count = $data['applications'];
										print ("<span class='pull-right text-muted small'><em>$count</em></span>");
									?>
                                </i>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
				</div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

	<?php include_once '../core/footer.php'; ?>

</body>
