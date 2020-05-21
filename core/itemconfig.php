<?php

	$items = array("Nessuno", "Cellulare", "Cellulare usa e getta", "Radio", "Vestito", "Provviste", "Localizzatore", 
	"Localizzatore avanzato", "Maschera", "Boombox", "Sprite", "Tanica di benzina", "Birra", "Munizioni leggere", 
	"Munizioni a pallettoni", "Munizioni pesanti", "Munizioni fucile", "Brass Knuckless", "Golf Club", "Nightstick", 
	"Knife", "Baseball Bat", "Shovel", "Pool Cue", "Katana", "Chainsaw", "Purple Dildo", "Dildo", "Vibrator", 
	"Silver Vibrator", "Flowers", "Cane", "Grenade", "Tear Gas", "Molotov Cocktail", "Colt 45", "Silenced 9mm", 
	"Desert Eagle", "Shotgun", "Sawnoff Shotgun", "Combat Shotgun", "Micro SMG", "MP5", "AK-47", "M4", "Tec-9", 
	"Country Rifle", "Sniper Rifle", "RPG", "HS Rocket", "Flamethrower", "Minigun", "Satchel Charge", "Detonator", 
	"Spray Can", "Fire Extinguisher", "Camera", "Night Goggles", "Thermal Goggles", "Parachute", "Colt 45 vuota", 
	"Silenced 9mm vuota", "Desert Eagle vuota", "Shotgun vuota", "Sawnoff Shotgun vuota", "Combat Shotgun vuota", 
	"Micro SMG vuota", "MP5 vuota", "AK-47 vuota", "M4 vuota", "Tec-9 vuota", "Country Rifle vuota", "Sniper Rifle vuota", 
	"Marijuana", "Hashish", "Cocaina", "Eroina", "Anfetamina", "Ecstasy", "LSD", "PCP", "Ketamina", "Metanfetamina", 
	"Crack", "Purple Drank", "Seme di cannabis", "Benzene", "Ciclopentilbromuro", "Metilammina", "Morfina", 
	"Acido cloridrico", "Ammoniaca", "Fenil-nitropropene", "Isopropanolo", "Acido ecetico", "Idrossido di sodio", 
	"Acido solforico", "Safrolo", "Metilammina cloridrato", "Cloruro di ammonio", "Dimetilammina cloridrato", "Chetone", 
	"Ergotamina", "Acido lisergico", "Piperidina", "Pseudoefedrina", "Fosforo rosso", "Codeina", "Prometazina", 
	"Acido iodidrico", "Stricnina");
	
	$weapon_names = array("Unarmed", "Brass Knuckles", "Golf Club", "Nightstick", "Knife", "Baseball Bat", "Shovel",
	"Pool Cue", "Katana", "Chainsaw", "Dildo 1", "Dildo 2", "Vibrator 1", "Vibrator 2", "Flowers", "Cane", "Grenade",
	"Tear Gas", "Molotov Cocktail", "Undefined", "Undefined", "Undefined", "9mm Pistol", "9mm Pistol silenced", "Desert Eagle", "Shotgun", "SawnOff Shotgun",
	"Combat Shotgun", "Micro SMG", "MP5", "AK-47", "M4", "Tec9", "Country Rifle", "Sniper Rifle", "RPG", "Heat Seeker Rocket",
	"Flamethrower", "Minigun", "Satchel Charge", "Detonator", "Sprycan", "Fire Extinguisher", "Camera", "Night Vision Goggles",
	"Thermal Goggles", "Parachute");

	$models = array("Landstalker", "Bravura", "Buffalo", "Linerunner", "Perenniel", "Sentinel", "Dumper", "Firetruck", "Trashmaster", "Stretch", "Manana", "Infernus",
	"Voodoo", "Pony", "Mule", "Cheetah", "Ambulance", "Leviathan", "Moonbeam", "Esperanto", "Taxi", "Washington", "Bobcat", "Mr Whoopee", "BF Injection",
	"Hunter", "Premier", "Enforcer", "Securicar", "Banshee", "Predator", "Bus", "Rhino", "Barracks", "Hotknife", "Trailer", "Previon", "Coach", "Cabbie",
	"Stallion", "Rumpo", "RC Bandit", "Romero", "Packer", "Monster", "Admiral", "Squalo", "Seasparrow", "Pizzaboy", "Tram", "Trailer", "Turismo", "Speeder",
	"Reefer", "Tropic", "Flatbed", "Yankee", "Caddy", "Solair", "Berkley's RC Van", "Skimmer", "PCJ-600", "Faggio", "Freeway", "RC Baron", "RC Raider", "Glendale",
	"Oceanic", "Sanchez", "Sparrow", "Patriot", "Quad", "Coastguard", "Dinghy", "Hermes", "Sabre", "Rustler", "ZR3 50", "Walton", "Regina", "Comet", "BMX", "Burrito", "Camper", "Marquis", "Baggage", "Dozer", "Maverick",
	"News Chopper", "Rancher", "FBI Rancher", "Virgo", "Greenwood", "Jetmax", "Hotring", "Sandking", "Blista Compact", "Police Maverick", "Boxville", "Benson", "Mesa",
	"RC Goblin", "Hotring Racer", "Hotring Racer", "Bloodring Banger", "Rancher", "Super GT", "Elegant", "Journey", "Bici", "Mountain Bike", "Beagle", "Cropdust", "Stunt", "Tanker", "RoadTrain", "Nebula",
	"Majestic", "Buccaneer", "Shamal", "Hydra", "FCR-900", "NRG-500", "HPV1000", "Cement Truck", "Tow Truck", "Fortune", "Cadrona", "FBI Truck", "Willard", "Forklift", "Tractor", "Combine", "Feltzer", "Remington",
	"Slamvan", "Blade", "Freight", "Streak", "Vortex", "Vincent", "Bullet", "Clover", "Sadler", "Firetruck", "Hustler", "Intruder", "Primo", "Cargobob", "Tampa",
	"Sunrise", "Merit", "Utility", "Nevada", "Yosemite", "Windsor", "Monster", "Monster", "Uranus", "Jester", "Sultan", "Stratum", "Elegy",
	"Raindance", "RC Tiger", "Flash", "Tahoma", "Savanna", "Bandito", "Freight", "Trailer", "Kart", "Mower", "Duneride", "Sweeper", "Broadway", "Tornado", "AT-400",
	"DFT-30", "Huntley", "Stafford", "BF-400", "Newsvan", "Tug", "Trailer", "Emperor", "Wayfarer", "Euros", "Hotdog", "Club", "Trailer", "Trailer", "Andromada",
	"Dodo", "RC Cam", "Launch", "Police Car (LSPD)", "Police Car (SFPD)", "Police Car (LVPD)", "Police Ranger", "Picador", "S.W.A.T. Van", "Alpha", "Phoenix", "Glendale",
	"Sadler", "Luggage Trailer", "Luggage Trailer", "Stair Trailer", "Boxburg", "Farm Plow", "Utility Trailer");
	
?>