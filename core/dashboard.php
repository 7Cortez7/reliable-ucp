<?php

include_once '../core/header.php';

include_once '../class/User.class.php';
include_once '../class/Alert.class.php';
include_once '../class/Character.class.php';
include_once '../class/Application.class.php';

include_once '../class/assets/dbconfig.php';

$user = new User($_COOKIE['account']);
$id = $user->getID();
$staff_level = $user->getStaffLevel();
$officer = $user->getOfficer();

?>

<body>
	<div id="wrapper">
		<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
						<?php print ("<li><a href='profile.php?account_id=$id'><i class='fa fa-user fa-fw'></i> Profil</a>"); ?>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../send/logout.php"><i class="fa fa-sign-out fa-fw"></i> Çıkış Yap</a>
                        </li>
                    </ul>
                </li>
            </ul>
			<div class="navbar-default sidebar" role="navigation">
				<div class="sidebar-nav navbar-collapse">
					<ul class="nav" id="side-menu">

						<?php

						if($staff_level > 1)
						{
							print ("<li class='sidebar-search'>");
								print ("<form role='form' action='../user/search.php' method='POST'>");
									print ("<div class='input-group custom-search-form'>");
										print ("<span class='input-group-addon'> <i class='fa fa-search fa-fw'></i> </span>");
										print ("<input type='text' name='keyword' class='form-control' placeholder='Ara...'>");
									print ("</div>");
								print ("</form>");
							print ("</li>");
						}

						?>

						<li>
							<a href="index.php"><i class="fa fa-home fa-fw"></i> Ana Sayfa</a>
						</li>
						<li>
							<?php print ("<a href='profile.php?account_id=$id'><i class='fa fa-user fa-fw'></i> Profil</a>"); ?>
						</li>
						<li>
							<?php print ("<a href='alerts.php'><i class='fa fa-bell fa-fw'></i> Bildirimler</a>"); ?>
						</li>
						<li>
							<?php print ("<a href='forum bağış linki'><i class='fa fa-support fa-fw'></i> rCoin</a>"); ?>
						</li>
						<li>
							<a href="#"><i class="fa fa-users fa-fw"></i> Karakterlerim<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">

								<?php

									print ("<li><a href='application.php'><i class='fa fa-plus fa-fw'></i> Oluştur</a></li>");
									print ("<li><a href='import.php'><i class='fa fa-backward fa-fw'></i> Aktar</a></li>");
									print ("<li><a href='#'><i class='fa fa-folder fa-fw'></i> Karakterler <span class='fa arrow'></span></a>");

                                    print ("<ul class='nav nav-third-level'>");

									foreach($user->getCharactersList() as $id) {
										$character = new Character($id);
										$charName = $character->getCharacterName();
										$charName = str_replace("_", " ", $charName);
										print ("<li><a href='character.php?character_id=$id'><i class = 'fa fa-check'></i> $charName</a></li>\n");
									}

									foreach($user->getApplicationsList() as $app_id) {
										$app = new Application($app_id);
										$charName = $app->getName();
										$charName = str_replace("_", " ", $charName);
										print ("<li><a href='alerts.php'><i class = 'fa fa-times'></i> $charName</a></li>\n");
									}

									print ("</ul>");
								?>
							</ul>
						</li>

						<?php

						if($staff_level > 0)
						{
							$count = Application::getCount();

							print ("<li>");
								print ("<a href='whitelist.php'><i class='fa fa-cogs fa-fw'></i> Başvurular ($count)</a>");
							print ("</li>");

							print ("<li>");
								print ("<a href='whitelist_historical.php'><i class='fa fa-archive fa-fw'></i> Geçmiş Başvurular</a>");
							print ("</li>");
						}

						if($officer == true)
						{
							print ("<li>");
								print ("<a href='mdc.php'><i class='fa fa-desktop fa-fw'></i>MDC</a>");
							print ("</li>");
						}

						?>

					</ul>
				</div>
			</div>
		</div>
		</nav>
	</div>

	<?php include_once '../core/footer.php'; ?>

</body>
