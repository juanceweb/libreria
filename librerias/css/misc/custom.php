<?php 

header("Content-type: text/css; charset: UTF-8");

include_once("../../inc/configuracion.php");

?>

.bg-gray-1{
  background-color:#f8f8f8; 
}

.bg-gray-2{
  background-color:#999999; 
}

.bg-gray-3{
  background-color:#555555; 
}

.bg-gray-4{
  background-color:#333333; 
}

.bg-gray-5{
  background-color:#222222; 
}

.bg-color-1{
  background-color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>; 
}

.bg-color-2{
  background-color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][1] ?>; 
}

.bg-color-3{
  background-color:#fff; 
}

.bg-color-4{
  background-color:#1f1f1f; 
}

.bg-color-5{
  background-color:#000; 
}


.cl-gray-1{
  color:#f8f8f8; 
}

.cl-gray-2{
  color:#999999; 
}

.cl-gray-3{
  color:#555555; 
}

.cl-gray-4{
  color:#333333; 
}

.cl-gray-5{
  color:#222222; 
}

.cl-color-1{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>; 
}

.cl-color-2{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][1] ?>; 
}

.cl-color-3{
  color:#fff; 
}

.cl-color-4{
  color:#1f1f1f; 
}

.cl-color-5{
  color:#000; 
}

.no-padding{
  padding: 0!important;
}

.padding-md{
  padding: 10px;
}

.bold{
  font-weight: 600;
}

.bolder{
  font-weight: 900;
}

.borde{
  padding: 20px;
  border: solid thin #ddd;
}

.sin-borde{
  border:none!important;
}

.titulo{
  text-transform: uppercase;
  padding: 20px;
  border: solid thin #ddd;
  margin-bottom: 30px; 
  margin-top: 0;   
}

.titulo2{
    font: normal 20px/20px Raleway;
    margin-top: 15px;
    color: #333333;  
}

.titulo3{
    font: normal 16px/16px Raleway;
    margin-top: 15px;
    color: #333333;  
}



.contenedor{
  padding: 15px;
}

.inner{
  overflow: hidden;
  border: solid thin #ddd;
  transition:0.5s;
  padding: 10px;        
}

.blink {
  animation: blink-animation 1s steps(50, start) infinite;
  -webkit-animation: blink-animation 1s steps(50, start) infinite;
}
@keyframes blink-animation {
  to {
    opacity: 0;
  }
}
@-webkit-keyframes blink-animation {
  to {
    opacity: 0;
  }
}

/********* Configuraciones Particulares *********/ 

a:hover, button:hover{
  cursor: pointer!important;
}

a:focus, button:hover{
  cursor: pointer!important;
}

input[type="range"]{
  box-shadow:none;
}


body{
  font-family: <?php echo $GLOBALS["configuracion"]["brand"]["fuentes"][0] ?>, sans-serif!important;
}

.h1, .h2, .h3, h1, h2, h3 {
    margin-top: 5px;
    margin-bottom: 5px;
}

.btn{
  border-radius: 0px!important;
  text-transform: uppercase;
}


input, select, textarea{
  border-radius: 0!important;
  z-index: 0!important;
}

label{
  font-weight: 300;
}

.input-group-addon{
  width: 30px;
}

.input-group{
  width: 100%;
}

textarea{
  width:100%;
  resize: none;
}

.inputerror{
  border-color:red;
}

.inputexito{
  border-color:green;
}

span.help-block.error {
    color: red;
    font-size: 8pt;
}

span.help-block.exito {
    color: green;
    font-size: 8pt;
}

span.help-block.info {
    color: blue;
    font-size: 8pt;
}


.table{
  margin-bottom:0!important;
}

dd{
  margin-bottom: 10px;
  text-align: justify;
}

.flex{
  display: flex;
}

.mayuscula{
  text-transform: uppercase;
}

.no-display{
  display: none!important;
}

a{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}

a:hover{
  text-decoration: none;
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}

a:focus{
  text-decoration: none;
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}

.badge{
  font-family: monospace;
  position: absolute;
  bottom: 0;
  right: -12px;
  background-color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
  color:#fff;
  border-radius: 50px;
  padding: 5px 9px;
  font-size: 18px;  
}

.pull-right>.dropdown-menu {
    right: 15px;
    left: auto;
    border-radius: 0;
    border: 0;
}

.btn-danger{
  background-color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>!important;
  border-color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>!important;  
}

.btn-danger:hover{
  opacity:0.8; 
}

.btn-danger:active{
  opacity:0.6; 
}

.btn-danger.is-checked{
  opacity:0.6; 
}

.btn-primary{
  background-color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][1] ?>!important;
  border-color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][1] ?>!important;  
}

.btn-primary:hover{
  opacity:0.8; 
}

.btn-primary:active{
  opacity:0.6; 
}

.btn-primary.is-checked{
  opacity:0.6; 
}

/* Header */

.affix {
  top: 0;
  width: 100%;
}


#login{
  background: <?php echo $GLOBALS["configuracion"]["brand"]["colores"][2] ?>;
  padding: 10px 0 10px 0;
}

#login input{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>!important; 
}

#logo{
  height: 120px;
  background-size: contain;
  background-repeat: no-repeat;     
}

.affix{
  padding: 0!important;
  z-index: 9;
}

#btn-cart{
  display: inline-block;
}

#btn-cart #boton-cesta{
  padding: 20px;
  margin-top: 10px;
  margin-bottom: 10px;
  box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.3);
  border: solid 1px #bbb;
  transition:0.3s;    
}

.dropdown-menu li{
  border:none!important;
}

#btn-cart:focus .btn{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][1] ?>;
  background-color: #fff;
}

#btn-cart:hover #boton-cesta{
  -ms-transform: rotateY(-180deg);
  -webkit-transform: rotateY(-180deg);
  transform: rotateY(-180deg);
  color:#fff; 
  background-color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][1] ?>;   
}

#encabezado-fijo{
  transition:0.5s ease;
}

#buscador{
  margin: 7px 0 7px 0;
}

@media (max-width: 992px){
  
  #encabezado-central div{
    text-align: center;
  }
  #logo{
    background-position:center center;
    margin-bottom: 20px;
  }
  #encabezado-central .pull-right{
    float: none!important;
  }
  #encabezado-central .pull-right .dropdown-menu {
    right: 0;
    left: 0;
  }
  #encabezado-central h3,h2{
    font-size: 20px;
  }

  .nav{
    font-size: 8pt;
  }

  .titulo{
    font-size: 10pt;  
  }

  .novedades-descripcion{
    padding: 10px!important;
  } 

  footer{
    text-align: center;
  }

  .badge{
    position: relative;
    bottom: -40px;
    right: 20px;
  }   
}

@media (min-width: 768px) {
  .navbar-nav {
    display: inline-block;
    float: none;
    vertical-align: top;
  }

  .navbar-collapse {
    text-align: center;
  }

  .nav li{
  border: 1px solid #d5d5d5;
  background-color: #fff;
}

.nav .active a{
  color: #fff!important;
  background: <?php echo $GLOBALS["configuracion"]["brand"]["colores"][2] ?>!important;
}

.nav .caret{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}


}


@media (max-width: 992px){
  .productosMostrar{
    display: none!important;
  }

  .form-submit{
    margin-top: 30px;
  }
}


@media (max-width: 1200px){
  
  #encabezado-fijo .pull-right, .pull-left{
    float: none!important;
  }
}

.navbar-collapse.in{
  overflow: hidden!important;
}

#encabezado-central{
  margin-top: 20px;
}

.nav{
  text-transform: uppercase;
}





/* Categorías */
#categorias{
  margin-top: 15px;
  margin-bottom: 15px;
}

#categorias h1{
  text-transform: uppercase;
}

.categoria-titulo1, .categoria-titulo2{
  font-size:30px
}

.categoria-contenedor{
  padding: 15px;
}

.categoria-inner{
  overflow: hidden;
  border: solid thin #ddd;
  transition:0.5s;        
}

.categoria-inner:hover{
  -webkit-box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);
  -moz-box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);
  box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);
}

.categoria-contenedor:hover .categoria-imagen{
  transform:  scale3d(1.02,1.02,1.02);
}

.categoria-imagen{
  transition: 0.3s;
  height: 250px;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
  margin-bottom: 10px;
}

.categoria-descripcion{
  background-color: #fff;
  z-index: 8;
  margin-bottom: 10px;
  padding: 20px;
}

.categorias-grandes .categoria-descripcion{
    position: absolute;
    margin: 15px;
    background-color:transparent;  
}

.categorias-grandes h1{
    color: white;
    text-shadow: #333 2px 2px 3px; 
}

.categorias-grandes h3{
    color: white;
    text-shadow: #333 1px 1px 1px; 
}

@media (max-width: 767px) {

  .categorias-grandes .categoria-descripcion{
      position: relative;
      margin: 0px;
      background-color:white;
  }

  .categorias-grandes h1{
      color: inherit;
      text-shadow: none; 
  }    

  .categorias-grandes h3{
    font-size:10pt;
    color:inherit;
    text-shadow:none;
  }
}


/* Destacados */

#destacados{
}

.destacados-contenedor{
  margin-bottom: 30px;
}

.destacados-inner{
  overflow: hidden;
  border: solid thin #ddd;
  transition:0.5s;        
}

.destacados-inner:hover{
  -webkit-box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);
  -moz-box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);
  box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);  
}

.destacados-contenedor:hover .destacados-imagen{
}

.nombre-producto{
  text-transform: uppercase;
  text-decoration: none!important;
  color: inherit;
  transition:0.5s;  
}

.destacados-inner:hover .nombre-producto{
  color: <?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}

.destacados-imagen{
  transition: 0.3s;
  height: 300px;
  margin-bottom: 10px;
  vertical-align: middle;
  display: table-cell;  
}

.destacados-imagen img{
  width: 100%;
}

.destacados-descripcion{
  background-color: #fff;
  z-index: 9;
  margin-bottom: 10px;
}

.contenedor-favoritos{
  margin-top: 10px;
  margin-bottom: 10px;
}

.favoritos{
  text-decoration: none!important;
  color: inherit;
  transition:0.5s;  
}

.favoritos:hover{
  color: <?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;  
}

#panel-favoritos, #panel-cesta{
  margin-bottom:30px;
}

#panel-favoritos .btn-danger, #panel-cesta .btn-danger{
  color:#fff!important;
}

.panel-favoritos-imagen{
  height:90px;
  background-size:contain;
  background-repeat:no-repeat;
}

#panel-favoritos .close, #panel-cesta .close{
  margin-right: -20px;
  margin-top: -13px;
}

/* Novedades */

#novedades{
  padding-top: 30px;
}

.novedades-titulo{
  text-transform: uppercase;
}
.novedades-contenedor{
  margin-bottom: 20px;
}

.novedades-inner{
  overflow: hidden;
}

.novedades-contenedor:hover .novedades-imagen{
}

.novedades-imagen{
  transition: 0.3s;
  height: 300px;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
}

.novedades-descripcion{
  z-index: 9;
  margin-bottom: 10px;
  color: #fff;
}

.novedades-btn{
  position: absolute;
  bottom: 10px;
}

.novedades-zoom{
  position: absolute;
  width: 100%;
  height: 100%;
  background-color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
  opacity:0.6; 
  transition:0.2s ease;
  transform:scale(0,0);
  text-align: center;
}

.novedades-zoom a{
  position: relative;
  top: 40%;
}

.novedades-zoom a:hover{
  color:#fff!important;
}


.novedades-contenedor:hover .novedades-zoom{
  transform:scale(1,1);
}
/* Footer */

footer{
  padding-top: 30px; 
}

#contenedor-contactos{
  padding-bottom: 20px;
}

#pie{
  padding-top: 10px;
  padding-bottom: 10px;
  background-color: <?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}

#pie a{
  color: #fff!important;
}

.input-group-addon{
  padding: 0;
  border: none;
}

.contactos-iconos{
  margin-top: 10px;
}

#newsletter-container{
  margin-top: 20px;
}


/************ Productos ***********/

#modalproducto{
  z-index:999!important;
}

#modalproducto .modal-footer{
  border:none;
}

.modal-backdrop{
  z-index:10!important;
}

.zoomContainer{
  z-index:9999!important;  
}

.productos-titulo, .productos-precio, .productos-controles{
  padding-top: 15px;
  padding-bottom: 15px;  
}

.producto-descripcion{
  margin-bottom: 10px;
}

.productos-contenedor{
  transition: 0.5s ease-out;
  transition: width 0s;
}


.grilla .productos-inner{
    overflow: hidden;
    border: solid thin #ddd!important;
}

.grilla .productos-inner{
  margin: 15px;
  padding:15px;
}

.grilla .productos-inner:hover{
  -webkit-box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);
  -moz-box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);
  box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);  
}

.grilla .productos-titulo, .grilla .productos-precio, .grilla .productos-controles{
  padding-top: 0;
  padding-bottom: 0;
}

.lista .producto-descripcion{
  display: none;
}

.lista {
    overflow: hidden;
    border: solid thin #ddd!important;
    padding: 10px!important;
    margin-bottom: 10px!important;
}


.lista:hover{
  -webkit-box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);
  -moz-box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);
  box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);  
}

.contenedor-opcion-atributo:hover{
  border: solid thin #ddd!important;
  cursor: pointer;
  opacity:0.5;
}


.lista:first-child{
  margin-top:15px!important;
}

.productos-contenedor-imagen{
  height: auto;
}

.productos-imagen{
  width: 100%;
  height:<?php echo $GLOBALS["configuracion"]["productos"]["altoimagen"]?>;
}

.grilla .productos-imagen{
  min-height:100px;
}

.lista .productos-imagen{
  height:auto;  
}

.productos-titulo a{
  color:#222222;
}

.productos-contenedor:hover .productos-titulo a{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}

.productos-titulo a:focus{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}


.panel-group .panel+.panel {
    margin-top: 0px!important;
}

.nav-tabs>li>a {
    background: #e9e9e9;
}

.detalleproducto{
  margin-top:15px;
  margin-bottom:15px;
}

tab-pane{
  padding: 10px;
}

/************ Portfolio ***********/

.portfolio-titulo{
  padding-top: 15px;
  padding-bottom: 15px;  
}

.portfolio-descripcion{
  margin-bottom: 10px;
}

.portfolio-contenedor{
  transition: 0.5s ease-out;
  transition: width 0s;
}

.grilla .portfolio-inner{
    overflow: hidden;
    border: solid thin #ddd!important;
}

.grilla .portfolio-inner{
  margin-bottom: 15px;
  margin-top: 15px;
  padding:15px;
}

.grilla .portfolio-inner:hover{
  -webkit-box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);
  -moz-box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);
  box-shadow: 2px 2px 20px 0px rgba(0,0,0,0.40);  
}

.grilla .portfolio-titulo{
  padding-top: 0;
  padding-bottom: 0;
}


.portfolio-contenedor-imagen{
  height: auto;
}

.portfolio-imagen{
  width: 100%;
}

.portfolio-titulo a{
  color:#222222;
}

.portfolio-contenedor:hover .portfolio-titulo a{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}

.portfolio-titulo a:focus{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}

#filters .btn{
  margin:5px;
}

.portfolio2-categoria{
  text-transform:uppercase;
}

#paginaportfolio ul{
  margin-top:10px;
}

#paginaportfolio ul li{
  margin-bottom:5px;
}

#paginaportfolio ul li a{
  margin-left:10px;
}



/*Menu*/

.panel{
  border-radius: 0!important;
  padding: 0;
  border: none;
  box-shadow: none;
}

#productos-menu{
  min-height: 500px;
}

#productos-categorias a{
  margin-left: 5px;
}
#productos-menu a{
  color:#1c1c1c;
}

#productos-menu a:hover{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}

#productos-menu a:focus{
  color:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][0] ?>;
}

#productos-categorias{
  margin-bottom: 30px;
}
.fa-ul{
  margin-left: 0;
}

#productos-categorias li{
  margin-bottom: 5px;
}

li.menu-linea {
  font-size: 8pt;
  margin-left: 10px;
  margin-bottom: 1px!important;
}

.productos-mas-valorados-imagen{
  height: 50px;
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center center;
  border: solid thin #ddd;

}

#cesta .row{
  margin-right: -5px!important;
  margin-left: -5px!important;
  margin-top: 0px!important;
  margin-bottom: 5px!important;
}

#cesta{
  width:300px!important;
}

/************ Registro ************/

.help-block{
  display: none;
}

.required{
  color: red;
  font-weight: bold;
}



#registro-confirmar{

}


/************ Descargas ************/

i.fa.fa-download {
    float: left;
    width: 25px;
}

span.descargas-tamanoarchivo {
    width: 100px;
    float: left;
}


span.descargas-tipoarchivo {
    width: 50px;
    float: left;
}

/************ Novedad ************/

.novedad img{
  max-width:100%;
  margin-top:15px;
  margin-bottom:15px;
}

.novedad p{
  margin-top:15px;
  margin-bottom:15px;
}

#paginanovedades{
  margin-bottom:30px;
}

.novedad{
  margin-bottom:30px;
}


/************ Parallax ************/
.parallax {
  position: relative;
  overflow: hidden;
}
.parallax_image, .parallax_pattern {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 100%;
  background-position: center center;
  will-change: transform;
}
.parallax_image {
  background-repeat: no-repeat;
  background-size: cover;
}
.parallax_pattern {
  background-repeat: repeat;
}
.parallax_cnt {
  position: relative;
}
@media (max-width: 979px) {
  .parallax_skins2 .parallax_image {
    background-position: 70% center;
  }
}
@media (max-width: 767px) {
  .parallax_skins2 .parallax_image {
    background-size: 280%;
    background-position: 68% 50%;
  }
}
@media (max-width: 767px) {
  .parallax_skins6 .parallax_image {
    background-position: 25% center;
  }
}
@media (max-width: 767px) {
  .parallax_skins7 .parallax_image {
    background-position: 65% center;
  }
}
@media (max-width: 479px) {
  .parallax_skins7 .parallax_image {
    background-position: 62% center;
  }
}
@media (max-width: 2000px) {
  .parallax_skins8 .parallax_image {
    background-position: 20% center;
  }
}
@media (max-width: 2000px) {
  .parallax_skins9 .parallax_image {
    background-position: 70% center;
  }
}
@media (max-width: 767px) {
  .parallax_skins11 .parallax_image {
    background-position: 20% center;
  }
}

/* PARALLAX SLIDER */

.parallax-slider {
  height:560px;
  position:relative;
  margin-bottom:30px;
  z-index:5;
}
@media (min-width: 980px) and (max-width: 1219px) {
  .parallax-slider { height:550px; }
}
@media (min-width: 768px) and (max-width: 979px) {
  .parallax-slider { height:550px; }
}
@media (max-width: 767px) {
  .parallax-slider {
    height:350px;
    margin:0 -10px 30px -10px;
  }
}
.parallax-slider #mainCaptionHolder .container { top:45%; }
@media (max-width: 767px) {
  .parallax-slider #mainCaptionHolder .container { top:30%; }
}
.parallax-slider #mainCaptionHolder .slider_caption {
  text-shadow: rgba(0,0,0,0.5) 1px 1px 1px;
  text-transform: uppercase;
  color:white!important;
  text-align:right;
  float:right;
  padding:50px;
  width:600px;
}

.parallax-slider #mainCaptionHolder .slider_caption a{
  color:white!important;  
}

@media (min-width: 1200px) {
  .parallax-slider #mainCaptionHolder .slider_caption { height:212px; }
}

@media (max-width: 767px) {
  .parallax-slider #mainCaptionHolder .slider_caption {
    text-align:center;
    float:none;
    width:100%;
  }
}
.parallax-slider #mainCaptionHolder .slider_caption em {
  position:absolute;
  top:0;
  right:-140%;
  width:200%;
  height:1000%;
  background:<?php echo $GLOBALS["configuracion"]["brand"]["colores"][1] ?>;
  opacity:0.6;
  margin-top:-270px;
  z-index:-1;
  -ms-transform:rotate(45deg);
  -webkit-transform:rotate(25deg);
  transform:rotate(25deg);
}
@media (max-width: 1200px) {
  .parallax-slider #mainCaptionHolder .slider_caption em { display:none; }
}
.parallax-slider .controlBtn {
  width:20px;
  height:30px;
  margin-top:-15px;
}
@media (max-width: 1400px) {
  .parallax-slider .controlBtn {
    margin:0;
    top:40px !important;
  }
}
.parallax-slider .controlBtn .innerBtn {
  font-size:35px;
  line-height:35px;
  color:#fff;
  background:none;
}
.parallax-slider .controlBtn .icon-angle-right:before { content:"\f054"; }
.parallax-slider .controlBtn .icon-angle-left:before { content:"\f053"; }
.parallax-slider .controlBtn .slidesCounter {
  font-size:16px;
  line-height:60px;
  color:#1c1c1c;
  background:#fc4241;
  display:none;
}
.parallax-slider .controlBtn:hover .innerBtn {
  color:#323232;
  background:none;
}
.parallax-slider .parallaxPrevBtn, .parallax-slider .parallaxNextBtn {
  -webkit-transition:all 0.5s ease;
  -moz-transition:all 0.5s ease;
  -o-transition:all 0.5s ease;
  transition:all 0.5s ease;
}
.parallax-slider .parallaxPrevBtn { left:-50px; }
@media (max-width: 1400px) {
  .parallax-slider .parallaxPrevBtn {
    left:auto;
    right:-50px;
  }
}
.parallax-slider .parallaxNextBtn { right:-50px; }
.parallax-slider:hover .parallaxPrevBtn { left:50px; }
@media (max-width: 1400px) {
  .parallax-slider:hover .parallaxPrevBtn {
    left:auto;
    right:40px;
  }
}
.parallax-slider:hover .parallaxNextBtn { right:50px; }
@media (max-width: 1400px) {
  .parallax-slider:hover .parallaxNextBtn { right:10px; }
}
.parallax-slider #paralaxSliderPagination {
  bottom:15px;
  display:none;
}
.parallax-slider #paralaxSliderPagination.buttons_pagination ul li {
  border-radius:50%;
  margin:3px;
  width:10px;
  height:10px;
  border:2px solid #1c1c1c;
}
.parallax-slider #paralaxSliderPagination.buttons_pagination ul li:hover, .parallax-slider #paralaxSliderPagination.buttons_pagination ul li.active { background:#1c1c1c; }
.parallax-slider #paralaxSliderPagination.images_pagination ul li {
  margin:3px;
  opacity:.5;
}
.parallax-slider #paralaxSliderPagination.images_pagination ul li:hover, .parallax-slider #paralaxSliderPagination.images_pagination ul li.active { opacity:1; }
.parallax-slider #previewSpinner {
  width:50px;
  height:50px;
  margin-left:-25px;
  margin-top:-25px;
  border-radius:25px;
  background:url(parallax-slider/img/spinner.GIF) 50% 50% #fff no-repeat;
}
.cherry-quick-view-content span {
  font:16px/23px 'Raleway';
  color:#1c1c1c;
}
.parallax-slider {
  overflow:hidden;
  position:relative;
  -ms-transform:translateZ(0);
  transform:translateZ(0);
}
.parallax-slider .baseList { display:none; }
.parallax-slider #mainImageHolder {
  position:absolute;
  width:100%;
  height:100%;
  top:0;
  left:0;
  z-index:1;
}
.parallax-slider #mainImageHolder .primaryHolder, .parallax-slider #mainImageHolder .secondaryHolder {
  position:absolute;
  width:100%;
  height:100%;
  top:0;
  left:0;
}
.parallax-slider #mainImageHolder .primaryHolder .imgBlock, .parallax-slider #mainImageHolder .secondaryHolder .imgBlock {
  max-width:inherit;
  background-repeat:no-repeat;
  background-position:center;
  background-size:cover;
}
.parallax-slider #mainImageHolder .primaryHolder { z-index:2; }
.parallax-slider #mainImageHolder .secondaryHolder { z-index:1; }
.parallax-slider #mainCaptionHolder {
  position:absolute;
  width:100%;
  height:100%;
  top:0;
  left:0;
  z-index:2;
}
.parallax-slider #mainCaptionHolder .container { position:relative; }
.parallax-slider #mainCaptionHolder .primaryCaption, .parallax-slider #mainCaptionHolder .secondaryCaption {
  position:absolute;
  width:100%;
  top:0;
  left:0;
}
.parallax-slider .controlBtn {
  position:absolute;
  cursor:pointer;
  display:block;
  top:50%;
  z-index:2;
}
.parallax-slider .controlBtn .innerBtn {
  -webkit-transition:all 0.3s ease;
  -moz-transition:all 0.3s ease;
  -o-transition:all 0.3s ease;
  transition:all 0.3s ease;
  position:relative;
  display:block;
  width:100%;
  height:100%;
  text-align:center;
  z-index:1;
}
.parallax-slider .controlBtn .slidesCounter {
  -webkit-transition:all 0.3s ease;
  -moz-transition:all 0.3s ease;
  -o-transition:all 0.3s ease;
  transition:all 0.3s ease;
  position:absolute;
  top:0;
  width:60%;
  height:100%;
}
.parallax-slider .controlBtn.parallaxPrevBtn .slidesCounter {
  left:0%;
  text-align:left;
}
.parallax-slider .controlBtn.parallaxPrevBtn:hover .slidesCounter { left:100%; }
.parallax-slider .controlBtn.parallaxNextBtn .slidesCounter {
  right:0%;
  text-align:right;
}
.parallax-slider .controlBtn.parallaxNextBtn:hover .slidesCounter { right:100%; }
.parallax-slider #paralaxSliderPagination {
  position:absolute;
  width:100%;
  text-align:center;
  z-index:2;
}
.parallax-slider #paralaxSliderPagination ul {
  list-style:none;
  margin:0;
}
.parallax-slider #paralaxSliderPagination ul li {
  -webkit-transition:all 0.5s ease;
  -moz-transition:all 0.5s ease;
  -o-transition:all 0.5s ease;
  transition:all 0.5s ease;
  cursor:pointer;
  display:inline-block;
}
.parallax-slider #previewSpinner {
  position:absolute;
  display:block;
  top:50%;
  left:50%;
  z-index:99;
}
.parallax-slider.zoom-fade-eff #mainImageHolder .primaryHolder {
  opacity:1;
  filter:alpha(opacity=100);
  -webkit-transform:scale(1);
  -moz-transform:scale(1);
  -ms-transform:scale(1);
  -o-transform:scale(1);
  transform:scale(1);
}
.parallax-slider.zoom-fade-eff #mainImageHolder .primaryHolder.animateState {
  opacity:0;
  filter:alpha(opacity=0);
  -webkit-transform:scale(3);
  -moz-transform:scale(3);
  -ms-transform:scale(3);
  -o-transform:scale(3);
  transform:scale(3);
}
.parallax-slider.zoom-fade-eff #mainImageHolder .secondaryHolder {
  opacity:1;
  filter:alpha(opacity=100);
}
.parallax-slider.zoom-fade-eff #mainImageHolder .secondaryHolder.animateState {
  opacity:0;
  filter:alpha(opacity=0);
}
.parallax-slider.zoom-fade-eff #mainCaptionHolder .primaryCaption {
  opacity:1;
  filter:alpha(opacity=100);
}
.parallax-slider.zoom-fade-eff #mainCaptionHolder .primaryCaption.animateState {
  opacity:0;
  filter:alpha(opacity=0);
}
.parallax-slider.zoom-fade-eff #mainCaptionHolder .secondaryCaption {
  opacity:1;
  filter:alpha(opacity=100);
}
.parallax-slider.zoom-fade-eff #mainCaptionHolder .secondaryCaption.animateState {
  opacity:0;
  filter:alpha(opacity=0);
}
.parallax-slider.simple-fade-eff #mainImageHolder .primaryHolder {
  opacity:1;
  filter:alpha(opacity=100);
}
.parallax-slider.simple-fade-eff #mainImageHolder .primaryHolder.animateState {
  opacity:0;
  filter:alpha(opacity=0);
}
.parallax-slider.simple-fade-eff #mainImageHolder .secondaryHolder.animateState {
  opacity:1;
  filter:alpha(opacity=100);
}
.parallax-slider.simple-fade-eff #mainImageHolder .secondaryHolder.animateState.animateState {
  opacity:0;
  filter:alpha(opacity=0);
}
.parallax-slider.simple-fade-eff #mainCaptionHolder .primaryCaption {
  opacity:1;
  filter:alpha(opacity=100);
  -webkit-transform:scale(1);
  -moz-transform:scale(1);
  -ms-transform:scale(1);
  -o-transform:scale(1);
  transform:scale(1);
}
.parallax-slider.simple-fade-eff #mainCaptionHolder .primaryCaption.animateState {
  opacity:0;
  filter:alpha(opacity=0);
}
.parallax-slider.simple-fade-eff #mainCaptionHolder .secondaryCaption {
  opacity:1;
  filter:alpha(opacity=100);
}
.parallax-slider.simple-fade-eff #mainCaptionHolder .secondaryCaption.animateState {
  opacity:0;
  filter:alpha(opacity=0);
}
.parallax-slider.slide-top-eff #mainImageHolder .primaryHolder { top:0; }
.parallax-slider.slide-top-eff #mainImageHolder .primaryHolder.animateState { top:-100%; }
.parallax-slider.slide-top-eff #mainImageHolder .secondaryHolder.animateState { top:0; }
.parallax-slider.slide-top-eff #mainImageHolder .secondaryHolder.animateState.animateState { top:100%; }
.parallax-slider.slide-top-eff #mainCaptionHolder .primaryCaption {
  opacity:1;
  filter:alpha(opacity=100);
  -webkit-transform:scale(1);
  -moz-transform:scale(1);
  -ms-transform:scale(1);
  -o-transform:scale(1);
  transform:scale(1);
}
.parallax-slider.slide-top-eff #mainCaptionHolder .primaryCaption.animateState {
  opacity:0;
  filter:alpha(opacity=0);
}
.parallax-slider.slide-top-eff #mainCaptionHolder .secondaryCaption {
  opacity:1;
  filter:alpha(opacity=100);
}
.parallax-slider.slide-top-eff #mainCaptionHolder .secondaryCaption.animateState {
  opacity:0;
  filter:alpha(opacity=0);
}


/******** PRELOADER ********/

.cargarmas{
  text-align:center;
  margin-bottom:30px;
}

.preloader-titulo{
  height:30px;
  margin-bottom:15px;
}

.preloader-imagen{
  height:300px;
  margin-bottom:15px;
}

.preloader-parrafo{
  height:50px;
  margin-bottom:15px;
}

.preloader-boton{
  height:30px;
  margin-bottom:15px;
  max-width:100px;
}

.breath {
  -webkit-animation: color_change 1s infinite alternate;
  -moz-animation: color_change 1s infinite alternate;
  -ms-animation: color_change 1s infinite alternate;
  -o-animation: color_change 1s infinite alternate;
  animation: color_change 1s infinite alternate;
}

@-webkit-keyframes color_change {
  from { background-color: rgba(221,221,221,0.2); }
  to { background-color: rgba(221,221,221,0.8); }
}

@-moz-keyframes color_change {
  from { background-color: rgba(221,221,221,0.2); }
  to { background-color: rgba(221,221,221,0.8); }
}

@-ms-keyframes color_change {
  from { background-color: rgba(221,221,221,0.2); }
  to { background-color: rgba(221,221,221,0.8); }
}

@-o-keyframes color_change {
  from { background-color: rgba(221,221,221,0.2); }
  to { background-color: rgba(221,221,221,0.8); }
}

@keyframes color_change {
  from { background-color: rgba(221,221,221,0.2); }
  to { background-color: rgba(221,221,221,0.8); }
}


/******** SPINNER ********/

.spinner{
  z-index:99999999;
  position:absolute;
  background-color:rgba(255,255,255,0.8);
  width:100%;
  height:100%;
  text-align:center;
  padding-top:80px;
}

.spinner-producto{
  z-index:99999999;
  background-color:rgba(255,255,255,0.8);
  width:100%;
  height:150px;
  text-align:center;
  padding-top:10px;
}

?> 