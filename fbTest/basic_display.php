<?php

		require_once('defines.php'); //imi includ header-ul cu informatii despre aplicatie
		// asa faci o clasa in php
		Class instagram_basic_display_api{
			private $_appId=INSTAGRAM_APP_ID; //astea sunt din header
			private $_appSecret=INSTAGRAM_APP_SECRET;
			private $_redirectUrl=INSTAGRAM_APP_REDIRECT_URI;
			private $_apiBaseUrl='https://api.instagram.com/'; //url-ul de la API-ul lor, la care o sa apendez diferite lucruri, in functie de necesitati
			//access token-ul pe care o sa trebuiasca sa-l obtin dupa ce obtin un cod, dupa logare (la facebook nu ai acel cod, iti da direct token-ul)
			private $_userAccessToken='';
			//codul de care vorbeam
			private $_getCode='';
			/**
			 *link-ul pe care trebuie sa intru ca sa ma conectez cu instagram
			 *la el o sa apendez base-url-ul de mai sus,appid,redirect url,scope,response type,
			 *evident cu sintaxa corespunzatoare parametrilor din linkuri,adica:
			 *linkul de baza + '?' + parametrii separati prin '&'
			 *din fericire, php face el asta pentru mine, eu doar ii dau parametrii :))
			 */ 
			public $authorizationUrl='';
			//se intelege de la sine, daca e true, inseamna ca am obtinut deja access token
			public $hasUserAccessToken=false;
			//asa faci un constructor in php
			function __construct($params){
				//deci am un array(mai degraba map) numit params, din el incerc sa iau elementul cu cheia 'get_code'
				//s-ar putea sa nu existe inca, evident
				$this->_getCode = $params['get_code'];
				//uita-te la urmatoarea functie mai intai
				//functia cu ajutorul careie obtin un token
				//ii trimit ca parametru arrey-ul params, care contine field-ul get_code,
				//de care am nevoie ca sa obtin token-ul
				//dupa cum am zis, la facebook nu cred ca ai nevoie de cod
				$this->_setUserInstagramAccessToken($params);
				//apelez functia care imi construieste link-ul pe care apesi pt a te duce pe pagina de conectare de pe insta
				$this->_setAuthorizationUrl();
			}
			public function getUserAccessToken(){
				return $this->_userAccessToken;
			}
			private function _setAuthorizationUrl(){
				//array(map) in care pun appid, redirecturl,scope si response_type
				//NUMELE LOR TREBUIE SA FIE EXACT ASA CUM VREA INSTAGRAM, pt ca ei fix la ele o sa se uite
				$getVars=array(
					'app_id'=>$this->_appId,
					'redirect_uri'=>$this->_redirectUrl,
					'scope'=>'user_profile,user_media',
					'response_type'=>'code');
				//contruiesc linkul pe care trebuie sa intri ca sa te autorizezi
				//functia http_build_query(array) imi construieste parametrii asa cum am zis mai sus
				//note: . inseamna concatenare in php
				$this->authorizationUrl=$this->_apiBaseUrl . 'oauth/authorize?' . http_build_query($getVars);

			}

			private function _setUserInstagramAccessToken($params){
				if($params['get_code']){ //daca am codul
					// incerc sa obtin token-ul
					$userAccessTokenResponse=$this->_getUserAccessToken(); 
					//_getUserAccessToken returneaza un array, pe mine ma intereseaza doar field-ul access_token
					$this->_userAccessToken=$userAccessTokenResponse['access_token'];
					//se intelege de la sine
					$this->hasUserAccessToken=true;
				}
			}

			private function _getUserAccessToken(){
				//asa deci functia asta contruieste array-ul necesar pt a face un API call la instagram,
				//obtine si returneaza raspunsul de la API call
				//numele la parametrii astia trebuie sa fie fix asa cum vrea instagram, chiar gasesti si in documentatie asta,
				// https://developers.facebook.com/docs/instagram-basic-display-api/getting-started
				//deci sunt sigur ca aveti si pt facebook
				$params= array(
					'endpoint_url'=>$this->_apiBaseUrl . 'oauth/access_token', //endpoint-ul este efectiv adresa la care vrei sa faci request-ul(API call-ul)
					'type'=>'POST', //requestul http e de tip POST, sincer nu am inteles de ce nu e GET
					'url_params'=>array( //array-ul de parametri la care o sa se uite Instagram cand ii facem api call-ul, note: este un array intr-un array
						'app_id'=>$this->_appId,
						'app_secret'=>$this->_appSecret,
						'grant_type'=>'authorization_code',
						'redirect_uri'=>$this->_redirectUrl,
						'code'=>$this->_getCode));
				$response=$this->_makeApiCall($params);
				return $response;
			}

			private function _makeApiCall($params){
				//cu ajutorul chestiei asteia, numite cURL, facem POST and GET http request la un server
				$ch=curl_init();
				$endpoint=$params['endpoint_url'];
				if('POST'==$params['type']){
					//deci adaugam la header-ul din POST array-ul ala din array-ul params
					curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($params['url_params']));
					curl_setopt($ch,CURLOPT_POST,1);
				}
				//setam endpoint-ul
				curl_setopt($ch,CURLOPT_URL,$endpoint);
				//mai jos niste optiune plictisitoare
				curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
				curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				//executam POST request-ul, si API-ul (server-ul la care am facut request) ne va returna un JSON
				$response=curl_exec($ch);
				curl_close($ch);
				//foarte usor decodam JSON-ul asta
				$responseArray=json_decode($response,true);
				//JSON-ul are si un field error_type,
				//daca e setat,nu e de bine, deci oprim scriptul
				if(isset($responseArray['error_type'])){
					var_dump($responseArray);
					die();
				}else{
					//return raspunsul
					return $responseArray;
				}
			}

		}
		?>