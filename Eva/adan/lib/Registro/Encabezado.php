<?php
/**
 * GenesisPHP - Registro Encabezado
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

namespace Registro;

/**
 * Clase Encabezado
 */
class Encabezado extends \Base\Plantilla {

    /**
     * PHP
     *
     * @return string Código PHP
     */
    public function php() {
        $columnas_vip = $this->columnas_vip_para_mensaje();
        if ($columnas_vip !== '') {
            $encabezado = $columnas_vip;
        } else {
            $encabezado = 'SED_TITULO_SINGULAR';
        }
        return <<<FIN
    /**
     * Encabezado
     *
     * @return string Encabezado
     */
    public function encabezado() {
        return "$encabezado";
    } // encabezado

FIN;
    } // php

} // Clase Encabezado

?>
