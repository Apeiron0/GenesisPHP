<?php
/**
 * GenesisPHP - Personalizar RenglonesFormularioWeb
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

namespace Personalizar;

/**
 * Clase RenglonesFormularioWeb
 */
class RenglonesFormularioWeb extends DetalleWeb implements \Base2\SalidaWeb {

    // protected $sesion;
    // protected $consultado;
    // public $id;
    // public $nom_corto;
    // public $nombre;
    // public $tipo;
    // public $tipo_descrito;
    // public $email;
    // public $listado_renglones;
    // public $contrasena_descrito;
    // public $contrasena_alerta = false;
    // public $sesiones_maximas;
    // public $sesiones_contador;
    // public $sesiones_descrito;
    // public $sesiones_alerta = false;
    // public $estatus;
    // public $estatus_descrito;
    // static public $dias_expira_contrasena_aviso;
    // protected $contrasena;
    // protected $contrasena_encriptada;
    protected $formulario;  // Instancia de \Base2\FormularioWeb
    static public $form_name = 'personalizar_renglones';

    /**
     * Elaborar formulario
     *
     * @param  string  Encabezado opcional
     * @return string  HTML del Formulario de Búsqueda Avanzada
     */
    protected function elaborar_formulario($in_encabezado='') {
        // Formulario
        $this->formulario          = new \Base2\FormularioWeb(self::$form_name);
        $this->formulario->mensaje = '(*) Campos obligatorios.';
        // Formulario campos ocultos
        $cadenero = new \AdmCadenero\Registro($this->sesion);
        $this->formulario->oculto('cadenero', $cadenero->crear_clave(self::$form_name));
        // Formulario sección principal
        $this->formulario->texto_entero('listado_renglones', 'Cantidad de renglones', $this->listado_renglones);
        // Formualrio botones
        $this->formulario->boton_guardar();
        // Encabezado
        if ($in_encabezado !== '') {
            $encabezado = $in_encabezado;
        } else {
            $encabezado = 'Renglones de los listados';
        }
        // Entregar
        return $this->formulario->html($encabezado);
    } // elaborar_formulario

    /**
     * Recibir los valores del formulario
     */
    protected function recibir_formulario() {
        // Cadenero
        $cadenero = new \AdmCadenero\Registro($this->sesion);
        $cadenero->validar_recepcion(self::$form_name, $_POST['cadenero']);
        // Recibir el unico valor del formulario
        $this->listado_renglones = \Base2\UtileriasParaFormularios::post_texto($_POST['listado_renglones']);
    } // recibir_formulario

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
        // Si viene el formulario
        if ($_POST['formulario'] == self::$form_name) {
            try {
                // Recibir el formulario y cambiar la contraseña
                $this->recibir_formulario();
                $msg = $this->cambiar_cantidad_renglones($this->listado_renglones);
                // Mostrar mensaje de exito
                $mensaje = new \Base2\MensajeWeb($msg);
                return $mensaje->html($in_encabezado);
            } catch (\Base2\RegistroExceptionValidacion $e) {
                // Fallo la validacion, se muestra el mensaje y formulario de nuevo
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
                return $mensaje->html('Validación')."\n".$this->elaborar_formulario($in_encabezado);
            } catch (\Exception $e) {
                // Error fatal
                $mensaje = new \Base2\MensajeWeb($e->getMessage());
                return $mensaje->html('Error');
            }
        }
        // Mostrar formulario, como cadenero puede provovar una excepcion se encierra en try-catch
        try {
            return $this->elaborar_formulario($in_encabezado);
        } catch (\Exception $e) {
            $mensaje = new \Base2\MensajeWeb($e->getMessage());
            return $mensaje->html();
        }
    } // html

    /**
     * Javascript
     *
     * @return string Javascript
     */
    public function javascript() {
        $a = array();
        if ($this->detalle instanceof \Base2\DetalleWeb) {
            $a[] = $this->detalle->javascript();
        }
        if ($this->formulario instanceof \Base2\FormularioWeb) {
            $a[] = $this->formulario->javascript();
        }
        return implode("\n", $a);
    } // javascript

} // Clase RenglonesFormularioWeb

?>
