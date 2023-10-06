<?php
include_once("req.php");

$t = new HTML_Template_ITX('html');
$t->loadTemplatefile('print-slips.html', true, true);
$t->touchBlock("__global__");

require_once("doAuth.php");

$item=1;
$items = explode(",", $_POST['stock_items']);
$t->setCurrentBlock("SlipRow");
while ( list($key, $stock) = each($items) ) {
	if ( !empty($_POST['selected'][$stock]) ) {
		$t->setCurrentBlock("OneSlip");

		$al = new DB("SELECT * FROM stock WHERE st_id=".$stock);
		$al->next_record();

		$prod = new DB("SELECT * FROM products, manufacturers WHERE pr_manufacturer=mf_id AND pr_id=".$al->f("st_product"));
		$prod->next_record();

		$spec = new DB("SELECT * FROM specifications WHERE se_id=".$al->f("st_specs"));
		$spec->next_record();

		$prodinfo = new DB("SELECT spec_name FROM specs WHERE spec_id=".$spec->f("se_cpu"));
		$prodinfo->next_record();
		$cpu = $prodinfo->f("spec_name");

		$prodinfo = new DB("SELECT spec_name FROM specs WHERE spec_id=".$spec->f("se_display"));
		$prodinfo->next_record();
		$display = $prodinfo->f("spec_name");

		$prodinfo = new DB("SELECT spec_name FROM specs WHERE spec_id=".$spec->f("se_ram"));
		$prodinfo->next_record();
		$ram = $prodinfo->f("spec_name");

		$prodinfo = new DB("SELECT spec_name FROM specs WHERE spec_id=".$spec->f("se_video"));
		$prodinfo->next_record();
		$video = $prodinfo->f("spec_name");

		$prodinfo = new DB("SELECT spec_name FROM specs WHERE spec_id=".$spec->f("se_hd"));
		$prodinfo->next_record();
		$hd = $prodinfo->f("spec_name");

		$prodinfo = new DB("SELECT spec_name FROM specs WHERE spec_id=".$spec->f("se_sound"));
		$prodinfo->next_record();
		$sound = $prodinfo->f("spec_name");

		$prodinfo = new DB("SELECT spec_name FROM specs WHERE spec_id=".$spec->f("se_optical"));
		$prodinfo->next_record();
		$optical = $prodinfo->f("spec_name");

		$prodinfo = new DB("SELECT spec_name FROM specs WHERE spec_id=".$spec->f("se_os"));
		$prodinfo->next_record();
		$os = $prodinfo->f("spec_name");

		$t->setVariable(array("sdescription" => $spec->f("se_description"),
												"sother" => $spec->f("se_other"),
												"scpu" => $cpu,
												"sdisplay" => $display,
												"sram" => $ram,
												"svideo" => $video,
												"shd" => $hd,
												"ssound" => $sound,
												"soptical" => $optical,
												"sos" => $os,
												"susb" => $spec->f("se_usb"),
												"sfw" => $specYesNo[$spec->f("se_firewire")],
												"svga" => $specYesNo[$spec->f("se_vga")],
												"stvout" => $specYesNo[$spec->f("se_tvout")],
												"slpt" => $specYesNo[$spec->f("se_parallel")],
												"sserial" => $spec->f("se_serial"),
												"wifi" => $spec->f("se_wifi")==0 ? "** ADD £15 for Wireless LAN WiFi **" : $spec->f("se_bluetooth")==0? "** Integrated Wireless LAN WiFi **" : "** Integrated Wireless LAN & BlueTooth **",
												"sbt" => $specYesNo[$spec->f("se_bluetooth")],
												"smodem" => $spec->f("se_modem")==1 ? "Internet Ready Modem / " : "",
												"snet" => $specNet[$spec->f("se_net")],
												"swarr" => $spec->f("se_bnib")==1 ? '<div class="style2" style="font-size:16px; text-transform: uppercase;"><b>*** '.$specWarranty[$spec->f("se_warranty")].' Warranty***<b></div>' : "",
												"bnibmsg" => $spec->f("se_bnib")==1 ? "*** BRAND NEW & BOXED ***" : "COMES BOXED AS NEW",
												"bnibfs" => $spec->f("se_bnib")==1 ? "19px" : "20px",
												"divheight" => $spec->f("se_bnib")==1 ? "13px" : "20px",
												"rrp" => $spec->f("se_bnib")==1 ? '<div style="height: 2px;"></div><div class="style2" style="font-size:30px">'."RRP &pound;".$al->f("st_rrp").'</div><br/>' : "",
												"manufacturer" => $prod->f("mf_name"),
												"model" => $prod->f("pr_name"),
												"st_retail" => substr($al->f("st_retail"), 0, strpos($al->f("st_retail"), '.')),));

		if ( $spec->f("se_bnib") == 1 ) {
			$t->removeBlockData("nBNIB");
		}

		$t->parseCurrentBlock("OneSlip");
		if ( $item == 3) {
			$t->parse("SlipRow");
			$item=0;
		}
		$item++;
	}
}

$t->show();
?>