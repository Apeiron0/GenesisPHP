<?php
/**
 * GenesisPHP - Usuarios ListadoHTML
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
 * @package GenesisPHP
 */

namespace AdmDepartamentos;

/**
 * Clase ListadoHTML
 */
class ListadoHTML extends Listado {

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;
    // public $nombre;
    // public $estatus;
    // static public $param_nombre;
    // static public $param_estatus;
    // public $filtros_param;
    public $viene_listado;   // Se usa en la pagina, si es verdadero debe mostrar el listado
    protected $estructura;
    protected $listado_html;

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Filtros que puede recibir por el url
        $this->nombre  = $_GET[parent::$param_nombre];
        $this->estatus = $_GET[parent::$param_estatus];
        // Estructura
        $this->estructura = array(
            'nombre'  => array(
                'enca'    => 'Nombre',
                'pag'     => 'departamentos.php',
                'color'   => 'estatus',
                'colores' => Registro::$estatus_colores),
            'clave' => array(
                'enca'    => 'Clave',
                'color'   => 'estatus',
                'colores' => Registro::$estatus_colores),
            'estatus' => array(
                'enca'    => 'Estatus',
                'cambiar' => Registro::$estatus_descripciones,
                'color'   => 'estatus',
                'colores' => Registro::$estatus_colores));
        // Iniciar listado controlado html
        $this->listado_html = new \Base\ListadoControladoHTML();
        // Su constructor toma estos parametros por url
        $this->limit              = $this->listado_html->limit;
        $this->offset             = $this->listado_html->offset;
        $this->cantidad_registros = $this->listado_html->cantidad_registros;
        // Si cualquiera de los filtros tiene valor, entonces viene listado sera verdadero
        if ($this->listado_html->viene_listado) {
            $this->viene_listado = true;
        } else {
            $this->viene_listado = ($this->nombre != '') || ($this->estatus != '');
        }
        // Ejecutar el constructor del padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * Barra
     *
     * @param  string Encabezado opcional
     * @return mixed  Instancia de BarraHTML
     */
    public function barra($in_encabezado='') {
    } // barra

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @return string HTML
     */
    public function html($in_encabezado='') {
        // Consultar
        try {
            $this->consultar();
        } catch (\Exception $e) {
            $mensaje = new \Base\MensajeHTML($e->getMessage());
            return $mensaje->html($in_encabezado);
        }
        // Eliminar columnas de la estructura que sean filtros aplicados
        if ($this->estatus != '') {
            unset($this->estructura['estatus']);
        }
        // Pasamos al listado controlado html
        $this->listado_html->estructura         = $this->estructura;
        $this->listado_html->listado            = $this->listado;
        $this->listado_html->cantidad_registros = $this->cantidad_registros;
        $this->listado_html->variables          = $this->filtros_param;
    //  $this->listado_html->limit              = $this->limit;
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = $this->encabezado();
        }
        // Entregar
        return $this->listado_html->html($encabezado, $this->sesion->menu->icono_en('departamentos'));
    } // html

} // Clase ListadoHTML

?>
