# [Laravel](https://laravel.com)-fly-view

>Is an Extension of Laravel View Class which compiles String Template on the fly. It automatically detects changes on your string template and recompiles it if needed.

> This is useful if you want to render your template which came from other sources like CMS or third party API

> Since its an Extension of laravel View class. It will not interfere on the usual flow of your application. You can still use laravel view as per normal but with the capability of passing string template.

>It supports all directives of Blade Template.

>Supports Laravel 5.2+



Installation :traffic_light:
-------
Add the package to your composer.json

```
"require": {
	...
	"johnturingan/laravel-fly-view": "{version}"
},
```

Or just run composer require

```bash
$ composer require johnturingan/laravel-fly-view
```

In config/app.php replace

######Illuminate\View\ViewServiceProvider::class

with

######Snp\FlyView\Providers\ViewServiceProvider::class


## Usage :white_check_mark:

######View normal usage:
Pass path to blade file using dot notation on the first parameter

```
return view('path.to.view', []);
```

######Flyview usage: 
Pass array of strings on the first parameter

```
return view([ 'String Template with {{$blade}} syntax and @directives' ], []);
```
or you can do

```
return view([
    '{{ $token }}',
    '{{ $me }}'
], [
    'token' => Str::uuid(),
    'me' => 'Laravel Fly View'
]);

```

Flyview will merge all strings inside the array before compile. Useful if you have multiple template sources.

You can also use if from response helper like this.

```
return response()->view([
    '{{ $token }}',
    '{{ $me }}'
], [
    'token' => Str::uuid(),
    'me' => 'Laravel Fly View on Response Helper'
]);
```

Like I said before, it will not interfere the usual flow of Laravel View. Meaning you can do something like this.

```
$bag = [
    'include' => [
        '{{ $token }} - This is FlyView Include',
        '@include("includes.nativeInclude") <br/> Above is Include Inception'
    ],
    'data' => [ 'token' => Str::uuid() ]
];

return view('includeTest', $bag);
```

Inside your includeTest.blade.php file is this:

```
@include('includes.nativeInclude', $data)

@include($include, $data)
```
Including string template to blade template file is possible.

## Config :page_facing_up:

All configuration is same as the default view config in your config folder.

**`NOTE:`**

If you find any bugs or you have some ideas in mind that would make this better. Please don't hesitate to send comment on github.

If you find this package helpful, a simple star is very much appreciated.

----
**[MIT](LICENSE) LICENSE** <br>
copyright &copy; 2018 Scripts and Pixels.
