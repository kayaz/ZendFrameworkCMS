<?php

class Default_NewsController extends kCMS_Site
{
		public function preDispatch() {
			$this->view->pagefastmail = 1;
		}
		
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

				$result = $db->select()
				->from(array('n' => 'news'), array('id', 'plik', 'data', 'status', 'wprowadzenie', 'tytul', 'tag'))
				->where('n.status =?', 1)
				->order('n.data DESC');
				
				$pageNo = $this->_getParam('strona', 1);
				$paginator = Zend_Paginator::factory($result);
				$paginator->setItemCountPerPage(4);
				$paginator->setCurrentPageNumber($pageNo);
				$this->view->news = $paginator;
			}
	   }

	   public function wpisAction() {
            $this->_helper->layout->setLayout('page');
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$tag = $this->getRequest()->getParam('news_tag');
			 
			$result = $db->select()
			->from('news')
			->where('status =?', 1)
			->where('tag = ?', $tag);
			$news = $this->view->news = $db->fetchRow($result);

	        $front = Zend_Controller_Front::getInstance();
	        $request = $front->getRequest();
			if(!$news) {
				$request->setModuleName('default');
				$request->setControllerName('error');
				$request->setActionName('error');
				$this->getResponse()->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
				$this->view->nofollow = 1;
				$this->view->strona_nazwa = 'Strona nie została znaleziona - błąd 404';
				$this->view->seo_tytul = 'Strona nie została znaleziona - błąd 404';
			} else {
			
				$page = $this->view->strona = $db->fetchRow($db->select()->from('strony')->where('id = ?', 1));

				$this->view->strona_nazwa = $page->nazwa;
				$this->view->strona_h1 = $page->nazwa;
                $this->view->strona_tytul = ' - '.$page->nazwa.' - '.$news->tytul;
                $this->view->seo_tytul = $page->meta_tytul;
                $this->view->seo_opis = $page->meta_opis;
                $this->view->seo_slowa = $page->meta_slowa;
				
				$this->view->strona_id = 1;
				$this->view->share = 1;
				
				$this->view->share_tytul = $news->tytul;
				$this->view->share_desc = $news->wprowadzenie;
				$this->view->share_image = 'http://testy.4dl.pl/melia/files/news/share/'.$news->plik;
				$this->view->share_url = 'http://testy.4dl.pl/melia/aktualnosci/'.$news->tag.'/';
				
				$this->view->inne = $db->fetchAll($db->select()->from('news')->where('status = ?', 1)->where('id != ?', $news->id)->order('data DESC'));

			}
	   }
}