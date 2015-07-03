<?php

class IndexVxmlFilmControllerTest extends TestBase
{
	const CONTROLLER = 'IndexVxmlFilm';
	const FILM_ID = 1;
	const FILM_TITLE = 'La Princesa prometida';
	const QUERY = 'someQuery';
	private $navigationMapMock;
	private $paramsCheck = 0;
	private $actionCheck = 0;

	public function setUp()
	{
		$this->navigationMapMock = $this->bindToMock('NavigationMap');
		$this->paramsCheck = 0;
		$this->actionCheck = 0;
	}

	public function testIndex()
	{
		$menuViewData = $this->bindToMock('MenuViewData');
		$menuViewData->expects($this->at(0))->method('addOption')
			->with("busqueda por titulo", "busqueda por titulo", $this->mockLink("searchTitle"));
		$menuViewData->expects($this->at(1))->method('addOption')
			->with("busqueda por titulo", KeyPhone::KEY_1, $this->mockLink("searchTitle"));
		$menuViewData->expects($this->at(2))->method('addOption')
			->with("busqueda por actor", "busqueda por actor", $this->mockLink("searchActor"));
		$menuViewData->expects($this->at(3))->method('addOption')
			->with("busqueda por actor", KeyPhone::KEY_2, $this->mockLink("searchActor"));
		$menuViewData->expects($this->at(4))->method('addOption')
			->with("busqueda por director", "busqueda por director", $this->mockLink("searchDirector"));
		$menuViewData->expects($this->at(5))->method('addOption')
			->with("busqueda por director", KeyPhone::KEY_3, $this->mockLink("searchDirector"));
		$menuViewData->expects($this->at(6))->method('addOption')
			->with("ver cartelera", "ver cartelera", $this->mockLink("getCartelera"));
		$menuViewData->expects($this->at(7))->method('addOption')
			->with("ver cartelera", KeyPhone::KEY_4, $this->mockLink("getCartelera"));
		$menuViewData->expects($this->once())->method('setPrompt');

		$menuView = $this->bindToMock('MenuView');
		$menuView->expects($this->once())->method('render')->with($menuViewData);

		$this->getController()->index(array());
	}

	public function testGetCarteleraWithoutPages()
	{
		$totalPages = 0;
		$currentPage = 0;
		$this->mockRenderCartelera($totalPages, $currentPage);

		$this->getController()->getCartelera(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage));
	}

	public function testGetCarteleraPaginatedOnFirstPage()
	{
		$totalPages = 2;
		$currentPage = 0;
		$this->mockRenderCartelera($totalPages, $currentPage);

		$this->getController()->getCartelera(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage));
	}

	public function testGetCarteleraPaginatedOnSomePage()
	{
		$totalPages = 2;
		$currentPage = 1;
		$this->mockRenderCartelera($totalPages, $currentPage);

		$this->getController()->getCartelera(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage));
	}

	public function testGetCarteleraPaginatedOnLastPage()
	{
		$totalPages = 3;
		$currentPage = 3;
		$this->mockRenderCartelera($totalPages, $currentPage);

		$this->getController()->getCartelera(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage));
	}

	public function testGetFilmWithBreadCrumbs()
	{
		$breadCrumbsEncoded = 'index.php';
		$data = array(IndexVxmlFilmController::FILM_ID => self::FILM_ID, IndexVxmlFilmController::BREADCRUMBS => $breadCrumbsEncoded);
		$film = $this->bindToMock('Film');
		$apiMock = $this->bindToMock('FilmAffinityApi');
		$apiMock->expects($this->once())->method('getFilm')->with(self::FILM_ID)->willReturn($film);
		$filmDetailedLink = $this->mockLink('getFilmDetailed', $data);
		$viewDataMock = $this->bindToMock('FilmBasicViewData');
		$viewDataMock->expects($this->once())->method('setFilm')->with($film, $filmDetailedLink);
		$viewDataMock->expects($this->once())->method('setMainMenuLink')->with($this->mockLink('index'));
		$viewDataMock->expects($this->once())->method('setPreviousPageLink')->with(Link::createFromEncondedHref($breadCrumbsEncoded));

		$viewMock = $this->bindToMock('MenuView');
		$viewMock->expects($this->once())->method('render')->with($viewDataMock);

		$this->getController()->getFilm($data);
	}

	public function testGetFilmWithoutBreadCrumbs()
	{
		$data = array(IndexVxmlFilmController::FILM_ID => self::FILM_ID);
		$film = $this->bindToMock('Film');
		$apiMock = $this->bindToMock('FilmAffinityApi');
		$apiMock->expects($this->once())->method('getFilm')->with(self::FILM_ID)->willReturn($film);
		$filmDetailedLink = $this->mockLink('getFilmDetailed', $data);
		$viewDataMock = $this->bindToMock('FilmBasicViewData');
		$viewDataMock->expects($this->once())->method('setFilm')->with($film, $filmDetailedLink);
		$viewDataMock->expects($this->once())->method('setMainMenuLink')->with($this->mockLink('index'));

		$viewMock = $this->bindToMock('MenuView');
		$viewMock->expects($this->once())->method('render')->with($viewDataMock);

		$this->getController()->getFilm($data);
	}

	public function testGetFilmDetailedWithBreadCrumbs()
	{
		$breadCrumbsEncoded = 'index.php';
		$data = array(IndexVxmlFilmController::FILM_ID => self::FILM_ID, IndexVxmlFilmController::BREADCRUMBS => $breadCrumbsEncoded);
		$film = $this->bindToMock('Film');
		$apiMock = $this->bindToMock('FilmAffinityApi');
		$apiMock->expects($this->once())->method('getFilm')->with(self::FILM_ID)->willReturn($film);
		$viewDataMock = $this->bindToMock('FilmDetailedViewData');
		$viewDataMock->expects($this->once())->method('setFilm')->with($film);
		$viewDataMock->expects($this->once())->method('setMainMenuLink')->with($this->mockLink('index'));
		$viewDataMock->expects($this->once())->method('setPreviousPageLink')->with(Link::createFromEncondedHref($breadCrumbsEncoded));

		$viewMock = $this->bindToMock('MenuView');
		$viewMock->expects($this->once())->method('render')->with($viewDataMock);

		$this->getController()->getFilmDetailed($data);
	}

	public function testGetFilmDetailedWithoutBreadCrumbs()
	{
		$data = array(IndexVxmlFilmController::FILM_ID => self::FILM_ID);
		$film = $this->bindToMock('Film');
		$apiMock = $this->bindToMock('FilmAffinityApi');
		$apiMock->expects($this->once())->method('getFilm')->with(self::FILM_ID)->willReturn($film);
		$viewDataMock = $this->bindToMock('FilmDetailedViewData');
		$viewDataMock->expects($this->once())->method('setFilm')->with($film);
		$viewDataMock->expects($this->once())->method('setMainMenuLink')->with($this->mockLink('index'));

		$viewMock = $this->bindToMock('MenuView');
		$viewMock->expects($this->once())->method('render')->with($viewDataMock);

		$this->getController()->getFilmDetailed($data);
	}

	public function testSearchTitle()
	{
		$viewData = $this->bindToMock('FormViewData');
		$viewData->expects($this->once())->method('setMainMenuLink')->with($this->mockLink('index'));
		$viewData->expects($this->once())->method('setVarReturnedName')->with(IndexVxmlFilmController::QUERY_PARAM);
		$viewData->expects($this->once())->method('setSubmitLink')->with($this->mockLink('searchTitleForm'));
		$viewData->expects($this->once())->method('setPrompt')->with("Busqueda por titulo. por favor diga el titulo a buscar");
		$viewData->expects($this->any())->method('addInput');
		$view = $this->bindToMock('FormView');
		$view->expects($this->once())->method('render')->with($viewData);

		$this->getController()->searchTitle(array());
	}

	public function testSearchActor()
	{
		$viewData = $this->bindToMock('FormViewData');
		$viewData->expects($this->once())->method('setMainMenuLink')->with($this->mockLink('index'));
		$viewData->expects($this->once())->method('setVarReturnedName')->with(IndexVxmlFilmController::QUERY_PARAM);
		$viewData->expects($this->once())->method('setSubmitLink')->with($this->mockLink('searchActorForm'));
		$viewData->expects($this->once())->method('setPrompt')->with("Busqueda por actor. Por favor diga el actor a buscar");
		$viewData->expects($this->once())->method('addInputFromCsv')->with(GRAMMAR_CSV_PATH . "/actors2.csv");
		$view = $this->bindToMock('FormView');
		$view->expects($this->once())->method('render')->with($viewData);

		$this->getController()->searchActor(array());
	}

	public function testSearchDirector()
	{
		$viewData = $this->bindToMock('FormViewData');
		$viewData->expects($this->once())->method('setMainMenuLink')->with($this->mockLink('index'));
		$viewData->expects($this->once())->method('setVarReturnedName')->with(IndexVxmlFilmController::QUERY_PARAM);
		$viewData->expects($this->once())->method('setSubmitLink')->with($this->mockLink('searchDirectorForm'));
		$viewData->expects($this->once())->method('setPrompt')->with("Busqueda por director. Por favor diga el director a buscar");
		$viewData->expects($this->once())->method('addInputFromCsv')->with(GRAMMAR_CSV_PATH . "/directors.csv");
		$view = $this->bindToMock('FormView');
		$view->expects($this->once())->method('render')->with($viewData);

		$this->getController()->searchDirector(array());
	}

	public function testSearchTitleFormWithoutPages()
	{
		$totalPages = 0;
		$currentPage = 0;
		$this->mockRenderByTitle($totalPages, $currentPage);

		$this->getController()->searchTitleForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}

	public function testSearchTitleFormFirstPage()
	{
		$totalPages = 3;
		$currentPage = 0;
		$this->mockRenderByTitle($totalPages, $currentPage);

		$this->getController()->searchTitleForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}

	public function testSearchTitleFormMediumPage()
	{
		$totalPages = 3;
		$currentPage = 1;
		$this->mockRenderByTitle($totalPages, $currentPage);

		$this->getController()->searchTitleForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}

	public function testSearchTitleFormLastPage()
	{
		$totalPages = 3;
		$currentPage = 3;
		$this->mockRenderByTitle($totalPages, $currentPage);

		$this->getController()->searchTitleForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}

	public function testSearchActorFormWithoutPages()
	{
		$totalPages = 0;
		$currentPage = 0;
		$this->mockRenderByActor($totalPages, $currentPage);

		$this->getController()->searchActorForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}

	public function testSearchActorFormFirstPage()
	{
		$totalPages = 3;
		$currentPage = 0;
		$this->mockRenderByActor($totalPages, $currentPage);

		$this->getController()->searchActorForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}

	public function testSearchActorFormMediumPage()
	{
		$totalPages = 3;
		$currentPage = 1;
		$this->mockRenderByActor($totalPages, $currentPage);

		$this->getController()->searchActorForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}

	public function testSearchActorFormLastPage()
	{
		$totalPages = 3;
		$currentPage = 3;
		$this->mockRenderByActor($totalPages, $currentPage);

		$this->getController()->searchActorForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}

	public function testSearchDirectorFormWithoutPages()
	{
		$totalPages = 0;
		$currentPage = 0;
		$this->mockRenderByDirector($totalPages, $currentPage);

		$this->getController()->searchDirectorForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}

	public function testSearchDirectorFormFirstPage()
	{
		$totalPages = 3;
		$currentPage = 0;
		$this->mockRenderByDirector($totalPages, $currentPage);

		$this->getController()->searchDirectorForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}

	public function testSearchDirectorFormMediumPage()
	{
		$totalPages = 3;
		$currentPage = 1;
		$this->mockRenderByDirector($totalPages, $currentPage);

		$this->getController()->searchDirectorForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}

	public function testSearchDirectorFormLastPage()
	{
		$totalPages = 3;
		$currentPage = 3;
		$this->mockRenderByDirector($totalPages, $currentPage);

		$this->getController()->searchDirectorForm(array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY));
	}
	private function mockRenderByDirector($totalPages, $currentPage)
	{
		$this->mockGetByDirector($totalPages, $currentPage);
		$viewData = $this->mockFilmPagedListViewData('searchDirectorForm', array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY), $currentPage, $totalPages);
		$viewData->expects($this->once())->method('setMainMenuLink')->with($this->mockLink('index'));
		$viewData->expects($this->once())->method('setPreviousPageLink')->with($this->mockLink('searchDirector'));
		$viewData->expects($this->once())->method('setTitle')->with("Peliculas encontradas para el director " . self::QUERY);
		$viewData->expects($this->once())->method('setCurrentPageNumber')->with($currentPage);
		$viewData->expects($this->once())->method('setTotalPages')->with($totalPages);
		$menuViewMock = $this->bindToMock('MenuView');
		$menuViewMock->expects($this->once())->method('render')->with($viewData);
	}

	private function mockGetByDirector($totalPagesReturn, $pageRequested)
	{
		$filmData = array(self::FILM_ID => self::FILM_TITLE);
		$this->bindToMock('FilmAffinityApi')->expects($this->once())
			->method('searchDirector')
			->with(self::QUERY, $pageRequested, IndexVxmlFilmController::FILMS_BY_PAGE)
			->willReturn(array($totalPagesReturn, $filmData));
	}

	private function mockRenderByActor($totalPages, $currentPage)
	{
		$this->mockGetByActor($totalPages, $currentPage);
		$viewData = $this->mockFilmPagedListViewData('searchActorForm', array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY), $currentPage, $totalPages);
		$viewData->expects($this->once())->method('setMainMenuLink')->with($this->mockLink('index'));
		$viewData->expects($this->once())->method('setPreviousPageLink')->with($this->mockLink('searchActor'));
		$viewData->expects($this->once())->method('setTitle')->with("Peliculas encontradas para el actor " . self::QUERY);
		$viewData->expects($this->once())->method('setCurrentPageNumber')->with($currentPage);
		$viewData->expects($this->once())->method('setTotalPages')->with($totalPages);
		$menuViewMock = $this->bindToMock('MenuView');
		$menuViewMock->expects($this->once())->method('render')->with($viewData);
	}

	private function mockGetByActor($totalPagesReturn, $pageRequested)
	{
		$filmData = array(self::FILM_ID => self::FILM_TITLE);
		$this->bindToMock('FilmAffinityApi')->expects($this->once())
			->method('searchActor')
			->with(self::QUERY, $pageRequested, IndexVxmlFilmController::FILMS_BY_PAGE)
			->willReturn(array($totalPagesReturn, $filmData));
	}


	private function mockRenderByTitle($totalPages, $currentPage)
	{
		$this->mockGetByTitle($totalPages, $currentPage);
		$viewData = $this->mockFilmPagedListViewData('searchTitleForm', array(IndexVxmlFilmController::PAGE_PARAM => $currentPage, IndexVxmlFilmController::QUERY_PARAM => self::QUERY), $currentPage, $totalPages);
		$viewData->expects($this->once())->method('setMainMenuLink')->with($this->mockLink('index'));
		$viewData->expects($this->once())->method('setPreviousPageLink')->with($this->mockLink('searchTitle'));
		$viewData->expects($this->once())->method('setTitle')->with("Peliculas encontradas para el titulo." . self::QUERY);
		$viewData->expects($this->once())->method('setCurrentPageNumber')->with($currentPage);
		$viewData->expects($this->once())->method('setTotalPages')->with($totalPages);
		$menuViewMock = $this->bindToMock('MenuView');
		$menuViewMock->expects($this->once())->method('render')->with($viewData);
	}

	private function mockGetByTitle($totalPagesReturn, $pageRequested)
	{
		$filmData = array(self::FILM_ID => self::FILM_TITLE);
		$this->bindToMock('FilmAffinityApi')->expects($this->once())
			->method('searchTitle')
			->with(self::QUERY, $pageRequested, IndexVxmlFilmController::FILMS_BY_PAGE)
			->willReturn(array($totalPagesReturn, $filmData));
	}

	private function mockRenderCartelera($totalPages, $currentPage)
	{
		$this->mockGetCartelera($totalPages, $currentPage);
		$viewData = $this->mockFilmPagedListViewData('getCartelera', array(IndexVxmlFilmController::PAGE_PARAM => $currentPage), $currentPage, $totalPages);
		$mainMenuLink = $this->mockLink('index');
		$viewData->expects($this->once())->method('setMainMenuLink')->with($mainMenuLink);
		$viewData->expects($this->once())->method('setPreviousPageLink')->with($mainMenuLink);
		$viewData->expects($this->once())->method('setTitle')->with("Peliculas en cartelera.");
		$viewData->expects($this->once())->method('setCurrentPageNumber')->with($currentPage);
		$viewData->expects($this->once())->method('setTotalPages')->with($totalPages);
		$menuViewMock = $this->bindToMock('MenuView');
		$menuViewMock->expects($this->once())->method('render')->with($viewData);
	}


	private function mockLink($action, $params = array())
	{

		$this->navigationMapMock->expects($this->any())->method('isValidAction')->willReturn(true);
		$this->navigationMapMock->expects($this->any())->method('isValidParam')->willReturn(true);
		$url = Controller::BASE_URL . NavigationMap::CONTROLLER_PARAM . '=' . urlencode(self::CONTROLLER);
		$url .= '&' . NavigationMap::ACTION_PARAM . "=" . urlencode($action);
		foreach ($params as $paramName => $paramValue) {
			$url .= '&' . $paramName . '=' . urlencode($paramValue);
		}
		return Link::createFromHref($url);

	}

	private function getController()
	{
		return new IndexVxmlFilmController($this->navigationMapMock);
	}

	private function mockGetCartelera($totalPagesReturn, $pageRequested)
	{
		$filmData = array(self::FILM_ID => self::FILM_TITLE);
		$this->bindToMock('FilmAffinityApi')->expects($this->once())
			->method('getCartelera')
			->with($pageRequested, IndexVxmlFilmController::FILMS_BY_PAGE)
			->willReturn(array($totalPagesReturn, $filmData));
	}

	private function mockFilmPagedListViewData($methodBreadCrumbs, $breadCrumbParams, $currentPage, $totalPages)
	{
		$viewData = $this->bindToMock('PagedListViewData');
		$breadcrumbLink = $this->mockLink($methodBreadCrumbs, $breadCrumbParams);
		$filmLinkParams = array(IndexVxmlFilmController::FILM_ID => self::FILM_ID, IndexVxmlFilmController::BREADCRUMBS => $breadcrumbLink->getHrefEncoded());
		$filmLink = $this->mockLink('getFilm', $filmLinkParams);
		$viewData->expects($this->once())->method('addOption')->with(self::FILM_TITLE, KeyPhone::KEY_1, $filmLink);

		if ($currentPage > 0) {
			$breadCrumbParams[IndexVxmlFilmController::PAGE_PARAM] = $currentPage - 1 ;
			$previousPageLink = $this->mockLink($methodBreadCrumbs, $breadCrumbParams);
			$viewData->expects($this->once())->method('setPreviousPageNumberLink')->with($previousPageLink);
			$breadCrumbParams[IndexVxmlFilmController::PAGE_PARAM] = 0;
			$firstPageLink = $this->mockLink($methodBreadCrumbs, $breadCrumbParams);
			$viewData->expects($this->once())->method('setFirstPageNumberLink')->with($firstPageLink);

		}

		if ($currentPage < $totalPages) {
			$breadCrumbParams[IndexVxmlFilmController::PAGE_PARAM] = $currentPage + 1;
			$nextPage = $this->mockLink($methodBreadCrumbs, $breadCrumbParams);
			$viewData->expects($this->once())->method('setNextPageNumberLink')->with($nextPage);
			$breadCrumbParams[IndexVxmlFilmController::PAGE_PARAM] = $totalPages;
			$lastPage = $this->mockLink($methodBreadCrumbs, $breadCrumbParams)  ;
			$viewData->expects($this->once())->method('setLastPageNumberLink')->with($lastPage);
		}

		return $viewData;
	}
}
