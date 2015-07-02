<?php
/**
 * GenesisPHP - Usuarios BusquedaHTML
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

namespace Usuarios;

/**
 * Clase BusquedaHTML
 */
class BusquedaHTML extends \Base\BusquedaHTML {

    // public $hay_resultados;
    // public $entrego_detalle;
    // public $hay_mensaje;
    // public $resultado;
    // protected $sesion;
    // protected $consultado;
    // protected $javascript;
    //
    static public $form_name = 'modulos_busqueda';

    /**
     * Validar
     */
    public function validar() {
    } // validar

    /**
     * Elaborar formulario
     *
     * @return string HTML del Formulario
     */
    protected function elaborar_formulario() {
    } // elaborar_formulario

    /**
     * Recibir los valores del formulario
     */
    protected function recibir_formulario() {
    } // recibir_formulario

    /**
     * Consultar
     *
     * @return mixed Objeto con el Listado_HTML o Detalle_HTML, falso si no se encontró nada
     */
    public function consultar() {
    } // consultar

} // Clase BusquedaHTML

?>