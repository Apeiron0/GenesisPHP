<?php
/**
 * GenesisPHP - Sesiones ListadoHTML
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

namespace Sesiones;

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
    // public $tipo;
    // static public $param_nombre;
    // static public $param_tipo;
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
            'nombre' => array(
                'enca'    => 'Usuario',
                'pag'     => 'usuarios.php',
                'id'      => 'usuario'),
            'ingreso' => array(
                'enca'    => 'Ingreso'),
            'tipo' => array(
                'enca'    => 'Tipo',
                'cambiar' => Registro::$tipo_descripciones,
                'color'   => 'tipo',
                'colores' => Registro::$tipo_colores),
            'listado_renglones' => array(
                'enca'    => 'Renglones'));
        // Tomar parámetros que pueden venir en el URL
        $this->nombre             = $_GET[parent::$param_nombre];
        $this->tipo               = $_GET[parent::$param_tipo];
        $this->limit              = $this->listado_controlado->limit;
        $this->offset             = $this->listado_controlado->offset;
        $this->cantidad_registros = $this->listado_controlado->cantidad_registros;
        // Si algún filtro tiene valor, entonces viene_listado será verdadero
        if ($this->listado_controlado->viene_listado) {
            $this->viene_listado = true;
        } else {
            $this->viene_listado = ($this->nombre != '') || ($this->tipo != '');
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
        if ($this->tipo != '') {
            unset($this->listado_controlado->estructura['tipo']);
        }
        // Cargar Listado Controlado
        $this->listado_controlado->encabezado         = $this->encabezado();
        $this->listado_controlado->icono              = $this->sesion->menu->icono_en('sesiones');
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
