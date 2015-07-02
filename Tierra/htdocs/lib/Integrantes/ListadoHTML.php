<?php
/**
 * GenesisPHP - Integrantes ListadoHTML
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

namespace Integrantes;

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
    // public $departamento;
    // public $departamento_nombre;
    // public $estatus;
    // public $estatus_descrito;
    // static public $param_usuario;
    // static public $param_departamento;
    // static public $param_estatus;
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
            'usuario_nombre' => array(
                'enca'    => 'Usuario',
                'pag'     => 'integrantes.php',
                'param'   => array(parent::$param_usuario => 'usuario')),
            'departamento_nombre' => array(
                'enca'    => 'Departamento',
                'pag'     => 'integrantes.php',
                'param'   => array(parent::$param_departamento => 'departamento')),
            'poder' => array(
                'enca'    => 'Poder',
                'pag'     => 'integrantes.php',
                'id'      => 'id',
                'cambiar' => Registro::$poder_descripciones,
                'color'   => 'poder',
                'colores' => Registro::$poder_colores),
            'estatus' => array(
                'enca'    => 'Estatus',
                'cambiar' => Registro::$estatus_descripciones,
                'color'   => 'estatus',
                'colores' => Registro::$estatus_colores));
        // Tomar parámetros que pueden venir en el URL
        $this->usuario            = $_GET[parent::$param_usuario];
        $this->departamento       = $_GET[parent::$param_departamento];
        $this->estatus            = $_GET[parent::$param_estatus];
        $this->limit              = $this->listado_controlado->limit;
        $this->offset             = $this->listado_controlado->offset;
        $this->cantidad_registros = $this->listado_controlado->cantidad_registros;
        // Si algún filtro tiene valor, entonces viene_listado será verdadero
        if ($this->listado_html->viene_listado) {
            $this->viene_listado = true;
        } else {
            $this->viene_listado = ($this->usuario != '') || ($this->departamento != '') || ($this->estatus != '');
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
        if ($this->departamento != '') {
            unset($this->listado_controlado->estructura['departamento_nombre']);
        }
        if ($this->estatus != '') {
            unset($this->listado_controlado->estructura['estatus']);
        }
        // Cargar Listado Controlado
        $this->listado_controlado->encabezado         = $this->encabezado();
        $this->listado_controlado->icono              = $this->sesion->menu->icono_en('integrantes');
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