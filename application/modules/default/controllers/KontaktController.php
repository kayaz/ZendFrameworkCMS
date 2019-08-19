<?php

class Default_KontaktController extends kCMS_Site
{
	
	
	   public function indexAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', 1));
			
	        $front = Zend_Controller_Front::getInstance();
	        $request = $front->getRequest();

			if(!$page) {
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');
				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {

				$this->view->strona_nazwa = $page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = 1;
				$this->view->validation = 1;
				$this->view->hideheader = 1;
				$this->view->notop = 1;
				$this->view->pageclass = ' contact-page';

				//Pobranie regulek
				$url = '#';
				$login = 'admin';
				$password = 'admin';
				$id = 1;
				$key = "s8as8dfy8a7sdf";
			 
				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true); 
				curl_setopt($ch, CURLOPT_POSTFIELDS, 'id='.$id.'&key='.$key);
				curl_setopt($ch, CURLOPT_USERPWD, $login.':'.$password);
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_CAPATH , FILES_PATH.'/grupolardevelopropl.crt');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($ch, CURLOPT_TIMEOUT, 4);
				
				$regulkiCURL = curl_exec($ch);  
				$regulki = $this->view->regulkis = json_decode($regulkiCURL, true);

				if ($this->_request->isPost()) {

					$ip = $_SERVER['REMOTE_ADDR'];
					$adresip = $db->fetchRow($db->select()->from('blokowanie')->where('ip = ?', $ip));
					
					$grecaptcha = $this->_request->getPost('g-recaptcha-response');
					$secret = '';
					$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$grecaptcha);
					$responseData = json_decode($verifyResponse);
		
					if($responseData->success){
						if($adresip){  } else {

							$imie = $this->_request->getPost('imie');
							$email = $this->_request->getPost('email');
							$temat = $this->_request->getPost('temat');
							$telefon = $this->_request->getPost('telefon');
							$wiadomosc = $this->_request->getPost('wiadomosc');
							$useremail = $this->_request->getPost('useremail');
							$ip = $_SERVER['REMOTE_ADDR'];
							$datadodania = date("d.m.Y - H:i:s");

							$ustawienia = $db->fetchRow($db->select()->from('ustawienia'));

							if(!$useremail) {	
								$mail = new Zend_Mail('UTF-8');
								$mail
								->setFrom($ustawienia->email, 'Zapytanie ze strony www')
								->addTo($ustawienia->email, 'Adres odbiorcy')
								->setReplyTo($email, $imie)
								->setSubject($ustawienia->domena.' - Zapytanie ze strony www - Kontakt')
								->setBodyHTML('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>'.$ustawienia->nazwa.'</title><style type="text/css">body {background-color: #ffffff}table {border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;}img {display: block; margin: 0; padding: 0; border: none;}</style></head><body style="-webkit-text-size-adjust:none; background-color:#ffffff; padding:0;margin:0">
								<div style="width:550px;border:1px solid #ececec;padding:0 20px;margin:0 auto;font-family:Arial;font-size:14px;line-height:27px">
								<p style="text-align:center">'.$ustawienia->nazwa.'</p>
								<p><b>Wiadomość wysłana: '. $datadodania .'</b></p>
								<hr style="border:0;border-bottom:1px solid #ececec" />
								<p><b>Imię i nazwisko:</b> '.$imie.'<br />
								<b>E-mail:</b> '. $email .'<br />
								<b>Telefon:</b> '. $telefon .'<br />
								<b>IP:</b> '. $ip .'<br />
								<hr style="border:0;border-bottom:1px solid #ececec" />
								<p style="margin-top:0">'. $wiadomosc .'</p>
								</div></body></html>')
								->setBodyText('
								Wiadomość wysłana: '. $datadodania .'
								Imię i nazwisko: '.$imie.'
								E-mail: '. $email .'
								Telefon:'. $telefon .'
								IP: '. $ip .'
								Wiadomość: '. $wiadomosc);

								try {
									$mail->send();
								} catch (Zend_Exception $e) {
									echo $e->getMessage();
									exit;
								} 
							}
							$this->view->message = '1';
							
							$stat = array(
								'akcja' => 1,
								'miejsce' => 4,
								'data' => date("d.m.Y - H:i:s"),
								'timestamp' => date("d-m-Y"),
								'godz' => date("H"),
								'dzien' => date("d"),
								'msc' => date("m"),
								'rok' => date("Y"),
								'tekst' => $wiadomosc,
								'email' => $email,
								'telefon' => $telefon,
								'ip' => $_SERVER['REMOTE_ADDR']
							);
							$db->insert('statystyki', $stat);
		
							$formData = $this->_request->getPost();
							$formData['data_dodania'] = date("d-m-Y H:s");
							$formData['miasto'] = 54;
							$formData['id_handlowiec'] = 56;
							$formData['status'] = 55;
							$formData['id_inwest'] = 1;
							$formData['ip'] = $_SERVER['REMOTE_ADDR'];
							$formData['www'] = $_SERVER['REQUEST_URI'];
							
							unset($formData['g-recaptcha-response']);

							foreach ($formData as $key => $value) {
								$post_items[] = $key . '=' . $value;
							}
							$post_string = implode ('&', $post_items); 

							$url2 = '#';
							$ch = curl_init(); 
							curl_setopt($ch, CURLOPT_URL, $url2);
							curl_setopt($ch, CURLOPT_POST, true); 
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
							curl_setopt($ch, CURLOPT_USERPWD, $login.':'.$password);
							curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
							curl_setopt($ch, CURLOPT_TIMEOUT, 4); 
							
							$regulkiCURL = curl_exec($ch);  
							curl_close($ch);
							
							// echo '<pre>';
							// print_r($regulkiCURL);
							// echo '</pre>';
							
							
						}	
					}

				}
				
			}
	   }
}