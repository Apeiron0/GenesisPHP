<?php
/**
 * GenesisPHP - PaginaWeb Propiedades
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

namespace PaginaWeb;

/**
 * Clase Propiedades
 */
class Propiedades extends \Base\Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        return <<<FINAL
    // protected \$sistema;
    // protected \$titulo;
    // protected \$descripcion;
    // protected \$autor;
    // protected \$favicon;
    // protected \$modelo;
    // protected \$menu_principal_logo;
    // protected \$modelo_ingreso_logos;
    // protected \$modelo_fluido_logos;
    // protected \$pie;
    // public \$clave;
    // public \$menu;
    // public \$contenido;
    // public \$javascript;
    // protected \$sesion;
    // protected \$sesion_exitosa;
    // protected \$usuario;
    // protected \$usuario_nombre;

FINAL;
    } // php

} // Clase Propiedades

?>
