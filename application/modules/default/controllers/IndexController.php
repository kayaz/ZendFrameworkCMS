<?php
class Default_IndexController extends kCMS_Site
{
		public function preDispatch() {

		}

		public function indexAction() {
			$this->_helper->viewRenderer->setNoRender(true);
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$this->view->slider = $db->fetchAll($db->select()->from('slider')->order('sort ASC'));
			$inwest = $db->select()
				->from('inwestycje', array('nazwa', 'header', 'tag', 'status', 'miniaturka', 'id', 'lista'))
				->where('status = ?', 1)
				->order('sort ASC');
			$inwestycje = $this->view->inwestycje = $db->fetchAll($inwest);
			$this->view->inwestycjecount = count($inwestycje);
		}

	   public function menuAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$tag = $this->getRequest()->getParam('tag');
	        $front = Zend_Controller_Front::getInstance();
	        $request = $front->getRequest();

			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('uri = ?', $tag));
			
	        $front = Zend_Controller_Front::getInstance();
	        $request = $front->getRequest();

			if(!$page) {
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');
				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {

				$master = explode("/", $page->uri);
				//echo $master[0];
				$this->getRequest()->setParam('parenttag', $master[0]);
				$this->getRequest()->setParam('sitetag', $master[0]);
				$this->getRequest()->setParam('siteid', $page->id);
				$this->getRequest()->setParam('uri', $page->uri);
				$this->getRequest()->setParam('tag', $page->tag);
				$this->view->parenttag = $master[0];

				$this->view->strona_nazwa = $page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = $page->id;
			}
	   }
	   
	   public function onasAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', 3));
			
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

				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = 3;
				$this->view->pageclass = ' aboutus-page';
				
				$this->view->validation = 1;

			}
	   }
	   
	   public function finansowanieAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', 2));
			
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

				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = 2;
				$this->view->pageclass = ' finansowanie-page';

			}
	   }
	   
	   public function jakupicAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', 5));
			
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

				$this->view->strona_tytul = ' - '.$page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
				$this->view->seo_tytul = $page->meta_tytul;
				$this->view->seo_opis = $page->meta_opis;
				$this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = 5;
				$this->view->selectinput = 1;
				$this->view->validation = 1;
				$this->view->pageclass = ' jakupic-page';
				
				$this->view->lista = $db->fetchAll($db->select()->from('minislider'));
				
				$inwest = $db->select()
					->from('inwestycje', array('nazwa', 'status', 'id'))
					->where('status = ?', 1)
					->order('sort ASC');
				$inwestycje = $this->view->inwestycje = $db->fetchAll($inwest);
				
			}
	   }

		public function inlineAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			
			$inline = $db->fetchRow($db->select()->from('inline')->where('id = ?', $id));
			unset($inline['id']);
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($inline);
		}
		
		public function inlineiconAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			
			$inline = $db->fetchRow($db->select()->from('inline_icons')->where('id = ?', $id));
			unset($inline['id']);
			
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($inline);
		}
		
		public function inlineimgAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			
			$inline = $db->fetchRow($db->select()->from('inline_img')->where('id = ?', $id));
			unset($inline['id']);
			
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($inline);
		}
		
		public function zapisziconAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');

			$id = (int)$this->_request->getParam('id');
			$inlineQuery = $db->fetchRow($db->select()->from('inline_icons')->where('id = ?', $id));
			
			$formData = $this->_request->getPost();
			unset($formData['MAX_FILE_SIZE']);
			unset($formData['obrazek']);
			
			$obrazek = $_FILES['obrazek']['name'];
			$uniqid = uniqid();
			$plik = zmiana($formData['modaltytul']).'_'.$uniqid.'.'.zmiennazwe($obrazek);
			$iconheight = $formData['iconheight'];
			$iconwidth = $formData['iconwidth'];
			unset($formData['iconheight']);
			unset($formData['iconwidth']);
			
			$db->update('inline_icons', $formData, 'id = '.$id);
			
			if($obrazek) {
				unlink(FILES_PATH."/ikonki/".$inlineQuery['plik']);

				move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/ikonki/'.$plik);
				$upfile = FILES_PATH.'/ikonki/'.$plik;
				chmod($upfile, 0755);
				require_once 'kCMS/Thumbs/ThumbLib.inc.php';
				$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant($iconwidth, $iconheight)->save($upfile);
				
				$dataImg = array('plik' => $plik);
				$db->update('inline_icons', $dataImg, 'id = '.$id);
			}
			
			$response_array['status'] = 'success';
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($response_array);
		}
		
		public function zapiszimgAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');

			$id = (int)$this->_request->getParam('id');
			$inlineQuery = $db->fetchRow($db->select()->from('inline_img')->where('id = ?', $id));
			
			$formData = $this->_request->getPost();
			unset($formData['MAX_FILE_SIZE']);
			unset($formData['obrazek']);
			
			$obrazek = $_FILES['obrazek']['name'];
			$uniqid = uniqid();
			$plik = zmiana($formData['modaltytul']).'_'.$uniqid.'.'.zmiennazwe($obrazek);
			$iconheight = $formData['iconheight'];
			$iconwidth = $formData['iconwidth'];
			unset($formData['iconheight']);
			unset($formData['iconwidth']);
			
			$db->update('inline_img', $formData, 'id = '.$id);
			
			if($obrazek) {
				unlink(FILES_PATH."/gfx/".$inlineQuery['plik']);

				move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/gfx/'.$plik);
				$upfile = FILES_PATH.'/gfx/'.$plik;
				chmod($upfile, 0755);
				require_once 'kCMS/Thumbs/ThumbLib.inc.php';
				$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant($iconwidth, $iconheight)->save($upfile);
				
				$dataImg = array('plik' => $plik);
				$db->update('inline_img', $dataImg, 'id = '.$id);
			}
			
			$response_array['status'] = 'success';
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($response_array);
		}
		
		public function nowaiconAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			
			$id_place = (int)$this->_request->getParam('id_place');
			
			$formData = $this->_request->getPost();
			$formData['id_place'] = $id_place;
			unset($formData['MAX_FILE_SIZE']);
			unset($formData['obrazek']);
			
			$obrazek = $_FILES['obrazek']['name'];
			$uniqid = uniqid();
			$plik = zmiana($formData['modaltytul']).'_'.$uniqid.'.'.zmiennazwe($obrazek);
			$iconheight = $formData['iconheight'];
			$iconwidth = $formData['iconwidth'];
			unset($formData['iconheight']);
			unset($formData['iconwidth']);
			
			$db->insert('inline_icons', $formData);
			$lastId = $db->lastInsertId();
			
			if($obrazek) {
				move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/ikonki/'.$plik);
				$upfile = FILES_PATH.'/ikonki/'.$plik;
				chmod($upfile, 0755);
				require_once 'kCMS/Thumbs/ThumbLib.inc.php';
				$thumb = PhpThumbFactory::create($upfile)->adaptiveResizeQuadrant($iconheight, $iconwidth)->save($upfile);
				
				$dataImg = array('plik' => $plik);
				$db->update('inline_icons', $dataImg, 'id = '.$lastId);
			}
			
			$response_array['status'] = 'success';
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($response_array);

		}
		
		public function usuniconAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			
			$where = $db->quoteInto('id = ?', $id);
			$db->delete('inline_icons', $where);
			
			$response_array['status'] = 'success';
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($response_array);

		}
		
		public function zapiszinlineAction() {
			$this->getHelper('Layout')->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			$this->getResponse()->setHeader('Content-Type', 'application/json');
			$db = Zend_Registry::get('db');
			$id = (int)$this->_request->getParam('id');
			$formData = $this->_request->getPost();
			$db->update('inline', $formData, 'id = '.$id);
			$response_array['status'] = 'success';
			header("Content-type: application/json; charset=UTF-8");
			echo json_encode($response_array);

		}

// Ustaw kolejność
        public function sortujAction() {
            $this->getHelper('Layout')->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            $db = Zend_Registry::get('db');
            $tabela = $this->_request->getParam('table');
            $updateRecordsArray = $_POST['recordsArray'];
            $listingCounter = 1;
            foreach ($updateRecordsArray as $recordIDValue) {
                $data = array('sort' => $listingCounter);
                $db->update($tabela, $data, 'id = '.$recordIDValue);
                $listingCounter = $listingCounter + 1;
            }
        }
}