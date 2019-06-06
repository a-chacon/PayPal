<!-- Agregado MVC -->
<? include($f."contabilidad/select/js/js.php"); ?>
<meta name="viewport" content="width=device-width">

<script>

        $(function() {

            var pull        = $('#pull');

                menu        = $('nav#menuprinc ul');

                menuHeight  = menu.height();



            $(pull).on('click', function(e) {

                e.preventDefault();

                menu.slideToggle();

            });



            $(window).resize(function(){

                var w = $(window).width();

                if(w > 320 && menu.is(':hidden')) {

                    menu.removeAttr('style');

                }

            });

        });

</script>

<!-- /Agregado MVC -->

<div id="headlogo">
  <img src="/contabilidad/img/logo_v.png">
  </div>

<!-- MENÚ -->
<nav id="menuprinc" style="background: #3a8aff;">
<style type="text/css">

<!--

nav#menuprinc{
    width: 100%;
    line-height: 1em;
    float: left;
    padding: .2em 0;
    margin: 0 0 1em 0;
}
nav#menuprinc ul{
    max-width: 100%;
    list-style: none;
    margin: 0 auto;
    padding: 0; 
    font-weight: bold;
	font-size: 1em;
}
nav#menuprinc ul li{
    float: left;
    display: block;
}
nav#menuprinc ul li {
    color: #fff;
    margin: .45em 0;
    padding: 0 .5em;
    display: block;
    position: relative;
}
nav#menuprinc ul li:last-child{
    border-right: none;
}
nav#menuprinc ul li a{
    color: #fff;
    padding: .5em 0;
	font-weight: bold;
	text-decoration:none;
}
nav#menuprinc ul li a:hover, .active{
    color: #FFC264;
}
nav#menuprinc ul li ul {
  padding: 0;
  position: absolute;
  top: 25px;
  left: 0;
  -webkit-box-shadow: none;
  -moz-box-shadow: none;
  box-shadow: none;
  display: none;
  z-index: 999;
  opacity: 0;
  visibility: hidden;
  -webkit-transiton: opacity 0.2s;
  -moz-transition: opacity 0.2s;
  -ms-transition: opacity 0.2s;
  -o-transition: opacity 0.2s;
  -transition: opacity 0.2s;
}
nav#menuprinc ul li ul li:before{
    content: "";
    margin: 0 0 0 0; 
    float: left;
}
nav#menuprinc ul li ul li { 
  width: 240px;
  background: #ABABAB; 
  display: block;   
  color: #fff;
  margin: 0;
  padding: .2em .3em;
  font-size: 1em;
  font-weight: normal;
  text-align: left;
}
nav#menuprinc ul li ul li:hover { background: #474747 ; }
nav#menuprinc ul li:hover ul {
  display: block;
  opacity: 1;
  visibility: visible;
}
#pull{
    display: none;
}
@media (max-width: 800px) {

nav#menuprinc {
    border-bottom: 0;
}
nav#menuprinc ul {
    display: none;
    height: auto;
    padding-left: 0;
    background: #3a8aff;
    position: relative; 
}
nav#menuprinc ul li {
    display: block;
    float: none;
    width: 100%;
    margin: 0;
    border-bottom: 1px solid #ccc;
    padding: 0;
}
nav#menuprinc ul li a{
    display: block;
    text-align: center;
    color: #fff;
}
nav#menuprinc a#pull {
    display: block;
    background-color: #3a8aff;
    width: 100%;
    position: relative;
    padding: .5em 0;
    color: #fff;
    text-align: center;
    font-weight: bold;
    text-decoration: none;

}
nav#menuprinc a#pull:after {
    content:"";
    width: 30px;
    height: 30px;
    display: inline-block;
    position: absolute;
    right: 15px;
    top: 10px;
}
nav#menuprinc ul li ul {
  padding: 0;
  position: relative;
  top: 0;
  left: 0;
  -webkit-box-shadow: none;
  -moz-box-shadow: none;
  box-shadow: none;
  display: none;
  opacity: 1;
  visibility: visible;
  -webkit-transiton: opacity 0.2s;
  -moz-transition: opacity 0.2s;
  -ms-transition: opacity 0.2s;
  -o-transition: opacity 0.2s;
  -transition: opacity 0.2s;
}
nav#menuprinc ul li ul li:before{
    content: " ";
    margin: 0; 
    float: left;
}
nav#menuprinc ul li ul li { 
  width: 100%;
  background: #ABABAB; 
  display: block;   
  color: #fff;

  margin: 0;
  padding:0;
  font-size: 1.2em;
  font-weight: normal;
  text-align: left;
}
nav#menuprinc ul li ul li:hover { background: #474747 ; }
nav#menuprinc ul li:hover ul {
  display: block;
  opacity: 1;
  visibility: visible;
}

-->

</style>