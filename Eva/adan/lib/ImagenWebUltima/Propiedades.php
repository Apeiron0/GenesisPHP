<?php
/**
 * GenesisPHP - ImagenWebUltima Propiedades
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

namespace ImagenWebUltima;

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
    // public \$id;
    // public \$caracteres_azar;
    // protected \$almacen_ruta;
    // protected \$almacen_tamanos;
    // protected \$tamano_en_uso;
    // protected \$imagen;
    // protected \$ancho;
    // protected \$alto;
    // protected \$ruta;
    // public \$pie;
    // public \$url;
    // public \$a_class;
    // public \$img_class;
    // public \$p_class;

FINAL;
    } // php

} // Clase Propiedades

?>
