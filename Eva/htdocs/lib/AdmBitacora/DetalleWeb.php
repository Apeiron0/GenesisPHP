<?php
/**
 * GenesisPHP - AdmBitacora DetalleWeb
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

namespace AdmBitacora;

/**
 * Clase DetalleWeb
 */
class DetalleWeb extends Registro implements \Base2\SalidaWeb {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $usuario;
    // public $usuario_nom_corto;
    // public $fecha;
    // public $pagina;
    // public $pagina_id;
    // public $tipo;
    // public $tipo_descrito;
    // public $url;
    // public $notas;
    // static public $tipo_descripciones;
    // static public $tipo_colores;
    protected $detalle;  // Instancia de \Base2\DetalleWeb
    const RAIZ_PHP_ARCHIVO = 'admbitacora.php';

    /**
     * Barra
     *
     * @param  string Encabezado opcional
     * @return mixed  Instancia de \Base2\BarraWeb
     */
    protected function barra($in_encabezado='') {
        // Si viene el parametro se usa, si no, el encabezado por defecto
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = $this->encabezado();
        }
        // Crear la barra
        $barra             = new \Base2\BarraWeb();
        $barra->encabezado = $encabezado;
        $barra->icono      = $this->sesion->menu->icono_en('adm_bitacora');
        // Entregar
        return $barra;
    } // barra

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @return string HTML
     */
    public function html($in_encabezado='') {
        // Debe estar consultado, de lo contrario se consulta y si falla se muestra mensaje
        if (!$this->consultado) {
            try {
                $this->consultar();
            } catch (\Exception $e) {
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
                return $mensaje->html($in_encabezado);
            }
        }
        // Detalle
        $this->detalle = new \Base2\DetalleWeb();
        // Seccion general
        $this->detalle->seccion('General');
        $this->detalle->dato('Fecha',     $this->fecha);
        $this->detalle->dato('Usuario',   $this->usuario_nom_corto);
        $this->detalle->dato('Página',    $this->pagina);
        $this->detalle->dato('Página ID', $this->pagina_id);
        $this->detalle->dato('URL',       $this->url);
        $this->detalle->dato('Tipo',      $this->tipo_descrito);
        $this->detalle->dato('Notas',     $this->notas);
        // Pasar la barra
        $this->detalle->barra = $this->barra($in_encabezado);
        // Entregar
        return $this->detalle->html();
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        if ($this->detalle instanceof \Base2\DetalleWeb) {
            return $this->detalle->javascript();
        }
    } // javascript

} // Clase DetalleWeb

?>
