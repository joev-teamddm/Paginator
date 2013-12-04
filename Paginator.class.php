<?php
class Paginator {

	protected $dbv2_object;
	protected $current_page;
	protected $count;
	protected $total_pages;
	protected $url_template;

	public function __construct($dbv2_object, $items_per_page = 10)
	{
		$this->dbv2_object = $dbv2_object;
		$this->count = $this->dbv2_object->count();
		$this->items_per_page = (int) $items_per_page;
		$this->total_pages = $this->calculateTotalPages();
	
	}

	public function getCurrentPage()
	{
		return $this->current_page;
	}

	public function getPage($page = 1)
	{
		if((int)$page > 0){
			$this->current_page = $page;
			$page_minus_1 = $page - 1;
		}else{
			$this->current_page = 1;
			$page_minus_1 = 0;
		}
		$offset = $page_minus_1 * $this->items_per_page;
		return new LimitIterator($this->dbv2_object, $offset, $this->items_per_page);
	}

	public function getTotalPages()
	{
		return $this->total_pages;
	}

	public function hasNextPage()
	{
		return $this->current_page < $this->total_pages;
	}

	public function hasPreviousPage()
	{
		return $this->current_page > 1;
	}

	public function printNavigation($url_template = '?page=:page')
	{
		$this->url_template = $url_template;
		
		echo '<ul class="pagination-list">';
		if($this->hasPreviousPage()){
			echo '<li class="previous"><a href="' . $this->getUrl($this->current_page - 1) . '">previous</a></li>';
		}

		$i = 1;
		while($i <= $this->total_pages){
			if($i == $this->current_page){
				echo '<li class="active">' . $i . '</li>';
			}else{
				echo '<li><a href="' . $this->getUrl($i) . '">' . $i . '</a></li>';
			}
			$i++;
		}
		
		if($this->hasNextPage()){
			echo '<li class="next"><a href="' . $this->getUrl($this->current_page + 1) . '">next</a></li>';
		}
		echo '</ul>';
	}

	private function calculateTotalPages()
	{
		if($this->count > $this->items_per_page){
			return ceil($this->count / $this->items_per_page);
		}else{
			return 1;
		}
	}

	private function getUrl($page_number)
	{
		return str_replace(':page', $page_number, $this->url_template);
	}

}