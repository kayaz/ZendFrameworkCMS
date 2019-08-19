<?php

class Admin_MinisliderController extends kCMS_Admin
{
		
		public function preDispatch() {
			$this->view->controlname = "Jak kupić";
			
			$this->duzaszerokosc = '540';
			$this->duzawysokosc = '330';
			$this->sql_table = 'minislider';
			$this->backto = '/admin/minislider/';
		}
		
// Ustaw kolejność
		public function ustawAction() {
			$db = Zend_Registry::get('db');
			$tabela = $this->_request->getParam('co');
			$updateRecordsArray = $_POST['recordsArray'];
			$listingCounter = 1;
			foreach ($updateRecordsArray as $recordIDValue) {
				$data = array('sort' => $listingCounter);
				$db->update($tabela, $data, 'id = '.$recordIDValue);
				$listingCounter = $listingCounter + 1;
				}
		}
		
// Pokaz wszystkie wpisy
		public function indexAction() {
			$db = Zend_Registry::get('db');
			$this->view->lista = $db->fetchAll($db->select()->from($this->sql_table));
		}
		
// Dodaj nowy wpis
		public function nowyAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->pagename = " - Nowy slider";
			$this->view->tinymce = "1";

			$this->view->back = '<div class="back"><a href="'.$this->backto.'">Wróć do listy</a></div>';
			$this->view->info = '<div class="info">Wymiary obrazka: szerokość <b>'.$this->duzaszerokosc.'px</b> / wysokość <b>'.$this->duzawysokosc.'px</b></div>';

			$form = new Form_PlikForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					$formData = $this->_request->getPost();
					unset($formData['MAX_FILE_SIZE']);
					unset($formData['obrazek']);
					unset($formData['submit']);
					$obrazek = $_FILES['obrazek']['name'];
					$plik = 'melia-apartamenty'.uniqid().'.'.zmiennazwe($obrazek);

					//Sprawdzenie poprawnosci forma
					if ($form->isValid($formData)) {
			
						$db->insert($this->sql_table, $formData);
						$lastId = $db->lastInsertId();

						if ($obrazek) {

							move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/minislider/'.$plik);
							$upfile = FILES_PATH.'/minislider/'.$plik;
							chmod($upfile, 0755);
							
							require_once 'kCMS/Thumbs/ThumbLib.inc.php';

							$options = array('jpegQuality' => 80);
							$thumb = PhpThumbFactory::create($upfile, $options)->adaptiveResizeQuadrant($this->duzaszerokosc, $this->duzawysokosc)->save($upfile);

							$dataImg = array('plik' => $plik);
							$db->update($this->sql_table, $dataImg, 'id = '.$lastId);
							
						}

						$this->_redirect($this->backto);

				} else {

					//Wyswietl bledy	
					$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
					$form->populate($formData);

				}
			}
		}
// Edytuj wpis
		public function edytujAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->tinymce = "1";
			
			$this->view->back = '<div class="back"><a href="'.$this->backto.'">Wróć do listy</a></div>';
			$this->view->info = '<div class="info">Wymiary obrazka: szerokość <b>'.$this->duzaszerokosc.'px</b> / wysokość <b>'.$this->duzawysokosc.'px</b></div>';
			
			$form = new Form_NazwaPlikTekstForm();
			$this->view->form = $form;

			// Polskie tlumaczenie errorów
			$polish = kCMS_Polish::getPolishTranslation();
			$translate = new Zend_Translate('array', $polish, 'pl');
			$form->setTranslator($translate);

			// Odczytanie id
			$id = (int)$this->getRequest()->getParam('id');
			$wpis = $db->fetchRow($db->select()->from($this->sql_table)->where('id = ?', $id));
			$this->view->pagename = " - Edytuj slider: ".$wpis->tytul;

			// Załadowanie do forma
			$array = json_decode(json_encode($wpis), true);
			$form->populate($array);

				//Akcja po wcisnieciu Submita
				if ($this->_request->getPost()) {

					//Odczytanie wartosci z inputów
					$formData = $this->_request->getPost();
					unset($formData['MAX_FILE_SIZE']);
					unset($formData['obrazek']);
					unset($formData['submit']);
					$obrazek = $_FILES['obrazek']['name'];
					$tag = zmiana($formData['nazwa']);
					$plik = $tag.'.'.zmiennazwe($obrazek);

						//Sprawdzenie poprawnosci forma
						if ($form->isValid($formData)) {
						
							$db->update($this->sql_table, $formData, 'id = '.$id);

							if ($obrazek) {
								//Usuwanie starych zdjęć
								unlink(FILES_PATH."/minislider/".$wpis->plik);
								
								move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/minislider/'.$plik);
								$upfile = FILES_PATH.'/minislider/'.$plik;
								chmod($upfile, 0755);
								
								require_once 'kCMS/Thumbs/ThumbLib.inc.php';

								$options = array('jpegQuality' => 80);
								$thumb = PhpThumbFactory::create($upfile, $options)->adaptiveResizeQuadrant($this->duzaszerokosc, $this->duzawysokosc)->save($upfile);

								$dataImg = array('plik' => $plik);
								$db->update($this->sql_table, $dataImg, 'id = '.$id);
								
							}
							
							$this->_redirect($this->backto);
							
						} else {
											
							//Wyswietl bledy	
							$this->view->message = '<div class="error">Formularz zawiera błędy</div>';
							$form->populate($formData);

						}

			}
		}

// Usun wpis
		public function usunAction() {
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			$wpis = $db->fetchRow($db->select()->from($this->sql_table)->where('id = ?', $id));
			
			unlink(FILES_PATH."/minislider/".$wpis->plik);
			
			$db->delete($this->sql_table, 'id = '.$id);
			$this->_redirect($this->backto);
		}

}