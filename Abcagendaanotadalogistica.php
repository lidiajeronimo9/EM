<?php 
session_start();
include "sessionstart.php";
$title = "Logistica - Agenda Anotada";

echo "<head>";
    //echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
    echo "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />";
    echo "<META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'>";
    echo "<title>" . $title . "</title>";
echo "</head>";
echo "<body>";
include "Includes/Header.php";



if (isset($_POST['op']) and $_POST['op'] != ''){
    $_POST['op'] = $_POST['op'];
}elseif (isset($_GET['op']) and $_GET['op'] != ''){
    $_POST['op'] = $_GET['op'];
}

if(!isset($_SESSION['hfdia'])){
    if (isset($_GET['hfdia'])) {
        $_SESSION['hfdia'] = $_GET['hfdia'];
    }elseif (isset($_POST['hfdia'])) {
        $_SESSION['hfdia'] = $_POST['hfdia'];
    }
}

if(!isset($_SESSION['hfmes'])){
    if (isset($_GET['hfmes'])) {
        $_SESSION['hfmes'] = $_GET['hfmes'];
    }elseif (isset($_POST['hfmes'])) {
        $_SESSION['hfmes'] = $_POST['hfmes'];
    }
}

if(!isset($_SESSION['hfanio'])){
    if (isset($_GET['hfanio'])) {
        $_SESSION['hfanio'] = $_GET['hfanio'];
    }elseif (isset($_POST['hfanio'])) {
        $_SESSION['hfanio'] = $_POST['hfanio'];
    }
}

if(!isset($_SESSION['dfdia'])){
    if (isset($_GET['dfdia'])) {
        $_SESSION['dfdia'] = $_GET['dfdia'];
    }elseif (isset($_POST['dfdia'])) {
        $_SESSION['dfdia'] = $_POST['dfdia'];
    }
}

if(!isset($_SESSION['dfmes'])){
    if (isset($_GET['dfmes'])) {
        $_SESSION['dfmes'] = $_GET['dfmes'];
    }elseif (isset($_POST['dfmes'])) {
        $_SESSION['dfmes'] = $_POST['dfmes'];
    }
}

if(!isset($_SESSION['dfanio'])){
    if (isset($_GET['dfanio'])) {
        $_SESSION['dfanio'] = $_GET['dfanio'];
    }elseif (isset($_POST['dfanio'])) {
        $_SESSION['dfanio'] = $_POST['dfanio'];
    }
}




if (isset($_GET['cveagendaanotada']) and $_GET['cveagendaanotada'] != ''){
    $_POST['cveagendaanotada'] = $_GET['cveagendaanotada'];
}


if (isset($_GET['cveflujo']) and $_GET['cveflujo'] != ''){
    $_POST['cveflujo'] = $_GET['cveflujo'];
}



if ($_POST['op'] == 'procesarinsert' or $_POST['op'] == 'procesarupdate' or $_POST['op'] == 'procesardelete'){
    $error = false;
    
    if ($_POST['op'] != 'procesardelete'){
        if (strlen($_POST['lugar']) == 0 and $error==false){
            $msgerror = "Capturar Lugar...";
            $error = true;
        }
        if (strlen($_POST['flujodelevento']) == 0 and $error==false){
            $msgerror = "Capturar Flujo del Evento...";
            $error = true;
        }
        
    }
    if ($error == false){
        if ($_POST['op'] == 'procesarinsert'){
            
            
            $path = "Archivos/agendaanotadalogistica/";
            
            $isql = "INSERT INTO agendaanotadaflujo(cveagendaanotada, lugar, flujodelevento, acompanianembamex, participan, documento1, documento2, documento3,
                    fecharegistro, fechaultimamodificacion, cveusuario, activo)
                VALUES('" . $_POST['cveagendaanotada'] . "','" . $_POST['lugar'] . "','" . $_POST['flujodelevento'] . "','" . $_POST['acompanianembamex'] 
                    . "','" . $_POST['participan'] . "','','','',Now(), Now(),
                '" . $_SESSION['cveusuario'] . "','1')";
            $result = mysql_query($isql);
            
            $cveflujo = mysql_insert_id();
            
            for($xx=1; $xx<=3; $xx++){
                $nombre_archivo = $_FILES['documento' . $xx]['name'];
                $tipo_archivo = $_FILES['documento' . $xx]['type'];
                $tamano_archivo = $_FILES['documento' . $xx]['size']; 
                $filename = $path  . $cveflujo . "_" . $xx . "_" . $nombre_archivo;
                
                
                
                if($tipo_archivo == 'text/plain' OR $tipo_archivo=='text/csv' OR $tipo_archivo == 'application/vnd.ms-excel' OR $tipo_archivo == 'application/octet-stream') {
                    if(move_uploaded_file($_FILES['documento' . $xx]['tmp_name'], $filename)) {
                        
                        $usql = "UPDATE  agendaanotadaflujo
                                SET documento" . $xx  . " = '" . $cveflujo . "_" . $xx . "_" . $nombre_archivo . "'
                                WHERE cveflujo = '" . $cveflujo . "'";
                        //echo "<br>" . $usql;
                        $uresult = mysql_query($usql);
                    }
                }
            }
            //exit;
            
            
            
            
            
            //echo "<br>" . $isql;
            echo "<table border='0' align='center' cellpadding='0' cellspacing='0' width='40%'>";
                echo "<tr height='10'><td></td></tr>";
                echo "<tr>";
                    echo "<td style='text-align:center; color:green' class='labelbnormal'>Registro Agregado con Exito</td>";
                echo "</tr>";
                echo "<tr height='10'><td></td></tr>";
            echo "</table>";
        }elseif ($_POST['op'] == 'procesarupdate'){
            $path = "Archivos/agendaanotadalogistica/";
            
            $usql = "UPDATE agendaanotadaflujo
                    SET cveagendaanotada = '" . $_POST['cveagendaanotada'] . "',
                        lugar = '" . $_POST['lugar'] . "',
                        flujodelevento = '" . $_POST['flujodelevento'] . "',
                        acompanianembamex = '" . $_POST['acompanianembamex'] . "',
                        participan = '" . $_POST['participan'] . "',
                        fechaultimamodificacion = Now()
                    WHERE cveflujo = '" . $_POST['cveflujo'] . "'";
            $result = mysql_query($usql);
            
            
            for($xx=1; $xx<=3; $xx++){
                $nombre_archivo = $_FILES['documento' . $xx]['name'];
                $tipo_archivo = $_FILES['documento' . $xx]['type'];
                $tamano_archivo = $_FILES['documento' . $xx]['size']; 
                $filename = $path  . $_POST['cveflujo'] . "_" . $xx . "_" . $nombre_archivo;
                
                if($tipo_archivo == 'text/plain' OR $tipo_archivo=='text/csv' OR $tipo_archivo == 'application/vnd.ms-excel' OR $tipo_archivo == 'application/octet-stream') {
                    if(move_uploaded_file($_FILES['documento' . $xx]['tmp_name'], $filename)) {
                        
                        $sqlf = "SELECT documento" . $xx . " as archivo FROM agendaanotadaflujo where cveflujo = '" . $_POST['cveflujo'] . "'";
                        $resultf = mysql_query($sqlf);
                        if($rowf = mysql_fetch_assoc($resultf)){
                            $arch = $path . $rowf['archivo'];
                            
                            if (!unlink($arch)){
                                //echo ("Error deleting " . $arch);
                            }else{
                                //echo ("Deleted " . $arch);
                            }
                            
                        }
                        
                        $usql = "UPDATE  agendaanotadaflujo
                                SET documento" . $xx  . " = '" . $_POST['cveflujo'] . "_" . $xx . "_" . $nombre_archivo . "'
                                WHERE cveflujo = '" . $_POST['cveflujo'] . "'";
                        //echo "<br>" . $usql;
                        $uresult = mysql_query($usql);
                    }
                }
            }
            
            
                
            echo "<table border='0' align='center' cellpadding='0' cellspacing='0' width='40%'>";
                echo "<tr height='10'><td></td></tr>";
                echo "<tr>";
                    echo "<td style='text-align:center; color:green' class='labelbnormal'>Registro Actualizado con Exito</td>";
                echo "</tr>";
                echo "<tr height='10'><td></td></tr>";
            echo "</table>";
        }elseif ($_POST['op'] == 'procesardelete'){
            $usql = "UPDATE agendaanotadaflujo
                    SET activo = '0'
                    WHERE cveflujo = '" . $_POST['cveflujo'] . "'";
            $result = mysql_query($usql);
                
            echo "<table border='0' align='center' cellpadding='0' cellspacing='0' width='40%'>";
                echo "<tr height='10'><td></td></tr>";
                echo "<tr>";
                    echo "<td style='text-align:center; color:green' class='labelbnormal'>Registro Eliminado con Exito</td>";
                echo "</tr>";
                echo "<tr height='10'><td></td></tr>";
            echo "</table>";
        }
        $_POST['op'] = '';
            
    }else{
        echo "<table border='0' align='center' cellpadding='0' cellspacing='0' width='40%'>";
            echo "<tr height='10'><td></td></tr>";
            echo "<tr>";
                echo "<td style='text-align:center; color:red' class='labelbnormal'>" . $msgerror . "</td>";
            echo "</tr>";
            echo "<tr height='10'><td></td></tr>";
        echo "</table>";
         if ($_POST['op'] == 'procesarinsert'){
            $_POST['op'] = 'insertar';
        }elseif ($_POST['op'] == 'procesarupdate'){
            $_POST['op'] = 'editar';
        }elseif ($_POST['op'] == 'procesardelete'){
            $_POST['op'] = 'eliminar';
        }
        
    }
    
}
    

    echo "<form name='FDatosB' method='post' action='" . $_SERVER['PHP_SELF'] . "'  enctype='multipart/form-data'>";
    echo "<table border='0' align='center' cellpadding='0' cellspacing='0' width='1024'>";
        echo "<tr height='10'><td></td></tr>";
        echo "<tr>";
            echo "<td style='text-align:center;' class='labeltitlenormalv1'>" . strtoupper($title) . "</td>";
        echo "</tr>";
        echo "<tr height='10'><td></td></tr>";
        if ($_POST['op'] == '' or isset($_POST['buscar'])){
            echo "<tr>";
                echo "<td><div class='menu_individual'><ul><li><a href='?op=insertar&cveagendaanotada=" .  $_POST['cveagendaanotada'] . "'><img src='./Images/icons/addpersonal.png' width='24' height='24' border='0'>&nbsp;Captura</a></li></ul></div></td>";
            echo "</tr>";
        }
        if (($_POST['op'] == 'insertar') or ($_POST['op'] == 'editar') or ($_POST['op'] == 'eliminar')){
            echo "<tr>";
                echo "<td><div class='menu_individual'><ul><li><a href='?op=&cveagendaanotada=" . $_POST['cveagendaanotada'] . "'><img src='./Images/icons/addpersonal.png' width='24' height='24' border='0'>&nbsp;Listado</a></li></ul></div></td>";
            echo "</tr>";
        }
    echo "</table>";
    
    if ($_POST['op'] == '' or isset($_POST['buscar']) or isset($_POST['ordenar'])){
        if(isset($_POST['ordenar'])){
            $_GET['currentpage'] = 1;
        }
        echo "<table border='1' align='center' cellpadding='2' cellspacing='0' width='1024' style='border-color:#F5F5F5;'>";
        
        
            echo "<tr>";
                echo "<td colspan='5'>";
                    echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
                        $liga = "hfdia=" . $_SESSION['hfdia'] . "&hfmes=" . $_SESSION['hfmes'] . "&hfanio=" . $_SESSION['hfanio'] . "&dfdia=" . $_SESSION['dfdia'] . "&dfmes=" . $_SESSION['dfmes'] . "&dfanio=" . $_SESSION['dfanio'];
                        $sqlagendaanotada = "SELECT * FROM agendaanotada WHERE cveagendaanotada = '" . $_POST['cveagendaanotada']. "'";
                        $resultagendaanotada = mysql_query($sqlagendaanotada);
                        if($rowagendaanotada = mysql_fetch_assoc($resultagendaanotada)){
                            echo "<tr>";
                                echo "<td class='labelbnormalv1' style='text-align:center;' rowspan='2' width='100'><a href='Abcagendaanotada.php?" . $liga . "'>";
                                echo _('Agenda Anotada') . "</a></td>";
                                echo "<td class='labelbnormalv1' style='font-size:12pt;' width='200'>" . _('Fecha / Hora') . ":&nbsp;</td>";
                                echo "<td class='labelnormalv1' style='font-size:12pt;'>" . substr($rowagendaanotada['fecha'],0,10) . " " . $rowagendaanotada['hora'] . ":" . $rowagendaanotada['minuto'] . "</td>";
                            echo "</tr>";
                            echo "<tr>";
                                echo "<td class='labelbnormalv1' style='font-size:12pt;'>" . _('Descripcion') . ":&nbsp;</td>";
                                echo "<td class='labelnormalv1' style='font-size:12pt;'>" . $rowagendaanotada['descripcion'] . "</td>";
                            echo "</tr>";
                        }
                    echo "</table>";
                echo "</td>";
            echo "</tr>";
        
            echo "<tr>";
                echo "<td class='labeltitleformnormalv1' colspan='13'>Temas Agenda Anotada</td>";
            echo "</tr>";
            
            echo "<tr>";
                echo "<td class='labeltitle2formnormalv1'>" . _('Lugar') . "</td>";
                echo "<td class='labeltitle2formnormalv1'>" . _('Flujo del Evento') . "</td>";
                echo "<td class='labeltitle2formnormalv1' style='font-size:8px; width:75px;'>Editar</td>";
                echo "<td class='labeltitle2formnormalv1' style='font-size:8px; width:75px;'>Eliminar</td>";
            echo "</tr>";
            $sql = "SELECT a.cveflujo, a.lugar, a.flujodelevento
                FROM agendaanotadaflujo a
                WHERE a.cveagendaanotada = '" . $_POST['cveagendaanotada'] . "'
                    and a.activo = '1'
                ORDER BY cveflujo";
                //echo "<br>" . $sql;
            $result = mysql_query($sql);
            $renglon = 0;
            while($row = mysql_fetch_assoc($result)){
                $renglon++;
                if ($renglon==1){
                    $bgcolor = '#FFFFFF';    
                }else{
                    $bgcolor = '#F5F5F5';
                    $renglon = 0;    
                }
                echo "<tr bgcolor='" . $bgcolor . "'>";
                    echo "<td style='text-align:left; border-style:solid; border-color:#F5F5F5;' class='labelnormal'>" . $row['lugar'] . "</td>";
                    echo "<td style='text-align:left; border-style:solid; border-color:#F5F5F5;' class='labelnormal'>" . $row['flujodelevento'] . "</td>";
                    echo "<td style='text-align:center; border-style:solid; border-color:#F5F5F5;' class='labelnormal'><a href='?op=editar&cveagendaanotada=" . $_POST['cveagendaanotada']  . "&cveflujo=" . $row['cveflujo'] . "'>Editar</a></td>";
                    echo "<td style='text-align:center; border-style:solid; border-color:#F5F5F5;' class='labelnormal'><a href='?op=eliminar&cveagendaanotada=" . $_POST['cveagendaanotada']  . "&cveflujo=" . $row['cveflujo'] . "'>Eliminar</a></td>";
                echo "</tr>";
            }
        echo "</table>";
    }

if (($_POST['op'] == 'insertar') or ($_POST['op'] == 'editar') or ($_POST['op'] == 'eliminar')){
    if (($_POST['op'] == 'editar') or ($_POST['op'] == 'eliminar')){
        $sql = "SELECT cveflujo, cveagendaanotada, lugar, flujodelevento, acompanianembamex, participan, documento1, documento2, documento3
                FROM agendaanotadaflujo
                WHERE activo = 1 and cveflujo = '" . $_POST['cveflujo'] . "'";
        $result = mysql_query($sql);
        if($row = mysql_fetch_assoc($result)){
            $_POST['cveagendaanotada'] = $row['cveagendaanotada'];
            $_POST['lugar'] = $row['lugar'];
            $_POST['flujodelevento'] = $row['flujodelevento'];
            $_POST['acompanianembamex'] = $row['acompanianembamex'];
            $_POST['participan'] = $row['participan'];
            $_POST['documento1'] = $row['documento1'];
            $_POST['documento2'] = $row['documento2'];
            $_POST['documento3'] = $row['documento3'];
        }
        
    }
    //echo "<form name='FDatosB' method='post'>";
    //echo "<form name='FDatosB' method='post' enctype='multipart/form-data'>";
        echo "<table border='0' align='center' cellpadding='2' cellspacing='0' width='1024' style='border-color:#F5F5F5;'>";
            echo "<tr>";
                if ($_POST['op'] == 'insertar'){
                    $tituloformulario = 'Agregar Flujo';
                }elseif ($_POST['op'] == 'editar'){
                    $tituloformulario = 'Editar Flujo';
                }elseif($_POST['op'] == 'eliminar'){
                    $tituloformulario = 'Eliminar Flujo';
                }    
                echo "<td colspan='6' class='labeltitleformnormalv1'>" . $tituloformulario . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<tr>";
                echo "<td  class='labelbnormalv1'>" . _('Lugar') . ": &nbsp;</td>";
                echo "<td class='cellinputnormalv1'>";
                    if (($_POST['op'] == 'eliminar')){
                        echo "<input type='text' name='lugar' value='" . $_POST['lugar'] . "' size='28' maxlength='50' class='textnormalv1' readonly>";
                    }else{
                        echo "<input type='text' name='lugar' value='" . $_POST['lugar'] . "' size='28' maxlength='50' class='textnormalv1'>";
                    }
                echo "</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='labelbnormalv1'>" . _('Flujo del Evento') . ":&nbsp;</td>";
                echo "<td class='cellinputnormalv1' colspan='5'>";
                    if ($_POST['op'] == 'eliminar'){
                        echo "<textarea name='flujodelevento' rows='3' cols='60' readonly class='textnormalv1'>" . $_POST['flujodelevento'] . "</textarea>";
                    }else{
                        echo "<textarea name='flujodelevento' rows='3' cols='60' class='textnormalv1'>" . $_POST['flujodelevento'] . "</textarea>";
                    }
                echo "</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='labelbnormalv1'>" . _('Acompanian Embamex') . ":&nbsp;</td>";
                echo "<td class='cellinputnormalv1' colspan='5'>";
                    if ($_POST['op'] == 'eliminar'){
                        echo "<textarea name='acompanianembamex' rows='3' cols='60' readonly class='textnormalv1'>" . $_POST['acompanianembamex'] . "</textarea>";
                    }else{
                        echo "<textarea name='acompanianembamex' rows='3' cols='60' class='textnormalv1'>" . $_POST['acompanianembamex'] . "</textarea>";
                    }
                echo "</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='labelbnormalv1'>" . _('Participan') . ":&nbsp;</td>";
                echo "<td class='cellinputnormalv1' colspan='5'>";
                    if ($_POST['op'] == 'eliminar'){
                        echo "<textarea name='participan' rows='3' cols='60' readonly class='textnormalv1'>" . $_POST['participan'] . "</textarea>";
                    }else{
                        echo "<textarea name='participan' rows='3' cols='60' class='textnormalv1'>" . $_POST['participan'] . "</textarea>";
                    }
                echo "</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='labelbnormalv1'>" . _('Adjuntar Archivos') . ": &nbsp;</td>";
                echo "<td class='cellinputnormalv1' colspan='2'>";
                    if (($_POST['op'] == 'eliminar') or ($_POST['op'] == 'editar')){
                        if ($_POST['op'] == 'editar'){
                            $path = "Archivos/agendaanotadalogistica/";
                            echo "<input type='file' name='documento1' id='documento1' class='textnormalv1'/>";
                            if ($_POST['documento1'] != ""){
                                echo "<a href='" . $path . $_POST['documento1'] . "' target='_blank'>" . $_POST['documento1'] . "</a>";
                            }
                            echo "<br>";
                            echo "<input type='file' name='documento2' id='documento2' class='textnormalv1'/>";
                            if ($_POST['documento2'] != ""){
                                echo "<a href='" . $path . $_POST['documento2'] . "' target='_blank'>" . $_POST['documento2'] . "</a>";
                            }
                            echo "<br>";
                            echo "<input type='file' name='documento3' id='documento3' class='textnormalv1'/>";
                            if ($_POST['documento2'] != ""){
                                echo "<a href='" . $path . $_POST['documento3'] . "' target='_blank'>" . $_POST['documento3'] . "</a>";
                            }
                            echo "<br>";
                        }
                        
                    }else{
                        echo "<input type='file' name='documento1' id='documento1' class='textnormalv1'/><br>";
                        echo "<input type='file' name='documento2' id='documento2' class='textnormalv1'/><br>";
                        echo "<input type='file' name='documento3' id='documento3' class='textnormalv1'/><br>";
                    }
                echo "</td>";
                echo "<td class='labelbnormalv1'></td>";
                echo "<td style='text-align:left; border-style:solid; border-color:#F5F5F5;' colspan='2'></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:center; border-style:solid; border-color:#F5F5F5;' colspan='6'>";
                    echo "<input type='hidden' name='cveagendaanotada' value='" . $_POST['cveagendaanotada'] . "'>";
                    echo "<input type='hidden' name='cveflujo' value='" . $_POST['cveflujo'] . "'>";
                    echo "<input type='submit' name='procesar' value='Procesar' class='buttonnormalv1'>";
                    if ($_POST['op'] == 'editar'){
                        echo "<input type='hidden' name='op' value='procesarupdate'>";
                    }elseif ($_POST['op'] == 'eliminar'){
                        echo "<input type='hidden' name='op' value='procesardelete'>";
                    }else{
                        echo "<input type='hidden' name='op' value='procesarinsert'>";
                    }
                echo "</td>";
            echo "</tr>";
        echo "</table>";
    echo "</form>";
}

include "Includes/Footer.php";
?>

