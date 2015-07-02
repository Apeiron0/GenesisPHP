<?php
/**
 * GenesisPHP - Bitacora ListadoHTML
 *
 * Copyright (C) 2015 Guillermo Valdés Lozano
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

namespace Bitacora;

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
    // public $usuario;
    // public $usuario_nombre;
    // public $tipo;
    // public $tipo_descrito;
    // public $fecha_desde;
    // public $fecha_hasta;
    // static public $param_usuario;
    // static public $param_tipo;
    // static public $param_fecha_desde;
    // static public $param_fecha_hasta;
    // public $filtros_param;
    public $viene_listado = false; // Boleano, para que en PaginaHTML se de cuenta de que viene el listado
    protected $listado_controlado; // Instancia de ListadoControladoHTML

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Iniciar Listado Controlado
        $this->listado_controlado = new \Base\ListadoControladoHTML();
        // Cargar la estructura
        $this->listado_controlado->estructura = array(
            'fecha' => array(
                'enca'    => 'Fecha',
                'pag'     => 'bitacora.php',
                'id'      => 'id'),
            'usuario_nom_corto' => array(
                'enca'    => 'Usuario',
                'pag'     => 'usuarios.php',
                'id'      => 'usuario'),
            'pagina' => array(
                'enca'    => 'Página',
                'pag'     => 'bitacora.php',
                'param'   => array('pagina' => 'pagina', 'id' => 'pagina_id')),
            'tipo' => array(
                'enca'    => 'Tipo',
                'cambiar' => Registro::$tipo_descripciones,
                'color'   => 'tipo',
                'colores' => Registro::$tipo_colores),
            'notas' => array(
                'enca'    => 'Notas'));
        // Tomar parámetros que pueden venir en el URL
        $this->usuario            = $_GET[parent::$param_usuario];
        $this->tipo               = $_GET[parent::$param_tipo];
        $this->fecha_desde        = $_GET[parent::$param_fecha_desde];
        $this->fecha_hasta        = $_GET[parent::$param_fecha_hasta];
        $this->limit              = $this->listado_controlado->limit;
        $this->offset             = $this->listado_controlado->offset;
        $this->cantidad_registros = $this->listado_controlado->cantidad_registros;
        // Si algún filtro tiene valor, entonces viene_listado será verdadero
        if ($this->listado_controlado->viene_listado) {
            $this->viene_listado = true;
        } else {
            $this->viene_listado = ($this->usuario != '') || ($this->tipo != '') || ($this->fecha_desde != '') || ($this->fecha_hasta != '');
        }
        // Ejecutar constructor en el padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // Debe estar consultado, de lo contrario se consulta y si falla se muestra mensaje
        if (!$this->consultado) {
            try {
                $this->consultar();
            } catch (\Exception $e) {
                $mensaje = new \Base\MensajeHTML($e->getMessage());
                return $mensaje->html('Error');
            }
        }
        // Eliminar columnas de la estructura que sean filtros aplicados
        if ($this->usuario != '') {
            unset($this->listado_controlado->estructura['usuario_nom_corto']);
        }
        if ($this->tipo != '') {
            unset($this->listado_controlado->estructura['tipo']);
        }
        // Cargar Listado Controlado
        $this->listado_controlado->                   = $this->encabezado();
        $this->listado_controlado->                   = $this->sesion->menu->icono_en('bitacora');
        $this->listado_controlado->listado            = $this->listado;
        $this->listado_controlado->cantidad_registros = $this->cantidad_registros;
        $this->listado_controlado->variables          = $this->filtros_param;
        // Entregar
        return $this->listado_controlado->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        return $this->listado_controlado->javascript();
    } // javascript

} // Clase ListadoHTML

?>
