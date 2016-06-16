<?php
/**
 * GenesisPHP - TemaWebDashboard
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

namespace Base2;

/**
 * Clase TemaWebDashboard
 */
class TemaWebDashboard extends TemaWeb {

    // public $sistema;
    // public $titulo;
    // public $descripcion;
    // public $autor;
    // public $css;
    // public $favicon;
    // public $menu_principal_logo;
    // public $icono;
    // public $contenido;
    // public $javascript;
    // public $pie;
    // public $menu;

    /**
     * Menu Principal HTML
     *
     * @return string Código HTML
     */
    protected function menu_principal_html() {
        // De inicio no hay opciones del lado derecho
        $hay_en_la_derecha = false;
        // En este arreglo acumulamos
        $a = array();
        // Acumular
        $a[] = '<nav class="navbar navbar-fixed-top" role="navigation" id="menu-principal">';
        $a[] = '  <div class="container-fluid">';
        // Navbar-header / brand / collapse
        $a[] = '    <div class="navbar-header">';
        $a[] = '      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-principal-collapse">';
        $a[] = '        <span class="sr-only">Toggle navigation</span>';
        $a[] = '        <span class="icon-bar"></span>';
        $a[] = '        <span class="icon-bar"></span>';
        $a[] = '        <span class="icon-bar"></span>';
        $a[] = '      </button>';
        if ($this->menu_principal_logo != '') {
            $a[] = "      <a class=\"navbar-brand\" href=\"index.php\"><img src=\"{$this->menu_principal_logo}\" alt=\"{$this->sistema}\"></a>";
        } else {
            $a[] = "      <a class=\"navbar-brand\" href=\"index.php\">{$this->sistema}</a>";
        }
        $a[] = '    </div>';
        // Menu
        $a[] = '    <div class="navbar-collapse collapse" id="menu-principal-collapse">';
        $a[] = '      <ul class="nav navbar-nav">';
        // Menu opciones del lado izquierdo
        foreach ($this->menu->opciones_menu_principal() as $opcion) {
            if ($opcion['posicion'] == 'izquierda') {
                if (strpos($opcion['icono'], 'glyphicon') === 0) {
                    $icono = "<span class=\"{$opcion['icono']}\"></span>";
                } elseif ($opcion['icono'] != '') {
                    $icono = "<img src=\"imagenes/16x16/{$opcion['icono']}\">";
                } elseif ($opcion['etiqueta'] == '') {
                    $icono = "<span class=\"glyphicon glyphicon-folder-close\"></span>";
                }
                $mostrar = "$icono {$opcion['etiqueta']}";
                if ($opcion['activo'] == true) {
                    $a[] = "        <li class=\"active\"><a href=\"{$opcion['url']}\">$mostrar</a></li>";
                } else {
                    $a[] = "        <li><a href=\"{$opcion['url']}\">$mostrar</a></li>";
                }
            }
            if ($opcion['posicion'] == 'derecha') {
                $hay_en_la_derecha = true;
            }
        }
        $a[] = '      </ul>';
        // Menu opciones del lado derecho
        if ($hay_en_la_derecha) {
            $a[] = '      <ul class="nav navbar-nav navbar-right">';
            foreach ($this->menu->opciones_menu_principal() as $opcion) {
                if ($opcion['posicion'] == 'derecha') {
                    if (strpos($opcion['icono'], 'glyphicon') === 0) {
                        $icono = "<span class=\"{$opcion['icono']}\"></span>";
                    } elseif ($opcion['icono'] != '') {
                        $icono = "<img src=\"imagenes/16x16/{$opcion['icono']}\">";
                    } elseif ($opcion['etiqueta'] == '') {
                        $icono = "<span class=\"glyphicon glyphicon-folder-close\"></span>";
                    }
                    $mostrar = "$icono {$opcion['etiqueta']}";
                    if ($opcion['activo'] == true) {
                        $a[] = "        <li class=\"active\"><a href=\"{$opcion['url']}\">$mostrar</a></li>";
                    } else {
                        $a[] = "        <li><a href=\"{$opcion['url']}\">$mostrar</a></li>";
                    }
                }
            }
            $a[] = '      </ul>';
        }
        $a[] = '    </div>';
        // Cerrar tags
        $a[] = '  </div>';
        $a[] = '</nav>';
        // Entregar
        return implode("\n", $a);
    } // menu_principal_html

    /**
     * Menu Secundario HTML
     *
     * @return string Código HTML
     */
    protected function menu_secundario_html() {
        // En este arreglo acumularemos la salida
        $a   = array();
        // Acumular
        $a[] = '<ul class="nav">';
        foreach ($this->menu->opciones_menu_secundario() as $opcion) {
            if (strpos($opcion['icono'], 'glyphicon') === 0) {
                $icono = "<span class=\"{$opcion['icono']}\"></span>";
            } elseif ($opcion['icono'] != '') {
                $icono = "<img src=\"imagenes/24x24/{$opcion['icono']}\">";
            } elseif ($opcion['etiqueta'] == '') {
                $icono = "<span class=\"glyphicon glyphicon-folder-close\"></span>";
            }
            $mostrar = "$icono {$opcion['etiqueta']}";
            if ($opcion['url'] != '') {
                $vinculo = "<a href=\"{$opcion['url']}\">$mostrar</a>";
            } else {
                $vinculo = $mostrar;
            }
            if ($opcion['activo'] == true) {
                $a[] = "  <li class=\"active\">$vinculo</li>";
            } else {
                $a[] = "  <li>$vinculo</li>";
            }
        }
        $a[] = '</ul>';
        // Entregar
        return implode("\n", $a);
    } // menu_secundario_html

    /**
     * Cabecera HTML
     *
     * @return string Código HTML
     */
    protected function cabecera_html() {
        // En este arreglo acumulamos
        $a = array();
        // Acumular
        $a[] = '<!DOCTYPE html>';
        $a[] = '<html lang="es">';
        $a[] = '<head>';
        $a[] = '  <meta charset="utf-8">';
        $a[] = '  <meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $a[] = '  <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        if ($this->descripcion != '') {
            $a[] = "  <meta name=\"description\" content=\"{$this->descripcion}\">";
        }
        if ($this->autor != '') {
            $a[] = "  <meta name=\"author\" content=\"{$this->autor}\">";
        }
        if ($this->favicon != '') {
            $a[] = "  <link rel=\"shortcut icon\" href=\"{$this->favicon}\">";
        } else {
            $a[] = '  <link rel="shortcut icon" href="favicon.ico">';
        }
        if ($this->titulo != '') {
            $a[] = "  <title>{$this->sistema} | {$this->titulo}</title>";
        } else {
            $a[] = "  <title>{$this->sistema}</title>";
        }
        // Acumular Twitter Bootstrap
        $a[] = '  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">';
        // Acumular selector de fechas
        $a[] = '  <link href="css/datepicker.css" rel="stylesheet" type="text/css">';
        $a[] = '  <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">';
        // Acumular graficador morris.js
        $a[] = '  <link href="css/morris.css" rel="stylesheet" type="text/css">';
        // Acumular archivo CSS propio de esta plantilla
        $a[] = '  <link href="css/plantilla-dashboard.css" rel="stylesheet" type="text/css">';
        if ($this->css != '') {
            $a[] = "  <link href=\"{$this->css}\" rel=\"stylesheet\" type=\"text/css\">";
        }
        $a[] = '</head>';
        // Acumular inicio de body
        $a[] = '<body>';
        // Entregar
        return implode("\n", $a);
    } // cabecera_html

    /**
     * Final HTML
     *
     * @return string Código HTML
     */
    protected function final_html() {
        // En este arreglo acumulamos
        $a = array();
        // Acumular pie
        $a[] = $this->bloque_html($this->pie, 'footer');
        // Acumular JQuery
        $a[] = '  <script src="js/jquery.min.js"></script>';
        // Acumular Twitter Bootstrap
        $a[] = '  <script src="js/bootstrap.min.js"></script>';
        // Acumular selector de fechas
        $a[] = '  <script src="js/bootstrap-datepicker.js"></script>';
        $a[] = '  <script src="js/locales/bootstrap-datepicker.es.js"></script>';
        $a[] = '  <script src="js/bootstrap-datetimepicker.min.js"></script>';
        $a[] = '  <script src="js/locales/bootstrap-datetimepicker.es.js"></script>';
        // Acumular graficador morris.js
        $a[] = '  <script src="js/raphael-min.js"></script>';
        $a[] = '  <script src="js/morris.min.js"></script>';
        // Acumular Javascript que se haya agregado desde fuera
        $a[] = $this->bloque_html($this->javascript, 'script');
        // Acumular cierre de body y html
        $a[] = '</body>';
        $a[] = '</html>';
        // Entregar
        return implode("\n", $a);
    } // final_html

    /**
     * HTML
     *
     * @return string Código HTML
     */
    public function html() {
        // Si esta definido el menu, se toman el título e ícono de éste
        if (is_object($this->menu)) {
            if ($this->titulo == '') {
                $this->titulo = $this->menu->titulo_en();
            }
            if ($this->icono == '') {
                $this->icono = $this->menu->icono_en();
            }
        }
        // En este arreglo acumularemos la salida
        $a = array();
        // Acumular
        $a[] = $this->cabecera_html();
        $a[] = $this->menu_principal_html();
        $a[] = '  <div class="container-fluid">';
        $a[] = '    <div class="row">';
        $a[] = '      <div class="col-sm-3 col-md-2" id="menu-secundario">';
        $a[] = $this->menu_secundario_html();
        $a[] = '      </div>';
        $a[] = '      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" id="contenido">';
        if ($this->icono != '') {
            if (strpos($this->icono, 'glyphicon') === 0) {
                $icono = "<span class=\"{$this->icono}\"></span>";
            } else {
                $icono = "<img src=\"imagenes/48x48/{$this->icono}\">";
            }
            $a[] = "        <h1 class=\"page-header titulo\">$icono {$this->titulo}</h1>";
        } else {
            $a[] = "        <h1 class=\"page-header titulo\">{$this->titulo}</h1>";
        }
        $a[] = $this->bloque_html($this->contenido, 'div');
        $a[] = '      </div>';
        $a[] = '    </div>'; // row
        $a[] = '  </div>'; // container-fluid
        $a[] = $this->final_html();
        // Entregar
        return implode("\n", $a);
    } // html

} // Clase TemaWebDashboard

?>
