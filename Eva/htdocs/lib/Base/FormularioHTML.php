<?php
/**
 * GenesisPHP - FormularioHTML
 *
 * Copyright (C) 2016 Guillermo Valdés Lozano
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package
 */

namespace Base;

/**
 * Clase FormularioHTML
 */
class FormularioHTML {

    public $encabezado;                              // Opcional, texto para el encabezado
    public $icono;                                   // Opcional, URL al icono
    public $barra;                                   // Opcional, instancia de BarraHTML
    public $name;                                    // Texto, nombre del formulario
    public $action;                                  // Texto, acción a tomar, es decir, URL de destino
    public $method                       = 'post';   // Texto, método post o get
    protected $secciones                 = array();  // Arreglo de arreglos, guarda las secciones y los elementos de cada una
    protected $seccion_actual;                       // Texto, identificador de la sección en uso
    protected $etiquetas                 = array();  // Arreglo asociativo, guarda las etiquetas
    protected $contenidos                = array();  // Arreglo asociativo, guarda el contenido HTML
    protected $contenidos_clases         = array();  // Arreglo asociativo, guarda las clases de los tamaños
    protected $pie                       = array();
    protected $javascript                = array();
    protected $onkeypress;
    protected $subir_archivo             = false;
    protected $html_elaborado;                       // El método HTML sólo se elabora una vez, después entrega el mismo HTML
    static public $adjunto_tamano_maximo = 24000000; // Tamaño máximo en bytes
    static public $botones_clases        = array(
        'default' => 'btn',              // Gris
        'primary' => 'btn btn-primary',  // Azul fuerte
        'info'    => 'btn btn-info',     // Azul claro
        'success' => 'btn btn-success',  // Verde
        'warning' => 'btn btn-warning',  // Amarillo
        'danger'  => 'btn btn-danger',   // Rojo
        'inverse' => 'btn btn-inverse'); // Negro

    /**
     * Constructor
     *
     * @param string Nombre del formulario
     */
    public function __construct($in_name) {
        // Definir los valores iniciales de las propiedades
        $this->name                             = $in_name;
        $this->action                           = $_SERVER['PHP_SELF'];
        $this->seccion_actual                   = 'sin_seccion'; // Sin sección no muestra encabezado
        $this->etiquetas[$this->seccion_actual] = 'Sin sección';
    } // constructor

    /**
     * Post texto
     *
     * Método estático para recibir el dato de un campo de texto y tratar con comillas y espacios
     *
     * @param  string
     * @return string
     */
    public static function post_texto($dato) {
        $comilla_simple = str_replace("\'", "'", strval($dato));
        $comilla_doble  = str_replace('\"', '"', strval($comilla_simple));
        return preg_replace('/\h+/', ' ', trim($comilla_doble)); // Reeplazar dos o más espacios horizontales por uno solo
    } // post_texto

    /**
     * Post select
     *
     * Método estático para recibir el dato de un select
     *
     * @param  string
     * @return string
     */
    public static function post_select($dato) {
        if ($dato === '-') {
            return '';
        } else {
            return $dato;
        }
    } // post_select

    /**
     * Seccion
     *
     * Las secciones sirven para organizar en conjuntos los campos del formulario
     *
     * @param string Identificador
     * @param string Etiqueta
     */
    public function seccion($in_identificador, $in_etiqueta) {
        $this->etiquetas[$in_identificador]     = $in_etiqueta;
        $this->seccion_actual                   = $in_identificador;
        $this->secciones[$this->seccion_actual] = array();
    } // seccion

    /**
     * Oculto
     *
     * Campo oculto que NO es visible, pero lleva consigo un valor
     *
     * @param string Identificador
     * @param string Opcional valor
     */
    public function oculto($in_identificador, $in_valor='') {
        $this->contenidos[$in_identificador]      = sprintf('<input name="%s" type="hidden" value="%s">', $in_identificador, $in_valor);
        $this->secciones[$this->seccion_actual][] = $in_identificador;
    } // oculto

    /**
     * Fijo
     *
     * Texto fijo para mostrar solamente
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Valor, texto a mostrar
     */
    public function fijo($in_identificador, $in_etiqueta, $in_mostrar) {
        if (is_string($in_mostrar)) {
            if ($in_mostrar != '') {
                $mostrar = $in_mostrar;
            } else {
                $mostrar = 'NULO';
            }
        } elseif (is_array($in_mostrar) && (count($in_mostrar) > 0)) {
            $mostrar = implode(', ', $in_mostrar);
        } else {
            $mostrar = 'VALOR INCORRECTO';
        }
        $this->etiquetas[$in_identificador]       = $in_etiqueta;
        $this->contenidos[$in_identificador]      = "<p class=\"form-control-static\">$mostrar</p>";
        $this->secciones[$this->seccion_actual][] = $in_identificador;
    } // fijo

    /**
     * Texto
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, valor
     * @param string Opcional, longitud de caracteres
     * @param string Opcional, comentario
     * @param string Opcional, JavaScript
     */
    public function texto($in_identificador, $in_etiqueta, $in_valor='', $in_tamano='', $in_texto_posterior='', $in_js='') {
        $tag = "<input type=\"text\" class=\"form-control\" name=\"$in_identificador\"";
        if ($in_valor !== '') {
            $tag .= ' value="'.htmlentities($in_valor).'"';
        }
        if ($in_tamano !== '') {
            $tag .= " size=\"$in_tamano\"";
        }
        if ($in_js !== '') {
            $tag .= " $in_js";
        }
        $tag .= '>';
        if ($in_texto_posterior !== '') {
            $tag .= " <span class=\"help-inline\">{$in_texto_posterior}</span>";
        }
        $this->contenidos[$in_identificador]      = $tag;
        $this->etiquetas[$in_identificador]       = $in_etiqueta;
        $this->secciones[$this->seccion_actual][] = $in_identificador;
    } // texto

    /**
     * Texto nombre
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, valor
     * @param string Opcional, longitud de caracteres
     * @param string Opcional, comentario
     * @param string Opcional, JavaScript
     */
    public function texto_nombre($in_identificador, $in_etiqueta, $in_valor='', $in_tamano='', $in_texto_posterior='', $in_js='') {
        $this->texto($in_identificador, $in_etiqueta, $in_valor, $in_tamano, $in_texto_posterior, $in_js);
    } // texto_nombre

    /**
     * Texto nombre corto
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, valor
     * @param string Opcional, longitud de caracteres
     * @param string Opcional, comentario
     * @param string Opcional, JavaScript
     */
    public function texto_nom_corto($in_identificador, $in_etiqueta, $in_valor='', $in_tamano=32, $in_texto_posterior='', $in_js='') {
        $this->texto($in_identificador, $in_etiqueta, $in_valor, $in_tamano, $in_texto_posterior, $in_js);
        $this->contenidos_clases[$in_identificador] = 'col-md-4';
    } // texto_nom_corto

    /**
     * Texto número entero
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, valor
     * @param string Opcional, longitud de caracteres
     * @param string Opcional, comentario
     * @param string Opcional, JavaScript
     */
    public function texto_entero($in_identificador, $in_etiqueta, $in_valor='', $in_tamano='', $in_texto_posterior='', $in_js='') {
        $tag = "<input type=\"text\" class=\"form-control\" name=\"$in_identificador\"";
        if ($in_valor !== '') {
            $tag .= ' value="'.htmlentities($in_valor).'"';
        }
        if ($in_tamano !== '') {
            $tag .= " size=\"$in_tamano\"";
        }
        if ($in_js !== '') {
            $tag .= " $in_js";
        }
        $tag .= '>';
        if ($in_texto_posterior !== '') {
            $tag .= " <span class=\"help-inline\">{$in_texto_posterior}</span>";
        }
        $this->contenidos[$in_identificador]        = $tag;
        $this->contenidos_clases[$in_identificador] = 'col-md-4';
        $this->etiquetas[$in_identificador]         = $in_etiqueta;
        $this->secciones[$this->seccion_actual][]   = $in_identificador;
    } // texto_entero

    /**
     * Texto número flotante
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, valor
     * @param string Opcional, longitud de caracteres
     * @param string Opcional, comentario
     * @param string Opcional, JavaScript
     */
    public function texto_flotante($in_identificador, $in_etiqueta, $in_valor='', $in_tamano='', $in_texto_posterior='', $in_js='') {
        $this->texto_entero($in_identificador, $in_etiqueta, $in_valor, $in_tamano, $in_texto_posterior, $in_js);
    } // texto_flotante

    /**
     * Texto número porcentaje
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, valor
     * @param string Opcional, longitud de caracteres
     * @param string Opcional, comentario
     * @param string Opcional, JavaScript
     */
    public function texto_porcentaje($in_identificador, $in_etiqueta, $in_valor='', $in_tamano='', $in_texto_posterior='', $in_js='') {
        $this->texto_entero($in_identificador, $in_etiqueta, $in_valor, $in_tamano, $in_texto_posterior, $in_js);
    } // texto_porcentaje

    /**
     * Fecha
     *
     * Usa https://github.com/eternicode/bootstrap-datepicker
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, valor
     */
    public function fecha($in_identificador, $in_etiqueta, $in_valor='') {
        $identificador_unico                 = "{$this->name}_{$in_identificador}";
        $this->etiquetas[$in_identificador]  = $in_etiqueta;
        $this->contenidos[$in_identificador] = <<<FINAL
<div class="input-group date" id="$identificador_unico">
          <input type="text" class="form-control" name="$in_identificador" value="$in_valor"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
        </div>
FINAL;
        $this->contenidos_clases[$in_identificador] = 'col-md-4';
        $this->secciones[$this->seccion_actual][]   = $in_identificador;
        $this->javascript[] = <<<FINAL
    $('#$identificador_unico').datepicker({
      orientation:    "top left",
      format:         "yyyy-mm-dd",
      language:       "es",
      autoclose:      true,
      todayHighlight: true,
      clearBtn:       true
    });
FINAL;
    } // fecha

    /**
     * Fecha Hora
     *
     * Usa https://github.com/smalot/bootstrap-datetimepicker
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, valor
     */
    public function fecha_hora($in_identificador, $in_etiqueta, $in_valor='') {
        $identificador_unico                 = "{$this->name}_{$in_identificador}";
        $this->etiquetas[$in_identificador]  = $in_etiqueta;
        $this->contenidos[$in_identificador] = <<<FINAL
<div class="input-group input-append date" id="$identificador_unico">
          <input type="text" class="form-control" name="$in_identificador" value="$in_valor" readonly><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
        </div>
FINAL;
        $this->contenidos_clases[$in_identificador] = 'col-md-4';
        $this->secciones[$this->seccion_actual][]   = $in_identificador;
        $this->javascript[] = <<<FINAL
    $("#$identificador_unico").datetimepicker({
      format:         "yyyy-mm-dd hh:ii",
      minuteStep:     1,
      language:       "es",
      autoclose:      true,
      todayBtn:       true,
      pickerPosition: "bottom-left"
    });
FINAL;
    } // fecha_hora

    /**
     * Rango de fechas
     *
     * Usa https://github.com/eternicode/bootstrap-datepicker
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Valor desde
     * @param string Valor hasta
     */
    public function rango_fechas($in_identificador, $in_etiqueta, $in_desde_val, $in_hasta_val) {
        $identificador_unico = "{$this->name}_{$in_identificador}";
        $desde_name          = "{$in_identificador}_desde";
        $hasta_name          = "{$in_identificador}_hasta";
        $this->etiquetas[$in_identificador]  = $in_etiqueta;
        $this->contenidos[$in_identificador] = <<<FINAL
<div class="input-daterange input-group" id="$identificador_unico">
          <input type="date" class="input-sm form-control" name="$desde_name" value="$in_desde_val">
          <span class="input-group-addon">a</span>
          <input type="date" class="input-sm form-control" name="$hasta_name" value="$in_hasta_val">
        </div>
FINAL;
        $this->contenidos_clases[$in_identificador] = 'col-md-6';
        $this->secciones[$this->seccion_actual][]   = $in_identificador;
        $this->javascript[] = <<<FINAL
    $('#$identificador_unico').datepicker({
      orientation:    "top left",
      format:         "yyyy-mm-dd",
      language:       "es",
      autoclose:      true,
      todayHighlight: true,
      clearBtn:       true
    });
FINAL;
    } // rango_fechas

    /**
     * Rango de fechas con horas
     *
     * Usa https://github.com/smalot/bootstrap-datetimepicker
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Valor desde
     * @param string Valor hasta
     */
    public function rango_fechas_horas($in_identificador, $in_etiqueta, $in_desde_val, $in_hasta_val) {
        $identificador_unico_desde           = "{$this->name}_{$in_identificador}_desde";
        $identificador_unico_hasta           = "{$this->name}_{$in_identificador}_hasta";
        $desde_name                          = "{$in_identificador}_desde";
        $hasta_name                          = "{$in_identificador}_hasta";
        $this->etiquetas[$in_identificador]  = $in_etiqueta;
        $this->contenidos[$in_identificador] = <<<FINAL
<div class="input-group input-append date" id="$identificador_unico_desde">
          <input type="text" class="form-control" name="$desde_name" value="$in_desde_val" readonly><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
        </div>
        <div class="input-group input-append date" id="$identificador_unico_hasta">
          <input type="text" class="form-control" name="$hasta_name" value="$in_hasta_val" readonly><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
        </div>
FINAL;
        $this->contenidos_clases[$in_identificador] = 'col-md-4';
        $this->secciones[$this->seccion_actual][]   = $in_identificador;
        $this->javascript[] = <<<FINAL
    $("#$identificador_unico_desde").datetimepicker({
      format:         "yyyy-mm-dd hh:ii",
      minuteStep:     1,
      language:       "es",
      autoclose:      true,
      todayBtn:       true,
      pickerPosition: "bottom-left"
    });
    $("#$identificador_unico_hasta").datetimepicker({
      format:         "yyyy-mm-dd hh:ii",
      minuteStep:     1,
      language:       "es",
      autoclose:      true,
      todayBtn:       true,
      pickerPosition: "bottom-left"
    });
FINAL;
    } // rango_fechas_horas

    /**
     * GeoPunto
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, longitud
     * @param string Opcional, latitud
     */
    public function geopunto($in_identificador, $in_etiqueta, $in_longitud='', $in_latitud='') {
        $longitud_nom                        = "{$in_identificador}_longitud";
        $latitud_nom                         = "{$in_identificador}_latitud";
        $this->etiquetas[$in_identificador]  = $in_etiqueta;
        $this->contenidos[$in_identificador] = <<<FINAL
<div class="input-group">
          <span class="input-group-addon">Longitud</span>
          <input type="text" class="form-control" name="$longitud_nom" value="$in_longitud">
        </div>
        <div class="input-group">
          <span class="input-group-addon">Latitud</span>
          <input type="text" class="form-control" name="$latitud_nom" value="$in_latitud">
        </div>
FINAL;
        $this->contenidos_clases[$in_identificador] = 'col-md-6';
        $this->secciones[$this->seccion_actual][]   = $in_identificador;
    } // geopunto

    /**
     * Rango de enteros
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Valor desde
     * @param string Valor hasta
     */
    public function rango_enteros($in_identificador, $in_etiqueta, $in_desde_val, $in_hasta_val) {
        $desde_nom                           = "{$in_identificador}_desde";
        $hasta_nom                           = "{$in_identificador}_hasta";
        $desde_html                          = sprintf('<input type="number" class="form-control" name="%s" value="%s">', $desde_nom, $in_desde_val);
        $hasta_html                          = sprintf('<input type="number" class="form-control" name="%s" value="%s">', $hasta_nom, $in_hasta_val);
        $this->etiquetas[$in_identificador]  = $in_etiqueta;
        $this->contenidos[$in_identificador] = <<<RANGO
<div class="input-group">
          <input type="number" class="input-sm form-control" name="$desde_nom" value="$in_desde_val">
          <span class="input-group-addon">a</span>
          <input type="number" class="input-sm form-control" name="$hasta_nom" value="$in_hasta_val">
        </div>
RANGO;
        $this->contenidos_clases[$in_identificador] = 'col-md-4';
        $this->secciones[$this->seccion_actual][]   = $in_identificador;
    } // rango_enteros

    /**
     * Rango de flotantes
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Valor desde
     * @param string Valor hasta
     */
    public function rango_flotantes($in_identificador, $in_etiqueta, $in_desde_val, $in_hasta_val) {
        $desde_nom                                = "{$in_identificador}_desde";
        $hasta_nom                                = "{$in_identificador}_hasta";
        $desde_html                               = sprintf('<input type="number" class="form-control" name="%s" value="%s">', $desde_nom, $desde_val);
        $hasta_html                               = sprintf('<input type="number" class="form-control" name="%s" value="%s">', $hasta_nom, $hasta_val);
        $this->etiquetas[$in_identificador]       = $in_etiqueta;
        $this->contenidos[$in_identificador]      = sprintf('desde %s hasta %s', $desde_html, $hasta_html);
        $this->secciones[$this->seccion_actual][] = $in_identificador;
    } // rango_flotantes

    /**
     * Rango de porcentajes
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Valor desde
     * @param string Valor hasta
     */
    public function rango_porcentajes($in_identificador, $in_etiqueta, $in_desde_val, $in_hasta_val) {
        $desde_nom                                  = "{$in_identificador}_desde";
        $hasta_nom                                  = "{$in_identificador}_hasta";
        $desde_html                                 = sprintf('<input type="number" class="form-control" name="%s" value="%s">', $desde_nom, $desde_val);
        $hasta_html                                 = sprintf('<input type="number" class="form-control" name="%s" value="%s">', $hasta_nom, $hasta_val);
        $this->etiquetas[$in_identificador]         = $in_etiqueta;
        $this->contenidos[$in_identificador]        = sprintf('desde %s hasta %s', $desde_html, $hasta_html);
        $this->contenidos_clases[$in_identificador] = 'col-md-4';
        $this->secciones[$this->seccion_actual][]   = $in_identificador;
    } // rango_porcentajes

    /**
     * Password
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, valor
     * @param string Opcional, longitud de caracteres
     * @param string Opcional, comentario
     */
    public function password($in_identificador, $in_etiqueta, $in_valor='', $in_tamano='', $in_texto_posterior='') {
        $tag = "<input type=\"password\" class=\"form-control\" name=\"$in_identificador\"";
        if ($in_tamano !== '') {
            $tag .= " size=\"$in_tamano\" maxlength=\"$in_tamano\"";
        }
        if ($in_valor !== '') {
            $tag .= " value=\"$in_valor\"";
        }
        $tag .= '>';
        if ($in_texto_posterior !== '') {
            $tag .= " $in_texto_posterior";
        }
        $this->contenidos[$in_identificador]        = $tag;
        $this->contenidos_clases[$in_identificador] = 'col-md-4';
        $this->etiquetas[$in_identificador]         = $in_etiqueta;
        $this->secciones[$this->seccion_actual][]   = $in_identificador;
    } // password

    /**
     * Textarea
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, valor
     * @param string Opcional, ancho en caracteres, por defecto 50
     * @param string Opcional, altura en caracteres, por defecto 7
     */
    public function area_texto($in_identificador, $in_etiqueta, $in_valor='', $in_ancho=50, $in_alto=7) {
        $tag = "<textarea type=\"text\" class=\"form-control\" name=\"$in_identificador\"";
        if ($in_ancho !== '') {
            $tag .= " cols=\"$in_ancho\"";
        }
        if ($in_alto !== '') {
            $tag .= " rows=\"$in_alto\"";
        }
        $tag .= '>';
        if ($in_valor !== '') {
            $tag .= $in_valor;
        }
        $tag .= '</textarea>';
        $this->contenidos[$in_identificador]      = $tag;
        $this->etiquetas[$in_identificador]       = $in_etiqueta;
        $this->secciones[$this->seccion_actual][] = $in_identificador;
    } // area_texto

    /**
     * Select
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param array  Opciones a mostrar, arreglo asociativo con clave y valor
     * @param string Opcional, valor seleccionado
     * @param string Opcional, cantidad de reglones, por defecto 1
     * @param string Opcional, comentario
     */
    public function select($in_identificador, $in_etiqueta, $in_opciones, $in_seleccionado='', $in_tamano=1, $in_texto_posterior='') {
        $a = array();
        if ($in_tamano == 1) {
            $a[] = sprintf('<select class="form-control" name="%s">', $in_identificador);
        } else {
            $a[] = sprintf('<select class="form-control" name="%s" size="%s">', $in_identificador, $in_tamano);
        }
        foreach ($in_opciones as $clave => $valor) {
            if ($clave == $in_seleccionado) {
                $a[] = sprintf('          <option value="%s" selected>%s</option>', $clave, $valor);
            } else {
                $a[] = sprintf('          <option value="%s">%s</option>', $clave, $valor);
            }
        }
        $a[] = '        </select>';
        if ($in_texto_posterior != '') {
            $a[] = sprintf('<span class="help-inline">%s</span>', $in_texto_posterior);
        }
        $this->etiquetas[$in_identificador]       = $in_etiqueta;
        $this->contenidos[$in_identificador]      = implode("\n", $a);
        $this->secciones[$this->seccion_actual][] = $in_identificador;
    } // select

    /**
     * Select con nulo
     *
     * Tiene la opción de que se elija NULO
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param array  Opciones a mostrar, arreglo asociativo con clave y valor
     * @param string Opcional, valor seleccionado
     * @param string Opcional, cantidad de reglones, por defecto 1
     * @param string Opcional, comentario
     */
    public function select_con_nulo($in_identificador, $in_etiqueta, $in_opciones, $in_seleccionado='', $in_size=1, $in_texto_posterior='') {
        $a = array('-' => '');
        foreach ($in_opciones as $clave => $valor) {
            $a[$clave] = $valor;
        }
        $this->select($in_identificador, $in_etiqueta, $a, $in_seleccionado, $in_size, $in_texto_posterior);
    } // select_con_nulo

    /**
     * Radio buttons
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param array  Opciones a mostrar, arreglo asociativo con clave y valor
     * @param string Opcional, valor seleccionado
     */
    public function radio_buttons($in_identificador, $in_etiqueta, $in_opciones, $in_seleccionado='') {
        $a = array();
        foreach ($in_opciones as $clave => $valor) {
            if ($clave == $in_seleccionado) {
                $a[] = sprintf('<p><input type="radio" name="%s" value="%s" checked /> %s</p>', $in_identificador, $clave, $valor);
            } else {
                $a[] = sprintf('<p><input type="radio" name="%s" value="%s" /> %s</p>', $in_identificador, $clave, $valor);
            }
        }
        $this->etiquetas[$in_identificador]       = $in_etiqueta;
        $this->contenidos[$in_identificador]      = implode("\n", $a);
        $this->secciones[$this->seccion_actual][] = $in_identificador;
    } // radio_buttons

    /**
     * Checkboxes
     *
     * @param string Identificador, a cada nombre de checkbox se le agregara un guion bajo y el id
     * @param string Etiqueta
     * @param array  Opciones a mostrar, arreglo asociativo con clave y valor
     * @param array  Opcional, arreglo con los valores seleccionados
     */
    public function checkboxes($in_identificador, $in_etiqueta, $in_opciones, $in_seleccionados=false) {
        $a = array();
        foreach ($in_opciones as $clave => $valor) {
            $nombre = "{$in_identificador}_{$k}";
            if (is_array($in_seleccionados) && in_array($clave, $in_seleccionados)) {
                $a[] = sprintf('<input type="checkbox" name="%s" value="1" checked> %s', $nombre, $valor);
            } else {
                $a[] = sprintf('<input type="checkbox" name="%s" value="1"> %s', $nombre, $valor);
            }
        }
        $this->etiquetas[$in_identificador]       = $in_etiqueta;
        $this->contenidos[$in_identificador]      = implode("<br>\n", $a);
        $this->secciones[$this->seccion_actual][] = $in_identificador;
    } // checkboxes

    /**
     * Adjuntar archivo
     *
     * @param string Identificador
     * @param string Etiqueta
     */
    public function adjuntar_archivo($in_identificador, $in_etiqueta) {
        $this->etiquetas[$in_identificador]         = $in_etiqueta;
        $this->contenidos[$in_identificador]        = sprintf('<input type="file" class="form-control" name="%s">', $in_identificador);
        $this->contenidos_clases[$in_identificador] = 'col-md-4';
        $this->secciones[$this->seccion_actual][]   = $in_identificador;
        $this->subir_archivo                        = true;
    } // adjuntar_archivo

    /**
     * Boton submit
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, clase CSS con el color del botón
     */
    public function boton_submit($in_identificador, $in_etiqueta, $in_clase='') {
        if (($in_clase == '') || !array_key_exists($in_clase, self::$botones_clases)) {
            $clase = 'default';
        } else  {
            $clase = self::$botones_clases[$in_clase];
        }
        $this->pie[$in_identificador] = sprintf('<button class="%s" type="submit">%s</button>', $clase, $in_etiqueta);
    } // boton_submit

    /**
     * Boton buscar
     */
    public function boton_buscar() {
        $this->boton_submit('submit', 'Buscar', 'info');
    } // boton_buscar

    /**
     * Boton guardar
     */
    public function boton_guardar() {
        $this->boton_submit('submit', 'Guardar', 'success');
    } // boton_guardar

    /**
     * Boton submit icono
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Icono
     * @param string Opcional, clase CSS con el color del botón
     */
    public function boton_submit_icono($in_identificador, $in_etiqueta, $in_icono, $in_clase='') {
        $this->boton_submit($in_identificador, $in_etiqueta, $in_clase); // PENDIENTE HACER QUE SE MUESTRE EL ICONO
    } // boton_submit_icono

    /**
     * Boton buscar icono
     */
    public function boton_buscar_icono() {
        $this->boton_submit_icono('submit', 'Guardar', '', 'info');
    } // boton_buscar_icono

    /**
     * Boton guardar icono
     */
    public function boton_guardar_icono() {
        $this->boton_submit_icono('submit', 'Guardar', '', 'success');
    } // boton_guardar_icono

    /**
     * Boton URL
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string Opcional, URL de destino, por defecto se hace atrás en la historia del navegador
     * @param string Opcional, clase CSS con el color del botón
     */
    public function boton_url($in_identificador, $in_etiqueta, $in_url='', $in_clase='') {
        if (($in_clase == '') || !array_key_exists($in_clase, self::$botones_clases)) {
            $clase = 'default';
        } else  {
            $clase = self::$botones_clases[$in_clase];
        }
        if ($in_url != '') {
            $this->pie[$in_identificador] = sprintf('<button class="%s" type="button" onclick="location.href=\'%s\'">%s</button>', $clase, $in_url, $in_etiqueta);
        } else {
            $this->pie[$in_identificador] = sprintf('<button class="%s" type="button" onclick="history.back()">%s</button>', $clase, $in_etiqueta);
        }
    } // boton_url

    /**
     * Boton cancelar
     *
     * @param string Opcional, URL de destino, por defecto se hace atrás en la historia del navegador
     */
    public function boton_cancelar($in_url='') {
        $this->boton_url('cancelar', 'Cancelar', $in_url, 'inverse');
    } // boton_cancelar

    /**
     * Boton URL icono
     *
     * @param string Identificador
     * @param string Etiqueta
     * @param string URL
     * @param string Icono
     */
    public function boton_url_icono($in_identificador, $in_etiqueta, $in_url, $in_icono) {
        $this->boton_url($in_identificador, $in_etiqueta, $in_url);
    } // boton_submit

    /**
     * Boton cancelar icono
     *
     * @param string URL de destino, por defecto '' lo que hace atrás en la historia del navegador
     */
    public function boton_cancelar_icono($in_url='') {
        $this->boton_url_icono('cancelar', 'Cancelar', $in_url, 'inverse');
    } // boton_cancelar_icono

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // Si está definida la barra, se ponen en blanco las propiedades encabezado e icono
        if (is_object($this->barra) && ($this->barra instanceof BarraHTML)) {
            $this->encabezado = '';
            $this->icono      = '';
        }
        // Si ya se elaboro el HTML, sólo se entrega y se termina
        if ($this->html_elaborado != '') {
            return $this->html_elaborado;
        }
        // Acumularemos el HTML en este arreglo
        $a   = array();
        $a[] = '<div class="formulario">';
        // Elaborar Barra
        if (is_object($this->barra) && ($this->barra instanceof BarraHTML)) {
            $a[]                = $this->barra->html();
            $this->javascript[] = $this->barra->javascript();
        } elseif ($this->encabezado != '') {
            $barra              = new BarraHTML();
            $barra->encabezado  = $this->encabezado;
            $barra->icono       = $this->icono;
            $a[]                = $barra->html();
            $this->javascript[] = $barra->javascript();
        }
        // Form
        if ($this->subir_archivo) {
            $a[] = sprintf('  <form id="%s" name="%s" action="%s" method="%s" enctype="multipart/form-data" class="form-horizontal" role="form">', $this->name, $this->name, $this->action, $this->method);
        } else {
            if ($this->onkeypress != '') {
                $a[] = sprintf('  <form id="%s" name="%s" action="%s" method="%s" onKeyPress="%s" onsubmit="return false" class="form-horizontal" role="form">', $this->name, $this->name, $this->action, $this->method, $this->onkeypress);
            } else {
                $a[] = sprintf('  <form id="%s" name="%s" action="%s" method="%s" class="form-horizontal" role="form">', $this->name, $this->name, $this->action, $this->method);
            }
        }
        // Campo oculto el que lleva el nombre del formulario
        $a[] = sprintf('    <input name="formulario" type="hidden" value="%s">', $this->name);
        // Campo oculto si va a subir archivos el tamaño maximo
        if ($this->subir_archivo) {
            $a[] = sprintf('    <input type="hidden" name="MAX_FILE_SIZE" value="%d">', self::$adjunto_tamano_maximo);
        }
        // Campos ocultos
        $secciones_con_visibles = array();
        foreach ($this->secciones as $seccion => $identificadores) {
            foreach ($identificadores as $identificador) {
                if (strpos($this->contenidos[$identificador], 'type="hidden"') !== false) {
                    // El campo es oculto, se incluye como tal
                    $a[] = "    {$this->contenidos[$identificador]}";
                } else {
                    // El campo es visible, se agrega la sección al arreglo de los que si tienen que mostrar
                    if (!in_array($seccion, $secciones_con_visibles)) {
                        $secciones_con_visibles[] = $seccion;
                    }
                }
            }
        }
        // Campos visibles
        foreach ($this->secciones as $seccion => $identificadores) {
            if (in_array($seccion, $secciones_con_visibles)) {
                foreach ($identificadores as $identificador) {
                    if (strpos($this->contenidos[$identificador], 'type="hidden"') === false) {
                        $a[] = '      <div class="form-group">';
                        $a[] = "        <label for=\"$identificador\" class=\"col-md-2 control-label\">{$this->etiquetas[$identificador]}</label>";
                        if ($this->contenidos_clases[$identificador] != '') {
                            $a[] = "        <div class=\"{$this->contenidos_clases[$identificador]}\">";
                        } else {
                            $a[] = '        <div class="col-md-10">';
                        }
                        $a[] = "          {$this->contenidos[$identificador]}";
                        $a[] = '        </div>';
                        $a[] = '      </div>';
                    }
                }
            }
        }
        // Pie
        if (count($this->pie) > 0) {
            $a[] = '    <div class="form-group">';
            $a[] = '      <div class="col-sm-offset-2 col-md-10">';
            foreach ($this->pie as $identificador => $tag) {
                $a[] = sprintf('          %s', $tag);
            }
            $a[] = '      </div>';
            $a[] = '    </div>';
        }
        // Form cerrar
        $a[] = '  </form>';
        $a[] = '</div>';
        // Guardar el html elaborado
        $this->html_elaborado = implode("\n", $a);
        // Entregar
        return $this->html_elaborado;
    } // html

    /**
     * Javascript
     *
     * Entregar el javascript. Si no lo hay, entrega falso.
     *
     * @return string Javascript
     */
    public function javascript() {
        if (is_array($this->javascript) && (count($this->javascript) > 0)) {
            $a = array();
            foreach ($this->javascript as $js) {
                if (is_string($js) && ($js != '')) {
                    $a[] = $js;
                }
            }
            if (count($a) > 0) {
                return implode("\n", $a);
            } else {
                return false;
            }
        } else {
            return false;
        }
    } // javascript

} // Clase FormularioHTML

?>
