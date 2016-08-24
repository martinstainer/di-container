# Simple DI Container
simple dependency injection container with lazy injection and circular dependency detection

## example
```
class Sugar {}

class Coffee {}

class Milch {} 

class MyFavoriteCoffee
{

  private $sugar;
  private $coffee;
  private $milch;

  public function __construct(Sugar $sugar, Milch $milch, Coffee $coffee) 
  {
      $this->sugar = $sugar;
      $this->milch = $milch;
      $this->coffee = $coffeee;
  }
  
  public function drink()
  {
  }

}

$container = new Container;

$container['myFavCoffee'] = function($c) {
    return new MyFavoriteCoffee($c['sugar'], $c['milch'], $c['coffee']);
};

$container['sugar'] = function() {
    return new Sugar;
};

$container['coffee'] = function() {
    return new Coffee;
};

$container['milch'] = function() {
    return new Milch;
};

$container['myFavCoffee']->drink();


```





