<?php

if(isset($_GET['character_id']) != true)
{
	header("location: index.php"); die();
}

include_once '../core/userarea.php';
include_once '../core/header.php';
include_once '../core/dashboard.php';
include_once '../core/itemconfig.php';

include_once '../class/User.class.php';
include_once '../class/Character.class.php';

include_once '../class/assets/dbconfig.php';

$user = new User($_COOKIE['account']);
$user_id = $user->getID();
$user_level = $user->getStaffLevel();

$db = getDB();
$stmt = $db->prepare("SELECT account_id, deleted FROM characters WHERE id=:id");
$stmt->bindParam("id", $_GET['character_id'], PDO::PARAM_INT);
$stmt->execute();
$count = $stmt->rowCount();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if($count < 1 or $data['deleted'] > 0 && $user_level < 4 or $data['account_id'] != $user_id && $user_level < 2)
{
	header("location: index.php"); die();
}

?>

<script>

	function toggleTxtBanned()
	{
		$("#txtBanned").fadeOut();
	}
	function updateBanStatus(id, status)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#txtBanned").fadeIn();
				document.getElementById("txtBanned").innerHTML = this.responseText;
				setTimeout(toggleTxtBanned, 3000);
			}
		};
		xmlhttp.open("GET", "../send/ban_character.php?character_id=" + id + "&banned=" + status, true);
		xmlhttp.send();
	}

	function updateSkin(id, sex)
	{
		var xmlhttp = new XMLHttpRequest();
		var skin = $('#skin_id').val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("skinImg").innerHTML = this.responseText;
				window.location.reload();
			}
		};
		xmlhttp.open("GET", "../send/skin_character.php?character_id=" + id + "&skin=" + skin + "&sex=" + sex, true);
		xmlhttp.send();
	}

	function toggleTxtJail()
	{
		$("#txtJail").fadeOut();
	}
	function jailCharacter(id)
	{
		var xmlhttp = new XMLHttpRequest();
		var time = $('#time_jail').val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#txtJail").fadeIn();
				document.getElementById("txtJail").innerHTML = this.responseText;
				setTimeout(toggleTxtJail, 3000);
			}
		};
		xmlhttp.open("GET", "../send/jail_character.php?character_id=" + id + "&time=" + time, true);
		xmlhttp.send();
	}

	function toggleTxtSpawn()
	{
		$("#txtSpawn").fadeOut();
	}
	function updateSpawnPoint(id, spawnpoint)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#txtSpawn").fadeIn();
				document.getElementById("txtSpawn").innerHTML = this.responseText;
				setTimeout(toggleTxtSpawn, 3000);
			}
		};
		xmlhttp.open("GET", "../send/spawn_character.php?character_id=" + id + "&spawn=" + spawnpoint, true);
		xmlhttp.send();
	}

</script>

<body>
	<div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">

				<?php

					$character = new Character($_GET['character_id']);
					$character_id = (int)$character->getID();
					$account_id = $character->getAccountId();

					$account = new User($account_id);
					$account_level = $account->getStaffLevel();

					$char_name = $character->getCharacterName();
					$account_name = $character->getAccountName();
					$cash = (int)$character->getCash();
					$cash_bank = (int)$character->getCashBank();
					$level = (int)$character->getLevel();
					$faction = (int)$character->getFaction();
					$faction_name = "Yok";
					$faction_short_name = "N/A";
					$faction_rank_name = "Tanımsız";
					$faction_rank_salary = 0;
					$faction_limit = $faction + 1;
					$faction_rank = (int)$character->getFactionRank() - 1;
					$skin = (int)$character->getSkin();
					$account_id = (int)$character->getAccountID();
					$background = $character->getBackground();
					$spawnpoint = $character->getSpawnPoint();
					$sex = $character->getSex();
					$job = $character->getJob();
					$job_pay = $character->getJobPay();
					$experience_points = $character->getExp();
					$experience_target = $level * 3;
					$playing_hours = $character->getHours();
					$upgrade_points = $character->getUpgrades();
					$drive_license = $character->getDriveLicense();
					$inventory = $character->getInventory();
					$weapons = $character->getWeapons();

					$inventory = explode("|", $inventory);
					$weapons = explode("|", $weapons);
					$char_name = str_replace("_", " ", $char_name);
					$job_names = array ("Kamyon Şoförü", "Tamirci", "Çiftçi", "Taksi Şoförü");

                    print ("<h1 class='page-header'>$char_name</h1>");
				?>

                </div>
                <!-- /.col-lg-12 -->
				<div class="col-lg-12">
                    <div class="panel panel-default">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">

							<?php

                                printf ("<li class='active'><a href='#profile' data-toggle='tab'>Profil</a>");
                                print ("</li>");

								printf ("<li><a href='#inventory' data-toggle='tab'>Envanter</a>");
								print ("</li>");

								printf ("<li><a href='#vehicles' data-toggle='tab'>Araçlar</a>");
								print ("</li>");

								print ("<li><a href='#userlogs' data-toggle='tab'>Cezalar</a>");
								print ("</li>");

								if($account_id == $user_id)
								{
									print ("<li><a href='#settings' data-toggle='tab'>Ayarlar</a>");
									print ("</li>");
								}

                                if($user_level > 1)
								{
									print ("<li><a href='#staffarea' data-toggle='tab'>51. Bölge</a>");
									print ("</li>");
								}
							?>

                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="profile">
                                    <br>

									<?php

										if($faction != -1)
										{
											$stmt = $db->prepare("SELECT id, name, short_name FROM factions LIMIT $faction,$faction_limit;");
											$stmt->execute();
											$data = $stmt->fetch(PDO::FETCH_ASSOC);
											$faction_name = $data['name'];
											$faction_short_name = $data['short_name'];
											$faction_id = $data['id'];

											$stmt = $db->prepare("SELECT rank_name, salary FROM factions_info WHERE faction_id=:faction_id AND slot=:faction_rank");
											$stmt->bindParam("faction_id", $faction_id, PDO::PARAM_INT);
											$stmt->bindParam("faction_rank", $faction_rank, PDO::PARAM_INT);
											$stmt->execute();
											$data = $stmt->fetch(PDO::FETCH_ASSOC);
											$faction_rank_name = $data['rank_name'];
											$faction_rank_salary = $data['salary'];
										}

										print ("<span id='skinImg'><img style='width: 700px; height: 500px;' img src='../core/images/accounts/skins/$skin.png' align='right'></span>");
										print ("<blockquote class='pull-center'>");

										if($user_level > 1)
										{
											$stmt = $db->prepare("SELECT id FROM character_applications WHERE character_id=:character AND account_id=:account AND is_accepted = 1");
											$stmt->bindParam("character", $character_id, PDO::PARAM_INT);
											$stmt->bindParam("account", $account_id, PDO::PARAM_INT);
											$stmt->execute();
											$count = $stmt->rowCount();

											if($count)
											{
												$data = $stmt->fetch(PDO::FETCH_ASSOC);

												$app_id = $data['id'];

												print ("<p>Başvurular: <a href='whitelist_details.php?app_id=$app_id'><b>$app_id </b><i class = 'fa fa-angle-right'></i></a></p>");
											}

											print ("<p>Hesap:<a href='profile.php?account_id=$account_id'><b> $account_name </b><i class = 'fa fa-angle-right'></i></p></a>");
										}

										print ("<p>Seviye: <b>$level</b></p>");

										print ("<p>Tecrübe: <b>$experience_points/$experience_target</b></p>");
										print ("<p>Oynanış Süresi: <b>$playing_hours</b></p>");
										print ("<p>Yükseltme Puanları: <b>$upgrade_points</b></p>");
										print ("<p>Para: $<b>$cash</b></p>");
										print ("<p>Bankadaki Para: $<b>$cash_bank</b></p>");
										print ($job != -1) ? ("<p>Meslek: <b>$job_names[$job]</b></p>") : ("<p>Meslek: <b>İşsiz</b></p>");
										print ("<p>Maaş: $<b>$job_pay</b></p>");
										print ("<p>Birlik: <b>$faction_name ($faction_short_name)</b></p>");
										print ("<p>Rütbe: <b>$faction_rank_name</b></p>");
										print ("<p>Birlik Maaşı: $<b>$faction_rank_salary</b></p>");
										print ($drive_license) ? ("<p>Ehliyet: <b>Var</b></p>") : ("<p>Ehliyet: <b>Yok</b></p>");
										print ("</blockquote><hr>");

										print ("<blockquote class='pull-center'>");
										print ("<p>$background</p>");
										print ("</blockquote>");
									?>

                                </div>
								<div class="tab-pane fade" id="inventory">
									<br>

									<?php

										print ("<blockquote class='pull-center'>");

										print ("<p><b>ENVANTER</b></p>");

										for($i = 0, $icount = 0; $i < 10; $i++)
										{
											$item = $inventory[$i + $icount];
											$amount = $inventory[$i + $icount + 1];
											$extra = $inventory[$i + $icount + 2];

											$icount += 2;
											$slot = $i + 1;

											print ("Slot $slot: <b>$items[$item] ($amount)</b><br>");
										}

										print ("<hr><p><b>SİLAHLAR</b></p>");

										for($w = 0, $wcount = 0; $w < 12; $w++)
										{
											$weapon = $weapons[$w + $wcount];
											$ammo = $weapons[$w + $wcount + 1];

											if(!$weapon or !$ammo)continue;

											$wcount += 1;

											print ("$weapon_names[$weapon] (<b>$ammo</b>)<br>");
										}

										print ("</blockquote>");
									?>

								</div>
                                <div class="tab-pane fade" id="vehicles">
                                    <br>

									<?php

										$stmt = $db->prepare("SELECT faction ,id, fuel, model, plate, health, engine, locked, spawned FROM vehicles WHERE owner_id=:character_id");
										$stmt->bindParam("character_id", $character_id, PDO::PARAM_INT);
										$stmt->execute();
										$count = $stmt->rowCount();

										if($count < 1)print ("<blockquote class='pull-center'><p>Araç bulunamadı.</p></blockquote>");

										while($data = $stmt->fetch(PDO::FETCH_ASSOC)) {

											$faction = $data['faction'];
											$id = $data['id'];
											$fuel = $data['fuel'];
											$model = $data['model'];
											$health = $data['health'];
											$engine = $data['engine'];
											$locked = $data['locked'];
											$spawned = $data['spawned'];
											$plate = $data['plate'];
											$model_name = $models[$model - 400];

											print ("<blockquote class='pull-center'>");
											print ("<p>ID: <b>$id</b></p>");
											print ("<p>Model: <b>$model_name</b> ($model)</p>");
											print ("<img src='../core/images/accounts/vehicles/Vehicle_$model.jpg' align='right'>");
											print ("<p>Plaka: <b>$plate</b></p>");
											print ("<p>Benzin: <b>$fuel%</b></p>");
											print ("<p>HP: <b>$health.0</b></p>");
											print ($faction != 65535) ? ("<p>Birlik Aracı: <b>Evet</b></p>") : ("<p>Birlik Aracı: <b>Hayır</b></p>");
											print ($engine) ? ("<p>Motor: <b>Açık</b></p>") : ("<p>Motor: <b>Kapalı</b></p>");
											print ($locked) ? ("<p>Kilit: <b>Kapalı</b></p>") : ("<p>Kilit: <b>Açık</b></p>");
											print ($spawned) ? ("<p>Spawn: <b>Evet</b></p>") : ("<p>Spawn: <b>Hayır</b></p>");
											print ("</blockquote>");
											print ("<hr>");
										}

									?>

									<p></p>
                                </div>
                                <div class="tab-pane fade" id="userlogs">
                                    <br>

									<?php

										print ("<blockquote class='pull-center'>");

										$stmt = $db->prepare("SELECT * FROM admin_records WHERE target_id=:character_id");
										$stmt->bindParam("character_id", $character_id, PDO::PARAM_INT);
										$stmt->execute();
										$count = $stmt->rowCount();

										if($count < 1)print ("<p>Ceza bulunamadı.</p>");

										while($data = $stmt->fetch(PDO::FETCH_ASSOC)) {

											$type = $data['type'];
											$reason = $data['reason'];
											$admin = $data['admin_id'];
											$date = $data['created_at'];

											$admin_id = new Character($admin);
											$admin_name = $admin_id->getCharacterName();

											$admin_name = str_replace("_", " ", $admin_name);

											print ("<p>(- $date) <b>$admin_name</b> tarafından <b>$type</b>. Sebep: $reason</p>");
										}
									?>

									<p></p>
                                </div>
								<div class="tab-pane fade" id="settings">
									<br>

									<?php

										print ("<blockquote>");
											print ("<span class='buttom' style='float: left;'><button type ='submit' class='btn btn-primary' onclick='updateSkin($character_id, $sex)'>Değiştir</button></span>");
											print ("<input id='skin_id' type='text' class='form-control' style='height: 35px; width: 100px;' placeholder='ID' name='skin'>");
											print ("<br>Skin listesine erişmek için <a href = 'http://wiki.sa-mp.com/wiki/Skins:All'>tıkla</a>.<br><br>");

											$spawn = array("Yok", "Varsayılan", "Son Konum");
											$spawn_to_show = ($spawnpoint == 1) ? 2 : 1;

											print ("<select name='spawn' class='form-control' style='height: 30px; width:180px' onchange='updateSpawnPoint($character_id, this.value)'>");
												$spawn_name = $spawn[$spawnpoint]; print ("<option value='$spawnpoint'>$spawn_name</option>");
												$spawn_name = $spawn[$spawn_to_show]; print ("<option value='$spawn_to_show'>$spawn_name</option>");
											print ("</select>");

											print ("<br><div id='txtSpawn' class='alert alert-info' style='height: 50px; width: 280px;' hidden></div>");
											print ("İlk seçenek, mevcut olan ayardır.");
										print ("</blockquote>");

										print ("<blockquote>");
										print ("<form role='form' action='../send/delete_character.php' method='POST'>");
											print ("<input name='character_id' type='hidden' value='$character_id'>");
											print ("<button type='submit' class='btn btn-danger'>Karakteri aktar</button>");
										print ("</form>");
										print ("(<b>DİKKAT</b>) Bir karakteri kurtarmak için aktarmayı kullanın.");
										print ("</blockquote>");
									?>

								</div>
                                <div class="tab-pane fade" id="staffarea">
                                    <br>

									<?php

										print ("<blockquote>");

										print ("<button type='button' class='btn btn-danger' onclick='updateBanStatus($character_id, 1)'><i class = 'fa fa-lock'></i></button> ");
										print ("<button type='button' class='btn btn-success' onclick='updateBanStatus($character_id, 0)'><i class = 'fa fa-unlock'></i></button> ");
										print ("<a href='refund.php?character_id=$character_id'><button type='button' class='btn btn-primary'><i class = 'fa fa-money'></i></button></a> ");
										print ("<br><br>");

										if($account_level < $user_level or $account_id == $user_id)
										{
											print ("<form role='form' action='logs.php' method='POST'>");
												print ("<fieldset>");
												print ("<div class='form-group input-group'>");
													print ("<input name='character_id' type='hidden' value='$character_id'>");
													print ("<span class='buttom' style='float: left;'><input type ='submit' class='btn btn-primary' style='height: 35px;' value='Günlük Log'></span>");

														print ("<select name='filter' class='form-control' style='height: 35px; width:180px' required>");

															$stmt = $db->prepare("SELECT DISTINCT command FROM transfer_logs WHERE giver_character_id=:id OR receiver_character_id=:same_id ORDER BY command ASC");
															$stmt->bindParam("id", $character_id, PDO::PARAM_INT);
															$stmt->bindParam("same_id", $character_id, PDO::PARAM_INT);
															$stmt->execute();

															while($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
																$action=$data['command'];
																print ("<option value='$action'>$action</option>");
															}

														print ("</select>");

												print ("</div>");
												print ("</fieldset>");
											print ("</form>");
										}

										print ("<div id='txtBanned' class='alert alert-info' style='height: 50px; width: 220px;' hidden></div>");

										print ("<span class='buttom' style='float: left;'><button type ='submit' class='btn btn-danger' onclick='jailCharacter($character_id)'>Hapis</button></span>");
										print ("<input id='time_jail' type='text' class='form-control' style='height: 35px; width: 100px;' placeholder='Dakika' name='time'>");
										print ("<br><div id='txtJail' class='alert alert-info' style='height: 50px; width: 220px;' hidden></div>");
										print ("Serbest bırakmak için boş bırakın.");

										print ("</blockquote>");
									?>

                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
		</div>
	</div>

	<?php include_once '../core/footer.php'; ?>

</body>
