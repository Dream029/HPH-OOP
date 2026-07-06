<?php
// Parent class
class Fruit {
  public $name;
  public $color;

  public function __construct($name, $color) {
    $this->name = $name;
    $this->color = $color;
  }

  public function intro() {
    echo "The fruit is $this->name and the color is $this->color.<br>";
  }
}

// Strawberry is inherited from Fruit
class Strawberry extends Fruit {
  public function message() {
    echo "Am I a fruit or a berry? ";
  }
}

$strawberry = new Strawberry("Strawberry", "red");
$strawberry->intro();
$strawberry->message();
?>

//-------------------------------------------------

<?php
class Fruit {
  public $name;
  public $color;

  public function __construct($name, $color) {
    $this->name = $name;
    $this->color = $color;
  }

  protected function intro() {
    echo "The fruit is $this->name and the color is $this->color.";
  }
}

class Strawberry extends Fruit {
  public function message() {
    echo "Am I a fruit or a berry? ";
  }
}

// Try to call all three methods from outside class
$strawberry = new Strawberry("Strawberry", "red");  // OK. __construct() is public
$strawberry->message(); // OK. message() is public
$strawberry->intro(); // ERROR. intro() is protected
?>

//--------------------------------------------------

<?php
class Fruit {
  public $name;
  public $color;

  public function __construct($name, $color) {
    $this->name = $name;
    $this->color = $color;
  }

  protected function intro() {
    echo "The fruit is $this->name and the color is $this->color.";
  }
}

class Strawberry extends Fruit {
  public function message() {
    echo "Am I a fruit or a berry? ";
    // Call protected method from within derived class - OK
    $this -> intro();
  }
}

$strawberry = new Strawberry("Strawberry", "red"); // OK. __construct() is public
$strawberry->message(); // OK. message() is public and calls intro() (which is protected) from within the derived class
?>

//-----------------------------------------------

<?php
class Fruit {
  public $name;
  public $color;

  public function __construct($name, $color) {
    $this->name = $name;
    $this->color = $color;
  }

  public function intro() {
    echo "The fruit is $this->name and the color is $this->color.";
  }
}

class Strawberry extends Fruit {
  public $weight;

  public function __construct($name, $color, $weight) {
    $this->name = $name;
    $this->color = $color;
    $this->weight = $weight;
  }

  public function intro() {
    echo "A $this->name is $this->color, and the weight is $this->weight gram.";
  }
}

$strawberry = new Strawberry("Strawberry", "red", 50);
$strawberry->intro();
?>

//--------------------------------------------------

<?php
final class Fruit {
  // some code
}

// will result in error
class Strawberry extends Fruit {
  // some code
}
?>

//-------------------------------------------------

<?php
class Fruit {
  final public function intro() {
    // some code
  }
}

class Strawberry extends Fruit {
  // will result in error
  public function intro() {
    // some code
  }
}
?>