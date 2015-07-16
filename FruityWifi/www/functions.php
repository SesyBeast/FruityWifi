<? 
/*
    Copyright (C) 2013-2015 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<?

# [Verifica characteres -> [a-z0-9-_. ] ]
function regex_standard($var, $url, $regex_extra) {
    
    $regex_extra = implode("\\", str_split($regex_extra));
    
    $regex = "/(?i)(^[a-z0-9 $regex_extra]{1,20})|(^$)/";
    //$regex = "/(?i)(^[a-z0-9]{1,20}$)|(^$)/";

    //$referer = $_SERVER['HTTP_REFERER'];

    if (preg_match($regex, $var) == 0) {

        //header("Location: ".$referer."?error=1");
        echo "<script>window.location = '$url?msg=1';</script>";
        //echo "<script>window.location = '$url?msg=1&debug=$var&regex=$regex&extra=$regex_extra';</script>";

        exit;

    } 

}

function exec_fruitywifi($exec) {

    $exec_mode = "sudo";

    if ($exec_mode == "danger") {
	
		$bin_exec = "/usr/share/fruitywifi/bin/danger";
		exec("$bin_exec \"" . $exec . "\"", $output);
		return $output;
		
    } else if ($exec_mode == "sudo") {
	
		$bin_exec = "/usr/bin/sudo";
		exec("$bin_exec sh -c \"$exec\"", $output);
		return $output;
	
    } else {
		return false;
    }
    
}

function exec_fruitywifi_env($exec) {

	# Construct our new PATH.
	$RBENV_ROOT="/root/.rbenv";
	$ROOT_PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin";
	$ROOT_PATH="$RBENV_ROOT/shims:$RBENV_ROOT/bin:$ROOT_PATH";

    $exec_mode = "sudo";

    if ($exec_mode == "danger") {
	
		$bin_exec = "/usr/share/fruitywifi/bin/danger";
		exec("$bin_exec \"" . $exec . "\"", $output);
		return $output;
    
    } else if ($exec_mode == "sudo") {
	
		$bin_exec = "/usr/bin/sudo";
		exec("$bin_exec env PATH=\"$ROOT_PATH\" sh -c \"$exec\"", $output);
		return $output;
	
    } else {
		return false;
    }
    
}

function setToken() {
	$token = sha1(microtime(true).mt_rand(10000,90000));
	return $token;
}

function module_deb($mod_name) {
    $module="fruitywifi-module-$mod_name";
    
    $exec = "apt-cache policy $module";
    exec($exec, $output);
    
    //print_r($output);
    
    if(empty($output)) {
	//echo "none...";
	return 0;
    } else {
    
	$installed = explode(" ", trim($output[1]));
	$candidate = explode(" ", trim($output[2]));
    
	if( $installed[1] == $candidate[1] ) {
	    //echo "installed...";
	    return 1;
	} else if( $installed[1] == "(none)" ) {
	    //echo "install...";
	    return 2;   
	} else {
	    //echo "upgrade...";
	    return 3;
	}
    
    }    
}

function start_monitor_mode($iface) {

    $bin_danger = "/usr/share/fruitywifi/bin/danger";

    // START MONITOR MODE (mon0)
    $iface_mon0 = exec("/sbin/ifconfig |grep mon0");
    if ($iface_mon0 == "") {
        $exec = "/usr/bin/sudo /usr/sbin/airmon-ng start $iface";
        //exec("$bin_danger \"" . $exec . "\"", $output); //DEPRECATED
	exec_fruitywifi($exec);
     }

}

function stop_monitor_mode($iface) {

    $bin_danger = "/usr/share/fruitywifi/bin/danger";

    // START MONITOR MODE (mon0)
    $iface_mon0 = exec("/sbin/ifconfig |grep mon0");
    if ($iface_mon0 != "") {
        $exec = "/usr/bin/sudo /usr/sbin/airmon-ng stop mon0";
        //exec("$bin_danger \"" . $exec . "\"", $output); //DEPRECATED
	exec_fruitywifi($exec);
    }

}

function open_file($filename) {

    if ( file_exists($filename) ) {
        if ( 0 < filesize( $filename ) ) {
            $fh = fopen($filename, "r"); // or die("Could not open file.");
            $data = fread($fh, filesize($filename)); // or die("Could not read file.");
            fclose($fh);
            return $data;
        }
    }

}

function start_iface($iface, $ip, $gw) {

    $bin_danger = "/usr/share/fruitywifi/bin/danger";

    // START MONITOR MODE (mon0)
    $iface_mon0 = exec("/sbin/ifconfig |grep mon0");
    //if ($iface_mon0 == "") {
        $exec = "/usr/bin/sudo /sbin/ifconfig $iface $ip";
        //exec("$bin_danger \"" . $exec . "\"", $output); //DEPRECATED
	exec_fruitywifi($exec);
	//}

	if (trim($gw) != "") {
	    $exec = "/usr/bin/sudo /sbin/route add default gw $gw";
	    //exec("$bin_danger \"" . $exec . "\"", $output); //DEPRECATED
	    exec_fruitywifi($exec);
	}

}

function stop_iface($iface, $ip, $gw) {

    $bin_danger = "/usr/share/fruitywifi/bin/danger";

    // START MONITOR MODE (mon0)
    $iface_mon0 = exec("/sbin/ifconfig |grep mon0");
    //if ($iface_mon0 != "") {
        $exec = "/usr/bin/sudo /sbin/ifconfig $iface 0.0.0.0";
        //exec("$bin_danger \"" . $exec . "\"", $output); //DEPRECATED
	exec_fruitywifi($exec);
    //}

}

?>