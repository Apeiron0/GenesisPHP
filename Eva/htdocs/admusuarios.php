<?php
/**
 * GenesisPHP - Usuarios
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

require_once('lib/Base/AutocargadorClases.php');

// Si se solicita la descarga de CSV
if ($_GET['csv'] == 'descargar') {
    $pagina_csv = new \AdmUsuarios\PaginaCSV();
    echo $pagina_csv->csv();
} else {
    // Mostrar la página web
    $pagina_html = new \AdmUsuarios\PaginaWeb();
    echo $pagina_html->html();
}

?>
