<?php
/**
 * GenesisPHP - Pruebas Página Inicial
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

require_once('autocargadorclases.php');

/**
 * Clase PaginaInicial
 */
class PaginaInicial extends \Base\PlantillaHTML {

    // protected $sistema;
    // protected $titulo;
    // protected $descripcion;
    // protected $autor;
    // protected $css;
    // protected $favicon;
    // protected $modelo;
    // protected $menu_principal_logo;
    // protected $modelo_ingreso_logos;
    // protected $modelo_fluido_logos;
    // protected $pie;
    // public $clave;
    // public $menu;
    // public $contenido;
    // public $javascript;

    /**
     * Constructor
     */
    public function __construct() {
    } // constructor

    /**
     * HTML
     *
     * @return string HTML
     */
    public function html() {
        // Ejecutar el padre y entregar su resultado
        return parent::html();
    } // html

} // Clase PaginaInicial

// Ejecutar y mostrar
$pagina = new PaginaInicial();
echo $pagina->html();

?>
