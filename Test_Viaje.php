<?php
include_once 'Persona.php';
include_once 'Pasajero.php';
include_once 'ResponsableV.php';
include_once 'Empresa.php';
include_once 'Viaje.php';
include_once 'BaseDatos.php';

while (true) {
    echo "\nMenú:\n";
    echo "1. Cargar informacion de Viaje\n";
    echo "2. Modificar informacion de Viaje\n";
    echo "3. Eliminar informacion de Viaje\n";
    echo "4. Ver datos del Viaje\n";
    echo "5. Cargar informacion de Empresa\n";
    echo "6. Modificar informacion Empresa\n";
    echo "7. Eliminar informacion de Empresa\n";
    echo "8. Agregar Pasajero\n";
    echo "9. Modificar Pasajero\n";
    echo "10. Eliminar Pasajero\n";
    echo "11. Salir\n";
    $opcion = readline("Ingrese la opción deseada: ");

    switch ($opcion) {
        case '1':
            cargarInformacionViaje();
            break;
        case '2':
            modificarInformacionViaje();
            break;
        case '3':
            eliminarInformacionViaje();
            break;
        case '4':
            verDatosViaje();
            break;
        case '5':
            cargarInformacionEmpresa();
            break;
        case '6':
            modificarInformacionEmpresa();
            break;
        case '7':
            eliminarInformacionEmpresa();
            break;
        case '8':
            agregarPasajero();
            break;
        case '9':
            modificarPasajero();
            break;
        case '10':
            eliminarPasajero();
            break;
        case '11':
            echo "Saliendo del programa...\n";
            exit;
        default:
            echo "Opción inválida. Por favor, seleccione una opción válida.\n";
            break;
    }
}

function cargarInformacionViaje() {
    $empresa = new Empresa();
    echo "Ingrese el Id de la empresa:\n";
    $id= trim(fgets(STDIN));
    if (!$empresa->Buscar($id)) {
        echo "No se encontró una empresa con el ID proporcionado.\n";
        return;
    } else {
        echo "La empresa es ".$empresa->getNombre()."\n";
    }

    $nroDocResponsableV = readline("Ingrese el numero de documento empleado del responsable del nuevo viaje: ");
    $nombreResponsableV = readline("Ingrese el Nombre del responsable del nuevo viaje: ");
    $apellidoResponsableV  = readline("Ingrese el apellido del responsable del nuevo viaje: ");
    $telefonoResponsableV = readline("Ingrese el telefono del responsable del nuevo viaje: ");
    $numEmpleado = readline("Ingrese el numero de empleado del responsable del nuevo viaje: ");
    $numLicencia = readline("Ingrese el numero de licencia del responsable del nuevo viaje: ");
    $nuevoResponsable = new ResponsableV();
    $nuevoResponsable->cargar($nroDocResponsableV, $nombreResponsableV, $apellidoResponsableV, $telefonoResponsableV, $numEmpleado, $numLicencia);
    if($nuevoResponsable->Buscar($numEmpleado)){
        echo "Esta persona ya ha sido cargada\n";
        return;
    } else {
        $nuevoResponsable->insertar();
    }

    $codigo = readline("Ingrese el codigo del nuevo viaje: ");
    $destino = readline("Ingrese el destino del nuevo viaje: ");
    $maxPasajeros = readline("Ingrese la cantidad maxima de pasajeros del nuevo viaje: ");
    $costoDelViaje = readline("Ingrese el costo del viaje: ");

    $viaje = new Viaje();
    $viaje->cargar($codigo, $destino, $maxPasajeros, $nuevoResponsable, $costoDelViaje, $empresa);
    $viaje->insertar();
}

function modificarInformacionViaje() {
    while (true) {
        echo "\nDesea modificar:\n";
        echo "1. Modificar destino del viaje\n";
        echo "2. Modificar maximo de pasajeros del viaje\n";
        echo "3. Modificar responsable del viaje\n";
        echo "4. Modificar costos del viaje\n";
        echo "5. Modificar Empresa\n";
        echo "6. Volver al menu anterior\n";
        $opcion = readline("Ingrese la opción deseada: ");
        $viaje = new Viaje();
        echo "Ingrese el id del viaje:\n";
        $id = trim(fgets(STDIN));
        if(!$viaje->Buscar($id)){
            echo "El viaje no ha podido ser encontrado\n";
            return;
        }

        switch ($opcion) {
            case '1':
                echo "Este es el Destino actual del viaje: " . $viaje->getDestino() . "\n";
                $viaje->setDestino(readline("Ingrese el destino del viaje: "));
                $viaje->modificar();
                break;
            case '2':
                echo "Esta es la cantidad maxima actual del viaje: " . $viaje->getMaxPasajeros() . "\n";
                $viaje->setMaxPasajeros(readline("Ingrese la cantidad maxima de pasajeros del viaje: "));
                $viaje->modificar();
                break;
            case '3':
                echo "Qué información desea modificar del responsable del viaje?\n";
                echo "1- El número de licencia\n";
                echo "2- El nombre\n";
                echo "3- El apellido\n";
                echo "4- Todos los datos\n";
                $opcion = trim(fgets(STDIN));
                modificarResponsableViaje($viaje, $opcion);
                break;
            case '4':
                echo "Este es el Costo actual del viaje: " . $viaje->getCosto() . "\n";
                $viaje->setCosto(readline("Ingrese el nuevo costo del viaje: "));
                $viaje->modificar();
                break;
            case '5':
                echo "Qué información desea modificar de la empresa?\n";
                echo "1- El nombre\n";
                echo "2- La dirección\n";
                echo "3- Todos los datos\n";
                $eleccion = trim(fgets(STDIN));
                modificarEmpresa($viaje, $eleccion);
                break;
            case '6':
                return;
            default:
                echo "Opción inválida. Por favor, seleccione una opción válida.\n";
                break;
        }
    }
}

function eliminarInformacionViaje() {
    echo "Ingrese el Id del viaje que desea eliminar:\n";
    $id = trim(fgets(STDIN));
    $viaje = new Viaje();
    if($viaje->Buscar($id)) {
        $viaje->eliminar();
        echo "El viaje ha sido eliminado correctamente.\n";
    } else {
        echo "No se encontró el viaje que se buscaba.\n";
    }
}

function verDatosViaje() {
    echo "Ingrese el Id del viaje:\n";
    $id = trim(fgets(STDIN));
    $viaje = new Viaje();
    if($viaje->Buscar($id)) {
        echo $viaje;
    } else {
        echo "No se encontró el viaje que se buscaba.\n";
    }
}

function cargarInformacionEmpresa() {
    $idEmpresa = readline("Ingrese el id de la empresa: ");
    $nombreEmpresa = readline("Ingrese el nombre de la empresa: ");
    $direccionEmpresa = readline("Ingrese la dirección de la empresa: ");
    $empresa = new Empresa();
    $empresa->cargar(null, $nombreEmpresa, $direccionEmpresa);
    $empresa->insertar();
}

function modificarInformacionEmpresa() {
    echo "Ingrese el id de la empresa que desea modificar:\n";
    $id = trim(fgets(STDIN));
    $empresa = new Empresa();
    if(!$empresa->Buscar($id)){
        echo "La empresa no ha podido ser encontrada\n";
        return;
    }

    echo "Qué información desea modificar de la empresa?\n";
    echo "1- El nombre\n";
    echo "2- La dirección\n";
    echo "3- Todos los datos\n";
    $eleccion = trim(fgets(STDIN));
    modificarEmpresa($empresa, $eleccion);
}

function eliminarInformacionEmpresa() {
    echo "Ingrese el Id de la empresa que desea eliminar:\n";
    $id = trim(fgets(STDIN));
    $empresa = new Empresa();
    if($empresa->Buscar($id)) {
        $empresa->eliminar();
        echo "La empresa ha sido eliminada correctamente.\n";
    } else {
        echo "No se encontró la empresa que se buscaba.\n";
    }
}

function agregarPasajero() {
    $viaje = new Viaje();
    echo "Ingrese el id del viaje:\n";
    $id = trim(fgets(STDIN));
    if ($viaje->Buscar($id)) {
        echo $viaje;
        if (!$viaje->hayPasajesDisponibles()) {
            echo "No hay pasajes disponibles\n";
            return;
        } else {
            $numDocPasajero = readline("Ingrese el numero de documento del pasajero: ");
            $nombrePasajero = readline("Ingrese el Nombre del pasajero: ");
            $apellidoPasajero  = readline("Ingrese el apellido del pasajero: ");
            $telefonoPasajero = readline("Ingrese el telefono del pasajero: ");
            $nuevoPasajero = new Pasajero();
            $nuevoPasajero->cargar($numDocPasajero, $nombrePasajero, $apellidoPasajero, $telefonoPasajero, $viaje);
            if ($nuevoPasajero->Buscar($numDocPasajero)) {
                echo "Este pasajero ya ha sido cargado\n";
                return;
            } else {
                $nuevoPasajero->insertar();
            }
        }
    } else {
        echo "No se encontró el viaje que se buscaba.\n";
    }
}

function modificarPasajero() {
    echo "Ingrese el documento del pasajero que desea modificar:\n";
    $id = trim(fgets(STDIN));
    $pasajero = new Pasajero();
    if (!$pasajero->Buscar($id)) {
        echo "El pasajero no ha podido ser encontrado\n";
        return;
    }

    echo "Qué información desea modificar del pasajero?\n";
    echo "1- El nombre\n";
    echo "2- El apellido\n";
    echo "3- El telefono\n";
    echo "4- Todos los datos\n";
    $eleccion = trim(fgets(STDIN));
    switch ($eleccion) {
        case '1':
            echo "Este es el nombre actual del pasajero: " . $pasajero->getNombre() . "\n";
            $pasajero->setNombre(readline("Ingrese el nuevo nombre del pasajero: "));
            break;
        case '2':
            echo "Este es el apellido actual del pasajero: " . $pasajero->getApellido() . "\n";
            $pasajero->setApellido(readline("Ingrese el nuevo apellido del pasajero: "));
            break;
        case '3':
            echo "Este es el telefono actual del pasajero: " . $pasajero->getTelefono() . "\n";
            $pasajero->setTelefono(readline("Ingrese el nuevo telefono del pasajero: "));
            break;
        case '4':
            $pasajero->setNombre(readline("Ingrese el nuevo nombre del pasajero: "));
            $pasajero->setApellido(readline("Ingrese el nuevo apellido del pasajero: "));
            $pasajero->setTelefono(readline("Ingrese el nuevo telefono del pasajero: "));
            break;
        default:
            echo "Opción inválida. Por favor, seleccione una opción válida.\n";
            return;
    }
    $pasajero->modificar();
}

function eliminarPasajero() {
    echo "Ingrese el documento del pasajero que desea eliminar:\n";
    $id = trim(fgets(STDIN));
    $pasajero = new Pasajero();
    if ($pasajero->Buscar($id)) {
        $pasajero->eliminar();
        echo "El pasajero ha sido eliminado correctamente.\n";
    } else {
        echo "No se encontró el pasajero que se buscaba.\n";
    }
}

function modificarResponsableViaje($viaje, $opcion) {
    $responsable = $viaje->getResponsable();
    switch ($opcion) {
        case '1':
            echo "Este es el numero de licencia actual del responsable del viaje: " . $responsable->getNumLicencia() . "\n";
            $responsable->setNumLicencia(readline("Ingrese el nuevo numero de licencia del responsable del viaje: "));
            break;
        case '2':
            echo "Este es el nombre actual del responsable del viaje: " . $responsable->getNombre() . "\n";
            $responsable->setNombre(readline("Ingrese el nuevo nombre del responsable del viaje: "));
            break;
        case '3':
            echo "Este es el apellido actual del responsable del viaje: " . $responsable->getApellido() . "\n";
            $responsable->setApellido(readline("Ingrese el nuevo apellido del responsable del viaje: "));
            break;
        case '4':
            $responsable->setNumLicencia(readline("Ingrese el nuevo numero de licencia del responsable del viaje: "));
            $responsable->setNombre(readline("Ingrese el nuevo nombre del responsable del viaje: "));
            $responsable->setApellido(readline("Ingrese el nuevo apellido del responsable del viaje: "));
            break;
        default:
            echo "Opción inválida. Por favor, seleccione una opción válida.\n";
            return;
    }
    $responsable->modificar();
}

function modificarEmpresa($empresa, $eleccion) {
    switch ($eleccion) {
        case '1':
            echo "Este es el nombre actual de la empresa: " . $empresa->getNombre() . "\n";
            $empresa->setNombre(readline("Ingrese el nuevo nombre de la empresa: "));
            break;
        case '2':
            echo "Esta es la dirección actual de la empresa: " . $empresa->getDireccion() . "\n";
            $empresa->setDireccion(readline("Ingrese la nueva dirección de la empresa: "));
            break;
        case '3':
            $empresa->setNombre(readline("Ingrese el nuevo nombre de la empresa: "));
            $empresa->setDireccion(readline("Ingrese la nueva dirección de la empresa: "));
            break;
        default:
            echo "Opción inválida. Por favor, seleccione una opción válida.\n";
            return;
    }
    $empresa->modificar();
}
?>
