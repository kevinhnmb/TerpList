<?php
	declare(strict_types=1);

	class Post {
		private $postingTitle;
		private $price;
		private $imagePath;
		private $description;
		private $userEmail;
		private $category;

		public function __construct(string $postingTitle, string $price, string $imagePath, string $description, string $userEmail, string $category) {
			$this->postingTitle = $postingTitle;
			$this->price = $price;
			$this->imagePath = $imagePath;
			$this->description = $description;
			$this->userEmail = $userEmail;
			$this->category = $category;
		}

		public function __toString() {
			return $this->postingTitle." ".$this->price." ".$this->description." ".$this->category;
		}

		public function getPostingTitle() {
			return $this->postingTitle;
		}

		public function getPrice() {
			return "$".$this->price;
		}

		public function getImagePath() {
			return $this->imagePath;
		}

		public function getDescription() {
			return $this->description;
		}

		public function getUserEmail() {
			return $this->userEmail;
		}

		public function getCategory() {
			return $this->category;
		}
	}

?>