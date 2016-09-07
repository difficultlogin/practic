<meta charset="utf-8">

<?php

class ShopProduct {
	public $title;
	public $productMainName; 
	public $producerFirstName;
	protected $price;
	private $discount = 0;
	private $id = 0;

	function __construct($title, $firstName, $mainName, $price) {
		$this->title 		 = $title;
		$this->producerFirstName = $firstName;
		$this->producerMainName  = $mainName;
		$this->price 		 = $price;
	}
	
	public function setID($id) {
		$this->id = $id;
	}

	public static function getInstance($id, PDO $pdo) {
		$stmt = $pdo->prepare("select * from products where id = ?");
		$result = $stmt->execute(array($id));

		$row = $stmt->fetch();

		if (empty($row)) { return null; }
		if ($row['type'] == 'book') {
			$product = new BookProduct($row['title'],
				$row['firstname'],
				$row['mainname'],
				$row['price'],
				$row['price'],
				$row['numpages']);
		} else if ($row['type'] == 'cd') {
			$product = new CDProduct($row['title'],
				$row['firstname'],
				$row['mainname'],
				$row['price'],
				$row['playlength']);
		} else {
			$product = new ShopProduct($row['title'],
				$row['firstname'],
				$row['mainname'],
				$row['price']);
		}

		$product->setId($row['id']);
		$product->setDiscount($row['discount']);

		return $product;

	}

	public function getProducerFirstName() {
		return $this->producerFirstName;
	}

	public function getProducerMainName() {
		return $this->producerMainName;
	}

	public function setDiscount($num) {
		$this->discount = $num;
	}

	public function getDiscount() {
		return $this->discount;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getPrice() {
		return ($this->price - $this->discount);
	}

	function getSummaryLine() {
		$base  = $this->title . ' ('. $this->producerMainName .')';
		$base .= $this->producerFirstName;
		return $base;
	}
}

class CDProduct extends ShopProduct {
	private $playLength;

	function __construct($title, $firstname, $mainName, $price, $playLength) {
		parent::__construct($title, $firstName, $mainName, $price);
		$this->playLength = $playLength;
	}

	function getPlayLength() {
		return $this->playLength;
	}

	function getSummaryLine() {
		$base  = parent::getSummaryLine();
		$base .= "{$this->producerFirstName} )";
		$base .= ": Время звучания - {$this->playLength}";

		return $base;
	}
}

class BookProduct extends ShopProduct {
	private $numPages = 0;

	function __construct($title, $firstName, $mainName, $price, $numPages) {
		parent::__construct($title, $firstName, $mainName, $price);
		$this->numPages = $numPages;
	}

	function getNumberOfPages() {
		return $this->numPages;
	}

	function getSummaryLine() {
		$base  = parent::getSummaryLine();
		$base .= ": $this->numPages стр.";

		return $base;
	}

	public function getPrice() {
		return $this->price;
	}
}
/*
class ShopProductWriter {
	private $products = array();

	public function addProduct(ShopProduct $shopProduct) {
		$this->products[] = $shopProduct;
	}

	public function write(ShopProduct $shopProduct) {
		$str = '';

		foreach ($this->products as $shopProduct) {
			$str .= "{$shopProduct->title}: ";
			$str .= $shopProduct->getProducer();
			$str .= "({$shopProduct->getPrice()})\n";
		}

		print $str;
	}
}
 */

abstract class ShopProductWriter {
	protected $products = array();

	public function addProduct(ShopProduct $shopProduct) {
		$this->products[] = $shopProduct;
	}

	abstract public function write();
}

$dsn = "sqlite:///Users/toor/Sites/practic.bit/php/3/product.db";
$pdo = new PDO($dsn, null, null);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$obj = ShopProduct::getInstance(1, $pdo);

print_r($obj);
