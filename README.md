simple dependency injection container with lazy injections and circular depenendency checking

## example
```
class A {}

class B 
{

  private $a;

  public function__construct(A $a) 
  {
      $this->a = $a;
  }

}

class C 
{

  private $a;
  private $b;

  public function__construct(A $a, B $b) 
  {
      $this->a = $a;
      $this->b = $b;
  }

}

$container = new Container;

$container['A'] = function() {
    return new A;
};

$container['B'] = function($c) {
    return new B($c['A']);
};

$container['C'] = function($c) {
    return new C($c['A'], $c['B']);
};
```





