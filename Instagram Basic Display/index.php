<?php
	//includ fisierul cu clasa
	require_once('basic_display.php');
	//construiesc un array, cu un singur field deocamdata,'get_code'
	//foarte frumos in php poti sa te uiti la link-ul din browser si sa vezi parametrii
	//$_GET[parametru] returneaza paremetrul, si functia isset verifica daca exista acest parametru (parametrul code)
	//daca nu exista,get_code il face sirul vid
	//pt a nu exista confuzii, daca parametrul exista, linkul din browser arata asa: http://localhost/IGBasicDisplayTest/
	//iar daca exista, linkul arata asa: https://localhost/IGBasicDisplayTest/?code=AQDAg8ViDX_r8Rcv1V6kzeZdy_m19dI86PPnfiL-bSmHLEvyqjjd2EG9OO87fKlOZG_uDMln1l0vxRA7XKEECtPBJtnfE5dMcszGVw53TPvxlZ6a_0lNsL1umaiDyetLnGJzTZBMGoy0wbNUemzbXf30Jjqdp2XpwOW5Z90SukUxxWrbUM8AOzAxudzUjs0Xp5enm7YiC_TOXpkdLOikEGUNuZMP4ZZsJfHmP7uVG4eVEw#_
	//dupa cum puteti vedea, link-ul de baza este acelasi,apoi dupa slash urmeaza '?' care inseamna ca urmeaza niste parametri, in cazul nostru
	//un singur parametru, code.
	//note: Instagram imi adauga el singur parametrul asta(in link), dupa redirectionare
	//note2: Dupa redirectionare, evident, clasa va fi instantiata din nou, dar de data asta o sa am acel code
	$params=array(
		'get_code'=>isset($_GET['code'])?$_GET['code']:'',
		);
	//instantiem clasa
	$ig=new instagram_basic_display_api($params);
?>

<h1>Instagram Basic Display API</h1>
<?php
//Un if-else, daca am access token-ul, ii dau echo, daca nu, afisez (folosindu-ma de html (acel a href)), link-ul acela pe care il construiesc cand imi instantiez clasa.
//ca sa recapitulam:
//daca iti apare link-ul clar nu ai access token-ul.
//dai click pe link, te duci pe pagina de autentificare, te loghezi, te intorci din nou cu un cod
//acel cod e folosit (in mod automat) pt generarea token-ului
	if($ig->hasUserAccessToken) : ?>
		<h4>IG INFO</h4>
		<hr />
		<?php $user = $ig->getUser(); ?>
		<h1>User ID: <?php echo $user['id']; ?></h1>
		<h1>Username: <?php echo $user['username']; ?></h1>
		<h1>Number of posts: <?php echo $user['media_count']; ?></h1>
		<h1>Account type: <?php echo $user['account_type']; ?></h1>
		<?php $usersPosts=$ig->getUsersPosts();
		foreach ($usersPosts['data'] as $photos )    : ?>
			<p><?php echo array_key_exists (  'caption' , $photos ) ? "Description: ".$photos['caption'] : ""; ?></p>
			<img src = "<?php echo $photos['media_url'] ?>">
	<?php endforeach; ?>
		<hr />
		<?php else : ?>
			<a href="<?php echo $ig->authorizationUrl; ?>">
				Login with Instagram
			</a>
		<?php endif; ?>
