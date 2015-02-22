### What is j-Eloquent?
Thanks to Laravel, Django and Rails we all know that convention over configuration (CoC) makes the development more funny. So suppose that you want to convert the Gregorian date attributes of your ```Eloquent``` models to Jalali (persian) dates, in this case ```j-Eloquent``` helps you to convert them conventionally, for example when you access a property named ```$model->jalali_created_at``` on your model, the ```PersianDateTrait``` detects the convention automatically and tries to convert ```created_at``` property of your model if it is a date attribute. This is also true for ```$model->toJson();``` and ```$model->toArray();``` fields.

#### Installation
require following line in  your composer ```require``` secion : 


```javascript

	"require" : {
			// Other dependecies ,
			"bigsinoos/j-eloquent" : "dev-master" // Laravel 5 , "1.0" for Laravel 4
	}

```

#### Requirement

this package requires : [miladr/jalali](https://github.com/miladr/jalali)


#### Features

- Coneventionally converts Eloquent model date attributes to Jalali date when the original date attribute is accessed prefixed by a string like ```jalali_```.
- Automatically converts date attributes to Jalali dates when model's ```toArrayl```, ```__toString();``` and ```toJson``` are called.
- Custom date formats can be set for dates.


#### Documentation

##### The ```PersianDateTrait``` :
By using ```\Bigsinoos\JEloquent\PersianDateTrait``` trait in your models you can enable the plugin : 
```php
<?php

class Role extends \Eloquent {
    use \Bigsinoos\JEloquent\PersianDateTrait;
    protected $table = 'roles';
}
```

#### Usage
By default you can access your eloquent date attributes in jalali date by adding a ```jalali_``` substring to the begining of your original attribute like ```jalali_created_at``` :

```php
    $userPremiumRole = Auth::user()->roles()->where('type', 'premium');
    $userPremiumRole->create_at; // 2014/12/30 22:12:34
    $userPremiumRole->jalali_created_at; // 93/09/08
```

##### Changing ```jalali_``` prefix
You can change the jalali date convention prefix with overriding ```$model->setJalaliFormat($format)``` and ```$model->getJalaliFormat();``` or by overrriding ```$model->jalaliDateFormat``` property on your model class :

```php

class Role extends \Eloquent {

    use \Bigsinoos\JEloquent\PersianDateTrait;

    protected $jalaliDateFormat = 'l j F Y H:i';
}

# or

class Role extends \Eloquent {
    
    use \Bigsinoos\JEloquent\PersianDateTrait;
    
    public function setJalaliFormat($format){
        // do custom things here
        $this->jalaliDateFormat = $format; return $this;
    }
    
    protected function getJalaliFormat()
    {
        // return any format you want
        return 'l j F Y H:i';
    }

}

```

##### Custom date attributes : 
You can tell Eloquent that which one of your fields are date attributes like created_at and updated_at, then Eloquent treats them like ```Carbon``` objects you define multiple date attributes like this :

```php

Class Role extends \Eloquent {
    
    use \Bigsinoos\JEloquent\PersianDateTrait;
    
    /**
    * Add this method to customize your date attributes
    *
    * @return array
    */
    protected function getDates()
    {
        return ['created_at', 'updated_at', 'expired_at'];
    }
    
}
```
When using the above trait all of the fields that are treated like date objects by Laravel will be available for conventional converting. They will be also added to model's ```toJson()``` , ```toArray();``` and ```__toString();``` methods.

##### Converter helper method

The ```$model->convertToPersian($attribute, $format);``` method allowes you to normally convert one of your fields, from Gregorian date to Jalali date :

```php

$user = Auth::user();
$user->convertToPersian('created_at', 'y/m/d'); // 93/09/08

```


