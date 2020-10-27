# TinyAnalytics

<img src="http://gget.it/27cgzhtl/TinyAnalytics.png" width="900"/>

**TinyAnalytics** is a lightweight web analytics tool based on the idea that:

* The two most useful things are: **number of unique visitors per day** (with a nice chart) and **list of referers** who send some traffic to your websites,

* It should give the idea of the traffic, even for multiple websites, **on a single dashboard** (without having to click in lots of menu items to change the currently displayed website, etc.),

* It should be fast and lightweight.

If you're looking for more informations than those (such as country, browser, screen resolution, time spent on a page, etc.), then **TinyAnalytics** is not the right tool for you. Please try [Google Analytics](https://analytics.google.com), [Open Web Analytics](https://www.openwebanalytics.com/) or [Piwik](https://www.piwik.org/) instead. I personally found the two last ones [not very handy for me](http://josephbasquin.fr/aboutanalytics).

> After years, I've noticed that **I prefer to have few (important) informations that I can consult each day in 30 seconds**, rather than lots of informations for which I would need 15 or 30 minutes per day for an in-depth analysis.

## Install

There are three easy steps:

1) Unzip this package to a host accessible with `https`.

2) Add the following tracking code to your websites at the end of files:
```html
<script>
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'https:// myhost /req.php');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({ "sn": "my-website-name" }));
</script>
```

3) Modify your password in config.php and set the allowed domains there.   

It's done! Visit at least one of your tracked websites, and open `https:// myhost /index.php` in your browser!

## About

Author: Joseph Ernest ([@JosephErnest](https://twitter.com/JosephErnest))

Thanks to [WhiteHat](http://stackoverflow.com/users/5090771/whitehat) for his help on the chart visualization design.

PHP only version by [Ben Yafai](https://github.com/benyafai)

Javascript version by Thomas Malicet ([@tmalicet](https://twitter.com/tmalicet))

## License

MIT license
